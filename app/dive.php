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

class Dive extends Model
{
    protected $table = 'dives';
    protected $primaryKey = 'idDive';
    public $timestamps = false;
    protected $fillable = [
        'idDive',
        'diveSite',
        'boat',
        'weather',
        'weight',
        'description',
        'location',
        'pressionInit',
        'xml',
        'diver',
        'public'
    ];
}
