<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuestionController extends Controller
{
    public function create(Quiz $quiz): View
    {
        $nextPosition = ((int) $quiz->questions()->max('position')) + 1;

        return view('admin.questions.create', compact('quiz', 'nextPosition'));
    }

    public function store(Request $request, Quiz $quiz): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['quiz_id'] = $quiz->id;
        $data['correct_option'] = strtoupper($data['correct_option']);

        Question::create($data);

        return redirect()
            ->route('admin.quizzes.show', $quiz)
            ->with('success', 'Soal berhasil ditambahkan.');
    }

    public function edit(Quiz $quiz, Question $question): View
    {
        $this->ensureQuestionBelongsToQuiz($quiz, $question);

        return view('admin.questions.edit', compact('quiz', 'question'));
    }

    public function update(Request $request, Quiz $quiz, Question $question): RedirectResponse
    {
        $this->ensureQuestionBelongsToQuiz($quiz, $question);

        $data = $this->validatedData($request);
        $data['correct_option'] = strtoupper($data['correct_option']);
        $question->update($data);

        return redirect()
            ->route('admin.quizzes.show', $quiz)
            ->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Quiz $quiz, Question $question): RedirectResponse
    {
        $this->ensureQuestionBelongsToQuiz($quiz, $question);
        $question->delete();

        return back()->with('success', 'Soal berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'question' => ['required', 'string', 'max:3000'],
            'option_a' => ['required', 'string', 'max:1000'],
            'option_b' => ['required', 'string', 'max:1000'],
            'option_c' => ['required', 'string', 'max:1000'],
            'option_d' => ['required', 'string', 'max:1000'],
            'correct_option' => ['required', 'in:A,B,C,D,a,b,c,d'],
            'difficulty' => ['required', 'in:mudah,sedang,sulit'],
            'time_limit' => ['nullable', 'integer', 'min:5', 'max:300'],
            'points' => ['required', 'integer', 'min:1', 'max:1000'],
            'position' => ['required', 'integer', 'min:1', 'max:10000'],
        ]);
    }

    private function ensureQuestionBelongsToQuiz(Quiz $quiz, Question $question): void
    {
        abort_unless($question->quiz_id === $quiz->id, 404);
    }
}
