<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Musician;

class Event extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'events';

    protected $fillable = [
        'name', 'place', 'date', 'musicians'
    ];

    public function musicians()
    {
        return $this->belongsToMany(Musician::class);
    }
}
