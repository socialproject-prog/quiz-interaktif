<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->uuid('session_token')->unique();
            $table->unsignedInteger('total_score')->default(0);
            $table->unsignedBigInteger('total_time_ms')->default(0);
            $table->unsignedInteger('current_question')->default(0);
            $table->timestamp('question_started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['quiz_id', 'total_score', 'total_time_ms']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
