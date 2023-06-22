<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		$genres = ['Action', 'Adventure', 'Animation', 'Comedy', 'Drama', 'Horror', 'Mystery', 'Romance', 'Thriller', 'Fantasy'];

		foreach ($genres as $genre) {
			Genre::create(['genre' => $genre]);
		}
	}
}
