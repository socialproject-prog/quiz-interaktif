<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Participant;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PublicQuizController extends Controller
{
    public function home(): View
    {
        return view('public.home');
    }

    public function join(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:10'],
            'name' => ['required', 'string', 'min:2', 'max:80'],
        ]);

        $quiz = Quiz::where('code', strtoupper(trim($data['code'])))
            ->where('is_active', true)
            ->first();

        if (! $quiz) {
            return back()
                ->withErrors(['code' => 'Kode kuis tidak ditemukan atau kuis belum aktif.'])
                ->onlyInput('code', 'name');
        }

        if ($quiz->questions()->count() === 0) {
            return back()
                ->withErrors(['code' => 'Kuis ini belum memiliki soal.'])
                ->onlyInput('code', 'name');
        }

        $participant = Participant::create([
            'quiz_id' => $quiz->id,
            'name' => trim($data['name']),
            'session_token' => (string) Str::uuid(),
        ]);

        $request->session()->put('quiz_participant_token', $participant->session_token);

        return redirect()->route('quiz.play', $quiz->code);
    }

    public function play(Request $request, string $code): View|RedirectResponse
    {
        $quiz = Quiz::where('code', strtoupper($code))->firstOrFail();
        $participant = $this->participantFromSession($request, $quiz);

        if ($participant->completed_at) {
            return redirect()->route('quiz.result', $quiz->code);
        }

        $question = $quiz->questions()
            ->orderBy('position')
            ->orderBy('id')
            ->skip($participant->current_question)
            ->first();

        if (! $question) {
            $participant->update([
                'completed_at' => now(),
                'question_started_at' => null,
            ]);

            return redirect()->route('quiz.result', $quiz->code);
        }

        if (! $participant->question_started_at) {
            $participant->update(['question_started_at' => now()]);
            $participant->refresh();
        }

        $timeLimit = $question->time_limit ?: $quiz->default_timer;
        $elapsedSeconds = (int) floor($participant->question_started_at->diffInMilliseconds(now()) / 1000);
        $remainingSeconds = max(0, $timeLimit - $elapsedSeconds);
        $totalQuestions = $quiz->questions()->count();

        return view('public.play', compact(
            'quiz',
            'participant',
            'question',
            'timeLimit',
            'remainingSeconds',
            'totalQuestions'
        ));
    }

    public function answer(Request $request, string $code, Question $question): RedirectResponse
    {
        $quiz = Quiz::where('code', strtoupper($code))->firstOrFail();
        $participant = $this->participantFromSession($request, $quiz);

        abort_unless($question->quiz_id === $quiz->id, 404);

        $currentQuestion = $quiz->questions()
            ->orderBy('position')
            ->orderBy('id')
            ->skip($participant->current_question)
            ->first();

        if (! $currentQuestion || $currentQuestion->id !== $question->id) {
            return redirect()->route('quiz.play', $quiz->code);
        }

        $data = $request->validate([
            'selected_option' => ['nullable', 'in:A,B,C,D'],
            'timed_out' => ['nullable', 'boolean'],
        ]);

        if ($participant->answers()->where('question_id', $question->id)->exists()) {
            return redirect()->route('quiz.play', $quiz->code);
        }

        $timeLimit = $question->time_limit ?: $quiz->default_timer;
        $startedAt = $participant->question_started_at ?? now();
        $elapsedMs = min(
            (int) $startedAt->diffInMilliseconds(now()),
            ($timeLimit * 1000) + 1500
        );

        $timedOut = $request->boolean('timed_out') || $elapsedMs > (($timeLimit * 1000) + 1200);
        $selectedOption = $timedOut ? null : ($data['selected_option'] ?? null);
        $isCorrect = $selectedOption !== null && $selectedOption === $question->correct_option;
        $score = $isCorrect ? $question->points : 0;

        DB::transaction(function () use ($participant, $question, $selectedOption, $isCorrect, $score, $elapsedMs) {
            Answer::create([
                'participant_id' => $participant->id,
                'question_id' => $question->id,
                'selected_option' => $selectedOption,
                'is_correct' => $isCorrect,
                'score' => $score,
                'response_time_ms' => $elapsedMs,
                'answered_at' => now(),
            ]);

            $participant->increment('total_score', $score);
            $participant->increment('total_time_ms', $elapsedMs);
            $participant->increment('current_question');
            $participant->update(['question_started_at' => null]);
        });

        $participant->refresh();

        $totalQuestions = $quiz->questions()->count();
        $hasNextQuestion = $participant->current_question < $totalQuestions;

        if (! $hasNextQuestion) {
            $participant->update(['completed_at' => now()]);

            return redirect()->route('quiz.result', $quiz->code);
        }

        return redirect()->route('quiz.play', $quiz->code);
    }

    public function result(Request $request, string $code): View|RedirectResponse
    {
        $quiz = Quiz::where('code', strtoupper($code))->firstOrFail();
        $participant = $this->participantFromSession($request, $quiz);

        if (! $participant->completed_at) {
            return redirect()->route('quiz.play', $quiz->code);
        }

        $totalQuestions = $quiz->questions()->count();
        $correctAnswers = $participant->answers()->where('is_correct', true)->count();
        $maxScore = (int) $quiz->questions()->sum('points');
        $percentage = $maxScore > 0 ? round(($participant->total_score / $maxScore) * 100) : 0;

        $rank = Participant::where('quiz_id', $quiz->id)
            ->whereNotNull('completed_at')
            ->where(function ($query) use ($participant) {
                $query->where('total_score', '>', $participant->total_score)
                    ->orWhere(function ($tie) use ($participant) {
                        $tie->where('total_score', $participant->total_score)
                            ->where('total_time_ms', '<', $participant->total_time_ms);
                    });
            })
            ->count() + 1;

        $leaderboard = $quiz->show_leaderboard
            ? $quiz->participants()
                ->whereNotNull('completed_at')
                ->orderByDesc('total_score')
                ->orderBy('total_time_ms')
                ->limit(10)
                ->get()
            : collect();

        return view('public.result', compact(
            'quiz',
            'participant',
            'totalQuestions',
            'correctAnswers',
            'maxScore',
            'percentage',
            'rank',
            'leaderboard'
        ));
    }

    public function leave(Request $request): RedirectResponse
    {
        $request->session()->forget('quiz_participant_token');

        return redirect()->route('home');
    }

    private function participantFromSession(Request $request, Quiz $quiz): Participant
    {
        $token = $request->session()->get('quiz_participant_token');

        if (! $token) {
            abort(403, 'Sesi peserta tidak ditemukan. Silakan bergabung kembali.');
        }

        return Participant::where('quiz_id', $quiz->id)
            ->where('session_token', $token)
            ->firstOrFail();
    }
}
