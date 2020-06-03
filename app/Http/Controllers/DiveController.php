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
use App\follow;
use App\Dive;
use App\DiveTag;
use App\Tag;
use App\Divesite;
use App\Xml;
use DB;

define('UDDF', 'uddf');
define('SUUNTO', 'Suunto');
define('AERIS', 'PDC Model: OCi');
define('EARTH_RADIUS', 6371000);
define('SEARCH_RADIUS', 200);
define('TAGS', array('computerName',
 'model', 
 'o2', 
 'n2', 
 'he', 
 'ar', 
 'h2', 
 'dateTime', 
 'tpb', 
 'lead', 
 'diveTime', 
 'depth'
 ));

//TODO: find an other way than encode->decode
class DiveController extends Controller
{

    public function index(){
        $dive = new Dive;
        $dives = $dive->getAllPublicDives();

        $data = $this->serializeDive($dives->toArray(), 0);
        
        return view('dives')->with( 'data', json_decode($data, true));
    }
 
    public function publicSite($site){
        $dive = new Dive;
        $dives = $dive->getAllPublicDivesOfSite($site);

        $data[0] = json_decode($this->serializeDive($dives->toArray(), 0), true);

        $siteData = DiveSite::where('name',$site)->first();
        $data[1] = $siteData->toArray();
        
        return view('dives')->with( 'data', $data);
    }

    public function personnal(){
        $userAuth = Auth::user();
        $dive = new Dive;
        $dives = $dive->getDivesOfUser($userAuth);

        $data = $this->serializeDive($dives->toArray(), 0);
        
        return view('dives')->with( 'data', json_decode($data, true));
    }

    public function personnalSite($site){
        $user = Auth::user();
        $dive = new Dive;
        $dives = $dive->getPersonnalDivesOfSite($user, $site);
        $siteData = DiveSite::where('name',$site)->first();

        $data[0] = json_decode($this->serializeDive($dives->toArray(), 0), true);
        $data[1] = $siteData->toArray();
        
        return view('dives')->with( 'data', $data);
    }

    public function followed(){
        $userAuth = Auth::user();

        $follow = new Follow;
        $follow_ar = $follow->getUserArr($userAuth->idUser);

        $dive = new Dive;
        $dives = $dive->getDivesOfUser($follow_ar);

        $data = $this->serializeDive($dives->toArray(), 0);
        
        return view('dives')->with( 'data', json_decode($data, true));
    }

    //TODO: find a way to send value from a controller to multiple vue
    public function show($idDive){
        $userAuth = Auth::user();

        $dive = new Dive;
        $diveData = $dive->getInfo($idDive, $userAuth->idUser);

        $data = json_decode($this->serializeDive($diveData[0]->toArray(), $diveData[1]), true);
        $data[0]["datetime"] = $this->humanDate($data[0]["datetime"]);

        $data[1] = $this->getDiveData($data[0]["xml"]);
        return view('dive')->with( 'data', $data);
    }

    //Format data for the graph
    public function getDiveData($xml){
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

    public function showUpdate($idDive){
        $userAuth = Auth::user();

        $dive = new Dive;
        $diveData = $dive->getInfo($idDive, $userAuth->idUser);

        $sites = DB::table('divesites')->get();
        
        $dataUpdate = json_decode($this->serializeDive($diveData[0]->toArray(), $diveData[1]), true);

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

        $type[UDDF] = strstr($file, 'uddf');
        $type[SUUNTO] = strstr($file, 'Suunto');
        $type[AERIS] = strstr($file, 'PDC Model: OCi');

        $test = count($type);
        foreach ($type as $key => $value) {
            if ($value != false) {
                switch ($key) {
                    case UDDF:
                        break 2;
                    
                    case SUUNTO:
                        $converted = $this->suunto($file);
                        break;
                    
                    case SUUNTO:
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
        if ($key != UDDF) {
            file_put_contents($upload, $converted->saveXML());
        }
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
            $public = false;
        }else{
            $public = true;
        }

        $dive = Dive::find($_POST["idDive"]);
        $dive->diveSite= $_POST["site"];
        $dive->boat= $_POST["boat"];
        $dive->weather= $_POST["weather"];
        $dive->description= $_POST["description"];
        $dive->pressionInit= (int)$_POST["tpb"];
        $dive->public= $public;
        $dive->save();

        $_POST["dateTime"] = $_POST["date"]."T".$_POST["entryTime"];
        $_POST["diveTime"] = $_POST["diveTime"]*60;

        foreach (TAGS as $key => $value) {
            DiveTag::where('idDive', $_POST["idDive"])
                ->where('idTag', $key+1)
                ->update(['txtValue' => $_POST[$value]]);
        }

        
        return redirect()->route('showDive', array('diveId' => $_POST["idDive"]));
    }

    public function delete(Dive $dive){
        $dive->delete();

        return response()->json(null, 204);
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


        //TODO: check if it can be upgraded
        //get all tags values
        $testResult = $this->test($tags, $xmldata);
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
            //TODO: why ?
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

        // Because Suunto has no information about diving site in the export.
        if ($data["latitude"] == "") {
            return array('success' => true, 'idDiveSite' => 0);
        }

        $site = DB::table('divesites')
        ->selectRaw('
            divesites.idDiveSite,
            ( '.EARTH_RADIUS.' * acos( cos( radians('.$data['latitude'].') ) * cos( radians( divesites.latitude ) ) * cos( radians( divesites.longitude ) - radians('.$data['longitude'].') ) + sin( radians('.$data['latitude'].') ) * sin(radians(divesites.latitude)) ) ) AS distance 
        ')
        ->orderBy('distance', 'ASC')
        ->havingRaw('distance <'.SEARCH_RADIUS)
        ->first();

        if (!$site) {
            return $this->insert_diveSite($data);
        }
        return array('success' => true, 'idDiveSite' => $site->idDiveSite);
        
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

    //TODO: watch for upgrade 1
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
    //TODO: watch for upgrade 2
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

        $xmlDefault->profiledata->repetitiongroup->dive->informationafterdive->greatestdepth = (explode(" ", $data[8])[2]/3.2808);//TODO check formula and watch for const
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

    //TODO: try to replace the tag reading in UDDF()
    public function test($tags, $xml){
        
        $device = array();

        try{
            foreach($xml as $level0)
            {
                foreach($level0 as $key => $level1)
                {
                    $test = $level1;
                    try{
                        foreach($level1 as $key => $level2){
                            $test = $level2;
                            try{
                                foreach($level1 as $key => $level2){
                                    $test = $level2;
                                    try{
                                        foreach($level2 as $key => $level3){
                                            $test = $level3;
                                        }
                                    }catch(exception $e){
                                        //read data
                                    }
                                }
                            }catch(exception $e){
                                //read data
                            }
                        }
                    }catch(exception $e){
                        //read data
                    }
                }

                $devices[] = $device;
            }
        }
        finally {
            echo "Et finalement...";
        }
    }
}
