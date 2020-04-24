<?php

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
        'diver',
        'public'
    ];
}
