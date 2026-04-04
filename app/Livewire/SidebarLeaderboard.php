<?php
namespace App\Livewire;
use App\Models\User;
use Livewire\Component;
class SidebarLeaderboard extends Component {
    public function render() {
        $top = User::orderByDesc('xp')->take(5)->get();
        return view('livewire.sidebar-leaderboard', ['top' => $top]);
    }
}
