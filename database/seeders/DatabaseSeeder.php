<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		// User::factory(10)->create();

		        User::create([
            'name' => 'مدیر سیستم',
            'mobile' => '09123456789',
            'email' => 'admin@dafi.com',
            'password' => Hash::make('123456'),
        ]);
	}
}
