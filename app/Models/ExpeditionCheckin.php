<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpeditionCheckin extends Model {
    protected $fillable = ['expedition_id','user_id','content'];
    public function expedition(): BelongsTo { return $this->belongsTo(Expedition::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
