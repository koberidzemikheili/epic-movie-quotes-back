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
		$genres = [
			['en' => 'Action', 'ka' => 'მოქმედება'],
			['en' => 'Adventure', 'ka' => 'თავგადასავლები'],
			['en' => 'Animation', 'ka' => 'ანიმაცია'],
			['en' => 'Comedy', 'ka' => 'კომედია'],
			['en' => 'Drama', 'ka' => 'დრამა'],
			['en' => 'Horror', 'ka' => 'საშინელება'],
			['en' => 'Mystery', 'ka' => 'მისტიური'],
			['en' => 'Romance', 'ka' => 'რომანტიკა'],
			['en' => 'Thriller', 'ka' => 'თრილერი'],
			['en' => 'Fantasy', 'ka' => 'ფანტასტიკა'],
		];

		foreach ($genres as $genreData) {
			Genre::create([
				'genre' => $genreData,
			]);
		}
	}
}
