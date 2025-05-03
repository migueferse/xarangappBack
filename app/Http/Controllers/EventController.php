<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventMusician;
use Illuminate\Http\Request;

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
    
        // if ($request->has('musicians')) {
        //     $event->musicians()->attach($request->musicians);
        // }
        if ($request->has('musicians') && is_array($request->musicians)) {
            foreach ($request->musicians as $musicianId) {
                EventMusician::create([
                    'event_id' => $event->id,
                    'musician_id' => $musicianId,
                    'status' => 'pending',
                ]);
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
    
        // if ($request->has('musicians')) {
        //     $event->musicians()->sync($request->musicians);
        // }

        // Sincronizar las invitaciones (esto podría necesitar una lógica más compleja dependiendo de tu UI)
        EventMusician::where('event_id', $event->id)->delete();
        if ($request->has('musicians') && is_array($request->musicians)) {
            foreach ($request->musicians as $musicianId) {
                EventMusician::create([
                    'event_id' => $event->id,
                    'musician_id' => $musicianId,
                    'status' => 'pending',
                ]);
            }
        }

        return response()->json($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Buscar el evento por ID
        $event = Event::findOrFail($id);

        // Eliminar todas las invitaciones relacionadas
        $event->eventMusicians()->delete();

        // Eliminar el evento
        $event->delete();

        return response()->json(['message' => 'Evento e invitaciones eliminados correctamente.']);
    }

    /**
     * Invite a musician to an event.
     */
    // public function inviteMusician(Request $request, Event $event)
    // {
    //     $request->validate([
    //         'musician_id' => 'required|exists:musicians,_id',
    //     ]);

    //     $musicianId = $request->musician_id;

    //     // Verificar si ya existe una invitación para este músico y evento
    //     $existingInvitation = EventMusician::where('event_id', $event->id)
    //                                       ->where('musician_id', $musicianId)
    //                                       ->first();

    //     if ($existingInvitation) {
    //         return response()->json(['message' => 'El músico ya ha sido invitado a este evento.'], 409);
    //     }

    //     EventMusician::create([
    //         'event_id' => $event->id,
    //         'musician_id' => $musicianId,
    //         'status' => 'pending',
    //     ]);

    //     return response()->json(['message' => 'Se ha enviado una invitación al músico para este evento.']);
    // }    

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
