@extends('layouts.admin')

@section('title', 'Tambah Soal')
@section('page-title', 'Tambah Soal')
@section('page-subtitle', $quiz->title)

@section('content')
<div class="page-heading">
    <div><h1>Tambah Soal Baru</h1><p>Buat pilihan A, B, C, D dan tentukan jawaban benar.</p></div>
    <a href="{{ route('admin.quizzes.show', $quiz) }}" class="btn btn-light">← Kembali</a>
</div>

<form method="POST" action="{{ route('admin.questions.store', $quiz) }}" class="card form-card">
    @csrf
    @include('admin.questions._form')
    <div class="form-actions">
        <a href="{{ route('admin.quizzes.show', $quiz) }}" class="btn btn-light">Batal</a>
        <button class="btn btn-primary">Simpan Soal</button>
    </div>
</form>
@endsection
