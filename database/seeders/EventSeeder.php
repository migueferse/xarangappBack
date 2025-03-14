<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                "name" => "Moros i Cristians",
                "place" => "Torrent",
                "date" => "2024-06-15",
                "musicians" => []
            ],
            [
                "name" => "Despedida solter",
                "place" => "Sollana",
                "date" => "2024-07-20",
                "musicians" => []
            ],
            [
                "name" => "Festes del poble",
                "place" => "Alaquàs",
                "date" => "2024-08-05",
                "musicians" => []
            ]
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
