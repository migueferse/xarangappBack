<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MusicianController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\InstrumentController;
use App\Http\Controllers\ScoreController;

Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando correctamente']);
});

Route::apiResource('musicians', MusicianController::class);
Route::apiResource('events', EventController::class);
Route::get('/instruments', [InstrumentController::class, 'index']);
Route::get('/scores', [ScoreController::class, 'index']);
Route::post('/scores', [ScoreController::class, 'store']);
Route::get('/scores/download/{id}', [ScoreController::class, 'download']);
Route::delete('/scores/{id}', [ScoreController::class, 'destroy']);
// Route::post('/events/{eventId}/assign-musician', [EventController::class, 'assignMusician']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
