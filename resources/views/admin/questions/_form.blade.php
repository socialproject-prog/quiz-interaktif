<div class="form-grid">
    <label class="field field-full">
        <span>Pertanyaan *</span>
        <textarea name="question" rows="4" required maxlength="3000" placeholder="Tuliskan pertanyaan...">{{ old('question', $question->question ?? '') }}</textarea>
    </label>

    @foreach(['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'] as $key => $label)
        <label class="field option-field">
            <span>Pilihan {{ $label }} *</span>
            <div class="option-input-wrap">
                <b class="option-letter option-{{ strtolower($label) }}">{{ $label }}</b>
                <input type="text" name="option_{{ $key }}" value="{{ old('option_'.$key, $question->{'option_'.$key} ?? '') }}" required maxlength="1000" placeholder="Isi jawaban {{ $label }}">
            </div>
        </label>
    @endforeach

    <label class="field">
        <span>Jawaban benar *</span>
        <select name="correct_option" required>
            @foreach(['A', 'B', 'C', 'D'] as $option)
                <option value="{{ $option }}" @selected(old('correct_option', $question->correct_option ?? 'A') === $option)>Pilihan {{ $option }}</option>
            @endforeach
        </select>
    </label>

    <label class="field">
        <span>Tingkat kesulitan *</span>
        <select name="difficulty" required>
            @foreach(['mudah' => 'Mudah', 'sedang' => 'Sedang', 'sulit' => 'Sulit'] as $value => $label)
                <option value="{{ $value }}" @selected(old('difficulty', $question->difficulty ?? 'sedang') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <small>Rekomendasi: mudah 20 dtk/10 poin, sedang 30 dtk/15 poin, sulit 45 dtk/20 poin.</small>
    </label>

    <label class="field">
        <span>Timer khusus (detik)</span>
        <input type="number" name="time_limit" value="{{ old('time_limit', $question->time_limit ?? '') }}" min="5" max="300" placeholder="Kosongkan untuk timer bawaan">
        <small>Jika kosong, memakai timer bawaan kuis.</small>
    </label>

    <label class="field">
        <span>Poin *</span>
        <input type="number" name="points" value="{{ old('points', $question->points ?? 10) }}" min="1" max="1000" required>
    </label>

    <label class="field">
        <span>Posisi/urutan *</span>
        <input type="number" name="position" value="{{ old('position', $question->position ?? $nextPosition ?? 1) }}" min="1" max="10000" required>
    </label>
</div>
