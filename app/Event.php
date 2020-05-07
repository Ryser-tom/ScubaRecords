<?php

/*******************************************************************************
AUTEUR      : Tom Ryser
LIEU        : CFPT Informatique Genève
DATE        : Avril 2020
TITRE PROJET: ScubaRecords
VERSION     : 1.0
*******************************************************************************/

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
