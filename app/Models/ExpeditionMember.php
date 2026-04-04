<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpeditionMember extends Model {
    public $timestamps = false;
    protected $fillable = [
        'expedition_id','user_id','class_at_join','joined_at','completed_at',
        'kicked_at','last_checkin_at','consecutive_missed_days','revenue_share_pct',
        'status','approved_at','approved_by','payment_amount','payment_ref','personal_starts_at',
    ];
    protected $casts = [
        'joined_at'=>'datetime','completed_at'=>'datetime','kicked_at'=>'datetime',
        'last_checkin_at'=>'datetime','approved_at'=>'datetime','personal_starts_at'=>'datetime',
        'payment_amount'=>'decimal:2',
    ];
    public function expedition(): BelongsTo { return $this->belongsTo(Expedition::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
