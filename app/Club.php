<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $table = 'club';
    protected $primaryKey = 'idClub';
    public $timestamps = false;
    protected $fillable = [
        'idClub',
        'Name',
        'Description',
        'Location',
        'CreatedBy',
        'Master',
        'StartDateTime',
        'EndDateTime'
    ];
}
