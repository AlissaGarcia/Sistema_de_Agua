<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Gestor Sistema',
            'email' => 'gestor@example.com',
            'password' => Hash::make('password'),
            'role' => 'gestor',
        ]);

        User::factory()->create([
            'name' => 'Leiturista Sistema',
            'email' => 'leiturista@example.com',
            'password' => Hash::make('password'),
            'role' => 'leiturista',
        ]);
    }
}
