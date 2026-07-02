<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class PresenterController extends Controller
{
    public function show(string $code): View
    {
        $quiz = Quiz::where('code', strtoupper($code))->firstOrFail();

        return view('public.presenter', compact('quiz'));
    }

    public function data(string $code): JsonResponse
    {
        $quiz = Quiz::where('code', strtoupper($code))->firstOrFail();

        $leaderboard = $quiz->participants()
            ->orderByDesc('total_score')
            ->orderBy('total_time_ms')
            ->orderBy('created_at')
            ->limit(10)
            ->get()
            ->values()
            ->map(fn ($participant, $index) => [
                'rank' => $index + 1,
                'name' => $participant->name,
                'score' => $participant->total_score,
                'time' => number_format($participant->total_time_ms / 1000, 2).' dtk',
                'completed' => (bool) $participant->completed_at,
            ]);

        return response()->json([
            'title' => $quiz->title,
            'code' => $quiz->code,
            'is_active' => $quiz->is_active,
            'participant_count' => $quiz->participants()->count(),
            'completed_count' => $quiz->participants()->whereNotNull('completed_at')->count(),
            'answer_count' => $quiz->participants()->withCount('answers')->get()->sum('answers_count'),
            'leaderboard' => $leaderboard,
            'updated_at' => now()->format('H:i:s'),
        ]);
    }
}
