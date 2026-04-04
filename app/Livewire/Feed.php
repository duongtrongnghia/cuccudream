<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Feed extends Component
{
    use WithPagination;

    #[Url]
    public string $tab = 'latest';   // latest|cot|popular|signal

    protected $paginationTheme = 'tailwind';

    #[On('post-created')]
    public function refreshFeed(): void
    {
        $this->resetPage();
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function render()
    {
        $query = Post::with(['user.daKhongCuc', 'likes', 'allComments'])
            ->withCount(['likes', 'allComments'])
            ->whereNull('deleted_at');

        match($this->tab) {
            'cot'     => $query->where('is_cot', true),
            'signal'  => $query->where('is_signal', true),
            'popular' => $query->where('created_at', '>=', now()->subDays(7))
                               ->orderByDesc(
                                   Post::selectRaw('count(*)')
                                       ->from('likes')
                                       ->whereColumn('likes.likeable_id', 'posts.id')
                                       ->where('likes.likeable_type', Post::class)
                               ),
            default   => null,
        };

        if ($this->tab !== 'popular') {
            $query->latest();
        }

        // Pinned posts always on top
        $pinned = Post::with(['user.daKhongCuc'])
            ->where('is_pinned', true)
            ->whereNull('deleted_at')
            ->latest()
            ->get();

        $posts = $query->where('is_pinned', false)->paginate(15);

        return view('livewire.feed', [
            'posts'       => $posts,
            'pinnedPosts' => $pinned,
        ])->layout('layouts.app', ['title' => 'Bảng tin — Cúc Cu Dream™']);
    }
}
