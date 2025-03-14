<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Instrument extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'instruments';

    protected $fillable = ['name'];

    public function musicians()
    {
        return $this->hasMany(Musician::class, 'instrument_id', '_id');
    }
}
