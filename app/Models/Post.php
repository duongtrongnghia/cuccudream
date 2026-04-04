<?php

namespace App\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'content', 'topic_id', 'is_cot', 'cot_at', 'cot_by',
        'is_pinned', 'is_signal', 'rune_active', 'rune_expires_at',
        'rune_first_comment_user_id', 'view_count',
    ];

    protected $casts = [
        'is_cot'          => 'boolean',
        'is_pinned'       => 'boolean',
        'is_signal'       => 'boolean',
        'rune_active'     => 'boolean',
        'cot_at'          => 'datetime',
        'rune_expires_at' => 'datetime',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function comments(): HasMany { return $this->hasMany(Comment::class)->whereNull('parent_id'); }
    public function allComments(): HasMany { return $this->hasMany(Comment::class); }
    public function likes(): MorphMany { return $this->morphMany(Like::class, 'likeable'); }
    public function attachments(): HasMany { return $this->hasMany(PostAttachment::class); }
    public function images(): HasMany { return $this->hasMany(PostImage::class)->orderBy('order_index'); }
    public function cotBy(): BelongsTo { return $this->belongsTo(User::class, 'cot_by'); }
    public function topic(): BelongsTo { return $this->belongsTo(Topic::class); }

    public function scopeCot($q) { return $q->where('is_cot', true); }
    public function scopeSignal($q) { return $q->where('is_signal', true); }

    public function isRuneActive(): bool {
        return $this->rune_active && $this->rune_expires_at?->isFuture();
    }

    public function isLikedBy(?User $user): bool {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function isBookmarkedBy(?User $user): bool {
        if (!$user) return false;
        return Bookmark::where('user_id', $user->id)->where('post_id', $this->id)->exists();
    }
}
