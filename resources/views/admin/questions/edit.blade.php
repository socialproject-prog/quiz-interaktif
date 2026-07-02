@extends('layouts.admin')

@section('title', 'Edit Soal')
@section('page-title', 'Edit Soal')
@section('page-subtitle', $quiz->title)

@section('content')
<div class="page-heading">
    <div><h1>Edit Soal</h1><p>Perubahan langsung berlaku pada kuis.</p></div>
    <a href="{{ route('admin.quizzes.show', $quiz) }}" class="btn btn-light">← Kembali</a>
</div>

<form method="POST" action="{{ route('admin.questions.update', [$quiz, $question]) }}" class="card form-card">
    @csrf
    @method('PUT')
    @include('admin.questions._form')
    <div class="form-actions">
        <a href="{{ route('admin.quizzes.show', $quiz) }}" class="btn btn-light">Batal</a>
        <button class="btn btn-primary">Simpan Perubahan</button>
    </div>
</form>
@endsection
