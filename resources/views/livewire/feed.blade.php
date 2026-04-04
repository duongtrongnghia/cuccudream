<div>
    {{-- Rune Banner --}}
    @php
        $activRune = \App\Models\Post::where('rune_active', true)
            ->where('rune_expires_at', '>', now())
            ->whereNull('rune_first_comment_user_id')
            ->first();
    @endphp
    @if($activRune)
    <div class="rune-banner flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <span style="font-size:1.25rem;">~</span>
            <div>
                <p style="font-weight:700; color:#C2410C; font-size:0.875rem;">Phù văn đang kích hoạt!</p>
                <p style="color:#636E72; font-size:0.75rem;">Comment đầu tiên vào bài của {{ $activRune->user->name }} → nhận <strong style="color:#FF6B6B;">2x EXP</strong></p>
            </div>
        </div>
        <a href="#post-{{ $activRune->id }}" style="font-size:0.75rem; color:#C2410C; font-weight:600; white-space:nowrap;">
            Đến bài →
        </a>
    </div>
    @endif

    {{-- Compose Box (Lv10+ only) --}}
    @auth
    @if(auth()->user()->level >= 10)
    <livewire:compose-post />
    @else
    <div class="card mb-4" style="text-align:center; padding:1rem;">
        <p style="font-size:0.85rem; color:#636E72;">▪ Đạt <strong style="color:#FF6B6B;">Level 10</strong> để mở khóa đăng bài. Hãy tương tác bằng comment!</p>
        <div class="xp-bar mt-2" style="height:6px; max-width:200px; margin:0.5rem auto 0;">
            <div class="xp-bar-fill" style="width:{{ min(100, auth()->user()->level * 10) }}%;"></div>
        </div>
        <p style="font-size:0.7rem; color:#636E72; margin-top:0.375rem;">Level {{ auth()->user()->level }}/10</p>
    </div>
    @endif
    @endauth

    {{-- Tab bar --}}
    <div class="tab-nav mt-4">
        <button wire:click="setTab('latest')" class="tab-item {{ $tab === 'latest' ? 'active' : '' }}" style="white-space:nowrap;">Mới nhất</button>
        <button wire:click="setTab('cot')" class="tab-item {{ $tab === 'cot' ? 'active' : '' }}" style="white-space:nowrap;">★ Tâm Đắc</button>
        <button wire:click="setTab('popular')" class="tab-item {{ $tab === 'popular' ? 'active' : '' }}" style="white-space:nowrap;"> Phổ biến</button>
        <button wire:click="setTab('signal')" class="tab-item {{ $tab === 'signal' ? 'active' : '' }}" style="white-space:nowrap;">⚡ Thành Quả</button>
    </div>

    {{-- Pinned posts --}}
    @foreach($pinnedPosts as $post)
    <div style="position:relative; margin-bottom:0.75rem;">
        <div style="position:absolute; top:0.75rem; right:0.75rem; display:flex; align-items:center; gap:0.25rem; font-size:0.65rem; color:#636E72;">
            <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
            Ghim
        </div>
        <livewire:post-card :post="$post" :key="'pin-'.$post->id" />
    </div>
    @endforeach

    {{-- Posts --}}
    <div class="flex flex-col gap-3">
        @forelse($posts as $post)
            <livewire:post-card :post="$post" :key="'p-'.$post->id" />
        @empty
            <div class="card text-center py-12">
                <p style="font-size:2rem; margin-bottom:0.5rem;">📭</p>
                <p style="color:#636E72;">Chưa có bài viết nào</p>
                @if(auth()->check())
                <p style="color:#636E72; font-size:0.875rem; margin-top:0.25rem;">Hãy là người đầu tiên đăng bài!</p>
                @endif
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $posts->links() }}
    </div>
</div>
