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

class ClubController extends Controller
{
    public function index()
    {
        $clubs = Club::all();
        return view('clubs')->with( 'data', json_decode($clubs, true));
    }

    public function userClubs()
    {
        $user = Auth::user();

        $clubs = DB::table('clubs')
        ->select('*')
        ->join('members', 'members.idClub', '=', 'clubs.idClub')
        ->where('members.idUser', $user->idUser)
        ->get();

        return view('clubs')->with( 'data', json_decode($clubs, true));
    }
    
 
    public function show($club)
    {
        $club = Club::find($club)->toJson();

        return view('profile')->with( 'info', json_decode($club, true));
    }

    public function store(Request $request)
    {
        $club = Club::create($request->all());

        return response()->json($club, 201);
    }

    public function update(Request $request, Club $club)
    {
        $club->update($request->all());

        return response()->json($club, 200);
    }

    public function delete(Club $club)
    {
        $club->delete();

        return response()->json(null, 204);
    }

    public function getMembers($club)
    {
        $members = DB::table('clubs')
        ->join('members', 'members.idClub', '=', 'clubs.idClub')
        ->join('users', 'members.idUser', '=', 'users.idUser')
        ->select('users.idUser', 'users.name as username', 'clubs.name')
        ->where('clubs.idClub', $club)
        ->get();

        return $members->toJson(JSON_PRETTY_PRINT);
    }

    public function addMember(Request $request)
    {
        $member = Member::create($request->all());

        return response()->json($member, 201);
    }

    public function deleteMember($idUser, $idClub)
    {
        Member::where('idUser', $idUser)
        ->where('idClub', $idClub)
        ->delete();

        return response()->json(null, 204);
    }
}
