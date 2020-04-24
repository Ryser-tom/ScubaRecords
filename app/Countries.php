<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    protected $table = 'countries';
    protected $primaryKey = 'idCountries';
    public $timestamps = false;
    protected $fillable = [
        'idCountries',
        'Name'
    ];
}
