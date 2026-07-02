<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('quiz:info', function () {
    $this->info('Quiz Interaktif siap digunakan.');
})->purpose('Menampilkan status aplikasi kuis');
