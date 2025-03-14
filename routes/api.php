<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MusicianController;
use App\Http\Controllers\EventController;

Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando correctamente']);
});

Route::apiResource('musicians', MusicianController::class);
Route::apiResource('events', EventController::class);
// Route::post('/events/{eventId}/assign-musician', [EventController::class, 'assignMusician']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
