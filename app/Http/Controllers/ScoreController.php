<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Score;

class ScoreController extends Controller
{
    public function index()
    {
        $scores = Score::all()->map(function ($score) {
            $score->public_url = asset('storage/' . $score->file_path);
            return $score;
        });
    
        return response()->json($scores);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'file' => 'required|file|mimes:pdf|max:10240',
        ]);

        $path = $request->file('file')->store('scores', 'public');

        $score = Score::create([
            'title' => $request->title,
            'file_path' => $path,
        ]);

        $score->public_url = asset('storage/' . $path);

        return response()->json($score, 201);
    }

    public function download($id)
    {
        $score = Score::findOrFail($id);
        return Storage::disk('public')->download($score->file_path);
    }

    public function destroy($id)
    {
        $score = Score::findOrFail($id);
        Storage::disk('public')->delete($score->file_path);
        $score->delete();

        return response()->json(['message' => 'Partitura eliminada correctamente']);
    }
}