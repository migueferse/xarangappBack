<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Score;
use App\Models\Instrument;

class ScoreController extends Controller
{
    public function index(Request $request)
    {
        $query = Score::with('instrument');

        if ($request->has('instrument_id')) {
            $query->where('instrument_id', $request->instrument_id);
        }

        $scores = $query->get()->map(function ($score) {
            $score->public_url = asset('storage/' . $score->file_path);
            $score->instrument_name = $score->instrument->name ?? '';
            return $score;
        });

        return response()->json($scores);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'file' => 'required|file|mimes:pdf|max:10240',
            'instrument_id' => 'required|exists:instruments,_id',
        ]);

        $path = $request->file('file')->store('scores', 'public');

        $score = Score::create([
            'title' => $request->title,
            'file_path' => $path,
            'instrument_id' => $request->instrument_id,
        ]);

        $score->public_url = asset('storage/' . $path);
        $score->instrument_name = Instrument::find($request->instrument_id)?->name ?? '';

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
