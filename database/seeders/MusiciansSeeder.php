<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Musician;
use App\Models\Instrument;

class MusiciansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $musicians = [
            [
                "name" => "Miguel Ángel",
                "lastName" => "Fernández",
                "nickname" => "Migue",
                "instrument_name" => "Saxo Tenor",
                "phone" => "123456789",
                "email" => "migue@a.com"
            ],
            [
                "name" => "Jose Luis",
                "lastName" => "Dominguez",
                "nickname" => "Jose",
                "instrument_name" => "Caja",
                "phone" => "987654321",
                "email" => "jose@a.com"
            ],
            [
                "name" => "Raúl",
                "lastName" => "Sanz",
                "nickname" => "Diavolo",
                "instrument_name" => "Bombo",
                "phone" => "555555555",
                "email" => "raul@a.com"
            ]
        ];

        foreach ($musicians as $musician) {
            $instrument = Instrument::where('name', $musician['instrument_name'])->first();

            Musician::create([
                "name" => $musician["name"],
                "lastName" => $musician["lastName"],
                "nickname" => $musician["nickname"],
                "instrument_id" => $instrument ? $instrument->_id : null,
                "phone" => $musician["phone"],
                "email" => $musician["email"]
            ]);
        }
    }
}
