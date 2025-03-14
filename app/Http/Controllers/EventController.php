<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Event::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'place' => 'required|string',
            'date' => 'required|date',
            'musicians' => 'array',
            'musicians.*' => 'exists:musicians,_id'
        ]);
    
        $event = Event::create([
            'name' => $request->name,
            'place' => $request->place,
            'date' => $request->date,
        ]);
    
        if ($request->has('musicians')) {
            $event->musicians()->attach($request->musicians);
        }
    
        return response()->json($event, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json(Event::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $event->update($request->all());
        return response()->json($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Event::findOrFail($id)->delete();
        return response()->json(['message' => 'Evento eliminado']);
    }

    // public function assignMusician(Request $request, $eventId)
    // {
    //     $event = Event::find($eventId);
    //     if (!$event) {
    //         return response()->json(['message' => 'Evento no encontrado'], 404);
    //     }

    //     $musician = Musician::find($request->musician_id);
    //     if (!$musician) {
    //         return response()->json(['message' => 'Músico no encontrado'], 404);
    //     }

    //     $event->push('musicians', $musician->_id, true);

    //     return response()->json([
    //         'message' => 'Músico asignado al evento correctamente',
    //         'event' => $event
    //     ]);
    // }    
}
