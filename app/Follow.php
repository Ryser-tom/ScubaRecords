<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $table = 'following';
    public $timestamps = false;
    protected $fillable = [
        'idFollower',
        'idFollowed'
    ];
}
