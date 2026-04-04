<div>
    <h1 style="font-size:1.25rem; font-weight:800; color:#1A1A1A; margin-bottom:1.25rem;">■ Admin Dashboard</h1>

    {{-- Stats --}}
    <div class="grid gap-4 mb-6" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));">
        <div class="card text-center">
            <p style="font-size:1.5rem; font-weight:800; color:#FF6B6B;">{{ $totalUsers }}</p>
            <p style="font-size:0.8rem; color:#636E72;">Thành viên</p>
        </div>
        <div class="card text-center">
            <p style="font-size:1.5rem; font-weight:800; color:#1A1A1A;">{{ $totalPosts }}</p>
            <p style="font-size:0.8rem; color:#636E72;">Bài viết</p>
        </div>
        <div class="card text-center">
            <p style="font-size:1.5rem; font-weight:800; color:{{ $pendingReports > 0 ? '#DC2626' : '#059669' }};">{{ $pendingReports }}</p>
            <p style="font-size:0.8rem; color:#636E72;">Báo cáo chờ</p>
        </div>
        <div class="card text-center">
            <p style="font-size:1.5rem; font-weight:800; color:{{ $pendingCot > 0 ? '#FF6B6B' : '#636E72' }};">{{ $pendingCot }}</p>
            <p style="font-size:0.8rem; color:#636E72;">Tâm Đắc chờ duyệt</p>
        </div>
    </div>

    {{-- Navigation --}}
    <div class="grid gap-3" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
        <a href="{{ route('admin.users') }}" class="card" style="text-decoration:none; transition:border-color 0.15s;">
            <div class="flex items-center gap-3">
                <span style="font-size:1.5rem;">■</span>
                <div>
                    <p style="font-size:0.9rem; font-weight:700; color:#1A1A1A;">Quản lý người dùng</p>
                    <p style="font-size:0.75rem; color:#636E72;">Ban, toggle admin/mod, xem thông tin</p>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.topics') }}" class="card" style="text-decoration:none;">
            <div class="flex items-center gap-3">
                <span style="font-size:1.5rem;">🏷️</span>
                <div>
                    <p style="font-size:0.9rem; font-weight:700; color:#1A1A1A;">Quản lý Topics</p>
                    <p style="font-size:0.75rem; color:#636E72;">Thêm/sửa/xóa loại bài viết</p>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.courses') }}" class="card" style="text-decoration:none;">
            <div class="flex items-center gap-3">
                <span style="font-size:1.5rem;">▦</span>
                <div>
                    <p style="font-size:0.9rem; font-weight:700; color:#1A1A1A;">Quản lý khóa học</p>
                    <p style="font-size:0.75rem; color:#636E72;">Tạo, xây dựng, publish khóa học</p>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.products') }}" class="card" style="text-decoration:none;">
            <div class="flex items-center gap-3">
                <span style="font-size:1.5rem;">▣</span>
                <div>
                    <p style="font-size:0.9rem; font-weight:700; color:#1A1A1A;">Quản lý sản phẩm</p>
                    <p style="font-size:0.75rem; color:#636E72;">Tạo, chỉnh sửa sản phẩm Marketplace</p>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.cot') }}" class="card" style="text-decoration:none;">
            <div class="flex items-center gap-3">
                <span style="font-size:1.5rem;">★</span>
                <div>
                    <p style="font-size:0.9rem; font-weight:700; color:#1A1A1A;">Duyệt Tâm Đắc</p>
                    <p style="font-size:0.75rem; color:#636E72;">Approve/reject bài viết Tâm Đắc</p>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.reports') }}" class="card" style="text-decoration:none;">
            <div class="flex items-center gap-3">
                <span style="font-size:1.5rem;">▲</span>
                <div>
                    <p style="font-size:0.9rem; font-weight:700; color:#1A1A1A;">Báo cáo vi phạm</p>
                    <p style="font-size:0.75rem; color:#636E72;">Xem và xử lý báo cáo từ thành viên</p>
                </div>
            </div>
        </a>
    </div>
</div>
