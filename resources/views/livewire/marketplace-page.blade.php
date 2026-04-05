<div>
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 style="font-size:1.25rem; font-weight:800; color:#1A1A1A;">▤ Marketplace</h1>
            <p style="font-size:0.8rem; color:#636E72;">Tài liệu và công cụ học tập cho bé yêu</p>
        </div>
    </div>

    {{-- Category filter --}}
    <div class="flex flex-wrap gap-1 mb-4">
        @foreach(['hoc_ve'=>'🎨 Học Vẽ','tieng_anh'=>'📚 Tiếng Anh','phat_trien'=>'🌱 Phát Triển'] as $key => $label)
        <button wire:click="setPillar('{{ $key }}')" class="badge" style="cursor:pointer; padding:0.25rem 0.625rem; font-size:0.75rem; {{ $pillar === $key ? 'background:#E8F5E9; color:#E85555;' : 'background:#EEECE9; color:#636E72;' }}">{{ $label }}</button>
        @endforeach
    </div>

    {{-- Product grid --}}
    <div class="grid gap-4" style="grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));">
        @forelse($products as $product)
        <div class="card" style="display:flex; flex-direction:column;">
            {{-- Thumbnail --}}
            @if($product->thumbnail)
            <div style="height:140px; background:#EEECE9; border-radius:0.5rem; margin-bottom:0.75rem; overflow:hidden;">
                <img src="{{ str_starts_with($product->thumbnail, '/') ? $product->thumbnail : asset('storage/' . $product->thumbnail) }}" alt="" style="width:100%; height:100%; object-fit:cover;">
            </div>
            @else
            <div style="height:100px; background:#F0EDE8; border-radius:0.5rem; margin-bottom:0.75rem; display:flex; align-items:center; justify-content:center;">
                <span style="font-size:2rem;">▣</span>
            </div>
            @endif

            {{-- Info --}}
            @if($product->pillar)
            <span class="badge badge-pillar-{{ $product->pillar }}" style="font-size:0.65rem; width:fit-content; margin-bottom:0.375rem;">{{ ucfirst($product->pillar) }}</span>
            @endif

            <h3 style="font-size:0.9rem; font-weight:700; color:#1A1A1A; margin-bottom:0.25rem; line-height:1.3;">{{ $product->title }}</h3>

            @if($product->description)
            <p style="font-size:0.8rem; color:#636E72; margin-bottom:0.75rem; line-height:1.4; flex:1;">{{ Str::limit($product->description, 120) }}</p>
            @else
            <div style="flex:1;"></div>
            @endif

            {{-- Delivery type badge --}}
            <div class="flex items-center gap-2 mb-3">
                @if(in_array($product->delivery_type, ['file', 'both']))
                <span style="font-size:0.65rem; color:#636E72; background:#FFF9F0; padding:0.15rem 0.375rem; border-radius:4px;">▫ File</span>
                @endif
                @if(in_array($product->delivery_type, ['link', 'both']))
                <span style="font-size:0.65rem; color:#636E72; background:#FFF9F0; padding:0.15rem 0.375rem; border-radius:4px;">◎ Link</span>
                @endif
            </div>

            {{-- Purchase area --}}
            @if(in_array($product->id, $purchasedIds))
                {{-- Already purchased — show access --}}
                <div style="background:#D1FAE5; border:1px solid #A7F3D0; border-radius:0.5rem; padding:0.5rem;">
                    <p style="font-size:0.75rem; font-weight:600; color:#065F46; margin-bottom:0.375rem;">✓ Đã mua</p>
                    <div class="flex gap-2">
                        @if($product->file_path)
                        <a href="{{ asset('storage/' . $product->file_path) }}" download="{{ $product->file_name }}" class="btn btn-primary" style="font-size:0.7rem; padding:0.25rem 0.5rem;">📥 Tải file</a>
                        @endif
                        @if($product->access_url)
                        <a href="{{ $product->access_url }}" target="_blank" rel="noopener" class="btn btn-ghost" style="font-size:0.7rem; padding:0.25rem 0.5rem;">◎ Mở link</a>
                        @endif
                    </div>
                </div>
            @elseif(in_array($product->id, $pendingIds))
                {{-- Pending payment --}}
                <div style="background:#FFFBEB; border:1px solid #FDE68A; border-radius:0.5rem; padding:0.5rem;">
                    <p style="font-size:0.75rem; font-weight:600; color:#92400E;">⏳ Chờ thanh toán</p>
                    <p style="font-size:0.7rem; color:#636E72; margin-top:0.25rem;">
                        Nội dung CK: <strong style="color:#FF6B6B;">PROD{{ $product->id }}U{{ auth()->id() }}</strong>
                    </p>
                </div>
            @else
                {{-- Not purchased --}}
                <div class="flex items-center justify-between">
                    @if($product->price > 0)
                    <p style="font-size:1rem; font-weight:800; color:#FF6B6B;">{{ number_format($product->price, 0, ',', '.') }}đ</p>
                    @else
                    <p style="font-size:0.85rem; font-weight:600; color:#059669;">Miễn phí</p>
                    @endif
                    <button wire:click="purchase({{ $product->id }})" class="btn btn-primary" style="font-size:0.8rem; padding:0.4rem 0.75rem;">
                        {{ $product->price > 0 ? 'Mua ngay' : 'Nhận miễn phí' }}
                    </button>
                </div>
            @endif
        </div>
        @empty
        <div class="card text-center py-12" style="grid-column:1/-1;">
            <p style="font-size:2rem; margin-bottom:0.5rem;">▤</p>
            <p style="color:#636E72;">Chưa có sản phẩm nào{{ $pillar ? ' trong danh mục này' : '' }}</p>
        </div>
        @endforelse
    </div>
</div>
