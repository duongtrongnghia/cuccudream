<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Attributes\On;
use Livewire\Component;

class PostModal extends Component
{
    public ?Post $post = null;
    public bool $show = false;

    #[On('open-post')]
    public function openPost(int $postId): void
    {
        $this->post = Post::with(['user.daKhongCuc', 'allComments.user', 'allComments.replies.user', 'likes', 'topic'])
            ->find($postId);
        $this->show = $this->post !== null;
    }

    public function close(): void
    {
        $this->show = false;
        $this->post = null;
        $this->js('document.body.style.overflow=""');
    }

    public function render()
    {
        return view('livewire.post-modal');
    }
}
