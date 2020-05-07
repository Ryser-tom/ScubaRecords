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

class Club extends Model
{
    protected $table = 'clubs';
    protected $primaryKey = 'idClub';
    public $timestamps = false;
    protected $fillable = [
        'idClub',
        'name',
        'description',
        'smallDesc',
        'email',
        'location',
        'createdBy',
        'master',
        'startDateTime',
        'endDateTime'
    ];
}
