<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Score extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'scores';

    protected $fillable = [
        'title',
        'file_path',
        'instrument_id',
    ];

    public function instrument()
    {
        return $this->belongsTo(Instrument::class, 'instrument_id', '_id');
    }
}

