<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'idEvent';
    public $timestamps = false;
    protected $fillable = [
        'idEvent',
        'name',
        'startDate',
        'endDate',
        'description',
        'location',
        'privacity',
        'club'
    ];
}
