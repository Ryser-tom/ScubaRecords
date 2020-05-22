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

class DiveSite extends Model
{
    protected $table = 'diveSites';
    protected $primaryKey = 'idDiveSite';
    public $timestamps = false;
    protected $fillable = [
        'idDiveSite',
        'name',
        'description',
        'difficulty',
        'depthMin',
        'depthMax',
        'latitude',
        'longitude'
    ];
}
