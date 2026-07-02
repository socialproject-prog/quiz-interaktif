<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function index(Request $request): View
    {
        $query = Quiz::query()->withCount(['questions', 'participants']);

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        return view('admin.quizzes.index', [
            'quizzes' => $query->latest()->paginate(10)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.quizzes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['code'] = $this->generateCode();
        $data['is_active'] = $request->boolean('is_active');
        $data['show_leaderboard'] = $request->boolean('show_leaderboard');

        $quiz = Quiz::create($data);

        return redirect()
            ->route('admin.quizzes.show', $quiz)
            ->with('success', 'Kuis berhasil dibuat. Sekarang tambahkan soal.');
    }

    public function show(Quiz $quiz): View
    {
        $quiz->load(['questions'])->loadCount('participants');

        return view('admin.quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz): View
    {
        return view('admin.quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['show_leaderboard'] = $request->boolean('show_leaderboard');

        $quiz->update($data);

        return redirect()
            ->route('admin.quizzes.show', $quiz)
            ->with('success', 'Pengaturan kuis berhasil diperbarui.');
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        $quiz->delete();

        return redirect()
            ->route('admin.quizzes.index')
            ->with('success', 'Kuis dan seluruh data terkait berhasil dihapus.');
    }

    public function toggle(Quiz $quiz): RedirectResponse
    {
        $quiz->update(['is_active' => ! $quiz->is_active]);

        return back()->with(
            'success',
            $quiz->fresh()->is_active ? 'Kuis sekarang aktif.' : 'Kuis sekarang dinonaktifkan.'
        );
    }

    public function regenerateCode(Quiz $quiz): RedirectResponse
    {
        $quiz->update(['code' => $this->generateCode()]);

        return back()->with('success', 'Kode kuis berhasil diganti.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'default_timer' => ['required', 'integer', 'min:5', 'max:300'],
        ]);
    }

    private function generateCode(): string
    {
        do {
            $code = Str::upper(Str::random(6));
        } while (Quiz::where('code', $code)->exists());

        return $code;
    }
}
