<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * UserSeeder
 *
 * Creates:
 * - 1 demo admin user   (demo@wanderly.app / password)
 * - 4 random fake users
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Demo user (easy login for testing) ──────────────────────
        User::firstOrCreate(
            ['email' => 'demo@wanderly.app'],
            [
                'name'     => 'Alex Wanderer',
                'password' => Hash::make('password'),
            ]
        );

        $this->command->info('✅ Demo user created: demo@wanderly.app / password');

        // ── Extra test user ──────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name'     => 'Taylor Explorer',
                'password' => Hash::make('password'),
            ]
        );

        // ── Random users ─────────────────────────────────────────────
        User::factory()->count(4)->create();

        $this->command->info('✅ Users seeded successfully.');
    }
}
