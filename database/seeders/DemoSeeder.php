<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Fill the marketplace with realistic-looking demo posts for local
     * development and demos. Never run this in production.
     */
    public function run(): void
    {
        $samples = [
            ['Autobusa vadītājs', 'Kravas auto vadītājs', 'D kategorija, 95. kods', 8, 'riga', 'immediately'],
            ['Pavārs', 'Šefpavāra vietnieks', 'Pārtikas higiēnas apliecība', 5, 'riga', '1_month'],
            ['Medmāsa', 'Ģimenes ārsta māsa', 'Māszinību sertifikāts', 12, 'kurzeme', 'negotiable'],
            ['Skolotājs (matemātika)', 'Skolotājs (fizika)', 'Pedagoga sertifikāts', 15, 'vidzeme', '2_months'],
            ['Noliktavas darbinieks', 'Loģistikas koordinators', 'Autoiekrāvēja apliecība', 3, 'riga', 'immediately'],
            ['Elektriķis', 'Būvuzraugs', 'B klases elektrodrošības grupa', 10, 'zemgale', '1_month'],
            ['Frizieris', 'Skaistumkopšanas speciālists', null, 6, 'riga', 'negotiable'],
            ['Grāmatvedis', 'Finanšu analītiķis', 'ACCA 1. līmenis', 9, 'latgale', '2_months'],
            ['Apsargs', 'Drošības sistēmu tehniķis', 'Apsardzes sertifikāts', 4, 'kurzeme', 'immediately'],
            ['Pārdevējs', 'Veikala vadītājs', null, 2, 'vidzeme', '1_month'],
            ['Automehāniķis', 'Servisa pieņēmējs', 'B, C kategorijas', 7, 'zemgale', 'negotiable'],
            ['IT atbalsta speciālists', 'Sistēmu administrators', 'CompTIA A+', 3, 'riga', 'immediately'],
        ];

        foreach ($samples as [$current, $desired, $licenses, $years, $region, $availability]) {
            Post::factory()
                ->for(User::factory())
                ->create([
                    'current_job_title' => $current,
                    'desired_job_title' => $desired,
                    'licenses' => $licenses,
                    'years_experience' => $years,
                    'region' => $region,
                    'availability' => $availability,
                    'created_at' => now()->subDays(rand(0, 20)),
                ]);
        }
    }
}
