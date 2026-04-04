<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateEarning extends Model {
    protected $fillable = ['referrer_id','referred_id','membership_id','amount','commission_rate','status','paid_at'];
    protected $casts = ['paid_at'=>'datetime'];
    public function referrer(): BelongsTo { return $this->belongsTo(User::class, 'referrer_id'); }
    public function referred(): BelongsTo { return $this->belongsTo(User::class, 'referred_id'); }
    public function membership(): BelongsTo { return $this->belongsTo(Membership::class); }
}
