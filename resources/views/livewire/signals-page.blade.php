<div>
    <div class="card mb-4" style="background:#F0EDE8; border-color:#078A48;">
        <div class="flex items-center gap-3">
            <span style="font-size:2.5rem;">⚡</span>
            <div>
                <h1 style="font-size:1.25rem; font-weight:800; color:#065F46;">Thành Quả</h1>
                <p style="font-size:0.8rem; color:#636E72;">Những thành quả của bé yêu · <strong style="color:#078A48;">{{ $todayCount }} thành quả hôm nay</strong></p>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-3">
        @forelse($posts as $post)
        <livewire:post-card :post="$post" :key="'sig-'.$post->id" />
        @empty
        <div class="card text-center py-12">
            <p style="font-size:2rem; margin-bottom:0.5rem;">⚡</p>
            <p style="color:#636E72;">Chưa có thành quả nào</p>
        </div>
        @endforelse
    </div>
    <div class="mt-6">{{ $posts->links() }}</div>
</div>
