<?php
namespace App\Livewire;
use App\Models\Post;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
class SignalsPage extends Component {
    use WithPagination;
    public function render() {
        $query = Post::with(['user.daKhongCuc'])->withCount(['likes','allComments'])->where('is_signal', true)->whereNull('deleted_at');
        $query->latest();
        $todayCount = Post::where('is_signal', true)->whereDate('created_at', today())->count();
        return view('livewire.signals-page', ['posts' => $query->paginate(15), 'todayCount' => $todayCount])
            ->layout('layouts.app', ['title' => 'Thành Quả — Cúc Cu Dream™']);
    }
}
