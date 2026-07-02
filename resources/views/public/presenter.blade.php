@extends('layouts.public')

@section('title', 'Layar Penyaji - '.$quiz->title)
@section('body-class', 'presenter-page')

@section('content')
<div class="presenter-shell">
    <header class="presenter-header">
        <div class="brand brand-presenter">
            <span class="brand-mark">Q</span>
            <div><strong>Quiz Interaktif</strong><small>Layar Penyaji</small></div>
        </div>
        <div class="presenter-status" id="statusBadge">Memuat...</div>
    </header>

    <main class="presenter-main">
        <section class="presenter-hero">
            <span class="eyebrow">SIAP BERGABUNG?</span>
            <h1>{{ $quiz->title }}</h1>
            <p>{{ $quiz->description ?: 'Masukkan kode di HP peserta untuk mengikuti kuis.' }}</p>

            <div class="join-code-large">
                <small>KODE KUIS</small>
                <strong>{{ $quiz->code }}</strong>
            </div>

            <div class="presenter-instruction">
                Buka <strong>{{ request()->getHost() }}</strong> lalu masukkan kode di atas.
            </div>

            <div class="presenter-metrics">
                <div><strong id="participantCount">0</strong><span>Peserta bergabung</span></div>
                <div><strong id="completedCount">0</strong><span>Sudah selesai</span></div>
                <div><strong id="answerCount">0</strong><span>Jawaban masuk</span></div>
            </div>
        </section>

        <aside class="presenter-leaderboard">
            <div class="presenter-leaderboard-head">
                <div><span class="eyebrow">LIVE</span><h2>Peserta Teratas</h2></div>
                <small>Diperbarui <span id="updatedAt">--:--:--</span></small>
            </div>
            <div id="leaderboardRows" class="presenter-leaderboard-rows">
                <div class="presenter-empty">Belum ada peserta.</div>
            </div>
        </aside>
    </main>

    <footer class="presenter-footer">
        <span>Peserta cukup memasukkan satu kode untuk mengerjakan seluruh soal.</span>
        <span>Peringkat berdasarkan skor dan kecepatan.</span>
    </footer>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const endpoint = @json(route('presenter.data', $quiz->code));
    const rows = document.getElementById('leaderboardRows');

    const escapeHtml = value => String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');

    const loadData = async () => {
        try {
            const response = await fetch(endpoint, {headers: {'Accept': 'application/json'}});
            if (!response.ok) throw new Error('Gagal memuat data');
            const data = await response.json();

            document.getElementById('participantCount').textContent = data.participant_count;
            document.getElementById('completedCount').textContent = data.completed_count;
            document.getElementById('answerCount').textContent = data.answer_count;
            document.getElementById('updatedAt').textContent = data.updated_at;

            const status = document.getElementById('statusBadge');
            status.textContent = data.is_active ? '● Kuis Aktif' : '○ Kuis Nonaktif';
            status.className = `presenter-status ${data.is_active ? 'active' : 'inactive'}`;

            if (!data.leaderboard.length) {
                rows.innerHTML = '<div class="presenter-empty">Belum ada peserta. Menunggu peserta bergabung...</div>';
                return;
            }

            rows.innerHTML = data.leaderboard.map(item => `
                <div class="presenter-rank-row rank-${item.rank}">
                    <span class="presenter-rank">${item.rank}</span>
                    <span class="presenter-avatar">${escapeHtml(item.name.charAt(0).toUpperCase())}</span>
                    <div><strong>${escapeHtml(item.name)}</strong><small>${item.completed ? 'Selesai' : 'Sedang mengerjakan'}</small></div>
                    <div class="presenter-score"><strong>${item.score} poin</strong><small>${escapeHtml(item.time)}</small></div>
                </div>
            `).join('');
        } catch (error) {
            console.error(error);
        }
    };

    loadData();
    setInterval(loadData, 2000);
})();
</script>
@endpush
