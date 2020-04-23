<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Club;

class ClubController extends Controller
{
    public function index()
    {
        return Club::all();
    }
 
    public function show($club)
    {
        return Club::find($club);
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
}
