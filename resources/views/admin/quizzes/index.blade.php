@extends('layouts.admin')

@section('title', 'Kelola Kuis')
@section('page-title', 'Kelola Kuis')
@section('page-subtitle', 'Tambah, ubah, aktifkan, dan hapus kuis')

@section('content')
<div class="page-heading">
    <div>
        <h1>Daftar Kuis</h1>
        <p>Soal dan timer dapat diubah kapan saja.</p>
    </div>
    <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary">+ Tambah Kuis</a>
</div>

<div class="card">
    <form method="GET" class="toolbar">
        <input class="search-input" type="search" name="search" value="{{ request('search') }}" placeholder="Cari judul atau kode kuis...">
        <button class="btn btn-light">Cari</button>
        @if(request('search'))
            <a href="{{ route('admin.quizzes.index') }}" class="btn btn-ghost">Reset</a>
        @endif
    </form>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
            <tr>
                <th>Judul Kuis</th>
                <th>Kode</th>
                <th>Timer</th>
                <th>Soal</th>
                <th>Peserta</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($quizzes as $quiz)
                <tr>
                    <td>
                        <strong>{{ $quiz->title }}</strong>
                        <small class="table-subtext">{{ \Illuminate\Support\Str::limit($quiz->description, 55) }}</small>
                    </td>
                    <td><span class="code-pill">{{ $quiz->code }}</span></td>
                    <td>{{ $quiz->default_timer }} dtk</td>
                    <td>{{ $quiz->questions_count }}</td>
                    <td>{{ $quiz->participants_count }}</td>
                    <td><span class="badge {{ $quiz->is_active ? 'badge-success' : 'badge-muted' }}">{{ $quiz->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td>
                        <div class="action-row">
                            <a class="btn btn-light btn-sm" href="{{ route('admin.quizzes.show', $quiz) }}">Kelola</a>
                            <a class="btn btn-light btn-sm" href="{{ route('presenter.show', $quiz->code) }}" target="_blank">Penyaji</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="empty-cell">Kuis belum tersedia.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $quizzes->links() }}</div>
</div>
@endsection
