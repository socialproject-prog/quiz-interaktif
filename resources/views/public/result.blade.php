@extends('layouts.public')

@section('title', 'Hasil Kuis - '.$quiz->title)
@section('body-class', 'result-page')

@section('content')
<div class="result-shell">
    <section class="result-card-main">
        <div class="result-check">✓</div>
        <span class="eyebrow">KUIS SELESAI</span>
        <h1>Hebat, {{ $participant->name }}!</h1>
        <p>Kamu telah menyelesaikan <strong>{{ $quiz->title }}</strong>.</p>

        <div
            class="score-circle"
            style="--score-angle: {{ max(0, min(100, $percentage)) * 3.6 }}deg;"
            role="img"
            aria-label="Nilai {{ $percentage }} persen"
        >
            <strong>{{ $percentage }}%</strong>
            <span>{{ $participant->total_score }} / {{ $maxScore }} poin</span>
        </div>

        <div class="result-stats">
            <div><small>Jawaban benar</small><strong>{{ $correctAnswers }}/{{ $totalQuestions }}</strong></div>
            <div><small>Waktu total</small><strong>{{ number_format($participant->total_time_ms / 1000, 2) }} dtk</strong></div>
            <div><small>Peringkat</small><strong>#{{ $rank }}</strong></div>
        </div>

        <form action="{{ route('quiz.leave') }}" method="POST">
            @csrf
            <button class="btn btn-primary btn-lg btn-block">Kembali ke Beranda</button>
        </form>
    </section>

    @if($quiz->show_leaderboard)
        <aside class="leaderboard-card-public">
            <div class="card-header">
                <div><h2>Peringkat Teratas</h2><p>Skor tertinggi dan waktu tercepat.</p></div>
            </div>
            <ol class="leaderboard-list-public">
                @forelse($leaderboard as $index => $item)
                    <li class="{{ $item->id === $participant->id ? 'current-user' : '' }}">
                        <span class="leaderboard-position">{{ $index + 1 }}</span>
                        <div class="leaderboard-avatar">{{ strtoupper(substr($item->name, 0, 1)) }}</div>
                        <div class="leaderboard-name"><strong>{{ $item->name }}</strong><small>{{ number_format($item->total_time_ms / 1000, 2) }} dtk</small></div>
                        <strong>{{ $item->total_score }} poin</strong>
                    </li>
                @empty
                    <li>Belum ada data peringkat.</li>
                @endforelse
            </ol>
        </aside>
    @endif
</div>
@endsection
