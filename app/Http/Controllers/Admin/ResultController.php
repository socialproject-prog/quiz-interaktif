<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ResultController extends Controller
{
    public function show(Quiz $quiz): View
    {
        return view('admin.results.show', [
            'quiz' => $quiz,
            'participants' => $quiz->participants()
                ->withCount('answers')
                ->orderByDesc('total_score')
                ->orderBy('total_time_ms')
                ->orderBy('created_at')
                ->paginate(20),
        ]);
    }

    public function participant(Quiz $quiz, Participant $participant): View
    {
        abort_unless($participant->quiz_id === $quiz->id, 404);

        $participant->load(['answers.question']);

        return view('admin.results.participant', compact('quiz', 'participant'));
    }

    public function reset(Quiz $quiz): RedirectResponse
    {
        $quiz->participants()->delete();

        return back()->with('success', 'Seluruh hasil peserta pada kuis ini telah dihapus.');
    }
}
