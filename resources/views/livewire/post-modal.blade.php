<div>
@if($show && $post)
<div style="position:fixed; inset:0; z-index:200; display:flex; align-items:center; justify-content:center;"
     x-data
     x-init="document.body.style.overflow='hidden'"
     x-on:keydown.escape.window="$wire.close(); document.body.style.overflow=''"
     wire:key="post-modal-{{ $post->id }}"

    {{-- Backdrop --}}
    <div wire:click="close" style="position:absolute; inset:0; background:rgba(0,0,0,0.5);"></div>

    {{-- Modal --}}
    <div style="position:relative; background:#FFF; border-radius:0.75rem; width:95%; max-width:680px; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,0.2); z-index:1;">

        {{-- Close button --}}
        <button wire:click="close" style="position:absolute; top:0.75rem; right:0.75rem; background:#FFF9F0; border-radius:50%; width:32px; height:32px; display:flex; align-items:center; justify-content:center; cursor:pointer; z-index:2; border:1px solid #E1E1E1;">
            <svg class="w-4 h-4" fill="none" stroke="#1A1A1A" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div style="padding:1.5rem;">
            {{-- Author --}}
            <div class="flex items-start gap-3 mb-4">
                <a href="{{ route('profile', $post->user->username ?? $post->user->id) }}">
                    <img src="{{ $post->user->avatar_url }}" style="width:44px; height:44px; border-radius:50%; object-fit:cover;" alt="">
                </a>
                <div>
                    <div class="flex flex-wrap items-center gap-1.5">
                        <a href="{{ route('profile', $post->user->username ?? $post->user->id) }}" style="font-weight:600; color:#1A1A1A; font-size:0.9rem;">{{ $post->user->name }}</a>
                        <span class="level-badge">Lv.{{ $post->user->level }}</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-1.5 mt-0.5">
                        @if($post->topic)
                        <span style="font-size:0.7rem; color:#636E72;">{{ $post->topic->emoji }} {{ $post->topic->name }}</span>
                        @endif
                        <span style="font-size:0.7rem; color:#636E72;">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            {{-- Title --}}
            @if($post->title)
            <h2 style="font-size:1.2rem; font-weight:700; color:#1A1A1A; margin-bottom:0.5rem; line-height:1.35;">{{ $post->title }}</h2>
            @endif

            {{-- Content (full, auto-linked) --}}
            <div style="color:#2E2E2E; font-size:0.9rem; line-height:1.75; margin-bottom:1.25rem; white-space:pre-line; overflow-wrap:break-word;">
                @php
                    $escaped = e($post->content);
                    $linked = preg_replace('#(https?://[^\s<]+)#i', '<a href="$1" target="_blank" rel="noopener" style="color:#FF6B6B; text-decoration:underline; word-break:break-all;">$1</a>', $escaped);
                @endphp
                {!! $linked !!}
            </div>

            {{-- Stats --}}
            <div class="flex items-center gap-4" style="padding:0.75rem 0; border-top:1px solid #E1E1E1; border-bottom:1px solid #E1E1E1; margin-bottom:1rem;">
                <span style="font-size:0.8rem; color:#636E72;">♥ {{ $post->likes->count() }}</span>
                <span style="font-size:0.8rem; color:#636E72;">💬 {{ $post->allComments->count() }}</span>
            </div>

            {{-- Comments --}}
            <div>
                @foreach($post->allComments->whereNull('parent_id') as $comment)
                <div class="flex gap-2 mb-3">
                    <img src="{{ $comment->user->avatar_url }}" style="width:32px; height:32px; border-radius:50%; object-fit:cover; flex-shrink:0;" alt="">
                    <div style="flex:1;">
                        <div style="background:#FFF9F0; border-radius:0.5rem; padding:0.5rem 0.75rem;">
                            <div class="flex flex-wrap items-center gap-1.5 mb-0.5">
                                <span style="font-weight:600; font-size:0.8rem; color:#1A1A1A;">{{ $comment->user->name }}</span>
                                <span style="font-size:0.65rem; color:#636E72;">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p style="color:#2E2E2E; font-size:0.8rem; line-height:1.5; overflow-wrap:break-word;">{{ $comment->content }}</p>
                        </div>

                        {{-- Replies --}}
                        @foreach($comment->replies as $reply)
                        <div class="flex gap-2 mt-2 ml-4">
                            <img src="{{ $reply->user->avatar_url }}" style="width:26px; height:26px; border-radius:50%; object-fit:cover; flex-shrink:0;" alt="">
                            <div style="flex:1; background:#F0EEE9; border-radius:0.5rem; padding:0.375rem 0.625rem;">
                                <div class="flex flex-wrap items-center gap-1 mb-0.5">
                                    <span style="font-weight:600; font-size:0.75rem; color:#1A1A1A;">{{ $reply->user->name }}</span>
                                    <span style="font-size:0.6rem; color:#636E72;">{{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                <p style="color:#2E2E2E; font-size:0.75rem; line-height:1.4; overflow-wrap:break-word;">{{ $reply->content }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach

                @if($post->allComments->count() === 0)
                <p style="text-align:center; color:#636E72; font-size:0.8rem; padding:1rem 0;">Chưa có bình luận nào</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
</div>
