<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'quizCount' => Quiz::count(),
            'activeQuizCount' => Quiz::where('is_active', true)->count(),
            'questionCount' => Question::count(),
            'participantCount' => Participant::count(),
            'recentQuizzes' => Quiz::withCount(['questions', 'participants'])
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
