<div>
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 style="font-size:1.25rem; font-weight:800; color:#1A1A1A;">▦ Khóa học</h1>
            <p style="font-size:0.8rem; color:#636E72;">Khóa học cho bé yêu</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3 items-center mb-4">
        <div class="flex gap-1">
            @foreach(['hoc_ve'=>'🎨 Học Vẽ','tieng_anh'=>'📚 Tiếng Anh','phat_trien'=>'🌱 Phát Triển'] as $key => $label)
            <button wire:click="setPillar('{{ $key }}')" class="badge" style="cursor:pointer; padding:0.25rem 0.625rem; font-size:0.75rem; {{ $pillar === $key ? 'background:#E8F5E9; color:#E85555;' : 'background:#EEECE9; color:#636E72;' }}">{{ $label }}</button>
            @endforeach
        </div>
        <div class="flex gap-1 ml-auto">
            @foreach([''=>'Tất cả','basic'=>'Cơ bản','advanced'=>'Nâng cao','expert'=>'Nâng cao+'] as $d => $l)
            <button wire:click="$set('difficulty','{{ $d }}')" class="badge" style="cursor:pointer; padding:0.25rem 0.625rem; font-size:0.7rem; {{ $difficulty === $d ? 'background:#E8F5E9; color:#E85555;' : 'background:#EEECE9; color:#636E72;' }}">{{ $l }}</button>
            @endforeach
        </div>
    </div>

    {{-- Course grid --}}
    <div class="grid gap-4" style="grid-template-columns: repeat(2, 1fr);">
        @forelse($courses as $course)
        <div class="card">
            @if($course->thumbnail)
            <div style="height:140px; background:#EEECE9; border-radius:0.5rem; margin-bottom:0.75rem; overflow:hidden;">
                <img src="{{ str_starts_with($course->thumbnail, '/') ? $course->thumbnail : asset('storage/' . $course->thumbnail) }}" alt="" style="width:100%; height:100%; object-fit:cover;">
            </div>
            @else
            <div style="height:140px; background:#F0EDE8; border-radius:0.5rem; margin-bottom:0.75rem; display:flex; align-items:center; justify-content:center;">
                <span style="font-size:2.5rem;">▦</span>
            </div>
            @endif

            <div class="flex items-center gap-2 mb-2">
                <span class="badge badge-pillar-{{ $course->pillar }}" style="font-size:0.65rem;">{{ ucfirst($course->pillar) }}</span>
                <span class="badge" style="font-size:0.65rem; {{ match($course->difficulty) { 'basic' => 'background:#D1FAE5; color:#065F46;', 'advanced' => 'background:#E8F5E9; color:#E85555;', 'expert' => 'background:#FEE2E2; color:#991B1B;', default => 'background:#EEECE9; color:#636E72;' } }}">{{ ucfirst($course->difficulty) }}</span>
                @if($course->min_level > 1)
                <span class="level-badge">Lv.{{ $course->min_level }}+</span>
                @endif
            </div>

            <h3 style="font-size:0.9rem; font-weight:700; color:#1A1A1A; margin-bottom:0.375rem; line-height:1.3;">{{ $course->title }}</h3>

            @if($course->description)
            <p style="font-size:0.8rem; color:#636E72; margin-bottom:0.75rem; line-height:1.4;">{{ Str::limit($course->description, 100) }}</p>
            @endif

            <div class="flex items-center gap-3 mb-3">
                <div>
                    <p style="font-size:0.75rem; font-weight:700; color:#1A1A1A;">{{ $course->modules_count ?? $course->modules()->count() }}</p>
                    <p style="font-size:0.65rem; color:#636E72;">Module</p>
                </div>
                <div>
                    <p style="font-size:0.75rem; font-weight:700; color:#FF6B6B;">+{{ $course->xp_reward }} XP</p>
                    <p style="font-size:0.65rem; color:#636E72;">Phần thưởng</p>
                </div>
                <div>
                    <p style="font-size:0.75rem; font-weight:700; color:#1A1A1A;">{{ $course->enrollments_count }}</p>
                    <p style="font-size:0.65rem; color:#636E72;">Học viên</p>
                </div>
            </div>

            <div class="flex items-center justify-between">
                @if($course->price > 0)
                <p style="font-size:0.9rem; font-weight:700; color:#FF6B6B;">{{ number_format($course->price, 0, ',', '.') }}đ</p>
                @else
                <p style="font-size:0.8rem; font-weight:600; color:#059669;">Miễn phí</p>
                @endif
                <a href="{{ route('academy.show', $course->id) }}" class="btn btn-primary" style="font-size:0.8rem; padding:0.4rem 0.75rem;">Xem khóa học</a>
            </div>
        </div>
        @empty
        <div class="card text-center py-12" style="grid-column:1/-1;">
            <p style="font-size:2rem; margin-bottom:0.5rem;">▦</p>
            <p style="color:#636E72;">Chưa có khóa học nào{{ $pillar ? ' trong danh mục này' : '' }}</p>
        </div>
        @endforelse
    </div>
</div>
