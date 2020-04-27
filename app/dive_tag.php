<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dive_tag extends Model
{
    protected $table = 'dive_tag';
    protected $primaryKey = 'idDive_tag';
    public $timestamps = false;
    protected $fillable = [
        'idDive',
        'idTag',
        'txtValue'
    ];
}
