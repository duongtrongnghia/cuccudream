<?php
namespace App\Livewire;
use App\Models\Post;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
class CotPage extends Component {
    use WithPagination;
    #[Url] public string $search = '';
    #[Url] public string $sort = 'latest';
    public function setSort(string $s) { $this->sort = $s; $this->resetPage(); }
    public function render() {
        $query = Post::with(['user.daKhongCuc'])->withCount(['likes','allComments'])->where('is_cot', true)->whereNull('deleted_at');
        if ($this->search) $query->where('content', 'ilike', '%'.$this->search.'%');
        if ($this->sort === 'popular') $query->orderByDesc(
            Post::selectRaw('count(*)')->from('likes')
                ->whereColumn('likes.likeable_id', 'posts.id')
                ->where('likes.likeable_type', Post::class)
        );
        else $query->latest('cot_at');
        return view('livewire.cot-page', ['posts' => $query->paginate(10)])
            ->layout('layouts.app', ['title' => 'Tâm Đắc — Cúc Cu Dream™']);
    }
}
