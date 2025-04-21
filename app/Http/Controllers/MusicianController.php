<?php

namespace App\Http\Controllers;

use App\Models\Musician;
use Illuminate\Http\Request;

class MusicianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Musician::with('instrument', 'events')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'lastName' => 'required|string',
            'nickname' => 'nullable|string',
            'phone' => 'required|string',
            'email' => 'required|email|unique:musicians,email',
            'instrument_id' => 'required|exists:instruments,_id'
        ]);

        $musician = Musician::create([
            'name' => $request->name,
            'lastName' => $request->lastName,
            'nickname' => $request->nickname,
            'phone' => $request->phone,
            'email' => $request->email,
            'instrument_id' => new \MongoDB\BSON\ObjectId($request->instrument_id),
        ]);

        return response()->json([
            'message' => 'Músico creado con éxito',
            'musician' => $musician
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json(Musician::with('instrument', 'events')->findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $musician = Musician::findOrFail($id);
        $musician->update($request->all());
        return response()->json($musician);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Musician::findOrFail($id)->delete();
        return response()->json(['message' => 'Músico eliminado']);
    }
}
