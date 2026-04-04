<?php

namespace App\Models;

use Database\Factories\TopicFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    /** @use HasFactory<TopicFactory> */
    use HasFactory;

    protected $fillable = ['name', 'emoji', 'slug', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function getLabelAttribute(): string
    {
        return $this->emoji ? $this->emoji . ' ' . $this->name : $this->name;
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true)->orderBy('sort_order');
    }
}
