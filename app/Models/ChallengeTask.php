<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ChallengeTask extends Model
{
    protected $fillable = [
        'expedition_id', 'day_number', 'title', 'description',
        'sop_content', 'video_url', 'meeting_at', 'evidence_type', 'evidence_label', 'admin_note',
    ];

    protected $casts = ['meeting_at' => 'datetime'];

    public function expedition(): BelongsTo
    {
        return $this->belongsTo(Expedition::class);
    }

    public function completedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'challenge_task_completions')
            ->withPivot('evidence')
            ->withTimestamps();
    }
}
