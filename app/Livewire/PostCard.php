<?php

namespace App\Livewire;

use App\Models\Bookmark;
use App\Models\Like;
use App\Models\Post;
use App\Notifications\GenericNotification;
use App\Services\XpService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PostCard extends Component
{
    public Post $post;
    public bool $showComments = false;
    public string $newComment = '';
    public bool $showFull = false;
    public ?int $replyToId = null;
    public ?string $replyToName = null;
    public bool $isLiked = false;
    public bool $isBookmarked = false;
    public int $likesCount = 0;

    public function mount(Post $post): void
    {
        $this->post        = $post->load(['user.daKhongCuc', 'likes', 'allComments', 'images']);
        $this->likesCount  = $post->likes_count ?? $post->likes->count();
        $user              = Auth::user();
        $this->isLiked     = $user ? $post->likes()->where('user_id', $user->id)->exists() : false;
        $this->isBookmarked = $user ? Bookmark::where('user_id', $user->id)->where('post_id', $post->id)->exists() : false;
    }

    public function toggleLike(): void
    {
        if (!Auth::check()) return;

        $existing = Like::where('likeable_type', Post::class)
            ->where('likeable_id', $this->post->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            $existing->delete();
            $this->likesCount--;
            $this->isLiked = false;
        } else {
            Like::create([
                'likeable_type' => Post::class,
                'likeable_id'   => $this->post->id,
                'user_id'       => Auth::id(),
            ]);
            $this->likesCount++;
            $this->isLiked = true;

            // Award EXP to post OWNER + notify
            $owner = $this->post->user;
            if ($owner->id !== Auth::id()) {
                app(XpService::class)->award($owner, 'post_liked', 1.0, Auth::user()->name . ' thích bài viết', $this->post);
                $owner->notify(new GenericNotification('♥', Auth::user()->name . ' thích bài viết của bạn', null, $this->post->id));
            }
        }
    }

    public function toggleBookmark(): void
    {
        if (!Auth::check()) return;

        $existing = Bookmark::where('user_id', Auth::id())->where('post_id', $this->post->id)->first();

        if ($existing) {
            $existing->delete();
            $this->isBookmarked = false;
        } else {
            Bookmark::create(['user_id' => Auth::id(), 'post_id' => $this->post->id]);
            $this->isBookmarked = true;

            // Award EXP to post owner
            $owner = $this->post->user;
            if ($owner->id !== Auth::id()) {
                app(XpService::class)->award($owner, 'post_bookmarked', 1.0, Auth::user()->name . ' lưu bài viết', $this->post);
            }
        }
    }

    public function toggleCommentLike(int $commentId): void
    {
        if (!Auth::check()) return;
        $comment = \App\Models\Comment::findOrFail($commentId);

        $existing = Like::where('likeable_type', \App\Models\Comment::class)
            ->where('likeable_id', $commentId)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            $existing->delete();
        } else {
            Like::create([
                'likeable_type' => \App\Models\Comment::class,
                'likeable_id' => $commentId,
                'user_id' => Auth::id(),
            ]);
            // Award EXP to comment owner
            app(XpService::class)->award($comment->user, 'comment_liked', 1.0, Auth::user()->name . ' thích bình luận', $comment);
        }
    }

    public function addComment(): void
    {
        if (!Auth::check() || blank($this->newComment)) return;

        $this->validate(['newComment' => 'required|max:2000']);

        // Anti-spam: max 20 comments per hour
        $recentComments = \App\Models\Comment::where('user_id', Auth::id())
            ->where('created_at', '>=', now()->subHour())->count();
        if ($recentComments >= 20) {
            $this->addError('newComment', 'Bạn đã bình luận quá nhiều. Vui lòng đợi.');
            return;
        }

        $isFirstRune = $this->post->isRuneActive() && !$this->post->rune_first_comment_user_id;

        $comment = $this->post->allComments()->create([
            'user_id'       => Auth::id(),
            'parent_id'     => $this->replyToId,
            'content'       => $this->newComment,
            'is_rune_winner'=> $isFirstRune,
        ]);

        // Commenter gets +1 base EXP
        if ($isFirstRune) {
            $this->post->update(['rune_first_comment_user_id' => Auth::id()]);
            app(XpService::class)->award(Auth::user(), 'comment', 2.0, 'Phù văn 2x EXP', $comment);
        } else {
            app(XpService::class)->award(Auth::user(), 'comment', 1.0, null, $comment);
        }

        // Post OWNER gets +3 EXP only for FIRST comment from each unique user
        $owner = $this->post->user;
        if ($owner->id !== Auth::id()) {
            $alreadyCommented = $this->post->allComments()
                ->where('user_id', Auth::id())
                ->where('id', '!=', $comment->id)
                ->exists();

            if (!$alreadyCommented) {
                app(XpService::class)->award($owner, 'post_commented', 1.0, Auth::user()->name . ' bình luận bài viết', $this->post);
            }
            $owner->notify(new GenericNotification('💬', Auth::user()->name . ' bình luận bài viết của bạn', null, $this->post->id));
        }

        $this->newComment = '';
        $this->replyToId = null;
        $this->replyToName = null;
        $this->post->refresh();
    }

    public function replyTo(int $commentId, string $name): void
    {
        $this->replyToId = $commentId;
        $this->replyToName = $name;
        $this->showComments = true;
    }

    public function cancelReply(): void
    {
        $this->replyToId = null;
        $this->replyToName = null;
    }

    public function deletePost(): void
    {
        if (!Auth::check()) return;
        if (Auth::id() !== $this->post->user_id && !Auth::user()->is_admin) return;
        $this->post->delete();
        $this->dispatch('post-created'); // refresh feed
    }

    public bool $editing = false;
    public string $editContent = '';

    public function startEdit(): void
    {
        if (Auth::id() !== $this->post->user_id) return;
        $this->editing = true;
        $this->editContent = $this->post->content;
    }

    public function saveEdit(): void
    {
        if (Auth::id() !== $this->post->user_id) return;
        if (blank($this->editContent)) return;
        $this->post->update(['content' => $this->editContent]);
        $this->editing = false;
        $this->post->refresh();
    }

    public function cancelEdit(): void
    {
        $this->editing = false;
        $this->editContent = '';
    }

    public function nominateCot(): void
    {
        if (!Auth::check()) return;
        $user = Auth::user();
        if ($user->level < 30) return;
        if ($this->post->is_cot || $this->post->cot_by) return;

        $this->post->update(['cot_by' => $user->id]);

        // Notify post owner
        $owner = $this->post->user;
        if ($owner->id !== $user->id) {
            $owner->notify(new GenericNotification('★', $user->name . ' đề cử bài viết của bạn cho CỐT', null, $this->post->id));
        }

        $this->dispatch('toast', message: 'Đã đề cử bài viết cho CỐT!', type: 'success');
    }

    public function reportPost(): void
    {
        if (!Auth::check()) return;
        if (Auth::id() === $this->post->user_id) return;

        $exists = \App\Models\Report::where('user_id', Auth::id())
            ->where('reportable_type', Post::class)
            ->where('reportable_id', $this->post->id)
            ->exists();

        if ($exists) {
            $this->dispatch('toast', message: 'Bạn đã báo cáo bài viết này rồi', type: 'error');
            return;
        }

        \App\Models\Report::create([
            'user_id' => Auth::id(),
            'reportable_type' => Post::class,
            'reportable_id' => $this->post->id,
            'reason' => 'Spam / Vi phạm',
        ]);

        $this->dispatch('toast', message: 'Đã báo cáo bài viết cho Admin', type: 'success');
    }

    // ─── Comment actions ──────────────────────────────────────────
    public ?int $editingCommentId = null;
    public string $editCommentContent = '';

    public function startEditComment(int $commentId): void
    {
        if (!Auth::check()) return;
        $comment = \App\Models\Comment::findOrFail($commentId);
        if (Auth::id() !== $comment->user_id) return;
        $this->editingCommentId = $commentId;
        $this->editCommentContent = $comment->content;
    }

    public function saveEditComment(): void
    {
        if (!Auth::check() || !$this->editingCommentId) return;
        $comment = \App\Models\Comment::findOrFail($this->editingCommentId);
        if (Auth::id() !== $comment->user_id) return;
        if (blank($this->editCommentContent)) return;
        $comment->update(['content' => $this->editCommentContent]);
        $this->editingCommentId = null;
        $this->editCommentContent = '';
        $this->post->refresh();
    }

    public function cancelEditComment(): void
    {
        $this->editingCommentId = null;
        $this->editCommentContent = '';
    }

    public function deleteComment(int $commentId): void
    {
        if (!Auth::check()) return;
        $comment = \App\Models\Comment::findOrFail($commentId);
        if (Auth::id() !== $comment->user_id && !Auth::user()->is_admin) return;
        $comment->delete();
        $this->post->refresh();
    }

    public function reportComment(int $commentId): void
    {
        if (!Auth::check()) return;
        $comment = \App\Models\Comment::findOrFail($commentId);
        if (Auth::id() === $comment->user_id) return;

        $exists = \App\Models\Report::where('user_id', Auth::id())
            ->where('reportable_type', \App\Models\Comment::class)
            ->where('reportable_id', $commentId)
            ->exists();

        if ($exists) {
            $this->dispatch('toast', message: 'Bạn đã báo cáo bình luận này rồi', type: 'error');
            return;
        }

        \App\Models\Report::create([
            'user_id' => Auth::id(),
            'reportable_type' => \App\Models\Comment::class,
            'reportable_id' => $commentId,
            'reason' => 'Spam / Vi phạm',
        ]);

        $this->dispatch('toast', message: 'Đã báo cáo bình luận cho Admin', type: 'success');
    }

    public function renderContent(bool $showFull): string
    {
        $text = ($showFull || strlen($this->post->content) <= 500)
            ? $this->post->content
            : \Illuminate\Support\Str::limit($this->post->content, 500);

        $escaped = e($text);

        // Auto-embed YouTube URLs
        $escaped = preg_replace_callback(
            '#(https?://(?:www\.)?(?:youtube\.com/watch\?v=|youtu\.be/)([a-zA-Z0-9_-]{11})[^\s<]*)#i',
            fn($m) => '<div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:0.5rem;margin:0.5rem 0;"><iframe src="https://www.youtube.com/embed/' . $m[2] . '" style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;" allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture" allowfullscreen></iframe></div>',
            $escaped
        );

        // Linkify remaining URLs (skip URLs inside HTML attributes like src="...")
        $escaped = preg_replace(
            '#(?<!src="|href=")(https?://[^\s<"]+)#i',
            '<a href="$1" target="_blank" rel="noopener" style="color:#2E7D32; text-decoration:underline; word-break:break-all;">$1</a>',
            $escaped
        );

        return $escaped;
    }

    public function render()
    {
        $comments = $this->showComments
            ? $this->post->comments()->with(['user.daKhongCuc', 'replies.user'])->oldest()->get()
            : collect();

        return view('livewire.post-card', [
            'comments' => $comments,
        ]);
    }
}
