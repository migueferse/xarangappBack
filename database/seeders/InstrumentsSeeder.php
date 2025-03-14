<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Instrument;

class InstrumentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instruments = [
            "Bombo", "Caja", "Platos", "Trombón", "Trompeta",
            "Saxo Alto", "Saxo Tenor", "Clarinete"
        ];

        foreach ($instruments as $instrument) {
            Instrument::create(["name" => $instrument]);
        }
    }
}
