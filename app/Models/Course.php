<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Course extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'pillar', 'difficulty', 'min_level',
        'xp_reward', 'aip_reward', 'price', 'thumbnail', 'is_published',
    ];

    protected $casts = ['is_published' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(function (Course $course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });

        static::updating(function (Course $course) {
            if ($course->isDirty('title') && !$course->isDirty('slug')) {
                $course->slug = Str::slug($course->title);
            }
        });
    }

    /** Route model binding uses slug */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isFree(): bool
    {
        return $this->price <= 0;
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class)->orderBy('order_index');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }
}
