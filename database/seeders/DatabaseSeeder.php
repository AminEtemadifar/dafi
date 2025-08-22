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

		User::factory()->create([
			'name' => 'Admin',
			'mobile' => '09123456789',
			'email' => 'admin@example.com',
			'password' => Hash::make('admin1234'),
		]);
	}
}
