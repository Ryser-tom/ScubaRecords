<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tag';
    protected $primaryKey = 'idTag';
    public $timestamps = false;
    protected $fillable = [
        'idTag',
        'name',
        'type'
    ];
}
