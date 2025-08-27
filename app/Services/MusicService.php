<?php

namespace App\Services;

use App\Models\Name as NameModel;
use Illuminate\Support\Str;

class MusicService implements MusicServiceInterface
{
    public function getMusicPathForName(string $personName): ?string
    {
        $normalized = $this->normalizeName($personName);

        // Try exact match first
        $record = NameModel::where('name', $normalized)->first();

        // If not found, try loose match (case-insensitive, trimmed)
        if (!$record) {
            $record = NameModel::whereRaw('LOWER(TRIM(name)) = ?', [mb_strtolower(trim($personName), 'UTF-8')])->first();
        }

        if (!$record) {
            return null;
        }

        // Increment use count
        $record->increment('use_count');

        return "storage/" . $record->path;
    }

    private function normalizeName(string $name): string
    {
        $name = trim($name);
        // Normalize multiple spaces and different dash/underscore variants
        $name = preg_replace('/\s+/u', ' ', $name) ?? $name;
        return $name;
    }
}
