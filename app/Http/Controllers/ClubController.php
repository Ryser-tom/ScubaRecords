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
use Illuminate\Support\Facades\Auth;
use App\Club;
use App\User;
use App\Member;
use DB;
use Carbon\Carbon;

class ClubController extends Controller
{
    public function index(){
        $clubs = Club::all();
        return view('clubs')->with( 'data', json_decode($clubs, true));
    }

    public function userClubs(){
        $user = Auth::user();

        $clubs = DB::table('clubs')
        ->select('*')
        ->join('members', 'members.idClub', '=', 'clubs.idClub')
        ->where('members.idUser', $user->idUser)
        ->get();

        return view('clubs')->with( 'data', json_decode($clubs, true));
    }   
 
    public function show($club){
        $userAuth = Auth::user();

        $club = json_decode(Club::where('idClub', $club)->first()->toJson(), true);

        $member = DB::table('members')
        ->select('*')
        ->where('idClub', $club["idClub"])
        ->where('idUser', $userAuth->idUser)
        ->get();
        if ($member->first()) {
            $club['member'] = true;
        }else{
            $club['member'] = false;
        }

        return view('profile')->with( 'info', $club);
    }

    public function store(Request $request){
        $club = Club::create($request->all());

        return response()->json($club, 201);
    }

    public function update(Request $request){
        $user = Auth::user();

        if ($_POST["master"] == $user->idUser) {
            if ($_POST["idClub"] == "") {
                $club =new Club();
            }else{
                $club = Club::find($_POST["idClub"]);
            }
            $club->name= $_POST["name"];
            $club->smallDesc= $_POST["motto"];
            $club->description= $_POST["description"];
            $club->email= $_POST["email"];
            $club->master= $_POST["master"];
            $club->save();

            return redirect()->route('showClub', array('clubName' => $_POST["name"]));
        }
        return redirect()->route('home');
    }

    public function delete(Club $club){
        $club->delete();

        return response()->json(null, 204);
    }

    public function getMembers($club){
        $members = DB::table('clubs')
        ->join('members', 'members.idClub', '=', 'clubs.idClub')
        ->join('users', 'members.idUser', '=', 'users.idUser')
        ->select('users.idUser', 'users.name as username', 'clubs.name')
        ->where('clubs.idClub', $club)
        ->get();

        return $members->toJson(JSON_PRETTY_PRINT);
    }

    public function addMember(Request $request){
        $member = Member::create($request->all());

        return response()->json($member, 201);
    }

    public function deleteMember($idUser, $idClub){
        Member::where('idUser', $idUser)
        ->where('idClub', $idClub)
        ->delete();

        return response()->json(null, 204);
    }

    //$club is the name of the club
    public function showUpdate($club){
        $user = Auth::user();

        $clubInfos = DB::table('clubs')
            ->select('*')
            ->where('clubs.name', $club)
            ->get();
        $dataUpdate = json_decode(json_encode($clubInfos->toArray()), true);

        $members = DB::table('users')
            ->join('members', 'members.idUser', '=', 'users.idUser')
            ->where('members.idClub', $dataUpdate[0]["idClub"])
            ->get();
        

        $dataUpdate[1] = json_decode(json_encode($members->toArray()), true);
        return view('clubUpdate')->with( 'data', $dataUpdate);
    }

    public function close($idClub){
        $user = Auth::user();

        $club = Club::find($idClub);
        if ($club->master == $user->idUser) {
            $club->endDateTime = Carbon::now()->toDateTimeString();
            $club->save();
            //TODO: temporary redirect
            return redirect()->route('showClub', array('clubName' => $club->name));
        }
        return redirect()->route('home');
    }

    public function member(Request $request){
        
        $userAuth = Auth::user();

        $result = DB::table('members')
        ->select('*')
        ->where('idUser', $userAuth->idUser)
        ->where('idclub', $_REQUEST["club"])
        ->first();

        if (is_null($result)) {
            $member = new Member();
            $member->idClub = $_REQUEST["club"];
            $member->idUser = $userAuth->idUser;
            $member->save();
        }else{
            $deletedRows = Member::where('idClub', $_REQUEST["club"])
            ->where('idUser', $userAuth->idUser)
            ->delete();
        }

        return redirect('/club/'.$_REQUEST["club"]);
    }
}
