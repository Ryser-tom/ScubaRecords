<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class DiveSiteController extends Controller
{

    public function public(){
        $sites = DB::table('divesites')
        ->select('divesites.*')
        ->join('dives', 'dives.diveSite', '=', 'divesites.idDiveSite')
        ->where('divesites.idDiveSite', '!=', 0)
        ->orderBy('divesites.idDiveSite', 'ASC')
        ->groupBy('divesites.idDiveSite')
        ->get();
        
        $geojson = array( 'type' => 'FeatureCollection', 'features' => array());
        foreach ($sites as $site) {
            $marker = array(
                'type' => 'Feature',
                'properties' => array(
                  'name' => $site->name,
                  'id' => $site->idDiveSite,
                  'marker-color' => '#f00',
                  'marker-size' => 'small'
                ),
                'geometry' => array(
                  'type' => 'Point',
                  'coordinates' => array( 
                    $site->longitude,
                    $site->latitude
                  )
                )
              );
              array_push($geojson['features'], $marker);
        }
        
        return view('map')->with( 'data', json_encode($geojson), true);
    }

    //TODO test
    public function personal($id){
        $user = Auth::user();

        $sites = DB::table('divesites')
        ->select('divesites.*', 'users.name as diver')
        ->join('dives', 'dives.diveSite', '=', 'divesites.idDiveSite')
        ->join('users', 'users.idUser', '=', 'dives.diver')
        ->where(function($q) use ($user) {
            $q->where('dives.public', 1)
              ->orWhere('dives.diver', $user->idUser);
        })
        ->where('dives.diver', $id)
        ->orderBy('divesites.idDiveSite', 'ASC')
        ->get();
        
        return view('map')->with( 'data', json_decode($sites, true));
    }

    //TODO test
    public function follow(){
        $user = Auth::user();

        $followed = DB::table('following')
        ->selectRaw('distinct GROUP_CONCAT(idFollowed) as id')
        ->where('idFollower', $user->idUser)
        ->get();
        $tmp = json_decode(json_encode($followed->toArray()), true);
        $follow_ar=explode(',', $tmp[0]['id']);

        $sites = DB::table('divesites')
        ->select('divesites.*', 'users.name as diver')
        ->join('dives', 'dives.diveSite', '=', 'divesites.idDiveSite')
        ->join('users', 'users.idUser', '=', 'dives.diver')
        ->whereIn('dives.diver', $follow_ar)
        ->where('dives.public', 1)
        ->orderBy('divesites.idDiveSite', 'ASC')
        ->get();
        
        return view('map')->with( 'data', json_decode($sites, true));
    }
}
