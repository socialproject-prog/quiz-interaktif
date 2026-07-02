<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->string('selected_option', 1)->nullable();
            $table->boolean('is_correct')->default(false);
            $table->unsignedInteger('score')->default(0);
            $table->unsignedBigInteger('response_time_ms')->default(0);
            $table->timestamp('answered_at');
            $table->timestamps();

            $table->unique(['participant_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
