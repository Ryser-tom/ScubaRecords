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
use App\Http\Controllers\Tags;
use App\Dive;
use App\DiveTag;
use App\Tag;
use App\Divesite;
use App\Xml;
use DB;

//TODO: find an other way than encode->decode
class DiveController extends Controller
{

    public function index(){
        $dives = DB::table('dive_tags')
        ->selectRaw('
            DISTINCT dives.*, GROUP_CONCAT( 
            DISTINCT CONCAT(tags.name,":",dive_tags.txtValue)
            ORDER BY dive_tags.idTag 
            SEPARATOR ";") AS tags, 
            users.name as username,
            diveSites.name as diveSiteName
            ')
        ->join('tags', 'tags.idTag', '=', 'dive_tags.idTag')
        ->join('dives', 'dives.idDive', '=', 'dive_tags.idDive')
        ->join('users', 'users.idUser', '=', 'dives.diver')
        ->join('diveSites', 'diveSites.idDiveSite', '=', 'dives.diveSite')
        ->where('dives.public', 1)
        ->orderBy('dives.idDive', 'DESC')
        ->groupBy('dives.idDive')
        ->get();

        $data = $this->serializeDive($dives->toArray(), 0);
        
        return view('dives')->with( 'data', json_decode($data, true));
    }
 
    public function publicSite($site){
        $dives = DB::table('dive_tags')
        ->selectRaw('
            DISTINCT dives.*, GROUP_CONCAT( 
            DISTINCT CONCAT(tags.name,":",dive_tags.txtValue)
            ORDER BY dive_tags.idTag 
            SEPARATOR ";") AS tags, 
            users.name as username,
            diveSites.name as diveSiteName
            ')
        ->join('tags', 'tags.idTag', '=', 'dive_tags.idTag')
        ->join('dives', 'dives.idDive', '=', 'dive_tags.idDive')
        ->join('users', 'users.idUser', '=', 'dives.diver')
        ->join('diveSites', 'diveSites.idDiveSite', '=', 'dives.diveSite')
        ->where('dives.public', 1)
        ->where('diveSites.name', $site)
        ->orderBy('dives.idDive', 'DESC')
        ->groupBy('dives.idDive')
        ->get();

        $data[0] = json_decode($this->serializeDive($dives->toArray(), 0), true);

        $siteData = DiveSite::where('name',$site)->first();
        $data[1] = $siteData->toArray();
        
        return view('site')->with( 'data', $data);
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
            diveSites.name as diveSiteName
            ')
        ->join('tags', 'tags.idTag', '=', 'dive_tags.idTag')
        ->join('dives', 'dives.idDive', '=', 'dive_tags.idDive')
        ->join('users', 'users.idUser', '=', 'dives.diver')
        ->join('diveSites', 'diveSites.idDiveSite', '=', 'dives.diveSite')
        ->where('dives.diver', $user->idUser)
        ->orderBy('dives.idDive', 'DESC')
        ->groupBy('dives.idDive')
        ->get();

        $data = $this->serializeDive($dives->toArray(), 0);
        
        return view('dives')->with( 'data', json_decode($data, true));
    }

    public function personnalSite($site){
        $user = Auth::user();
        $dives = DB::table('dive_tags')
        ->selectRaw('
            DISTINCT dives.*, GROUP_CONCAT( 
            DISTINCT CONCAT(tags.name,":",dive_tags.txtValue)
            ORDER BY dive_tags.idTag 
            SEPARATOR ";") AS tags, 
            users.name as username, 
            diveSites.name as diveSiteName
            ')
        ->join('tags', 'tags.idTag', '=', 'dive_tags.idTag')
        ->join('dives', 'dives.idDive', '=', 'dive_tags.idDive')
        ->join('users', 'users.idUser', '=', 'dives.diver')
        ->join('diveSites', 'diveSites.idDiveSite', '=', 'dives.diveSite')
        ->where('dives.diver', $user->idUser)
        ->where('diveSites.name', $site)
        ->orderBy('dives.idDive', 'DESC')
        ->groupBy('dives.idDive')
        ->get();

        $data[0] = json_decode($this->serializeDive($dives->toArray(), 0), true);

        $siteData = DiveSite::where('name',$site)->first();
        $data[1] = $siteData->toArray();
        
        return view('site')->with( 'data', $data);
    }

    public function followed(){
        $user = Auth::user();

        $followed = DB::table('following')
        ->selectRaw('distinct GROUP_CONCAT(idFollowed) as id')
        ->where('idFollower', $user->idUser)
        ->get();
        $tmp = json_decode(json_encode($followed->toArray()), true);
        $follow_ar=explode(',', $tmp[0]['id']);
        
        $dives = DB::table('dive_tags')
        ->selectRaw('
            DISTINCT dives.*, GROUP_CONCAT( 
            DISTINCT CONCAT(tags.name,":",dive_tags.txtValue)
            ORDER BY dive_tags.idTag 
            SEPARATOR ";") AS tags, 
            users.name as username,
            diveSites.name as diveSiteName
            ')
        ->join('tags', 'tags.idTag', '=', 'dive_tags.idTag')
        ->join('dives', 'dives.idDive', '=', 'dive_tags.idDive')
        ->join('users', 'users.idUser', '=', 'dives.diver')
        ->join('diveSites', 'diveSites.idDiveSite', '=', 'dives.diveSite')
        ->whereIn('dives.diver', $follow_ar)
        ->orderBy('dives.idDive', 'DESC')
        ->groupBy('dives.idDive')
        ->get();

        $data = $this->serializeDive($dives->toArray(), 0);
        
        return view('dives')->with( 'data', json_decode($data, true));
    }

    //TODO: find a way to send value from a controller to multiple vue
    public function show($id){
        $user = Auth::user();

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
                dives.*, diveSites.name as diveSiteName,  GROUP_CONCAT( 
                DISTINCT CONCAT(tags.name,":",dive_tags.txtValue)
                ORDER BY dive_tags.idTag 
                SEPARATOR ";") AS tags
                ')
            ->join('tags', 'tags.idTag', '=', 'dive_tags.idTag')
            ->join('dives', 'dives.idDive', '=', 'dive_tags.idDive')
            ->join('diveSites', 'diveSites.idDiveSite', '=', 'dives.diveSite')
            ->where(function($q) use ($user) {
                $q->where('dives.public', 1)
                ->orWhere('dives.diver', $user->idUser);
            })
            ->where('dives.idDive', $id)
            ->groupBy('dives.idDive')
            ->get();

        $data = json_decode($this->serializeDive($dive->toArray(), $diveNb), true);
        $data[0]["datetime"] = $this->humanDate($data[0]["datetime"]);

        $data[1] = $this->getDiveDataRT($data[0]["xml"]);
        return view('dive')->with( 'data', $data);
    }

    public function getDiveDataRT($xml){
        $file = Storage::get($xml);
        $xmldata = simplexml_load_string($file);
        $result="";

        //$waypoint = $xmldata->profiledata->repetitiongroup->dive->sample->waypoint;
        $waypoints = $xmldata->profiledata->repetitiongroup->dive->samples;
        $temperature;
        for ($i=0; $i < count($waypoints->waypoint) ; $i++) { 
            $tmp = $waypoints->waypoint[$i];
            if (isset($tmp->temperature)) {
                $temperature = $tmp->temperature;
            }
            $time = (int) $tmp->divetime;
            $result = $result.$time.";".$tmp->depth.";".$temperature."|";
        }
        return $result;
    }

    public function showUpdate($id){
        $user = Auth::user();

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
                dives.*, diveSites.name as diveSiteName,  GROUP_CONCAT( 
                DISTINCT CONCAT(tags.name,":",dive_tags.txtValue)
                ORDER BY dive_tags.idTag 
                SEPARATOR ";") AS tags
                ')
            ->join('tags', 'tags.idTag', '=', 'dive_tags.idTag')
            ->join('dives', 'dives.idDive', '=', 'dive_tags.idDive')
            ->join('diveSites', 'diveSites.idDiveSite', '=', 'dives.diveSite')
            ->where(function($q) use ($user) {
                $q->where('dives.public', 1)
                ->orWhere('dives.diver', $user->idUser);
            })
            ->where('dives.idDive', $id)
            ->groupBy('dives.idDive')
            ->get();

        $sites = DB::table('divesites')
            ->select('*')
            ->get();
        
        $dataUpdate = json_decode($this->serializeDive($dive->toArray(), $diveNb), true);

        $dataUpdate[1] = json_decode($sites, true);
        return view('diveUpdate')->with( 'data', $dataUpdate);
    }

    public function store(Request $request){
        //validate
        $this->validate($request, [
            'log' => 'required',
        ]);

        $user = Auth::user();
        if ($request->public == 'on') {
            $public = 0;
        }else{
            $public = 1;
        };

        $upload = $request->file('log');
        $file = file_get_contents($upload->getPathname(), true);

        $type['Uddf'] = strstr($file, 'uddf');
        $type['Suunto'] = strstr($file, 'Suunto');
        $type['Aeris'] = strstr($file, 'PDC Model: OCi');

        $test = count($type);
        foreach ($type as $key => $value) {
            if ($value != false) {
                switch ($key) {
                    case 'Uddf':
                        # code...
                        break 2;
                    
                    case 'Suunto':
                        $converted = $this->suunto($file);
                        break;
                    
                    case 'Aeris':
                        $converted = $this->aeris($file);
                        break;

                    default:
                        break;
                }
            }else{
                $test--;
            }
        }
        if ($test==0) {
            return "The file you just sended is not supported. You can contact me at tom.rsr@eduge.ch";
        }
        // replace old data in imported file for save
        file_put_contents($upload, $converted->saveXML());
        $did_it_worked = file_get_contents($upload->getPathname(), true);
        // https://laracasts.com/discuss/channels/laravel/how-to-write-texts-to-file-in-laravel?page=1
        $path=$upload->store('public/storage');

        $tags = Tag::all()->toJson();

        $result = $this->uddf($public, $path, json_decode($tags, true));

        if ($result["success"]) {
            return redirect()->route('showUpdateDive', array('diveId' => $result["diveId"]));
        }
    }

    //TODO: optimize tag update, correct the date
    public function update(Request $request, Dive $diveData){
        
        if (isset($_POST["public"])) {
            $public = 0;
        }else{
            $public = 1;
        }

        $dive = Dive::find($_POST["idDive"]);
        $dive->diveSite= $_POST["site"];
        $dive->boat= $_POST["boat"];
        $dive->weather= $_POST["weather"];
        $dive->description= $_POST["description"];
        $dive->pressionInit= $_POST["tpb"];
        $dive->public= $public;
        $dive->save();

        $dateTime = $_POST["date"]."T".$_POST["entryTime"];
        $diveTime = $_POST["diveTime"]*60;

        DiveTag::where('idDive', $_POST["idDive"])
          ->where('idTag', 1)
          ->update(['txtValue' => $_POST["computerName"]]);

        DiveTag::where('idDive', $_POST["idDive"])
          ->where('idTag', 2)
          ->update(['txtValue' => $_POST["model"]]);

        DiveTag::where('idDive', $_POST["idDive"])
          ->where('idTag', 3)
          ->update(['txtValue' => $_POST["o2"]]);

        DiveTag::where('idDive', $_POST["idDive"])
          ->where('idTag', 4)
          ->update(['txtValue' => $_POST["n2"]]);

        DiveTag::where('idDive', $_POST["idDive"])
          ->where('idTag', 5)
          ->update(['txtValue' => $_POST["he"]]);

        DiveTag::where('idDive', $_POST["idDive"])
          ->where('idTag', 6)
          ->update(['txtValue' => $_POST["ar"]]);

        DiveTag::where('idDive', $_POST["idDive"])
          ->where('idTag', 7)
          ->update(['txtValue' => $_POST["h2"]]);

        DiveTag::where('idDive', $_POST["idDive"])
          ->where('idTag', 8)
          ->update(['txtValue' => $dateTime]);
        DiveTag::where('idDive', $_POST["idDive"])
          ->where('idTag', 10)
          ->update(['txtValue' => $_POST["tpb"]]);

        DiveTag::where('idDive', $_POST["idDive"])
          ->where('idTag', 12)
          ->update(['txtValue' => $_POST["lead"]]);


        DiveTag::where('idDive', $_POST["idDive"])
          ->where('idTag', 13)
          ->update(['txtValue' => $diveTime]);



        DiveTag::where('idDive', $_POST["idDive"])
          ->where('idTag', 14)
          ->update(['txtValue' => $_POST["depth"]]);

        return redirect()->route('showDive', array('diveId' => $_POST["idDive"]));
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
        $json = json_encode($array, JSON_PRETTY_PRINT);
        return $json;
    }

    /*
        multiple foreach to read each child, and if a node (child) 
        corespond to the tag name add it's tag name and value in the $result array
    */
    public function uddf($public, $path, $tags){
        $user = Auth::user();
        $file = Storage::get($path);
        $xmldata = simplexml_load_string($file);

        $result_site['name']        =(string)$xmldata->divesite->site->name;
        $result_site['latitude']    =(string)$xmldata->divesite->site->geography->latitude;
        $result_site['longitude']   =(string)$xmldata->divesite->site->geography->longitude;


        //get all tags values
        foreach ($tags as $key => $tag) {
            $tag_name = $tag["name"];
            foreach ($xmldata as $key => $level0) {
                $test = $level0->$tag_name;
                if(!empty($test)){
                    if ($tag["name"] == "datetime") {
                        if (strstr($test, 'T')) {
                            $result_tag[$tag["idTag"]] = (string)$test[0];
                            break;
                        }
                    }else{
                        $result_tag[$tag["idTag"]] = (string)$test[0];
                        break;
                    }
                }
                foreach ($level0 as $key => $level1) {
                    $test = $level1->$tag_name;
                    if(!empty($test)){
                        $result_tag[$tag["idTag"]] = (string)$test[0];
                        break;
                    }
                    foreach ($level1 as $key => $level2) {
                        $test = $level2->$tag_name;
                        if(!empty($test)){
                            $result_tag[$tag["idTag"]] = (string)$test[0];
                            break;
                        }
                        foreach ($level2 as $key => $level3) {
                            $test = $level3->$tag_name;
                            if(!empty($test)){
                                $result_tag[$tag["idTag"]] = (string)$test[0];
                                break;
                            }
                            foreach ($level3 as $key => $level3) {
                                $test = $level3->$tag_name;
                                if(!empty($test)){
                                    $result_tag[$tag["idTag"]] = (string)$test[0];
                                    break;
                                }
                
                            }
                        }
                    }
                }
            }
        }

        $diveSite_id=$this->check_divesite_exist($result_site);
        if($diveSite_id["success"]){
            $dive_id=$this->insert_dive($diveSite_id["idDiveSite"], $path, $user->idUser, $public);
            if($dive_id["success"]){
                $result = $this->insert_diveTags($result_tag, $dive_id["idDive"]);
            }
        }
        if ($result) {
            $result['diveId'] = $dive_id["idDive"];
            return $result;
        } 
    }

    /**
     * function to insert dive site
     * 1.   check if dive site exist in db 
     *          (WHERE latitude BETWEEN 46.300 AND 46.309 AND longitude BETWEEN 6.240 AND 6.249)
     * 2.   if it exist return id
     * 2.5  if not insert it and return id
     * 
     * 
     * 
     * 
     * 
     * 
     */
    public function insert_diveSite($data){

        $site = new Divesite;

        $site->name = $data['name'];
        $site->latitude = $data['latitude'];
        $site->longitude = $data['longitude'];
        
        $site->save();
        Divesite::create($request->all());

        return array('success' => true, 'idDiveSite' => $site->idDiveSite);
    }

    public function check_divesite_exist($data){

        // actualy suunto has no information about diving site in the export.
        if ($data["latitude"] == "") {
            return array('success' => true, 'idDiveSite' => 0);
        }

        $site = DB::table('diveSites')
        ->selectRaw('
            diveSites.idDiveSite,
            ( 6371000 * acos( cos( radians('.$data['latitude'].') ) * cos( radians( divesites.latitude ) ) * cos( radians( divesites.longitude ) - radians('.$data['longitude'].') ) + sin( radians('.$data['latitude'].') ) * sin(radians(divesites.latitude)) ) ) AS distance 
        ')
        ->orderBy('distance', 'ASC')
        ->havingRaw('distance < 200')
        ->first();

        if (!$site) {
            return $this->insert_diveSite($data);
        }else{
            return array('success' => true, 'idDiveSite' => $site->idDiveSite);
        }
        
    }

    public function insert_dive($diveSite_id, $path, $idUser, $public){
        $dive = new dive;
        $dive->diveSite = $diveSite_id;
        $dive->xml = $path;
        $dive->diver = $idUser;
        $dive->public = $public;
        $dive->save();

        return array('success' => true, 'idDive' => $dive->idDive);
    }

    public function insert_diveTags($diveTags, $dive_id){
        $data=[];
        foreach ($diveTags as $key => $value) {
            $data[$key] = ['idDive'=>$dive_id, 'idTag'=> $key, 'txtValue'=> $value];
        }
        
        $result = diveTag::insert($data); // Eloquent approach


        return array('success' => $result);
    }

    public function suunto($string){
        $xmldata = simplexml_load_string($string);
        $defaultFile = Storage::get('public/default/default.uddf');
        $xmlDefault = simplexml_load_string($defaultFile);


        $xmlDefault->gasdefinitions->mix->he = $xmldata->DiveMixtures->DiveMixture->Helium;
        $xmlDefault->gasdefinitions->mix->o2 = $xmldata->DiveMixtures->DiveMixture->Oxygen;

        $xmlDefault->profiledata->repetitiongroup->dive->informationbeforedive->tankdata->tankvolume = $xmldata->CylinderVolume;
        $xmlDefault->profiledata->repetitiongroup->dive->informationbeforedive->tankdata->tankpressurebegin = $xmldata->CylinderWorkPressure;

        $xmlDefault->profiledata->repetitiongroup->dive->informationafterdive->greatestdepth = $xmldata->MaxDepth;
        $xmlDefault->profiledata->repetitiongroup->dive->informationafterdive->averagedepth = $xmldata->AvgDepth;
        $xmlDefault->profiledata->repetitiongroup->dive->informationafterdive->diveduration = $xmldata->Duration;

        $samplesJson = json_encode($xmldata->DiveSamples);
        $sample = json_decode($samplesJson, true);
        foreach ($sample['Dive.Sample'] as $key => $value) {

            $waypoint = $xmlDefault->profiledata->repetitiongroup->dive->samples->addChild('waypoint');
            $waypoint->addChild('depth', $value["Depth"]);
            $waypoint->addChild('divetime', $value["Time"]);
            $waypoint->addChild('temperature', $value["Temperature"]);
        }

        return $xmlDefault;

    }

    public function aeris($string){
        $data = explode(PHP_EOL, $string);
        $defaultFile = Storage::get('public/default/default.uddf');
        $xmlDefault = simplexml_load_string($defaultFile);

        $date = explode(" ", $data[4]);
        $date = explode("/", $date[2]);
        $time = explode(" ", $data[5]);
        $timeEx = explode(":", $data[5]);

        $xmlDefault->profiledata->repetitiongroup->dive->informationbeforedive->datetime = $date[2]."-".$date[0]."-".$date[1]."T".$time[2];
        $xmlDefault->profiledata->repetitiongroup->dive->informationbeforedive->surfaceintervalbeforedive = explode(": ", $data[7])[1];

        $xmlDefault->profiledata->repetitiongroup->dive->informationbeforedive->tankdata->tankvolume = explode(": ", $data[12])[1];
        $xmlDefault->profiledata->repetitiongroup->dive->informationbeforedive->tankdata->tankpressurebegin = explode(": ", $data[13])[1];
        $xmlDefault->profiledata->repetitiongroup->dive->informationbeforedive->tankdata->breathingconsumptionvolume = explode(" ", $data[14])[2];

        $xmlDefault->profiledata->repetitiongroup->dive->informationafterdive->greatestdepth = (explode(" ", $data[8])[2]/3.2808);//TODO check formula
        $xmlDefault->profiledata->repetitiongroup->dive->informationafterdive->averagedepth;

        $xmlDefault->profiledata->repetitiongroup->dive->informationafterdive->diveduration = ($timeEx[1]*60+$timeEx[2]);

        
        //test to know what type of separator has been used
        $separators = array(",", ";", "\t");
        $separator;
        foreach ($separators as $key => $s) {
            $test = explode($s, $data[37]);
            if (count($test) != 1) {
                $separator = $s;
                break;
            }
        }

        for ($i=37; $i < count($data); $i++) { 
            $tmp = explode($separator, $data[$i]);
            if (count($tmp) < 2) {
                break;// break on last line
            }
            $time = explode(":", $tmp[0]);
            $time = ($time[0]*3600)+($time[1]*60)+$time[2];
            $depth = number_format($tmp[1]/3.2808, 1, ',', '');
            $temperature = number_format(5/9*($tmp[9]-32) + 273.15, 2, ',', '');

            $waypoint = $xmlDefault->profiledata->repetitiongroup->dive->samples->addChild('waypoint');
            $waypoint->addChild('depth', $depth);
            $waypoint->addChild('divetime', $time);
            $waypoint->addChild('temperature', $temperature);
        }

        return $xmlDefault;

    }

    public function humanDate($datetime){
        $humanDate = "";
		$date = preg_split('/[-T]/',  $datetime);
			
		date_default_timezone_set('Europe/Paris');
		setlocale(LC_TIME, 'fr_FR.utf8','fra');
        $humanDate = strftime("%A %d %B %Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
        return $humanDate;
    }
}
