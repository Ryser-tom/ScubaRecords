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
 
    public function show($userName){
        $userAuth = Auth::user();

        $user = new User;
        // send and array with the username for ->whereIn
        $user = $user->getUserInfosByName(explode(' ', $userName))[0];

        $follow = new Follow;
        $user['follow'] = $follow->followStatus($userAuth->idUser, $user['idUser']);
        return view('profile')->with( 'info', $user);

    }

    // update the "follow" status 
    public function follow(Request $request){
        
        $userAuth = Auth::user();
        
        $follow = new Follow;
        $result = $follow->followStatus($userAuth->idUser, $_REQUEST["followed"]);

        if (is_null($result)) {
            $follow = new Follow();
            $follow->idFollowed = $_REQUEST["followed"];
            $follow->idFollower = $userAuth->idUser;
            $follow->save();
        }else{
            $follow->unfollow($userAuth->idUser, $_REQUEST["followed"]);
        }

        return redirect('/user/'.$_REQUEST["name"]);
    }

    public function showUpdate(){
        $userAuth = Auth::user();

        $user = new User;
        $user = $user->getUserInfosById(explode(' ', $userAuth->idUser));
        $dataUpdate = $user;
        return view('userUpdate')->with( 'data', $dataUpdate[0]);
    }

    public function update(){
        $userAuth = Auth::user();

        $user = new User;
        $user->updateInfo($_POST, $userAuth->idUser);

        return redirect()->route('showUser', array('username' => $_POST["name"]));
    }
}
