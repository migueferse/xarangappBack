<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventMusician;
use App\Models\Musician;
use Illuminate\Http\Request;
use App\Mail\InvitationMail;
use Illuminate\Support\Facades\Mail;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        {
            $events = Event::with('acceptedMusicians.musician')->get();
        
            // Reemplazar la propiedad `musicians` con los que aceptaron
            $events->transform(function ($event) {
                $accepted = $event->acceptedMusicians->pluck('musician')->filter();
                $event->musicians = $accepted->values(); // asignamos solo músicos aceptados
                // unset($event->acceptedMusicians); // opcional: limpia respuesta
                return $event;
            });
        
            return response()->json($events);
        }
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
    
        if ($request->has('musicians') && is_array($request->musicians)) {
            foreach ($request->musicians as $musicianId) {
                EventMusician::create([
                    'event_id' => $event->id,
                    'musician_id' => $musicianId,
                    'status' => 'pending',
                ]);

                $musician = Musician::find($musicianId);
                if ($musician) {
                    Mail::to($musician->email)->send(new InvitationMail($event, $musician));
                }
            }
            return response()->json(['message' => 'Evento creado e invitaciones enviadas a los músicos.'], 201);
        }
    
        return response()->json($event, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $event = Event::with('acceptedMusicians.musician.instrument')->findOrFail($id);
        return response()->json($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'place' => 'required|string',
            'date' => 'required|date',
            'musicians' => 'array',
            'musicians.*' => 'exists:musicians,_id'
        ]);
    
        $event = Event::findOrFail($id);
        $event->update([
            'name' => $request->name,
            'place' => $request->place,
            'date' => $request->date,
        ]);
    
        EventMusician::where('event_id', $event->id)->delete();
        if ($request->has('musicians') && is_array($request->musicians)) {
            foreach ($request->musicians as $musicianId) {
                EventMusician::create([
                    'event_id' => $event->id,
                    'musician_id' => $musicianId,
                    'status' => 'pending',
                ]);

                $musician = Musician::find($musicianId);
                if ($musician) {
                    Mail::to($musician->email)->send(new InvitationMail($event, $musician));
                }
            }
        }

        return response()->json($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->eventMusicians()->delete();
        $event->delete();

        return response()->json(['message' => 'Evento e invitaciones eliminados correctamente.']);
    }
}
