<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database. Safe to re-run.
     */
    public function run(): void
    {
        if (! User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test Worker',
                'email' => 'test@example.com',
            ]);
        }

        if (! User::where('email', 'admin@jobswap.lv')->exists()) {
            User::factory()->admin()->create([
                'name' => 'Admin',
                'email' => 'admin@jobswap.lv',
            ]);
        }
    }
}
