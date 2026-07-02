@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan aplikasi kuis')

@section('content')
<div class="page-heading">
    <div>
        <h1>Selamat datang, {{ auth()->user()->name }}!</h1>
        <p>Kelola seluruh kuis dari satu halaman.</p>
    </div>
    <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary">+ Buat Kuis</a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <span class="stat-icon blue">▣</span>
        <div><small>Total Kuis</small><strong>{{ $quizCount }}</strong></div>
    </div>
    <div class="stat-card">
        <span class="stat-icon green">●</span>
        <div><small>Kuis Aktif</small><strong>{{ $activeQuizCount }}</strong></div>
    </div>
    <div class="stat-card">
        <span class="stat-icon orange">?</span>
        <div><small>Total Soal</small><strong>{{ $questionCount }}</strong></div>
    </div>
    <div class="stat-card">
        <span class="stat-icon purple">♟</span>
        <div><small>Total Peserta</small><strong>{{ $participantCount }}</strong></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h2>Kuis Terbaru</h2>
            <p>Kuis yang terakhir dibuat atau diperbarui.</p>
        </div>
        <a href="{{ route('admin.quizzes.index') }}" class="text-link">Lihat semua →</a>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
            <tr>
                <th>Judul</th>
                <th>Kode</th>
                <th>Soal</th>
                <th>Peserta</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($recentQuizzes as $quiz)
                <tr>
                    <td><strong>{{ $quiz->title }}</strong></td>
                    <td><span class="code-pill">{{ $quiz->code }}</span></td>
                    <td>{{ $quiz->questions_count }}</td>
                    <td>{{ $quiz->participants_count }}</td>
                    <td><span class="badge {{ $quiz->is_active ? 'badge-success' : 'badge-muted' }}">{{ $quiz->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td><a class="btn btn-light btn-sm" href="{{ route('admin.quizzes.show', $quiz) }}">Kelola</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty-cell">Belum ada kuis.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
