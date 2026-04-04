<?php
namespace App\Livewire;
use App\Models\User;
use App\Models\XpTransaction;
use Livewire\Attributes\Url;
use Livewire\Component;
class LeaderboardPage extends Component {
    #[Url] public string $period = 'week';
    public function setPeriod(string $p) { $this->period = $p; }
    public function render() {
        $top = match($this->period) {
            'week' => User::select('users.*')
                ->selectSub(
                    XpTransaction::selectRaw('COALESCE(SUM(amount),0)')
                        ->whereColumn('user_id', 'users.id')
                        ->where('created_at', '>=', now()->startOfWeek()),
                    'period_xp'
                )
                ->orderByDesc('period_xp')->take(50)->get(),
            'month' => User::select('users.*')
                ->selectSub(
                    XpTransaction::selectRaw('COALESCE(SUM(amount),0)')
                        ->whereColumn('user_id', 'users.id')
                        ->where('created_at', '>=', now()->startOfMonth()),
                    'period_xp'
                )
                ->orderByDesc('period_xp')->take(50)->get(),
            'alltime' => User::orderByDesc('xp')->take(50)->get(),
            'da'      => User::select('users.*')
                ->join('da_khong_cuc', 'da_khong_cuc.user_id', '=', 'users.id')
                ->orderByDesc('da_khong_cuc.total_count')
                ->take(50)->get(),
            default   => User::orderByDesc('xp')->take(50)->get(),
        };
        $myRank = null;
        if (auth()->check()) {
            $myRank = $top->search(fn($u)=>$u->id === auth()->id());
            if ($myRank === false) $myRank = null;
        }
        return view('livewire.leaderboard-page', ['top' => $top, 'myRank' => $myRank])
            ->layout('layouts.app', ['title' => 'Leaderboard — Cúc Cu Dream™']);
    }
}
