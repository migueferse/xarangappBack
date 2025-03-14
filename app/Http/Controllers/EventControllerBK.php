<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller {
    public function index() {
        return response()->json(Event::all());
    }

    public function store(Request $request) {
        $event = Event::create($request->all());
        return response()->json($event, 201);
    }

    public function addMusician(Request $request, $id) {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Evento no encontrado'], 404);
        }
        $event->push('musicians', $request->input('musicianId'));
        return response()->json(['message' => 'Músico agregado al evento']);
    }
}