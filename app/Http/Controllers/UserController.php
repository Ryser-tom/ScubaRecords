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

use App\User;
use App\Club;
use DB;

class UserController extends Controller
{
    public function index()
    {
        return json_encode(User::all());
    }
 
    public function show($user)
    {
        $user = User::where('name', $user)->first()->toJson();
        return view('profile')->with( 'info', json_decode($user, true));
    }

    public function store(Request $request)
    {
        $user = User::create($request->all());

        return response()->json($user, 201);
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->all());

        return response()->json($user, 200);
    }

    public function delete(User $user)
    {
        $user->delete();

        return response()->json(null, 204);
    }

    public function getMembers($club)
    {
        $members = DB::table('users')
        ->join('members', 'members.idUser', '=', 'users.idUser')
        ->join('clubs', 'members.idClub', '=', 'clubs.idClub')
        ->select('users.idUser', 'users.firstName', 'users.lastName', 'clubs.name')
        ->where('clubs.idClub', $club)
        ->get();
        
        return $members->toJson(JSON_PRETTY_PRINT);
    }
}
