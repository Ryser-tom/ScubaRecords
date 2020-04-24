<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'idUser';
    public $timestamps = false;
    protected $fillable = [
        'idUser',
        'firstName',
        'lastName',
        'email',
        'password',
        'remember_token'
    ];
}
