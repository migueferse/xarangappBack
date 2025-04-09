<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Score;
use App\Models\Instrument;

class ScoresSeeder extends Seeder
{
    public function run(): void
    {
        $instrumentMap = Instrument::all()->pluck('_id', 'name');

        $scores = [
            [
                'title' => 'Bakalao Salao',
                'file_path' => 'scores/bakalao_salao.pdf'
            ],
            [
                'title' => 'Karrasketón',
                'file_path' => 'scores/karrasketon.pdf'
            ],
            [
                'title' => 'Fanfarria Iniciación',
                'file_path' => 'scores/fanfarria.pdf'
            ],
        ];

        foreach ($scores as $score) {
            Score::create($score);
        }
    }
}
