<div id="post-{{ $post->id }}" class="post-card {{ $post->is_cot ? 'is-cot' : '' }} {{ $post->isRuneActive() ? 'has-rune' : '' }} {{ $post->is_signal ? 'is-signal' : '' }}">

    {{-- Rune indicator --}}
    @if($post->isRuneActive())
    <div class="rune-banner flex items-center gap-2 mb-3" style="padding:0.375rem 0.625rem;">
        <span style="font-size:0.875rem;">~</span>
        <span style="font-size:0.75rem; color:#C2410C; font-weight:600;">Phù văn kích hoạt · 2x EXP cho comment đầu tiên</span>
        <span style="margin-left:auto; font-size:0.7rem; color:#636E72;" x-data="{ remaining: '' }" x-init="
            const exp = new Date('{{ $post->rune_expires_at?->toISOString() }}');
            const update = () => {
                const diff = exp - Date.now();
                if (diff <= 0) { remaining = 'Hết hạn'; return; }
                const h = Math.floor(diff / 3600000);
                const m = Math.floor((diff % 3600000) / 60000);
                remaining = h + 'h ' + m + 'm';
                setTimeout(update, 30000);
            };
            update();
        " x-text="remaining"></span>
    </div>
    @endif

    {{-- CỐT badge --}}
    @if($post->is_cot)
    <div style="display:flex; justify-content:flex-end; margin-bottom:0.5rem;">
        <span class="cot-badge">★ Tâm Đắc</span>
    </div>
    @endif

    {{-- Author --}}
    <div class="flex items-start gap-3 mb-3">
        <a href="{{ route('profile', $post->user->username ?? $post->user->id) }}">
            <img src="{{ $post->user->avatar_url }}" class="avatar w-10 h-10 shrink-0" alt="">
        </a>
        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-1.5">
                <a href="{{ route('profile', $post->user->username ?? $post->user->id) }}"
                   style="font-weight:600; color:#1A1A1A; font-size:0.875rem;">
                    {{ $post->user->name }}
                </a>
                <span class="level-badge">Lv.{{ $post->user->level }}</span>
                @if($post->user->da_count > 0)
                <span class="da-gem">◆ {{ $post->user->da_count }}</span>
                @endif
            </div>
            <div class="flex flex-wrap items-center gap-1.5 mt-0.5">
                @if($post->topic)
                <span style="font-size:0.7rem; color:#636E72;">{{ $post->topic->emoji }} {{ $post->topic->name }}</span>
                @endif
                <span style="font-size:0.7rem; color:#636E72;">{{ $post->created_at->diffForHumans() }}</span>
            </div>
        </div>
        @auth
        <div x-data="{ open: false }" class="relative shrink-0">
            <button @click="open = !open" style="color:#636E72; padding:0.25rem; cursor:pointer;">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/></svg>
            </button>
            <div x-show="open" @click.away="open = false" x-transition style="position:absolute; right:0; top:calc(100% + 4px); background:#FFF; border:1px solid #E1E1E1; border-radius:0.5rem; padding:0.25rem; min-width:120px; z-index:50; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                @if(auth()->id() === $post->user_id)
                <button wire:click="startEdit" @click="open = false" style="display:block; width:100%; text-align:left; padding:0.375rem 0.75rem; font-size:0.8rem; color:#1A1A1A; border-radius:0.25rem; cursor:pointer;">Sửa bài</button>
                @endif
                @if(auth()->id() === $post->user_id || auth()->user()->is_admin)
                <button wire:click="deletePost" wire:confirm="Xóa bài viết này?" @click="open = false" style="display:block; width:100%; text-align:left; padding:0.375rem 0.75rem; font-size:0.8rem; color:#991B1B; border-radius:0.25rem; cursor:pointer;">Xóa bài</button>
                @endif
                @if(auth()->id() !== $post->user_id)
                <button wire:click="reportPost" @click="open = false" style="display:block; width:100%; text-align:left; padding:0.375rem 0.75rem; font-size:0.8rem; color:#636E72; border-radius:0.25rem; cursor:pointer;">▲ Báo cáo</button>
                @endif
            </div>
        </div>
        @endauth
    </div>

    {{-- Title --}}
    @if($post->title)
    <h2 style="font-size:1.0625rem; font-weight:700; color:#1A1A1A; margin-bottom:0.375rem; line-height:1.35;">{{ $post->title }}</h2>
    @endif

    {{-- Content --}}
    @if($editing)
    <div style="margin-bottom:0.875rem;">
        <textarea wire:model="editContent" class="input" rows="4" style="font-size:0.875rem;"></textarea>
        <div class="flex gap-2 mt-2">
            <button wire:click="saveEdit" class="btn btn-primary" style="font-size:0.75rem; padding:0.25rem 0.625rem;">Lưu</button>
            <button wire:click="cancelEdit" class="btn btn-ghost" style="font-size:0.75rem; padding:0.25rem 0.625rem;">Hủy</button>
        </div>
    </div>
    @else
    <div style="color:#2E2E2E; font-size:0.875rem; line-height:1.7; margin-bottom:0.875rem; white-space:pre-line; overflow-wrap:break-word;">{!! $this->renderContent($showFull) !!}@if(!$showFull && strlen($post->content) > 500)
        <button wire:click="$set('showFull', true)" style="color:#FF6B6B; font-size:0.8rem; font-weight:500;">Xem thêm</button>@endif</div>
    @endif

    {{-- Post images --}}
    @if($post->images->count() > 0)
    <div class="flex gap-1 mb-3" style="border-radius:0.5rem; overflow:hidden; {{ $post->images->count() === 1 ? '' : 'display:grid; grid-template-columns: repeat(' . min($post->images->count(), 2) . ', 1fr); gap:2px;' }}">
        @foreach($post->images as $img)
        <a href="{{ asset('storage/' . $img->path) }}" target="_blank" style="display:block; {{ $post->images->count() === 1 ? 'max-height:400px;' : 'height:200px;' }} overflow:hidden;">
            <img src="{{ asset('storage/' . $img->path) }}" alt="" style="width:100%; height:100%; object-fit:cover; border-radius:{{ $post->images->count() === 1 ? '0.5rem' : '0' }};">
        </a>
        @endforeach
    </div>
    @endif

    {{-- Actions --}}
    <div class="flex items-center gap-4" style="padding-top:0.75rem; border-top:1px solid #E1E1E1;">
        {{-- Like --}}
        <button wire:click="toggleLike" wire:loading.attr="disabled" class="flex items-center gap-1.5 transition-colors" style="color:{{ $isLiked ? '#EF4444' : '#636E72' }}; font-size:0.8rem;">
            @if($isLiked)
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
            @else
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            @endif
            <span>{{ $likesCount }}</span>
        </button>

        {{-- Comment --}}
        <button wire:click="$toggle('showComments')" class="flex items-center gap-1.5 transition-colors" style="color:#636E72; font-size:0.8rem;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            <span>{{ $post->all_comments_count ?? $post->allComments->count() }}</span>
        </button>

        {{-- Bookmark --}}
        <button wire:click="toggleBookmark" wire:loading.attr="disabled" class="flex items-center gap-1.5 transition-colors" style="color:{{ $isBookmarked ? '#FF6B6B' : '#636E72' }}; font-size:0.8rem;">
            @if($isBookmarked)
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
            @else
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
            @endif
        </button>

        {{-- Nominate CỐT (GD3+) --}}
        @auth
        @if(!$post->is_cot && auth()->user()->level >= 30)
        <button wire:click="nominateCot" class="flex items-center gap-1.5 ml-auto" style="color:#636E72; font-size:0.75rem;" title="Đề cử Tâm Đắc">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            Đề cử Tâm Đắc
        </button>
        @endif
        @endauth
    </div>

    {{-- Comments section --}}
    @if($showComments)
    <div style="margin-top:1rem; padding-top:1rem; border-top:1px solid #E1E1E1;">
        @auth
        <div class="flex gap-2 mb-3">
            <img src="{{ auth()->user()->avatar_url }}" class="avatar w-8 h-8 shrink-0" alt="">
            <div class="flex-1">
                @if($replyToName)
                <div class="flex items-center gap-2 mb-1">
                    <span style="font-size:0.75rem; color:#FF6B6B;">Trả lời {{ $replyToName }}</span>
                    <button wire:click="cancelReply" style="font-size:0.7rem; color:#636E72; cursor:pointer;">✕</button>
                </div>
                @endif
                <textarea wire:model="newComment" class="input" rows="2" placeholder="{{ $replyToName ? 'Trả lời '.$replyToName.'...' : 'Viết bình luận...' }}"
                    x-data x-init="$el.style.height = $el.scrollHeight + 'px'"
                    @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"
                    style="overflow:hidden; resize:none;"></textarea>
                <div class="flex items-center justify-between mt-1">
                    <div class="flex items-center gap-1">
                        <button type="button" title="Đính kèm" style="padding:0.25rem; color:#636E72; cursor:pointer; border-radius:0.25rem;" class="hover:bg-gray-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        </button>
                        <button type="button" title="Link" style="padding:0.25rem; color:#636E72; cursor:pointer; border-radius:0.25rem;" class="hover:bg-gray-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        </button>
                        <button type="button" title="Emoji" style="padding:0.25rem; color:#636E72; cursor:pointer; border-radius:0.25rem;" class="hover:bg-gray-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </button>
                        <button type="button" title="GIF" style="padding:0.25rem; color:#636E72; cursor:pointer; font-size:0.65rem; font-weight:700; border-radius:0.25rem;" class="hover:bg-gray-100">GIF</button>
                    </div>
                    <button wire:click="addComment" wire:loading.attr="disabled" class="btn btn-primary" style="padding:0.3rem 0.875rem; font-size:0.8rem;">
                        <span wire:loading.remove wire:target="addComment">Đăng</span>
                        <span wire:loading wire:target="addComment">Đang gửi...</span>
                    </button>
                </div>
            </div>
        </div>
        @endauth

        @foreach($comments as $comment)
        <div class="flex gap-2 mb-3 group/comment">
            <img src="{{ $comment->user->avatar_url }}" class="avatar w-8 h-8 shrink-0" alt="">
            <div style="flex:1;">
                <div style="background:#FFF9F0; border-radius:0.5rem; padding:0.625rem 0.875rem; position:relative;">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span style="font-weight:600; font-size:0.8rem; color:#1A1A1A;">{{ $comment->user->name }}</span>
                        @if($comment->is_rune_winner)
                        <span style="font-size:0.65rem; background:#FDE8D8; color:#C2410C; border:1px solid #F4B184; padding:0.1rem 0.375rem; border-radius:4px;">~ Rune</span>
                        @endif
                        <span style="font-size:0.7rem; color:#636E72;">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    @if($editingCommentId === $comment->id)
                    <div>
                        <textarea wire:model="editCommentContent" class="input" rows="2" style="font-size:0.8rem;"></textarea>
                        <div class="flex gap-2 mt-1">
                            <button wire:click="saveEditComment" class="btn btn-primary" style="font-size:0.7rem; padding:0.2rem 0.5rem;">Lưu</button>
                            <button wire:click="cancelEditComment" class="btn btn-ghost" style="font-size:0.7rem; padding:0.2rem 0.5rem;">Hủy</button>
                        </div>
                    </div>
                    @else
                    <p style="color:#2E2E2E; font-size:0.8rem; line-height:1.5; overflow-wrap:break-word; word-break:break-word;">{{ $comment->content }}</p>
                    @endif

                    {{-- 3-dot menu: hover on desktop, always on mobile --}}
                    @auth
                    <div x-data="{ open: false }" style="position:absolute; top:0.375rem; right:0.375rem;">
                        <button @click="open = !open" class="comment-menu-btn opacity-0 group-hover/comment:opacity-100 lg:opacity-0 lg:group-hover/comment:opacity-100" style="color:#636E72; padding:0.125rem; cursor:pointer; transition:opacity 0.15s;">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition style="position:absolute; right:0; top:calc(100% + 2px); background:#FFF; border:1px solid #E1E1E1; border-radius:0.375rem; padding:0.2rem; min-width:110px; z-index:50; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                            @if(auth()->id() === $comment->user_id)
                            <button wire:click="startEditComment({{ $comment->id }})" @click="open = false" style="display:block; width:100%; text-align:left; padding:0.3rem 0.625rem; font-size:0.75rem; color:#1A1A1A; border-radius:0.25rem; cursor:pointer;">Sửa</button>
                            @endif
                            @if(auth()->id() === $comment->user_id || auth()->user()->is_admin)
                            <button wire:click="deleteComment({{ $comment->id }})" wire:confirm="Xóa bình luận này?" @click="open = false" style="display:block; width:100%; text-align:left; padding:0.3rem 0.625rem; font-size:0.75rem; color:#991B1B; border-radius:0.25rem; cursor:pointer;">Xóa</button>
                            @endif
                            @if(auth()->id() !== $comment->user_id)
                            <button wire:click="reportComment({{ $comment->id }})" @click="open = false" style="display:block; width:100%; text-align:left; padding:0.3rem 0.625rem; font-size:0.75rem; color:#636E72; border-radius:0.25rem; cursor:pointer;">▲ Báo cáo</button>
                            @endif
                        </div>
                    </div>
                    @endauth
                </div>
                <div x-data="{ showReply: false }" class="mt-1">
                    <div class="flex items-center gap-3">
                        @auth
                        <button wire:click="toggleCommentLike({{ $comment->id }})" style="font-size:0.7rem; color:{{ $comment->likes()->where('user_id', auth()->id())->exists() ? '#EF4444' : '#636E72' }}; cursor:pointer;">
                            ♥ {{ $comment->likes()->count() ?: '' }}
                        </button>
                        <button @click="showReply = !showReply; $nextTick(() => { if(showReply) $refs.reply{{ $comment->id }}?.focus() })" style="font-size:0.7rem; color:#636E72; cursor:pointer;">Trả lời</button>
                        @endauth
                    </div>
                    {{-- Inline reply form --}}
                    @auth
                    <div x-show="showReply" x-transition style="margin-top:0.5rem;">
                        <div class="flex gap-2">
                            <img src="{{ auth()->user()->avatar_url }}" class="avatar w-6 h-6 shrink-0" alt="">
                            <div class="flex-1">
                                <textarea wire:model="newComment" x-ref="reply{{ $comment->id }}" class="input" rows="1" placeholder="Trả lời {{ $comment->user->name }}..."
                                    x-data x-init="$el.style.height = $el.scrollHeight + 'px'"
                                    @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"
                                    @focus="$wire.replyTo({{ $comment->id }}, '{{ addslashes($comment->user->name) }}')"
                                    style="overflow:hidden; resize:none; font-size:0.8rem;"></textarea>
                                <div class="flex gap-1 mt-1">
                                    <button wire:click="addComment" class="btn btn-primary" style="font-size:0.7rem; padding:0.2rem 0.5rem;">Gửi</button>
                                    <button @click="showReply = false; $wire.cancelReply()" class="btn btn-ghost" style="font-size:0.7rem; padding:0.2rem 0.5rem;">Hủy</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endauth
                </div>

                {{-- Replies (nested) --}}
                @if($comment->replies->count())
                <div style="margin-left:1rem; margin-top:0.5rem;">
                    @foreach($comment->replies as $reply)
                    <div class="flex gap-2 mb-2 group/reply">
                        <img src="{{ $reply->user->avatar_url }}" class="avatar w-6 h-6 shrink-0" alt="">
                        <div style="flex:1;">
                            <div style="background:#F0EEE9; border-radius:0.5rem; padding:0.5rem 0.75rem; position:relative;">
                                <div class="flex flex-wrap items-center gap-1 mb-0.5">
                                    <span style="font-weight:600; font-size:0.75rem; color:#1A1A1A;">{{ $reply->user->name }}</span>
                                    <span style="font-size:0.65rem; color:#636E72;">{{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                @if($editingCommentId === $reply->id)
                                <div>
                                    <textarea wire:model="editCommentContent" class="input" rows="2" style="font-size:0.75rem;"></textarea>
                                    <div class="flex gap-2 mt-1">
                                        <button wire:click="saveEditComment" class="btn btn-primary" style="font-size:0.7rem; padding:0.2rem 0.5rem;">Lưu</button>
                                        <button wire:click="cancelEditComment" class="btn btn-ghost" style="font-size:0.7rem; padding:0.2rem 0.5rem;">Hủy</button>
                                    </div>
                                </div>
                                @else
                                <p style="color:#2E2E2E; font-size:0.75rem; line-height:1.4; overflow-wrap:break-word;">{{ $reply->content }}</p>
                                @endif

                                {{-- Reply 3-dot menu --}}
                                @auth
                                <div x-data="{ open: false }" style="position:absolute; top:0.25rem; right:0.375rem;">
                                    <button @click="open = !open" class="comment-menu-btn opacity-0 group-hover/reply:opacity-100 lg:opacity-0 lg:group-hover/reply:opacity-100" style="color:#636E72; padding:0.125rem; cursor:pointer; transition:opacity 0.15s;">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/></svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-transition style="position:absolute; right:0; top:calc(100% + 2px); background:#FFF; border:1px solid #E1E1E1; border-radius:0.375rem; padding:0.2rem; min-width:110px; z-index:50; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                                        @if(auth()->id() === $reply->user_id)
                                        <button wire:click="startEditComment({{ $reply->id }})" @click="open = false" style="display:block; width:100%; text-align:left; padding:0.3rem 0.625rem; font-size:0.75rem; color:#1A1A1A; border-radius:0.25rem; cursor:pointer;">Sửa</button>
                                        @endif
                                        @if(auth()->id() === $reply->user_id || auth()->user()->is_admin)
                                        <button wire:click="deleteComment({{ $reply->id }})" wire:confirm="Xóa bình luận này?" @click="open = false" style="display:block; width:100%; text-align:left; padding:0.3rem 0.625rem; font-size:0.75rem; color:#991B1B; border-radius:0.25rem; cursor:pointer;">Xóa</button>
                                        @endif
                                        @if(auth()->id() !== $reply->user_id)
                                        <button wire:click="reportComment({{ $reply->id }})" @click="open = false" style="display:block; width:100%; text-align:left; padding:0.3rem 0.625rem; font-size:0.75rem; color:#636E72; border-radius:0.25rem; cursor:pointer;">▲ Báo cáo</button>
                                        @endif
                                    </div>
                                </div>
                                @endauth
                            </div>
                            <div class="flex items-center gap-3 mt-0.5">
                                @auth
                                @if(auth()->id() !== $reply->user_id)
                                <button wire:click="toggleCommentLike({{ $reply->id }})" style="font-size:0.65rem; color:{{ $reply->likes()->where('user_id', auth()->id())->exists() ? '#EF4444' : '#636E72' }}; cursor:pointer;">
                                    ♥ {{ $reply->likes()->count() ?: '' }}
                                </button>
                                @elseif($reply->likes()->count() > 0)
                                <span style="font-size:0.65rem; color:#636E72;">♥ {{ $reply->likes()->count() }}</span>
                                @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
