@extends('layouts.admin')

@section('title', $quiz->title)
@section('page-title', $quiz->title)
@section('page-subtitle', 'Kode kuis: '.$quiz->code)

@section('content')
<div class="page-heading">
    <div>
        <div class="heading-badges">
            <span class="badge {{ $quiz->is_active ? 'badge-success' : 'badge-muted' }}">{{ $quiz->is_active ? 'Aktif' : 'Nonaktif' }}</span>
            <span class="code-pill">{{ $quiz->code }}</span>
        </div>
        <h1>{{ $quiz->title }}</h1>
        <p>{{ $quiz->description ?: 'Tidak ada deskripsi.' }}</p>
    </div>
    <div class="action-row wrap">
        <a class="btn btn-light" href="{{ route('presenter.show', $quiz->code) }}" target="_blank">Buka Layar Penyaji</a>
        <a class="btn btn-light" href="{{ route('admin.results.show', $quiz) }}">Lihat Hasil</a>
        <a class="btn btn-primary" href="{{ route('admin.questions.create', $quiz) }}">+ Tambah Soal</a>
    </div>
</div>

<div class="summary-grid">
    <div class="summary-card"><small>Jumlah soal</small><strong>{{ $quiz->questions->count() }}</strong></div>
    <div class="summary-card"><small>Peserta</small><strong>{{ $quiz->participants_count }}</strong></div>
    <div class="summary-card"><small>Timer bawaan</small><strong>{{ $quiz->default_timer }} dtk</strong></div>
    <div class="summary-card"><small>Peringkat</small><strong>{{ $quiz->show_leaderboard ? 'Ditampilkan' : 'Disembunyikan' }}</strong></div>
</div>

<div class="two-column-layout">
    <div class="card">
        <div class="card-header">
            <div>
                <h2>Bank Soal</h2>
                <p>Urutan ditentukan oleh angka posisi.</p>
            </div>
            <a href="{{ route('admin.questions.create', $quiz) }}" class="btn btn-primary btn-sm">+ Soal</a>
        </div>

        <div class="question-list">
            @forelse($quiz->questions as $question)
                <article class="question-admin-item">
                    <div class="question-number">{{ $question->position }}</div>
                    <div class="question-admin-content">
                        <h3>{{ $question->question }}</h3>
                        <div class="question-meta">
                            <span class="difficulty-badge difficulty-{{ $question->difficulty }}">{{ ucfirst($question->difficulty) }}</span>
                            <span>Jawaban: <strong>{{ $question->correct_option }}</strong></span>
                            <span>Timer: <strong>{{ $question->time_limit ?: $quiz->default_timer }} dtk</strong></span>
                            <span>Poin: <strong>{{ $question->points }}</strong></span>
                        </div>
                    </div>
                    <div class="action-row">
                        <a class="btn btn-light btn-sm" href="{{ route('admin.questions.edit', [$quiz, $question]) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.questions.destroy', [$quiz, $question]) }}" onsubmit="return confirm('Hapus soal ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">?</div>
                    <h3>Belum ada soal</h3>
                    <p>Tambahkan soal pilihan ganda A, B, C, dan D.</p>
                    <a class="btn btn-primary" href="{{ route('admin.questions.create', $quiz) }}">Tambah Soal Pertama</a>
                </div>
            @endforelse
        </div>
    </div>

    <aside class="stack-column">
        <div class="card compact-card">
            <div class="card-header"><div><h2>Pengaturan Cepat</h2><p>Kontrol akses peserta.</p></div></div>
            <form method="POST" action="{{ route('admin.quizzes.toggle', $quiz) }}">
                @csrf
                <button class="btn {{ $quiz->is_active ? 'btn-warning' : 'btn-success' }} btn-block">
                    {{ $quiz->is_active ? 'Nonaktifkan Kuis' : 'Aktifkan Kuis' }}
                </button>
            </form>
            <a class="btn btn-light btn-block" href="{{ route('admin.quizzes.edit', $quiz) }}">Edit Pengaturan</a>
            <form method="POST" action="{{ route('admin.quizzes.regenerate-code', $quiz) }}" onsubmit="return confirm('Ganti kode kuis? Kode lama tidak dapat dipakai lagi.')">
                @csrf
                <button class="btn btn-light btn-block">Ganti Kode Kuis</button>
            </form>
        </div>

        <div class="card compact-card danger-zone">
            <div class="card-header"><div><h2>Zona Berbahaya</h2><p>Tindakan ini tidak dapat dibatalkan.</p></div></div>
            <form method="POST" action="{{ route('admin.quizzes.destroy', $quiz) }}" onsubmit="return confirm('Hapus kuis beserta soal dan semua hasil peserta?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-block">Hapus Kuis</button>
            </form>
        </div>
    </aside>
</div>
@endsection
