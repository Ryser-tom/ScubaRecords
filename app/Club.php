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

class Club extends Model
{
    protected $table = 'clubs';
    protected $primaryKey = 'idClub';
    public $timestamps = false;
    protected $fillable = [
        'idClub',
        'name',
        'description',
        'smallDesc',
        'email',
        'location',
        'createdBy',
        'master',
        'startDateTime',
        'endDateTime'
    ];

    public function getAllClubsOfUser($user){
        $clubs = DB::table('clubs')
        ->select('*')
        ->join('members', 'members.idClub', '=', 'clubs.idClub')
        ->where('members.idUser', $user)
        ->get();
        return $clubs;
    }

    public function getClubInfo($club){
        $clubInfos = DB::table('clubs')
            ->select('*')
            ->where('clubs.name', $club)
            ->get();
        $data = json_decode(json_encode($clubInfos->toArray()), true);
        return $data;
    }
}
