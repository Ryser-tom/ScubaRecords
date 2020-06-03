<?php

/*******************************************************************************
AUTEUR      : Tom Ryser
LIEU        : CFPT Informatique Genève
DATE        : Avril 2020
TITRE PROJET: ScubaRecords
VERSION     : 1.0
*******************************************************************************/

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\user;

class Member extends Model
{
    protected $table = 'members';
    public $timestamps = false;
    protected $fillable = [
        'idUser',
        'idClub'
    ];

    public function getMembership($club, $user){
        $member = DB::table('members')
        ->where('idClub', $club)
        ->where('idUser', $user)
        ->get();
        
        return $member;
    }
    public function getMembersOfClub($clubInfos){
        $members = DB::table('users')
            ->select('users.idUser', 'users.name')
            ->join('members', 'members.idUser', '=', 'users.idUser')
            ->where('members.idClub', $clubInfos[0]["idClub"])
            ->get();

        return json_decode(json_encode($members->toArray()), true);
    }
}
