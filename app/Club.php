<?php

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
        'location',
        'createdBy',
        'master',
        'startDateTime',
        'endDateTime'
    ];
}
