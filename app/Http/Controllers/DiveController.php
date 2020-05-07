<?php

/*******************************************************************************
AUTEUR      : Tom Ryser
LIEU        : CFPT Informatique Genève
DATE        : Avril 2020
TITRE PROJET: ScubaRecords
VERSION     : 1.0
*******************************************************************************/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Dive;
use App\Xml;
use DB;

class DiveController extends Controller
{
    /*


        SELECT dives.*, GROUP_CONCAT( 
            DISTINCT CONCAT('{',tags.name,':',dive_tags.txtValue,'}')
            ORDER BY dive_tags.idTag 
            SEPARATOR ';')
        FROM dive_tags
        INNER JOIN tags ON tags.idTag = dive_tags.idTag
        INNER JOIN dives ON dives.idDive = dive_tags.idDive
        WHERE dives.public = 1
        GROUP BY dive_tags.idDive

        $dives = DB::table('dive_tags')
        ->selectRaw('
            dives.*, GROUP_CONCAT( 
            DISTINCT CONCAT(tags.name,":",dive_tags.txtValue)
            ORDER BY dive_tags.idTag 
            SEPARATOR " ")
            ')
        ->join('tags', 'tags.idTag', '=', 'dive_tags.idTag')
        ->join('dives', 'dives.idDive', '=', 'dive_tags.idDive')
        ->where('public', 1)
        ->groupBy('dives.idDive')
        ->get();

        return $dives->toJson(JSON_PRETTY_PRINT);
    */


    public function index(){
        $dives = DB::table('dive_tags')
        ->selectRaw('
            DISTINCT dives.*, GROUP_CONCAT( 
            DISTINCT CONCAT(tags.name,":",dive_tags.txtValue)
            ORDER BY dive_tags.idTag 
            SEPARATOR ";") AS tags, 
            users.name as username, 
            locations.name as locationName, 
            diveSites.name as diveSiteName
            ')
        ->join('tags', 'tags.idTag', '=', 'dive_tags.idTag')
        ->join('dives', 'dives.idDive', '=', 'dive_tags.idDive')
        ->join('users', 'users.idUser', '=', 'dives.diver')
        ->join('locations', 'locations.idLocation', '=', 'dives.location')
        ->join('diveSites', 'diveSites.idDiveSite', '=', 'dives.diveSite')
        ->where('dives.public', 1)
        ->orderBy('dives.idDive', 'DESC')
        ->groupBy('dives.idDive')
        ->get();

        $data = $this->serializeDive($dives->toArray(), 0);
        
        return view('dives')->with( 'data', json_decode($data, true));
    }
 
    public function personnal(){
        $user = Auth::user();
        $dives = DB::table('dive_tags')
        ->selectRaw('
            DISTINCT dives.*, GROUP_CONCAT( 
            DISTINCT CONCAT(tags.name,":",dive_tags.txtValue)
            ORDER BY dive_tags.idTag 
            SEPARATOR ";") AS tags, 
            users.name as username, 
            locations.name as locationName, 
            diveSites.name as diveSiteName
            ')
        ->join('tags', 'tags.idTag', '=', 'dive_tags.idTag')
        ->join('dives', 'dives.idDive', '=', 'dive_tags.idDive')
        ->join('users', 'users.idUser', '=', 'dives.diver')
        ->join('locations', 'locations.idLocation', '=', 'dives.location')
        ->join('diveSites', 'diveSites.idDiveSite', '=', 'dives.diveSite')
        ->where('dives.diver', $user->idUser)
        ->orderBy('dives.idDive', 'DESC')
        ->groupBy('dives.idDive')
        ->get();

        $data = $this->serializeDive($dives->toArray(), 0);
        
        return view('dives')->with( 'data', json_decode($data, true));
    }

    public function show($id){
        $diver = DB::table('dives')
            ->select('diver')
            ->where('idDive', $id)
            ->get();
        $tmp = json_decode(json_encode($diver->toArray()), true);

        $diveNb = DB::table('dives')
            ->join('users', 'users.idUser', '=', 'dives.diver')
            ->where('dives.diver', $tmp[0]["diver"])
            ->where('dives.idDive', '<=', $id)
            ->count();

        $dive = DB::table('dive_tags')
        ->selectRaw('
            dives.*,  GROUP_CONCAT( 
            DISTINCT CONCAT(tags.name,":",dive_tags.txtValue)
            ORDER BY dive_tags.idTag 
            SEPARATOR ";") AS tags
            ')
        ->join('tags', 'tags.idTag', '=', 'dive_tags.idTag')
        ->join('dives', 'dives.idDive', '=', 'dive_tags.idDive')
        ->where('dives.public', 1)
        ->where('dives.idDive', $id)
        ->groupBy('dives.idDive')
        ->get();

        $data = $this->serializeDive($dive->toArray(), $diveNb);
        
        return view('dive')->with( 'data', json_decode($data, true));
    }

    public function store(Request $request){
        //validate
        $this->validate($request, [
            'xml' => 'required',
        ]);

        $user = Auth::user();
        if ($request->public == 'on') {
            $public = 0;
        }else{
            $public = 1;
        };

        $upload = $request->file('xml');
        $path=$upload->store('public/storage');
        $this->readXml($path);

        $dive = new Dive;
        $dive->diveSite = $request->diveSite;
        $dive->boat = $request->boat;
        $dive->weather = $request->weather;
        $dive->weight = $request->weight;
        $dive->description = $request->description;
        $dive->location = $request->location;
        $dive->pressionInit = $request->pressionInit;
        $dive->xml = $path;
        $dive->diver = $user->idUser;
        $dive->public = $public;

        $dive->save();

        return response()->json($dive, 201);
    }

    public function update(Request $request, Dive $dive){
        $dive->update($request->all());

        return response()->json($dive, 200);
    }

    public function delete(Dive $dive){
        $dive->delete();

        return response()->json(null, 204);
    }

    public function test($id){
        $diver = DB::table('dives')
            ->select('diver')
            ->where('idDive', $id)
            ->get();
        $tmp = json_decode(json_encode($diver->toArray()), true);

        $diveNb = DB::table('dives')
            ->join('users', 'users.idUser', '=', 'dives.diver')
            ->where('dives.diver', $tmp[0]["diver"])
            ->where('dives.idDive', '<=', $id)
            ->count();

        $dive = DB::table('dive_tags')
        ->selectRaw('
            dives.*,  GROUP_CONCAT( 
            DISTINCT CONCAT(tags.name,":",dive_tags.txtValue)
            ORDER BY dive_tags.idTag 
            SEPARATOR ";") AS tags
            ')
        ->join('tags', 'tags.idTag', '=', 'dive_tags.idTag')
        ->join('dives', 'dives.idDive', '=', 'dive_tags.idDive')
        ->where('dives.public', 1)
        ->where('dives.idDive', $id)
        ->groupBy('dives.idDive')
        ->get();

        $data = $this->serializeDive($dive->toArray(), $diveNb);
        
        return view('dive')->with( 'dive', json_decode($data[0], true));
    }

    public function serializeDive($dive, $diveNb){
        $array = json_decode(json_encode($dive), true);
        foreach ($array as $key => $value) {
            $tags = $value["tags"];
            unset($array[$key]['tags']);
            $array[$key]['diveNb'] = $diveNb;

            $tmp = explode(';', $tags);
            foreach ($tmp as $key2 => $valueTag) {
                $tag = explode(':', $valueTag, 2);
                $array[$key][$tag[0]]=$tag[1];
            }
        }
        return json_encode($array, JSON_PRETTY_PRINT);
        /* computer_name:Shearwater Predator;
        computer_model:Shearwater Predator;
        mix_o2:0.210;
        mix_n2:0.790;
        mix_he:0.000;
        mix_ar:0.000;
        mix_h2:0.000;
        datetime_start:2006-04-28T15:49;
        tank_volume:0.015;
        tank_press_start:20000000.0;
        consumption:0.0002;
        lead_quantity:0;
        dive_duration:4900;
        datetime_end:

        */
    }

    public function readXml($path){
        $file = Storage::get($path);
        $xmldata = simplexml_load_string($file);
        $test = (string) $xmldata->greatestdepth;
        $result = $xmldata->xpath("greatestdepth");
        $json = json_encode($xmldata);

        foreach ($variable as $key => $value) {
            $dive_site_name = $xmldata->divesite->site->xpath('name');
            $site_name = $xmldata->divesite->site->geography->xpath('name');
            // name, o2, n2, he, ar, h2
            $value = $xmldata->gasdefinitions->mix->xpath($tag);
            // divenumber, datetime
            $value = $xmldata->profiledata->repetitiongroup->dive->informationbeforedive->xpath($tag);
            // leadquantity
            $value = $xmldata->profiledata->repetitiongroup->dive->informationbeforedive->equipmentused->xpath($tag);
            // greatestdepth, averagedepth, diveduration
            $value = $xmldata->profiledata->repetitiongroup->dive->informationafterdive->xpath($tag);

            //$value = $xmldata->profiledata->repetitiongroup->dive->sample->xpath($tag);

        }
        //echo $xmldata->employee[0]->firstname . "<\n>";
        //echo $xmldata->employee[1]->firstname; 
    }
}
