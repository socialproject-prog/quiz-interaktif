@extends('layouts.admin')

@section('title', 'Hasil Peserta')
@section('page-title', 'Hasil Peserta')
@section('page-subtitle', $quiz->title)

@section('content')
<div class="page-heading">
    <div>
        <h1>Peringkat dan Hasil</h1>
        <p>Urutan berdasarkan skor tertinggi, lalu waktu tercepat.</p>
    </div>
    <div class="action-row">
        <a href="{{ route('presenter.show', $quiz->code) }}" target="_blank" class="btn btn-primary">Layar Penyaji</a>
        <a href="{{ route('admin.quizzes.show', $quiz) }}" class="btn btn-light">← Kembali</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div><h2>{{ $quiz->title }}</h2><p>Kode <span class="code-pill">{{ $quiz->code }}</span></p></div>
        @if($participants->total() > 0)
            <form method="POST" action="{{ route('admin.results.reset', $quiz) }}" onsubmit="return confirm('Hapus seluruh hasil peserta?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm">Reset Hasil</button>
            </form>
        @endif
    </div>

    <div class="table-responsive">
        <table class="data-table leaderboard-table">
            <thead>
            <tr>
                <th>Peringkat</th>
                <th>Nama</th>
                <th>Skor</th>
                <th>Waktu</th>
                <th>Jawaban</th>
                <th>Status</th>
                <th>Detail</th>
            </tr>
            </thead>
            <tbody>
            @forelse($participants as $index => $participant)
                <tr>
                    <td><span class="rank-badge">{{ $participants->firstItem() + $index }}</span></td>
                    <td><strong>{{ $participant->name }}</strong><small class="table-subtext">{{ $participant->created_at->format('d M Y H:i') }}</small></td>
                    <td><strong>{{ $participant->total_score }}</strong> poin</td>
                    <td>{{ number_format($participant->total_time_ms / 1000, 2) }} dtk</td>
                    <td>{{ $participant->answers_count }}</td>
                    <td><span class="badge {{ $participant->completed_at ? 'badge-success' : 'badge-warning' }}">{{ $participant->completed_at ? 'Selesai' : 'Mengerjakan' }}</span></td>
                    <td><a class="btn btn-light btn-sm" href="{{ route('admin.results.participant', [$quiz, $participant]) }}">Lihat</a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="empty-cell">Belum ada peserta.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $participants->links() }}</div>
</div>
@endsection
