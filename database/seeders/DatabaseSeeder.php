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
            'email' => 'admin@example.com',
            "password" => Hash::make("admin123"),
            "role" => "admin"
        ]);
        
        User::factory()->create([
            'name' => 'Usuario Normal',
            'email' => 'user@example.com',
            "password" => Hash::make("user123"),
            "role" => "user"
        ]);

        $this->call(InstrumentsSeeder::class);
        $this->call(MusiciansSeeder::class);
        $this->call(EventSeeder::class);
        $this->call(ScoresSeeder::class);
    }
}
