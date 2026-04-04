<div>
    <div class="flex items-center justify-between mb-4">
        <h1 style="font-size:1.25rem; font-weight:800; color:#1A1A1A;">▣ Quản lý sản phẩm</h1>
        <button wire:click="create" class="btn btn-primary" style="font-size:0.8rem;">+ Thêm sản phẩm</button>
    </div>

    {{-- Create/Edit form --}}
    @if($showForm)
    <div class="card mb-4" style="border-left:3px solid #FF6B6B;">
        <h2 style="font-size:0.9rem; font-weight:700; color:#E85555; margin-bottom:0.75rem;">{{ $editingId ? 'Sửa sản phẩm' : 'Thêm sản phẩm mới' }}</h2>

        <div class="flex flex-col gap-3">
            <div>
                <label style="font-size:0.75rem; font-weight:600; color:#636E72;">Tên sản phẩm *</label>
                <input wire:model="title" type="text" class="input" placeholder="Ví dụ: Template Funnel 10 bước">
                @error('title') <p style="color:#DC2626; font-size:0.7rem;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label style="font-size:0.75rem; font-weight:600; color:#636E72;">Mô tả</label>
                <textarea wire:model="description" class="input" rows="3" placeholder="Mô tả ngắn về sản phẩm..."
                    x-data x-init="$el.style.height = $el.scrollHeight + 'px'"
                    @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"
                    style="overflow:hidden; resize:none;"></textarea>
            </div>

            <div class="flex flex-wrap gap-3">
                <div>
                    <label style="font-size:0.75rem; font-weight:600; color:#636E72;">Giá (VND)</label>
                    <input wire:model="price" type="number" class="input" min="0" step="1000" style="width:150px;">
                    <p style="font-size:0.65rem; color:#636E72;">0 = miễn phí</p>
                </div>
                <div>
                    <label style="font-size:0.75rem; font-weight:600; color:#636E72;">Danh mục</label>
                    <select wire:model="pillar" class="input" style="width:150px;">
                        <option value="">— Không —</option>
                        <option value="hoc_ve">🎨 Học Vẽ</option>
                        <option value="tieng_anh">📚 Tiếng Anh</option>
                        <option value="phat_trien">🌱 Phát Triển</option>
                    </select>
                </div>
                <div>
                    <label style="font-size:0.75rem; font-weight:600; color:#636E72;">Loại giao hàng</label>
                    <select wire:model="deliveryType" class="input" style="width:150px;">
                        <option value="file">File download</option>
                        <option value="link">Link truy cập</option>
                        <option value="both">Cả hai</option>
                    </select>
                </div>
            </div>

            <div>
                <label style="font-size:0.75rem; font-weight:600; color:#636E72;">File upload</label>
                <input wire:model="uploadFile" type="file" class="input" style="font-size:0.8rem;">
                @if($editingId)
                @php $existing = \App\Models\DigitalProduct::find($editingId); @endphp
                @if($existing && $existing->file_name)
                <p style="font-size:0.7rem; color:#636E72; margin-top:0.25rem;">File hiện tại: {{ $existing->file_name }}</p>
                @endif
                @endif
            </div>

            <div>
                <label style="font-size:0.75rem; font-weight:600; color:#636E72;">Link truy cập</label>
                <input wire:model="accessUrl" type="url" class="input" placeholder="https://drive.google.com/...">
            </div>

            <div class="flex items-center gap-2">
                <input wire:model="isPublished" type="checkbox" id="published">
                <label for="published" style="font-size:0.8rem; color:#1A1A1A;">Hiển thị trên Marketplace</label>
            </div>

            <div class="flex gap-2">
                <button wire:click="save" class="btn btn-primary" style="font-size:0.8rem;">{{ $editingId ? 'Cập nhật' : 'Tạo sản phẩm' }}</button>
                <button wire:click="cancel" class="btn btn-ghost" style="font-size:0.8rem;">Hủy</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Product list --}}
    <div class="card">
        <table style="width:100%; font-size:0.8rem;">
            <thead>
                <tr style="border-bottom:1px solid #E1E1E1; text-align:left;">
                    <th style="padding:0.5rem; color:#636E72; font-weight:600;">Sản phẩm</th>
                    <th style="padding:0.5rem; color:#636E72; font-weight:600;">Giá</th>
                    <th style="padding:0.5rem; color:#636E72; font-weight:600;">Loại</th>
                    <th style="padding:0.5rem; color:#636E72; font-weight:600;">Đã bán</th>
                    <th style="padding:0.5rem; color:#636E72; font-weight:600;">Trạng thái</th>
                    <th style="padding:0.5rem; color:#636E72; font-weight:600;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr style="border-bottom:1px solid #E1E1E1;">
                    <td style="padding:0.5rem;">
                        <p style="font-weight:600; color:#1A1A1A;">{{ $product->title }}</p>
                        @if($product->pillar)
                        <span class="badge badge-pillar-{{ $product->pillar }}" style="font-size:0.6rem;">{{ ucfirst($product->pillar) }}</span>
                        @endif
                    </td>
                    <td style="padding:0.5rem; font-weight:600; color:#FF6B6B;">
                        {{ $product->price > 0 ? number_format($product->price, 0, ',', '.') . 'đ' : 'Miễn phí' }}
                    </td>
                    <td style="padding:0.5rem; color:#636E72;">
                        {{ match($product->delivery_type) { 'file' => '▫ File', 'link' => '◎ Link', 'both' => '▫+◎', default => '—' } }}
                    </td>
                    <td style="padding:0.5rem; font-weight:600;">{{ $product->purchases_count }}</td>
                    <td style="padding:0.5rem;">
                        @if($product->is_published)
                        <span class="badge" style="background:#D1FAE5; color:#065F46; font-size:0.6rem;">Live</span>
                        @else
                        <span class="badge" style="background:#EEECE9; color:#636E72; font-size:0.6rem;">Ẩn</span>
                        @endif
                    </td>
                    <td style="padding:0.5rem;">
                        <div class="flex gap-1">
                            <button wire:click="edit({{ $product->id }})" class="btn btn-ghost" style="font-size:0.65rem; padding:0.2rem 0.4rem;">Sửa</button>
                            <button wire:click="togglePublish({{ $product->id }})" class="btn btn-ghost" style="font-size:0.65rem; padding:0.2rem 0.4rem;">{{ $product->is_published ? 'Ẩn' : 'Hiện' }}</button>
                            <button wire:click="deleteProduct({{ $product->id }})" wire:confirm="Xóa sản phẩm này?" class="btn btn-danger" style="font-size:0.65rem; padding:0.2rem 0.4rem;">Xóa</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:2rem; text-align:center; color:#636E72;">Chưa có sản phẩm nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
