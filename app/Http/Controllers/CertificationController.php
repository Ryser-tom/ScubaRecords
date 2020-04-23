<?php

namespace App\Http\Controllers;
use App\Certification;

use Illuminate\Http\Request;

class CertificationController extends Controller
{
    public function index()
    {
        return Certification::all();
    }
 
    public function lastOfUser($user)
    {
        return Certification::find($user);
    }

    public function store(Request $request)
    {
        //TODO
        $cert = Certification::create($request->all());

        return response()->json($cert, 201);
    }

    public function delete(Certification $cert)
    {
        $cert->delete();

        return response()->json(null, 204);
    }
}
