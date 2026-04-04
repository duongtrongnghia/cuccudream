<div>
    <div class="card mb-4" style="background:#F0EDE8; border-color:#FF6B6B;">
        <div class="flex items-center gap-3">
            <span style="font-size:2.5rem;">★</span>
            <div>
                <h1 style="font-size:1.25rem; font-weight:800; color:#E85555;">Tâm Đắc</h1>
                <p style="font-size:0.8rem; color:#636E72;">Nội dung chọn lọc chất lượng cao · Thư viện vàng của cộng đồng</p>
            </div>
        </div>
    </div>

    <div class="card mb-4" style="padding:0.875rem;">
        <div class="flex flex-wrap gap-3 items-center">
            <input wire:model.live.debounce.300="search" type="search" class="input" style="max-width:220px;" placeholder="Tìm trong Tâm Đắc...">
            <div class="flex gap-2 ml-auto">
                <button wire:click="setSort('latest')" class="btn btn-ghost" style="font-size:0.75rem; padding:0.25rem 0.625rem; {{ $sort === 'latest' ? 'color:#1A1A1A; font-weight:600;' : '' }}">Mới nhất</button>
                <button wire:click="setSort('popular')" class="btn btn-ghost" style="font-size:0.75rem; padding:0.25rem 0.625rem; {{ $sort === 'popular' ? 'color:#1A1A1A; font-weight:600;' : '' }}">Phổ biến</button>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-3">
        @forelse($posts as $post)
        <livewire:post-card :post="$post" :key="'cot-'.$post->id" />
        @empty
        <div class="card text-center py-12">
            <p style="font-size:2rem; margin-bottom:0.5rem;">★</p>
            <p style="color:#636E72;">Chưa có bài Tâm Đắc nào{{ $search ? ' khớp với tìm kiếm' : '' }}</p>
        </div>
        @endforelse
    </div>
    <div class="mt-6">{{ $posts->links() }}</div>
</div>
