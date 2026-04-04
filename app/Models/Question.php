<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model {
    protected $fillable = ['user_id','title','body','pillar','status','is_anonymous','is_paid','paid_aip_amount'];
    protected $casts = ['is_anonymous'=>'boolean','is_paid'=>'boolean'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function answers(): HasMany { return $this->hasMany(Answer::class); }
    public function bestAnswer() { return $this->answers()->where('is_best', true)->first(); }
}
