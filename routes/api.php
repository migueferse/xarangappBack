<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MusicianController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\InstrumentController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\AuthController;

Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando correctamente']);
});

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::middleware('auth:sanctum')->post('/logout', 'logout');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(MusicianController::class)->group(function () {
        Route::get('/musicians/pending-events', 'pendingEvents');
        Route::get('/musicians', 'index')->middleware('can:viewAny,App\Models\Musician');
        Route::get('/musicians/{id}', 'show')->middleware('can:view,App\Models\Musician');
        Route::middleware('can:create,App\Models\Musician')->post('/musicians', 'store');
        Route::middleware('can:update,App\Models\Musician')->put('/musicians/{id}', 'update');
        Route::middleware('can:delete,App\Models\Musician')->delete('/musicians/{id}', 'destroy');

         // Nuevas rutas para las invitaciones de eventos
        Route::post('/events/{id}/accept', 'acceptEvent');
        // Route::post('/events/{id}/accept',  function () {
        //     return response()->json(['message' => 'API funcionando correctamente']);
        // });
        // Route::post('/events/{id}/reject', 'rejectEvent');
    });

    Route::controller(EventController::class)->group(function () {
        Route::get('/events', 'index')->middleware('can:viewAny,App\Models\Event');
        Route::get('/events/{id}', 'show')->middleware('can:view,App\Models\Event');
        Route::middleware('can:create,App\Models\Event')->post('/events', 'store');
        Route::middleware('can:update,App\Models\Event')->put('/events/{id}', 'update');
        Route::middleware('can:delete,App\Models\Event')->delete('/events/{id}', 'destroy');
        Route::middleware('can:update,App\Models\Event')->post('/events/{id}/invite', 'inviteMusician');
    });

    Route::controller(InstrumentController::class)->group(function () {
        Route::get('/instruments', 'index');
    });

    Route::controller(ScoreController::class)->group(function () {
        Route::get('/scores', 'index');
        Route::post('/scores', 'store');
        Route::get('/scores/download/{id}', 'download');
        Route::delete('/scores/{id}', 'destroy');
    });

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

