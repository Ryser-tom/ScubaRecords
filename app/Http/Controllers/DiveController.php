<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dive;
use App\DiveRelation;
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


    public function index()
    {
        $dives = DB::table('dive_tags')
        ->selectRaw('
            dives.*,  GROUP_CONCAT( 
            DISTINCT CONCAT(tags.name,":",dive_tags.txtValue)
            ORDER BY dive_tags.idTag 
            SEPARATOR ";") AS tags
            ')
        ->join('tags', 'tags.idTag', '=', 'dive_tags.idTag')
        ->join('dives', 'dives.idDive', '=', 'dive_tags.idDive')
        ->where('dives.public', 1)
        ->groupBy('dives.idDive')
        ->get();

        return $this->serializeDive($dives->toArray());
        //return $dives->toJson(JSON_PRETTY_PRINT);
    }
 
    public function show($dive)
    {
        $dives = DB::table('dive_tags')
        ->selectRaw('
            dives.*,  GROUP_CONCAT( 
            DISTINCT CONCAT(tags.name,":",dive_tags.txtValue)
            ORDER BY dive_tags.idTag 
            SEPARATOR ";") AS tags
            ')
        ->join('tags', 'tags.idTag', '=', 'dive_tags.idTag')
        ->join('dives', 'dives.idDive', '=', 'dive_tags.idDive')
        ->where('dives.public', 1)
        ->where('dives.idDive', $dive)
        ->groupBy('dives.idDive')
        ->get();

        return $this->serializeDive($dives->toArray());
        //return $dives->toJson(JSON_PRETTY_PRINT);
    }

    public function store(Request $request)
    {
        $dive = Dive::create($request->all());

        return response()->json($dive, 201);
    }

    public function update(Request $request, Dive $dive)
    {
        $dive->update($request->all());

        return response()->json($dive, 200);
    }

    public function delete(Dive $dive)
    {
        $dive->delete();

        return response()->json(null, 204);
    }

    public function test()
    {
        $dive = App\DiveRelation::find(1)
            ->dive_tags()
            ->get();
            
        return $dive->toJson(JSON_PRETTY_PRINT);
    }

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



    public function serializeDive($dive){
        $array = json_decode(json_encode($dive), true);
        foreach ($array as $key => $value) {
            $tags = $value["tags"];
            unset($array[$key]['tags']);

            $tmp = explode(';', $tags);
            foreach ($tmp as $key2 => $valueTag) {
                $tag = explode(':', $valueTag, 2);
                $array[$key][$tag[0]]=$tag[1];
            }
        }
        return json_encode($array, JSON_PRETTY_PRINT);
    }
}
