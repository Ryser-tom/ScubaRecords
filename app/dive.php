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
use Illuminate\Support\Facades\Auth;
use DB;

class Dive extends Model
{
    protected $table = 'dives';
    protected $primaryKey = 'idDive';
    public $timestamps = false;
    protected $fillable = [
        'idDive',
        'diveSite',
        'boat',
        'weather',
        'weight',
        'description',
        'location',
        'pressionInit',
        'xml',
        'diver',
        'public'
    ];

    public function getInfo($idDive, $idUser){
        $diver = DB::table('dives')
            ->select('diver')
            ->where('idDive', $idDive)
            ->get();
        $diver = $diver->toArray()[0];

        $diveNb = DB::table('dives')
            ->join('users', 'users.idUser', '=', 'dives.diver')
            ->where('dives.diver', $diver->diver)
            ->where('dives.idDive', '<=', $idDive)
            ->count();

        $dive = DB::table('dive_tags')
            ->selectRaw('
                dives.*, divesites.name as diveSiteName,  GROUP_CONCAT( 
                DISTINCT CONCAT(tags.name,":",dive_tags.txtValue)
                ORDER BY dive_tags.idTag 
                SEPARATOR ";") AS tags
                ')
            ->join('tags', 'tags.idTag', '=', 'dive_tags.idTag')
            ->join('dives', 'dives.idDive', '=', 'dive_tags.idDive')
            ->join('divesites', 'divesites.idDiveSite', '=', 'dives.diveSite')
            ->where(function($q) use ($idUser) {
                $q->where('dives.public', 1)
                ->orWhere('dives.diver', $idUser);
            })
            ->where('dives.idDive', $idDive)
            ->groupBy('dives.idDive')
            ->get();
        return array( $dive, $diveNb );
    }

    public function getAllPublicDives(){
        $dives = DB::table('dive_tags')
        ->selectRaw('
            DISTINCT dives.*, GROUP_CONCAT( 
            DISTINCT CONCAT(tags.name,":",dive_tags.txtValue)
            ORDER BY dive_tags.idTag 
            SEPARATOR ";") AS tags, 
            users.name as username,
            divesites.name as diveSiteName
            ')
        ->join('tags', 'tags.idTag', '=', 'dive_tags.idTag')
        ->join('dives', 'dives.idDive', '=', 'dive_tags.idDive')
        ->join('users', 'users.idUser', '=', 'dives.diver')
        ->join('divesites', 'divesites.idDiveSite', '=', 'dives.diveSite')
        ->where('dives.public', 1)
        ->orderBy('dives.idDive', 'DESC')
        ->groupBy('dives.idDive')
        ->get();

        return $dives;
    }

    public function getAllPublicDivesOfSite($site){
        $dives = DB::table('dive_tags')
        ->selectRaw('
            DISTINCT dives.*, GROUP_CONCAT( 
            DISTINCT CONCAT(tags.name,":",dive_tags.txtValue)
            ORDER BY dive_tags.idTag 
            SEPARATOR ";") AS tags, 
            users.name as username,
            divesites.name as diveSiteName
            ')
        ->join('tags', 'tags.idTag', '=', 'dive_tags.idTag')
        ->join('dives', 'dives.idDive', '=', 'dive_tags.idDive')
        ->join('users', 'users.idUser', '=', 'dives.diver')
        ->join('divesites', 'divesites.idDiveSite', '=', 'dives.diveSite')
        ->where('dives.public', 1)
        ->where('divesites.name', $site)
        ->orderBy('dives.idDive', 'DESC')
        ->groupBy('dives.idDive')
        ->get();

        return $dives;
    }

    public function getDivesOfUser($user){
        $dives = DB::table('dive_tags')
        ->selectRaw('
            DISTINCT dives.*, GROUP_CONCAT( 
            DISTINCT CONCAT(tags.name,":",dive_tags.txtValue)
            ORDER BY dive_tags.idTag 
            SEPARATOR ";") AS tags, 
            users.name as username, 
            divesites.name as diveSiteName
            ')
        ->join('tags', 'tags.idTag', '=', 'dive_tags.idTag')
        ->join('dives', 'dives.idDive', '=', 'dive_tags.idDive')
        ->join('users', 'users.idUser', '=', 'dives.diver')
        ->join('divesites', 'divesites.idDiveSite', '=', 'dives.diveSite')
        ->whereIn('dives.diver', $user)
        ->orderBy('dives.idDive', 'DESC')
        ->groupBy('dives.idDive')
        ->get();

        return $dives;
    }

    public function getPersonnalDivesOfSite($user, $site){
        $dives = DB::table('dive_tags')
        ->selectRaw('
            DISTINCT dives.*, GROUP_CONCAT( 
            DISTINCT CONCAT(tags.name,":",dive_tags.txtValue)
            ORDER BY dive_tags.idTag 
            SEPARATOR ";") AS tags, 
            users.name as username, 
            divesites.name as diveSiteName
            ')
        ->join('tags', 'tags.idTag', '=', 'dive_tags.idTag')
        ->join('dives', 'dives.idDive', '=', 'dive_tags.idDive')
        ->join('users', 'users.idUser', '=', 'dives.diver')
        ->join('divesites', 'divesites.idDiveSite', '=', 'dives.diveSite')
        ->where('dives.diver', $user->idUser)
        ->where('divesites.name', $site)
        ->orderBy('dives.idDive', 'DESC')
        ->groupBy('dives.idDive')
        ->get();

        return $dives;
    }

}
