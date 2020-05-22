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

class Tag extends Model
{
    protected $table = 'tags';
    protected $primaryKey = 'idTag';
    public $timestamps = false;
    protected $fillable = [
        'idTag',
        'name',
        'type'
    ];
}
