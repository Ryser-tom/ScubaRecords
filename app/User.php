<?php

/*******************************************************************************
AUTEUR      : Tom Ryser
LIEU        : CFPT Informatique Genève
DATE        : Avril 2020
TITRE PROJET: ScubaRecords
VERSION     : 1.0
*******************************************************************************/

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;

//class User extends Authenticatable
//{
//    
//}

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'idUser';
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idUser',
        'name',
        'email',
        'password',
        'smallDesc',
        'description',
        'phone',
        'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
    ];

    //TODO: get rid of the encode-Decode
    public function getUserInfosById($idUser){
        $user = DB::table('users')
            ->select('*')
            ->whereIn('users.idUser', $idUser)
            ->get();
        return json_decode(json_encode($user->toArray()), true);
    }

    public function getUserInfosByName($name){
        $user = DB::table('users')
            ->select('*')
            ->whereIn('users.name', $name)
            ->get();
        return json_decode(json_encode($user), true);
    }

    public function updateInfo($data, $idUser){
        $user = User::find($idUser);
        $user->name= $data["name"];
        $user->email= $data["email"];
        $user->phone= $data["phone"];
        $user->smallDesc= $data["smallDesc"];
        $user->description= $data["description"];
        $user->save();
    }
}