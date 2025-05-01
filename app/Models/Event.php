<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Musician;

class Event extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'events';

    protected $fillable = [
        'name', 'place', 'date',
    ];

    public function musicians()
    {
        return $this->belongsToMany(Musician::class, 'event_musicians', 'event_id', 'musician_id')
                    ->withPivot('status')
                    ->wherePivot('status', 'accepted'); // Solo los músicos aceptados
    }

    public function invitations()
    {
        return $this->hasMany(EventMusician::class, 'event_id');
    }

    protected $with = ['musicians'];
    protected $hidden = ['musician_ids'];
}
