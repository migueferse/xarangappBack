<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Instrument;
use App\Models\Event;


class Musician extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'musicians';

    protected $fillable = [
        'name', 'lastName', 'nickname', 'instrument', 'phone', 'email', 'instrument_id'
    ];

    public function instrument()
    {
        return $this->belongsTo(Instrument::class, 'instrument_id', '_id');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    protected $hidden = ['event_ids'];
}
