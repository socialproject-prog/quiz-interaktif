<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'code',
        'is_active',
        'default_timer',
        'show_leaderboard',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'show_leaderboard' => 'boolean',
            'default_timer' => 'integer',
        ];
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('position');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }
}
