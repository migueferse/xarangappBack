<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Musician;
use App\Models\EventMusician;

class Event extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'events';
    protected $fillable = [
        'name', 'place', 'date', 'musicians'
    ];
    protected $hidden = ['musician_ids', 'musicians'];
    protected $with = ['acceptedMusicians'];

    public function musicians()
    {
        return $this->belongsToMany(Musician::class);
    }

    public function eventMusicians()
    {
        return $this->hasMany(EventMusician::class, 'event_id', '_id');
    }

    public function acceptedMusicians()
    {
        return $this->hasMany(EventMusician::class, 'event_id', '_id')
            ->where('status', 'accepted')
            ->with(['musician' => function ($query) {
                $query->whereNotNull('_id');
            }, 'musician.instrument']);
    }

}
