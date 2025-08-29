<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\MusicService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MusicServiceTest extends TestCase
{
    // اگر نمی‌خوای دیتابیس پاک بشه هرچی که هست نگه داره، RefreshDatabase رو حذف کن
    // use RefreshDatabase;

    protected MusicService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(MusicService::class);
    }

    public function testFuzzyNameMatching()
    {
        $expectedPath = 'storage/music/Tavalod Amin.mp3';

        $inputs = [
            'امین' => 'storage/music/Tavalod Amin.mp3',
            'امییین'=> 'storage/music/Tavalod Amin.mp3',
            'امییین اعتمادی فر'=> 'storage/music/Tavalod Amin.mp3',
            'محمدامییین اعتمادی فر'=> 'storage/music/Tavalod Mohammad Amin.mp3',
            'محمد امییین اعتمادی فر'=> 'storage/music/Tavalod Mohammad Amin.mp3',
            'محمد  امین'=> 'storage/music/Tavalod Mohammad Amin.mp3',
            'محمدامین'=> 'storage/music/Tavalod Mohammad Amin.mp3',
            'محمدامییین'=> 'storage/music/Tavalod Mohammad Amin.mp3',
            'سید امییین'=> 'storage/music/Tavalod Amin.mp3',
            'سید امیsaasیین'=> null,
            'عبدالرحمان الهی'=> null,
        ];


        foreach ($inputs as $key => $input) {
            $result = $this->service->getMusicPathForName($key);
            $this->assertEquals($input, $result, "Failed for input: $key");
        }
    }
}
