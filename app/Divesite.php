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

class DiveSite extends Model
{
    protected $table = 'divesites';
    protected $primaryKey = 'idDiveSite';
    public $timestamps = false;
    protected $fillable = [
        'idDiveSite',
        'name',
        'description',
        'difficulty',
        'depthMin',
        'depthMax',
        'latitude',
        'longitude'
    ];



    public function getPublicSites(){
        $sites = DB::table('divesites')
        ->select('divesites.*')
        ->join('dives', 'dives.diveSite', '=', 'divesites.idDiveSite')
        ->where('divesites.idDiveSite', '!=', 0)
        ->orderBy('divesites.idDiveSite', 'ASC')
        ->groupBy('divesites.idDiveSite')
        ->get();

        return $sites;
    }

    public function getuserSites($user){
        $sites = DB::table('divesites')
        ->select('divesites.*')
        ->join('dives', 'dives.diveSite', '=', 'divesites.idDiveSite')
        ->where('divesites.idDiveSite', '!=', 0)
        ->whereIn('dives.diver', $user)
        ->orderBy('divesites.idDiveSite', 'ASC')
        ->groupBy('divesites.idDiveSite')
        ->get();

        return $sites;
    }
}
