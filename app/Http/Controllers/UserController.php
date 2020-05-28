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

use App\User;
use App\Club;
use App\Follow;
use DB;

class UserController extends Controller
{
    public function index(){
        return json_encode(User::all());
    }
 
    public function show($user){
        $userAuth = Auth::user();

        $user = json_decode(User::where('name', $user)->first()->toJson(), true);

        $following = DB::table('following')
        ->select('*')
        ->where('idFollower', $userAuth->idUser)
        ->where('idFollowed', $user["idUser"])
        ->get();
        if ($following->first()) {
            $user['followed'] = true;
        }else{
            $user['followed'] = false;
        }
        return view('profile')->with( 'info', $user);

    }

    public function store(Request $request){
        $user = User::create($request->all());

        return response()->json($user, 201);
    }

    public function delete(User $user){
        $user->delete();

        return response()->json(null, 204);
    }

    public function getMembers($club){
        $userAuth = Auth::user();

        $members = DB::table('users')
        ->join('members', 'members.idUser', '=', 'users.idUser')
        ->join('clubs', 'members.idClub', '=', 'clubs.idClub')
        ->select('users.idUser', 'users.firstName', 'users.lastName', 'clubs.name')
        ->where('clubs.idClub', $club)
        ->get();
        
        return $members->toJson(JSON_PRETTY_PRINT);
    }

    // if possible change to ajax
    public function follow(Request $request){
        
        $userAuth = Auth::user();

        $result = DB::table('following')
        ->select('*')
        ->where('idFollowed', $_REQUEST["followed"])
        ->where('idFollower', $userAuth->idUser)
        ->first();

        if (is_null($result)) {
            $follow = new Follow();
            $follow->idFollowed = $_REQUEST["followed"];
            $follow->idFollower = $userAuth->idUser;
            $follow->save();
        }else{
            $deletedRows = Follow::where('idFollowed', $_REQUEST["followed"])
            ->where('idFollower', $userAuth->idUser)
            ->delete();
        }

        return redirect('/user/'.$_REQUEST["name"]);
    }

    public function showUpdate(){
        $userAuth = Auth::user();

        $user = DB::table('users')
            ->select('*')
            ->where('users.idUser', $userAuth->idUser)
            ->get();
        $dataUpdate = json_decode(json_encode($user->toArray()), true);
        return view('userUpdate')->with( 'data', $dataUpdate[0]);
    }

    public function update(){
        $user = Auth::user();
        
        $user = User::find($user->idUser);
        $user->name= $_POST["name"];
        $user->email= $_POST["email"];
        $user->phone= $_POST["phone"];
        $user->smallDesc= $_POST["smallDesc"];
        $user->description= $_POST["description"];
        $user->save();

        return redirect()->route('showUser', array('username' => $_POST["name"]));
    }
}
