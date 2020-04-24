<?php

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
        'boat',
        'weather',
        'weight',
        'description',
        'location',
        'pressionInit',
        'diver',
        'public'
    ];
}
