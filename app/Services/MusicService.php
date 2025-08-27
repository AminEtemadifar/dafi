<?php

namespace App\Services;

use App\Models\Name as NameModel;

class MusicService implements MusicServiceInterface
{
    public function getMusicPathForName(string $personName): ?string
    {
        $variants = $this->normalizeVariants($personName);

        // 1) Exact match
        foreach ($variants as $normalized) {
            $record = NameModel::whereRaw('LOWER(TRIM(name)) = ?', [mb_strtolower($normalized, 'UTF-8')])->first();
            if ($record) {
                $record->increment('use_count');
                return "storage/" . $record->path;
            }
        }

        // 2) Fuzzy search
        $allNames = NameModel::all();
        $scores = [];

        $units = [];
        foreach ($variants as $v) {
            $units = array_merge($units, $this->wordUnits($v));
        }
        $uniqueUnits = [];
        foreach ($units as $u) {
            $uniqueUnits[$u['text']] = $u;
        }

        foreach ($allNames as $candidate) {
            $candidateNorms = $this->normalizeVariants($candidate->name);

            $bestForCandidate = 0;

            // (a) full input vs candidate
            foreach ($variants as $v) {
                foreach ($candidateNorms as $cn) {
                    $score = $this->similarityScore($v, $cn, 'full');
                    $score -= $this->surnamePenalty($cn);
                    $bestForCandidate = max($bestForCandidate, $score);
                }
            }

            // (b) units vs candidate
            foreach ($candidateNorms as $cn) {
                foreach ($uniqueUnits as $meta) {
                    $unit = $meta['text'];
                    $type = $meta['type'];
                    $pos  = $meta['pos'];
                    if (mb_strlen($unit) < 2) continue;

                    $score = $this->similarityScore($unit, $cn, $type);
                    if ($pos === 0 && $type === 'combo') $score += 10;
                    if ($pos === 0 && $type === 'word') $score += 3;
                    if ($score >= 80 && mb_strlen($cn) <= 6) $score += 3;
                    $score -= $this->surnamePenalty($cn);

                    $bestForCandidate = max($bestForCandidate, $score);
                }
            }

            $scores[$candidate->id] = [
                'candidate' => $candidate,
                'score' => $bestForCandidate,
            ];
        }

        if (empty($scores)) {
            return null;
        }

        // Sort by score, then by length (longer wins if close)
        usort($scores, function ($a, $b) {
            if ($a['score'] === $b['score']) {
                return mb_strlen($b['candidate']->name) <=> mb_strlen($a['candidate']->name);
            }
            return $b['score'] <=> $a['score'];
        });

        $best = $scores[0];
        $second = $scores[1] ?? null;

        // --- Tie-breaker rules ---
        if ($second && abs($best['score'] - $second['score']) <= 5) {
            if (mb_strlen($second['candidate']->name) > mb_strlen($best['candidate']->name)) {
                $best = $second;
            }
        }

        // Special rule: prefer محمدامین over محمد
        if ($best['candidate']->name === 'محمد' && isset($second) && $second['candidate']->name === 'محمدامین') {
            $best = $second;
        }

        if ($best['score'] >= 70) {
            $best['candidate']->increment('use_count');
            return "storage/" . $best['candidate']->path;
        }

        return null;
    }

    private function advancedNormalizeName(string $name): string
    {
        $name = trim($name);
        $name = $this->removePrefixes($name);
        $name = preg_replace('/\s+/u', ' ', $name) ?? $name;
        $name = preg_replace('/ـ+/u', '', $name);
        $name = preg_replace('/[\x{064B}-\x{065F}\x{0670}]/u', '', $name);
        $name = preg_replace('/(.)\1{2,}/u', '$1$1', $name);
        $name = str_replace(['ي', 'ى'], 'ی', $name);
        $name = str_replace(['ك'], 'ک', $name);
        $name = str_replace(['ة'], 'ه', $name);
        return mb_strtolower($name, 'UTF-8');
    }

    private function normalizeVariants(string $name): array
    {
        $base = $this->advancedNormalizeName($name);
        $noSpace = str_replace(' ', '', $base);
        return array_values(array_unique([$base, $noSpace]));
    }

    private function wordUnits(string $normalized): array
    {
        $words = preg_split('/\s+/u', $normalized, -1, PREG_SPLIT_NO_EMPTY);
        $units = [];
        foreach ($words as $i => $w) {
            $units[] = ['text' => $w, 'pos' => $i, 'type' => 'word'];
        }
        for ($i = 0; $i + 1 < count($words); $i++) {
            $units[] = ['text' => $words[$i] . $words[$i + 1], 'pos' => $i, 'type' => 'combo'];
        }
        $noSpace = str_replace(' ', '', $normalized);
        if ($noSpace !== $normalized) {
            $units[] = ['text' => $noSpace, 'pos' => 0, 'type' => 'combo'];
        }
        return $units;
    }

    private function removePrefixes(string $name): string
    {
        $prefixes = ['سید','سیده','دکتر','مهندس','حاج','شیخ','آیت‌الله','استاد'];
        return preg_replace('/^(' . implode('|', array_map('preg_quote', $prefixes)) . ')\s+/u', '', $name);
    }

    private function looksLikeSurname(string $name): bool
    {
        $suffixes = ['زاده','پور','نیا','نژاد','یان','لو','چی','وند','فر','گر'];
        foreach ($suffixes as $suf) {
            if (mb_strlen($name) >= mb_strlen($suf) + 2 &&
                mb_substr($name, -mb_strlen($suf)) === $suf) {
                return true;
            }
        }
        return false;
    }

    private function surnamePenalty(string $name): int
    {
        return $this->looksLikeSurname($name) ? 8 : 0;
    }

    private function similarityScore(string $a, string $b, string $type = 'word'): int
    {
        $aNo = str_replace(' ', '', $a);
        $bNo = str_replace(' ', '', $b);

        similar_text($aNo, $bNo, $percent);

        if (mb_strlen($aNo) >= 3 && mb_strlen($bNo) >= 3 &&
            (mb_strpos($aNo, $bNo) !== false || mb_strpos($bNo, $aNo) !== false)) {
            $percent = max($percent, ($type === 'combo' ? 95 : 85));
        }

        // prefix handling
        if (mb_substr($aNo, 0, mb_strlen($bNo)) === $bNo ||
            mb_substr($bNo, 0, mb_strlen($aNo)) === $aNo) {
            if (mb_strlen($aNo) > mb_strlen($bNo)) {
                $percent += 10;
            } elseif (mb_strlen($bNo) > mb_strlen($aNo)) {
                $percent -= 10;
            }
        }

        if ($type === 'word') {
            $percent = min($percent, 88);
        }

        return (int) round(min(100, max(0, $percent)));
    }
}
