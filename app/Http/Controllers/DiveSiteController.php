<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\DiveSite;
use App\Follow;

define("MARKER_COLOR", "#f00");
define("MARKER_SIZE", "small");

class DiveSiteController extends Controller
{

    public function public(){
        $diveSite = new DiveSite;
        $sites = $diveSite->getPublicSites();
        
        $geojson = $this->geoJson($sites);
        
        return view('map')->with( 'data', json_encode($geojson), true);
    }

    public function personnal(){
      $userAuth = Auth::user();
      $diveSite = new DiveSite;
        $sites = $diveSite->getuserSites($userAuth);
        
        $geojson = $this->geoJson($sites);
        
        return view('map')->with( 'data', json_encode($geojson), true);
    }

    public function followed(){
      $userAuth = Auth::user();

      $follow = new Follow;
      $follow_ar = $follow->getUserArr($userAuth->idUser);

      $diveSite = new DiveSite;
      $sites = $diveSite->getuserSites($follow_ar);
        
        $geojson = $this->geoJson($sites);
        
        return view('map')->with( 'data', json_encode($geojson), true);
    }

    //TODO: check if site not to close to another
    public function update(){

      if ($_POST["idSite"] == "") {
          $site =new DiveSite();
      }else{
          $site = DiveSite::find($_POST["idSite"]);
      }
      $site->name         = $_POST["name"];
      $site->description  = $_POST["description"];
      $site->difficulty   = $_POST["difficulty"];
      $site->latitude     = $_POST["lat"];
      $site->longitude    = $_POST["lng"];
      $site->save();

      return redirect()->route('showSite', array('site' => $_POST["name"]));
    }

    // function to create a geojson to feed Leaflet and add markpoint
    public function geoJson($sites){
      $geojson = array( 'type' => 'FeatureCollection', 'features' => array());
        foreach ($sites as $site) {
            $marker = array(
                'type' => 'Feature',
                'properties' => array(
                  'name' => $site->name,
                  'id' => $site->idDiveSite,
                  'marker-color' => MARKER_COLOR,
                  'marker-size' => MARKER_SIZE
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
      return $geojson;
    }
}
