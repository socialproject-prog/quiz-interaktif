@extends('layouts.admin')

@section('title', 'Detail Peserta')
@section('page-title', 'Detail Peserta')
@section('page-subtitle', $participant->name)

@section('content')
<div class="page-heading">
    <div><h1>{{ $participant->name }}</h1><p>Rincian jawaban pada {{ $quiz->title }}.</p></div>
    <a href="{{ route('admin.results.show', $quiz) }}" class="btn btn-light">← Kembali</a>
</div>

<div class="summary-grid">
    <div class="summary-card"><small>Total skor</small><strong>{{ $participant->total_score }}</strong></div>
    <div class="summary-card"><small>Waktu total</small><strong>{{ number_format($participant->total_time_ms / 1000, 2) }} dtk</strong></div>
    <div class="summary-card"><small>Benar</small><strong>{{ $participant->answers->where('is_correct', true)->count() }}</strong></div>
    <div class="summary-card"><small>Status</small><strong>{{ $participant->completed_at ? 'Selesai' : 'Belum selesai' }}</strong></div>
</div>

<div class="card">
    <div class="card-header"><div><h2>Jawaban Peserta</h2><p>Jawaban kosong berarti waktu habis.</p></div></div>
    <div class="answer-review-list">
        @forelse($participant->answers as $answer)
            <article class="answer-review {{ $answer->is_correct ? 'correct' : 'wrong' }}">
                <div class="answer-status">{{ $answer->is_correct ? '✓' : '×' }}</div>
                <div>
                    <h3>{{ $answer->question->question }}</h3>
                    <p>Jawaban peserta: <strong>{{ $answer->selected_option ?: 'Tidak menjawab' }}</strong>
                        @if($answer->selected_option)
                            — {{ $answer->question->optionText($answer->selected_option) }}
                        @endif
                    </p>
                    <small>Kunci: {{ $answer->question->correct_option }} · {{ number_format($answer->response_time_ms / 1000, 2) }} dtk · {{ $answer->score }} poin</small>
                </div>
            </article>
        @empty
            <div class="empty-state"><p>Peserta belum menjawab soal.</p></div>
        @endforelse
    </div>
</div>
@endsection
