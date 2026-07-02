@extends('layouts.public')

@section('title', 'Soal '.($participant->current_question + 1).' - '.$quiz->title)
@section('body-class', 'play-page')

@section('content')
<div class="quiz-shell">
    <header class="quiz-topbar">
        <div>
            <span class="quiz-label">{{ $quiz->title }}</span>
            <strong>{{ $participant->name }}</strong>
        </div>
        <div class="timer-box" id="timerBox">
            <span>WAKTU TERSISA</span>
            <strong id="timerValue">00:{{ str_pad((string) $remainingSeconds, 2, '0', STR_PAD_LEFT) }}</strong>
        </div>
    </header>

    <div class="quiz-progress-wrap">
        <div class="quiz-progress-info">
            <span>SOAL {{ $participant->current_question + 1 }} / {{ $totalQuestions }}</span>
            <span>{{ round((($participant->current_question) / max(1, $totalQuestions)) * 100) }}% selesai</span>
        </div>
        <div class="progress-track"><div class="progress-bar" style="width: {{ (($participant->current_question) / max(1, $totalQuestions)) * 100 }}%"></div></div>
    </div>

    <main class="question-card-public">
        <div class="question-tags">
            <span class="question-tag difficulty-{{ $question->difficulty }}">{{ ucfirst($question->difficulty) }}</span>
            <span class="question-tag question-tag-neutral">{{ $question->points }} poin</span>
            <span class="question-tag question-tag-neutral">Pilih satu jawaban</span>
        </div>
        <h1>{{ $question->question }}</h1>

        <form id="answerForm" action="{{ route('quiz.answer', [$quiz->code, $question]) }}" method="POST">
            @csrf
            <input type="hidden" name="timed_out" id="timedOut" value="0">

            <div class="answer-options">
                @foreach([
                    'A' => $question->option_a,
                    'B' => $question->option_b,
                    'C' => $question->option_c,
                    'D' => $question->option_d,
                ] as $letter => $text)
                    <label class="answer-option option-public-{{ strtolower($letter) }}">
                        <input type="radio" name="selected_option" value="{{ $letter }}">
                        <span class="answer-letter">{{ $letter }}</span>
                        <span class="answer-text">{{ $text }}</span>
                        <span class="answer-check">✓</span>
                    </label>
                @endforeach
            </div>

            <div class="question-footer">
                <span class="helper-text">Jawaban terkirim otomatis saat waktu habis.</span>
                <button type="submit" id="submitButton" class="btn btn-primary btn-lg" disabled>Kirim Jawaban →</button>
            </div>
        </form>
    </main>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const form = document.getElementById('answerForm');
    const timerValue = document.getElementById('timerValue');
    const timerBox = document.getElementById('timerBox');
    const timedOut = document.getElementById('timedOut');
    const submitButton = document.getElementById('submitButton');
    const radios = [...document.querySelectorAll('input[name="selected_option"]')];
    let remaining = {{ $remainingSeconds }};
    let submitted = false;

    const renderTimer = () => {
        const minutes = Math.floor(remaining / 60).toString().padStart(2, '0');
        const seconds = (remaining % 60).toString().padStart(2, '0');
        timerValue.textContent = `${minutes}:${seconds}`;
        timerBox.classList.toggle('danger', remaining <= 5);
    };

    radios.forEach(radio => {
        radio.addEventListener('change', () => {
            submitButton.disabled = false;
            document.querySelectorAll('.answer-option').forEach(item => item.classList.remove('selected'));
            radio.closest('.answer-option').classList.add('selected');
        });
    });

    form.addEventListener('submit', () => {
        if (submitted) return;
        submitted = true;
        submitButton.disabled = true;
        submitButton.textContent = 'Mengirim...';
    });

    renderTimer();

    if (remaining <= 0) {
        timedOut.value = '1';
        submitted = true;
        form.submit();
        return;
    }

    const interval = setInterval(() => {
        remaining -= 1;
        renderTimer();

        if (remaining <= 0) {
            clearInterval(interval);
            timedOut.value = '1';
            submitted = true;
            radios.forEach(radio => radio.disabled = true);
            submitButton.disabled = true;
            submitButton.textContent = 'Waktu habis...';
            form.submit();
        }
    }, 1000);
})();
</script>
@endpush
