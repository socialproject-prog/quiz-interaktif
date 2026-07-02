@extends('layouts.admin')

@section('title', 'Edit Kuis')
@section('page-title', 'Edit Kuis')
@section('page-subtitle', $quiz->title)

@section('content')
<div class="page-heading">
    <div><h1>Edit Pengaturan Kuis</h1><p>Kode: <span class="code-pill">{{ $quiz->code }}</span></p></div>
    <a href="{{ route('admin.quizzes.show', $quiz) }}" class="btn btn-light">← Kembali</a>
</div>

<form action="{{ route('admin.quizzes.update', $quiz) }}" method="POST" class="card form-card">
    @csrf
    @method('PUT')
    @include('admin.quizzes._form')
    <div class="form-actions">
        <a href="{{ route('admin.quizzes.show', $quiz) }}" class="btn btn-light">Batal</a>
        <button class="btn btn-primary">Simpan Perubahan</button>
    </div>
</form>
@endsection
