<div class="form-grid">
    <label class="field field-full">
        <span>Judul kuis *</span>
        <input type="text" name="title" value="{{ old('title', $quiz->title ?? '') }}" maxlength="150" required placeholder="Contoh: Kuis Pancasila">
    </label>

    <label class="field field-full">
        <span>Deskripsi</span>
        <textarea name="description" rows="4" maxlength="2000" placeholder="Tuliskan petunjuk atau deskripsi singkat...">{{ old('description', $quiz->description ?? '') }}</textarea>
    </label>

    <label class="field">
        <span>Timer bawaan per soal (detik) *</span>
        <input type="number" name="default_timer" value="{{ old('default_timer', $quiz->default_timer ?? 20) }}" min="5" max="300" required>
        <small>Dipakai jika suatu soal tidak memiliki timer khusus.</small>
    </label>

    <div class="field">
        <span>Pengaturan</span>
        <label class="switch-row">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $quiz->is_active ?? false))>
            <span><strong>Aktifkan kuis</strong><small>Peserta dapat bergabung menggunakan kode.</small></span>
        </label>
        <label class="switch-row">
            <input type="checkbox" name="show_leaderboard" value="1" @checked(old('show_leaderboard', $quiz->show_leaderboard ?? true))>
            <span><strong>Tampilkan peringkat</strong><small>Peringkat muncul setelah peserta selesai.</small></span>
        </label>
    </div>
</div>
