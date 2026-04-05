<div>
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 style="font-size:1.25rem; font-weight:800; color:#1A1A1A;">🎨 Khóa học</h1>
            <p style="font-size:0.85rem; color:#2D2926;">Khóa học vẽ cùng Cúc Cu's Dream Factory</p>
        </div>
    </div>

    {{-- Course grid --}}
    <div class="grid gap-4" style="grid-template-columns: repeat(2, 1fr);">
        @forelse($courses as $course)
        <div class="card" style="padding:0; overflow:hidden;">
            @if($course->thumbnail)
            <div style="background:#EEECE9; overflow:hidden;">
                <img src="{{ str_starts_with($course->thumbnail, '/') ? $course->thumbnail : asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" style="width:100%; display:block;">
            </div>
            @else
            <div style="height:180px; background:#F0EDE8; display:flex; align-items:center; justify-content:center;">
                <span style="font-size:2.5rem;">🎨</span>
            </div>
            @endif

            <div style="padding:1.25rem;">
                <h3 style="font-size:1rem; font-weight:800; color:#2D2926; margin-bottom:0.5rem; line-height:1.3;">{{ $course->title }}</h3>

                @if($course->description)
                <p style="font-size:0.85rem; color:#5A524C; margin-bottom:1rem; line-height:1.5;">{{ Str::limit($course->description, 120) }}</p>
                @endif

                <div class="flex items-center gap-4 mb-3">
                    <div>
                        <p style="font-size:0.8rem; font-weight:800; color:#2D2926;">{{ $course->modules_count ?? $course->modules()->count() }}</p>
                        <p style="font-size:0.7rem; color:#6B6158; font-weight:600;">Module</p>
                    </div>
                    <div>
                        <p style="font-size:0.8rem; font-weight:800; color:#D4896E;">+{{ $course->xp_reward }} XP</p>
                        <p style="font-size:0.7rem; color:#6B6158; font-weight:600;">Phần thưởng</p>
                    </div>
                    <div>
                        <p style="font-size:0.8rem; font-weight:800; color:#2D2926;">{{ $course->enrollments_count }}</p>
                        <p style="font-size:0.7rem; color:#6B6158; font-weight:600;">Học viên</p>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    @if($course->price > 0)
                    <p style="font-size:1rem; font-weight:800; color:#D4896E;">{{ number_format($course->price, 0, ',', '.') }}đ</p>
                    @else
                    <p style="font-size:0.9rem; font-weight:700; color:#7B8B6F;">Miễn phí</p>
                    @endif
                    <a href="{{ route('academy.show', $course->id) }}" class="btn btn-gold" style="font-size:0.85rem; padding:0.5rem 1rem; border-radius:0.5rem;">Xem khóa học</a>
                </div>
            </div>
        </div>
        @empty
        <div class="card text-center py-12" style="grid-column:1/-1;">
            <p style="font-size:2rem; margin-bottom:0.5rem;">🎨</p>
            <p style="color:#6B6158;">Chưa có khóa học nào</p>
        </div>
        @endforelse
    </div>
</div>
