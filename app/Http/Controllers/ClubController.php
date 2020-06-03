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
        $club = new Club;
        $clubs = $club->getAllClubsOfUser($user->idUser);

        return view('clubs')->with( 'data', json_decode($clubs, true));
    }   
 
    public function show($clubName){
        $userAuth = Auth::user();
        $club = json_decode(Club::where('name', $clubName)->first(), true);
        
        $member = new Member;
        $member = $member->getMembership($club["idClub"], $userAuth->idUser);
        
        $club['member'] = $member->first();

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

    public function showUpdate($clubName){
        $user = Auth::user();

        $club = new Club;
        $dataUpdate = $club->getClubInfo($clubName);

        $member = new Member;
        $dataUpdate[1] = $member->getMembersOfClub($dataUpdate);

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

    public function changeMembership(Request $request){
        
        $userAuth = Auth::user();

        $member = new Member;
        $membership = $member->getMembership($_REQUEST["club"], $userAuth->idUser);

        if ($membership->count() == 0) {
            $member = new Member();
            $member->idClub = $_REQUEST["club"];
            $member->idUser = $userAuth->idUser;
            $member->save();
        }else{
            $deletedRows = Member::where('idClub', $_REQUEST["club"])
            ->where('idUser', $userAuth->idUser)
            ->delete();
        }

        return redirect('/club/'.$_REQUEST["clubName"]);
    }
}
