@extends('layouts.admin')

@section('title', 'Buat Kuis')
@section('page-title', 'Buat Kuis')
@section('page-subtitle', 'Buat kuis baru dan atur timer')

@section('content')
<div class="page-heading">
    <div><h1>Buat Kuis Baru</h1><p>Kode kuis akan dibuat otomatis.</p></div>
    <a href="{{ route('admin.quizzes.index') }}" class="btn btn-light">← Kembali</a>
</div>

<form action="{{ route('admin.quizzes.store') }}" method="POST" class="card form-card">
    @csrf
    @include('admin.quizzes._form')
    <div class="form-actions">
        <a href="{{ route('admin.quizzes.index') }}" class="btn btn-light">Batal</a>
        <button class="btn btn-primary">Simpan Kuis</button>
    </div>
</form>
@endsection
