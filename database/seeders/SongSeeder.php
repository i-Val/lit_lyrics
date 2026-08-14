<?php

namespace Database\Seeders;

use App\Models\Song;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SongSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('seeders/hymn.json');
        

        if (!File::exists($jsonPath)) {
            $this->command->error("JSON file not found: {$jsonPath}");
            return;
        }

        $json = File::get($jsonPath);
        $hymns = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('Invalid JSON: ' . json_last_error_msg());
            return;
        }

        $this->command->info('Seeding ' . count($hymns) . ' hymns...');

        $bar = $this->command->getOutput()->createProgressBar(count($hymns));

        foreach ($hymns as $hymn) {
            Song::create([
                'title' => $hymn['title'],
                'author' => $hymn['author'] ?? 'pending',
                'category' => $hymn['category'] ?? 'general',
                'verses' => $hymn['verses'],
            ]);
            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();
        $this->command->info('Hymns seeded successfully!');
    }
}