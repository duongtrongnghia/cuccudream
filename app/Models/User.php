<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'username', 'password', 'avatar', 'bio',
        'account_type', 'parent_id',
        'level', 'xp', 'aip', 'streak', 'last_active_at',
        'referred_by', 'is_admin', 'is_moderator',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_active_at'    => 'datetime',
            'is_admin'          => 'boolean',
            'is_moderator'      => 'boolean',
            'password'          => 'hashed',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────────

    public function membership(): HasOne
    {
        return $this->hasOne(Membership::class)->latestOfMany();
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function referredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    // ─── Family Relationships ───────────────────────────────────────

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    public function isParent(): bool
    {
        return $this->account_type === 'parent';
    }

    public function isKid(): bool
    {
        return $this->account_type === 'kid';
    }

    public function xpTransactions(): HasMany
    {
        return $this->hasMany(XpTransaction::class);
    }

    public function aipTransactions(): HasMany
    {
        return $this->hasMany(AipTransaction::class);
    }

    public function daKhongCuc(): HasOne
    {
        return $this->hasOne(DaKhongCuc::class);
    }

    public function powerSymbols(): HasMany
    {
        return $this->hasMany(PowerSymbol::class);
    }

    public function expeditionMembers(): HasMany
    {
        return $this->hasMany(ExpeditionMember::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function affiliateEarnings(): HasMany
    {
        return $this->hasMany(AffiliateEarning::class, 'referrer_id');
    }

    public function userBadges(): HasMany
    {
        return $this->hasMany(UserBadge::class);
    }

    // ─── Computed Attributes ─────────────────────────────────────────

    public function getJobStageAttribute(): string
    {
        if ($this->account_type === 'kid') {
            return match(true) {
                $this->level <= 5   => 'Hạt Giống Nhỏ',
                $this->level <= 15  => 'Mầm Non Xinh',
                $this->level <= 30  => 'Họa Sĩ Tí Hon',
                $this->level <= 50  => 'Ngôi Sao Sáng Tạo',
                $this->level <= 80  => 'Nhà Phát Minh Nhí',
                default             => 'Giấc Mơ Bay Xa',
            };
        }

        return match(true) {
            $this->level <= 5   => 'Người Gieo Hạt',
            $this->level <= 15  => 'Người Tưới Cây',
            $this->level <= 30  => 'Người Chăm Vườn',
            $this->level <= 50  => 'Người Ngắm Hoa',
            $this->level <= 80  => 'Người Nuôi Mơ',
            default             => 'Vườn Mơ Trọn Vẹn',
        };
    }

    public function getDaCountAttribute(): int
    {
        return $this->daKhongCuc?->total_count ?? 0;
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        $initials = collect(explode(' ', $this->name))
            ->map(fn($w) => strtoupper(substr($w, 0, 1)))
            ->take(2)
            ->join('');
        return 'https://ui-avatars.com/api/?name=' . urlencode($initials) . '&background=EDE9FE&color=4C1D95&bold=true&size=80';
    }

    public function isActive(): bool
    {
        $m = $this->membership;
        return $m && in_array($m->status, ['trial', 'active']);
    }
}
