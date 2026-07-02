<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'name',
        'session_token',
        'total_score',
        'total_time_ms',
        'current_question',
        'question_started_at',
        'completed_at',
    ];

    protected $hidden = ['session_token'];

    protected function casts(): array
    {
        return [
            'total_score' => 'integer',
            'total_time_ms' => 'integer',
            'current_question' => 'integer',
            'question_started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}
