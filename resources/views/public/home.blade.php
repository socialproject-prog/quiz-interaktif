@extends('layouts.public')

@section('title', 'Gabung Kuis - Quiz Interaktif')
@section('body-class', 'home-page')

@section('content')
<div class="public-home-shell">
    <header class="public-header">
        <a href="{{ route('home') }}" class="brand">
            <span class="brand-mark">Q</span>
            <span>Quiz Interaktif</span>
        </a>
        <a href="{{ route('admin.login') }}" class="btn btn-light btn-sm">Login Admin</a>
    </header>

    <main class="hero-layout">
        <section class="hero-copy">
            <span class="eyebrow">KUIS PILIHAN GANDA A–D</span>
            <h1>Belajar jadi lebih seru, cepat, dan interaktif.</h1>
            <p>Masukkan kode kuis dari penyaji, tulis nama, lalu jawab setiap soal sebelum waktu habis.</p>
            <div class="feature-chips">
                <span>✓ Timer per soal</span>
                <span>✓ Nilai otomatis</span>
                <span>✓ Peringkat langsung</span>
            </div>
        </section>

        <section class="join-card">
            <div class="join-card-icon">⚡</div>
            <h2>Gabung ke Kuis</h2>
            <p>Gunakan kode yang tampil pada layar penyaji.</p>

            @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('quiz.join') }}" method="POST" class="stack-form">
                @csrf
                <label class="field">
                    <span>Kode kuis</span>
                    <input class="code-input" type="text" name="code" value="{{ old('code', '123456') }}" maxlength="10" required autocomplete="off" placeholder="Contoh: 123456">
                </label>
                <label class="field">
                    <span>Nama peserta</span>
                    <input type="text" name="name" value="{{ old('name') }}" maxlength="80" required autocomplete="name" placeholder="Masukkan nama kamu">
                </label>
                <button class="btn btn-primary btn-block btn-lg">Mulai Kuis →</button>
            </form>

            <small class="join-note">Kode demo bawaan: <strong>123456</strong></small>
        </section>
    </main>
</div>
@endsection
