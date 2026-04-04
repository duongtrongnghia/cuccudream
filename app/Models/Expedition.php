<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expedition extends Model {
    protected $fillable = [
        'title','description','boss_name','difficulty','required_days','max_members',
        'created_by','status','deposit_aip','starts_at','ends_at','price',
    ];
    protected $casts = ['starts_at'=>'datetime','ends_at'=>'datetime','price'=>'decimal:2'];

    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function members(): HasMany { return $this->hasMany(ExpeditionMember::class); }
    public function checkins(): HasMany { return $this->hasMany(ExpeditionCheckin::class); }
    public function tasks(): HasMany { return $this->hasMany(ChallengeTask::class); }

    public function activeMembersCount(): int { return $this->members()->whereNull('kicked_at')->count(); }
    public function uniqueClassCount(): int {
        return $this->members()->whereNull('kicked_at')->distinct('class_at_join')->count('class_at_join');
    }
    public function getXpBonusMultiplier(): float {
        $classes = $this->uniqueClassCount();
        return match(true) { $classes >= 5 => 1.5, $classes >= 3 => 1.2, default => 1.0 };
    }
    public function getDifficultyLabelAttribute(): string {
        return match($this->difficulty) { 'normal'=>'Normal','hard'=>'Hard','chaos'=>'Chaos',default=>$this->difficulty };
    }
    public function getDifficultyColorAttribute(): string {
        return match($this->difficulty) { 'normal'=>'emerald','hard'=>'amber','chaos'=>'red',default=>'gray' };
    }

    public function start(): void {
        if ($this->status !== 'open') return;
        $this->update([
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addDays($this->required_days),
        ]);
    }

    public function complete(): void {
        if ($this->status !== 'active') return;
        $this->update(['status' => 'completed']);
    }

    public function fail(): void {
        if ($this->status !== 'active') return;
        $this->update(['status' => 'failed']);
    }
}
