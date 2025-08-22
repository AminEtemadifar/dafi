<?php

namespace App\Services;

interface MusicServiceInterface
{
    /**
     * Resolve a music file path for a given person name.
     * Returns a relative public path like 'assets/songs/x.mp3' or null when not found.
     */
    public function getMusicPathForName(string $personName): ?string;
}
