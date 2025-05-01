<?php

namespace App\Http\Controllers;

use App\Models\Musician;
use App\Models\EventMusician;
use App\Models\Event;
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

        // $musician = Musician::create([
        //     'name' => $request->name,
        //     'lastName' => $request->lastName,
        //     'nickname' => $request->nickname,
        //     'phone' => $request->phone,
        //     'email' => $request->email,
        //     'instrument_id' => new \MongoDB\BSON\ObjectId($request->instrument_id),
        // ]);

        // return response()->json([
        //     'message' => 'Músico creado con éxito',
        //     'musician' => $musician
        // ], 201);
        $musician = Musician::create($request->all());
        return response()->json($musician, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // return response()->json(Musician::with('instrument', 'events')->findOrFail($id));
        $musician = Musician::with('instrument', 'events')->findOrFail($id);
        return response()->json($musician);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // $musician = Musician::findOrFail($id);
        // $musician->update($request->all());
        // return response()->json($musician);
        $request->validate([
            'name' => 'nullable|string',
            'lastName' => 'nullable|string',
            'nickname' => 'nullable|string',
            'instrument_id' => 'nullable|exists:instruments,_id',
            'phone' => 'nullable|string',
            'email' => 'nullable|email|unique:musicians,email,' . $id,
        ]);

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

    /**
     * Get the pending event invitations for the authenticated musician.
     */
    public function pendingEvents(Request $request)
    {
        $user = $request->user();

        // Verificar si el usuario autenticado tiene el rol de administrador
        if (isset($user->role) && $user->role === 'admin') {
            // Si es administrador, obtener todas las invitaciones pendientes con la información del evento
            $pendingInvitations = EventMusician::with('event')->where('status', 'pending')->get();
        } else {
            // Si no es administrador, buscar el perfil del músico asociado al usuario por su email
            $musician = Musician::where('email', $user->email)->first();

            if ($musician) {
                // Obtener las invitaciones pendientes para este músico específico con la información del evento
                $pendingInvitations = EventMusician::where('musician_id', $musician->_id)
                    ->where('status', 'pending')
                    ->with('event')
                    ->get();
            } else {
                // Si no se encuentra el perfil del músico, devolver una colección vacía
                $pendingInvitations = collect(); // Usamos collect() para devolver una colección vacía
            }
        }

        return response()->json($pendingInvitations);
    }

    /**
     * Accept an invitation to an event for the authenticated musician.
     */
    public function acceptEvent(Request $request, $id) // Recibimos el ID del evento
    // {
    //     $user = $request->user(); // El músico autenticado
    //     $event = Event::findOrFail($id);

    //     $invitation = EventMusician::where('event_id', $event->_id) // Usar _id para MongoDB
    //         ->where('musician_id', $user->_id) // Usar _id para MongoDB
    //         ->where('status', 'pending')
    //         ->firstOrFail();

    //     $invitation->update(['status' => 'accepted']);

    //     return response()->json(['message' => 'Has confirmado tu asistencia al evento.']);
    // }
    {
        $user = $request->user();
        $event = Event::findOrFail($id);
    
        $query = EventMusician::where('event_id', $event->_id)
            ->where('status', 'pending');
    
        // Si el usuario NO es administrador, filtramos por su musician_id
        if (!(isset($user->role) && $user->role === 'admin')) {
            $musician = Musician::where('email', $user->email)->firstOrFail();
            $query->where('musician_id', $musician->_id);
        }
    
        $invitation = $query->firstOrFail();
    
        $invitation->update(['status' => 'accepted']);
    
        return response()->json(['message' => 'Has confirmado la asistencia al evento.']);
    }

    /**
     * Reject an invitation to an event for the authenticated musician.
     */
    public function rejectEvent(Request $request, $id) // Recibimos el ID del evento
    {
        $user = $request->user();
        $event = Event::findOrFail($id);
    
        $query = EventMusician::where('event_id', $event->_id)
            ->where('status', 'pending');
    
        if (!(isset($user->role) && $user->role === 'admin')) {
            $musician = Musician::where('email', $user->email)->firstOrFail();
            $query->where('musician_id', $musician->_id);
        }
    
        $invitation = $query->firstOrFail();
    
        $invitation->update(['status' => 'rejected']);
    
        return response()->json(['message' => 'Has rechazado tu asistencia al evento.']);
    }
}
