<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DigitalProduct extends Model
{
    protected $fillable = [
        'title', 'description', 'thumbnail', 'pillar', 'price',
        'delivery_type', 'file_path', 'file_name', 'access_url',
        'is_published', 'sort_order',
    ];

    protected $casts = ['is_published' => 'boolean'];

    public function purchases(): HasMany
    {
        return $this->hasMany(ProductPurchase::class);
    }

    public function isFree(): bool
    {
        return $this->price <= 0;
    }
}
