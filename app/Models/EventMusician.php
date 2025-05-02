<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Event;
use App\Models\Musician;

class EventMusician extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'event_musicians';

    protected $fillable = ['event_id', 'musician_id', 'status'];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', '_id');
    }

    public function musician()
    {
        return $this->belongsTo(Musician::class, 'musician_id', '_id')->with('instrument');
    }
}
