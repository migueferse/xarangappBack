<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Musician;

class MusicianController extends Controller {
    public function index() {
        return response()->json(Musician::all());
    }

    public function store(Request $request) {
        $musician = Musician::create($request->all());
        return response()->json($musician, 201);
    }
}