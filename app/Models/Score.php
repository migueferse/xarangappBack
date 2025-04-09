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
    ];    
}

