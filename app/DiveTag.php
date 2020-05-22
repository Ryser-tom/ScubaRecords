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

class DiveTag extends Model
{
    protected $table = 'dive_tags';
    protected $primaryKey = 'idDive';
    public $timestamps = false;
    protected $fillable = [
        'idDive',
        'idTag',
        'txtValue'
    ];
}
