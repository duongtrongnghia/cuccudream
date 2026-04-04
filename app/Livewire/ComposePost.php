<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\PostImage;
use App\Models\Topic;
use App\Services\XpService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class ComposePost extends Component
{
    use WithFileUploads;

    public bool $expanded = false;
    public array $uploadedImages = [];

    #[Rule('nullable|max:150')]
    public string $title = '';

    #[Rule('required|min:5|max:50000')]
    public string $content = '';

    public bool $isSignal = false;

    #[Rule('nullable|exists:topics,id')]
    public ?int $topic_id = null;

    public $imageUploads = [];

    public function updatedImageUploads(): void
    {
        $this->validate(['imageUploads.*' => 'image|max:5120']); // 5MB per image
        foreach ($this->imageUploads as $img) {
            if (count($this->uploadedImages) >= 4) break; // Max 4 images
            $path = $img->store('post-images', 'public');
            $this->uploadedImages[] = $path;
        }
        $this->imageUploads = [];
    }

    public function removeImage(int $index): void
    {
        if (isset($this->uploadedImages[$index])) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($this->uploadedImages[$index]);
            array_splice($this->uploadedImages, $index, 1);
        }
    }

    public function submit(): void
    {
        $this->validate();

        $user = Auth::user();

        if ($user->level < 10) {
            $this->addError('content', 'Bạn cần đạt Level 10 để đăng bài. Hãy tương tác bằng comment để lên level!');
            return;
        }

        if ($this->isSignal && str_word_count($this->content) > 500) {
            $this->addError('content', 'Tín hiệu tối đa 500 từ');
            return;
        }

        $post = Post::create([
            'user_id'   => $user->id,
            'title'     => $this->title ?: null,
            'content'   => $this->content,
            'topic_id'  => $this->topic_id ?: null,
            'is_signal' => $this->isSignal,
        ]);

        // Save uploaded images
        foreach ($this->uploadedImages as $i => $path) {
            PostImage::create(['post_id' => $post->id, 'path' => $path, 'order_index' => $i]);
        }

        $this->reset(['title', 'content', 'topic_id', 'isSignal', 'expanded', 'uploadedImages']);
        $this->dispatch('post-created');
    }

    public function render()
    {
        return view('livewire.compose-post', [
            'topics' => Topic::active()->get(),
        ]);
    }
}
