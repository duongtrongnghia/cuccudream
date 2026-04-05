<div>
    {{-- Top bar: back + course title + progress --}}
    <div class="flex items-center justify-between mb-3">
        <a href="{{ route('academy') }}" style="font-size:0.85rem; color:#4A4A4A; font-weight:600;">← Khóa học</a>
        @if($enrolled && $totalLessons > 0)
        <span style="font-size:0.8rem; color:#4A4A4A; font-weight:600;">{{ $completedLessons }}/{{ $totalLessons }} bài</span>
        @endif
    </div>

    @if(!$enrolled && !$pendingPayment)
    {{-- NOT ENROLLED: show course info + buy --}}
    <div class="card" style="max-width:700px;">
        @if($course->thumbnail)
        <img src="{{ str_starts_with($course->thumbnail, '/') ? $course->thumbnail : asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" style="width:100%; border-radius:0.5rem; margin-bottom:1rem;">
        @endif
        <h1 style="font-size:1.3rem; font-weight:800; color:#1A1A1A; margin-bottom:0.5rem;">{{ $course->title }}</h1>
        @if($course->description)
        <p style="font-size:0.95rem; color:#1A1A1A; line-height:1.7; margin-bottom:1.5rem;">{{ $course->description }}</p>
        @endif
        <div class="flex items-center gap-4">
            @if($course->price > 0)
            <p style="font-size:1.1rem; font-weight:800; color:#D4896E;">{{ number_format($course->price, 0, ',', '.') }}đ</p>
            @else
            <p style="font-size:1rem; font-weight:700; color:#7B8B6F;">Miễn phí</p>
            @endif
            @auth
            <button wire:click="enroll" class="btn btn-gold" style="font-size:1rem; padding:0.65rem 1.5rem;">
                {{ $course->price > 0 ? 'Mua khóa học' : 'Đăng ký học' }}
            </button>
            @endauth
        </div>

        {{-- Module preview --}}
        <div style="margin-top:2rem; border-top:1px solid #E0D5C5; padding-top:1.5rem;">
            <h2 style="font-size:1rem; font-weight:800; color:#1A1A1A; margin-bottom:1rem;">Nội dung khóa học ({{ $totalLessons }} bài)</h2>
            @foreach($course->modules as $module)
            <div style="margin-bottom:1rem;">
                <p style="font-size:0.85rem; font-weight:700; color:#1A1A1A; margin-bottom:0.375rem;">{{ $module->title }}</p>
                @foreach($module->lessons as $lesson)
                <p style="font-size:0.8rem; color:#4A4A4A; padding-left:1rem; margin-bottom:0.25rem;">
                    🔒 {{ $lesson->title }}
                    @if($lesson->duration_minutes)
                    <span style="color:#999; font-size:0.7rem;">({{ $lesson->duration_minutes }}p)</span>
                    @endif
                </p>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>

    @elseif($pendingPayment)
    {{-- PENDING PAYMENT: QR --}}
    <div class="card" style="max-width:500px;">
        <h1 style="font-size:1.1rem; font-weight:800; color:#1A1A1A; margin-bottom:1rem;">{{ $course->title }}</h1>
        <div style="background:#FFFBEB; border:1px solid #FDE68A; border-radius:0.75rem; padding:1.25rem;">
            <p style="font-size:0.9rem; font-weight:700; color:#92400E; margin-bottom:0.75rem;">⏳ Chờ thanh toán</p>
            @php
                $bankAccount = config('services.sepay.bank_account');
                $bankName = config('services.sepay.bank_name');
                $transferContent = 'COURSE' . $course->id . 'U' . auth()->id();
                $qrUrl = 'https://qr.sepay.vn/img?acc=' . $bankAccount . '&bank=' . $bankName . '&amount=' . (int)$course->price . '&des=' . urlencode($transferContent);
            @endphp
            <div style="text-align:center; margin-bottom:0.75rem;">
                <img src="{{ $qrUrl }}" alt="QR" style="width:200px; height:200px; margin:0 auto; border-radius:0.5rem; border:1px solid #E0D5C5;">
            </div>
            <p style="font-size:0.85rem; color:#1A1A1A; text-align:center;">
                Chuyển khoản <strong style="color:#D4896E;">{{ number_format($course->price, 0, ',', '.') }}đ</strong>
            </p>
            <p style="font-size:0.85rem; color:#1A1A1A; margin-top:0.25rem; text-align:center;">
                Nội dung: <strong style="color:#D4896E;">{{ $transferContent }}</strong>
            </p>
            <p style="font-size:0.75rem; color:#92400E; margin-top:0.5rem; text-align:center; font-weight:600;">Hệ thống tự kích hoạt sau khi nhận tiền.</p>
        </div>
    </div>

    @else
    {{-- ENROLLED: Video player + lesson list --}}
    <div class="flex gap-4" style="align-items:flex-start;">

        {{-- LEFT: Video + lesson info --}}
        <div style="flex:1; min-width:0;">
            @php
                $activeLesson = $openLessonId
                    ? $course->modules->flatMap(fn($m) => $m->lessons)->firstWhere('id', $openLessonId)
                    : null;
            @endphp

            @if($activeLesson && in_array($activeLesson->id, $unlockedIds))
                {{-- Video player (fixed aspect ratio) --}}
                @if($activeLesson->video_url)
                @php
                    $ytId = null;
                    if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([a-zA-Z0-9_-]{11})/', $activeLesson->video_url, $m)) {
                        $ytId = $m[1];
                    }
                    $isEmbed = str_contains($activeLesson->video_url, '<iframe');
                @endphp
                <div style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden; border-radius:0.75rem; margin-bottom:1rem; background:#000;">
                    @if($ytId)
                    <iframe src="https://www.youtube.com/embed/{{ $ytId }}" style="position:absolute; top:0; left:0; width:100%; height:100%; border:0;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    @elseif($isEmbed)
                    <div style="position:absolute; top:0; left:0; width:100%; height:100%;">{!! $activeLesson->video_url !!}</div>
                    @else
                    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); color:#fff; text-align:center;">
                        <a href="{{ $activeLesson->video_url }}" target="_blank" style="color:#D4896E; font-weight:700;">▶ Xem video</a>
                    </div>
                    @endif
                </div>
                @else
                <div style="padding-bottom:56.25%; background:#1A1A1A; border-radius:0.75rem; margin-bottom:1rem; position:relative;">
                    <p style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); color:#666; font-size:0.85rem;">Bài học này chưa có video</p>
                </div>
                @endif

                {{-- Lesson title + description below video --}}
                <div class="card mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <h2 style="font-size:1.05rem; font-weight:800; color:#1A1A1A;">{{ $activeLesson->title }}</h2>
                        @if(!in_array($activeLesson->id, $completedIds))
                        <button wire:click="completeLesson({{ $activeLesson->id }})" class="btn btn-gold" style="font-size:0.8rem; padding:0.4rem 0.75rem;">✓ Hoàn thành</button>
                        @else
                        <span style="font-size:0.8rem; color:#059669; font-weight:700;">✓ Đã hoàn thành</span>
                        @endif
                    </div>
                    @if($activeLesson->duration_minutes)
                    <p style="font-size:0.75rem; color:#4A4A4A; margin-bottom:0.5rem;">⏱ {{ $activeLesson->duration_minutes }} phút</p>
                    @endif
                    @if($activeLesson->content)
                    <div style="font-size:0.9rem; color:#1A1A1A; line-height:1.7; white-space:pre-line;">{{ $activeLesson->content }}</div>
                    @endif
                </div>

                {{-- Course info (collapsed) --}}
                <div class="card" x-data="{ showInfo: false }">
                    <button @click="showInfo = !showInfo" style="width:100%; text-align:left; font-size:0.85rem; font-weight:700; color:#1A1A1A; cursor:pointer;">
                        📖 {{ $course->title }} <span style="float:right; color:#4A4A4A;" x-text="showInfo ? '▲' : '▼'"></span>
                    </button>
                    <div x-show="showInfo" x-transition style="margin-top:0.75rem;">
                        @if($course->description)
                        <p style="font-size:0.85rem; color:#4A4A4A; line-height:1.6;">{{ $course->description }}</p>
                        @endif
                        @if($totalLessons > 0)
                        <div class="xp-bar mt-2" style="height:6px;">
                            <div class="xp-bar-fill" style="width:{{ round($completedLessons / $totalLessons * 100) }}%;"></div>
                        </div>
                        <p style="font-size:0.7rem; color:#4A4A4A; margin-top:0.25rem;">{{ $completedLessons }}/{{ $totalLessons }} bài hoàn thành</p>
                        @endif
                    </div>
                </div>
            @else
                {{-- No lesson selected --}}
                <div style="padding-bottom:56.25%; background:#1A1A1A; border-radius:0.75rem; margin-bottom:1rem; position:relative;">
                    <p style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); color:#888; font-size:0.95rem;">Chọn bài học để bắt đầu →</p>
                </div>
                <div class="card">
                    <h1 style="font-size:1.1rem; font-weight:800; color:#1A1A1A; margin-bottom:0.5rem;">{{ $course->title }}</h1>
                    @if($course->description)
                    <p style="font-size:0.9rem; color:#4A4A4A; line-height:1.6;">{{ $course->description }}</p>
                    @endif
                </div>
            @endif
        </div>

        {{-- RIGHT: Lesson list (replaces default sidebar) --}}
        <div style="width:340px; flex-shrink:0; max-height:calc(100vh - 80px); overflow-y:auto; position:sticky; top:70px;">
            @foreach($course->modules as $module)
            <div style="margin-bottom:0.75rem;">
                <p style="font-size:0.75rem; font-weight:800; color:#4A4A4A; text-transform:uppercase; letter-spacing:0.05em; padding:0.5rem 0.25rem;">
                    {{ $module->title }}
                </p>
                @foreach($module->lessons as $lesson)
                @php
                    $isCompleted = in_array($lesson->id, $completedIds);
                    $isUnlocked = in_array($lesson->id, $unlockedIds);
                    $isActive = $openLessonId === $lesson->id;
                    $ytThumb = null;
                    if ($lesson->video_url && preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([a-zA-Z0-9_-]{11})/', $lesson->video_url, $tm)) {
                        $ytThumb = 'https://img.youtube.com/vi/' . $tm[1] . '/mqdefault.jpg';
                    }
                @endphp
                <button
                    @if($isUnlocked) wire:click="toggleLesson({{ $lesson->id }})" @endif
                    style="display:flex; gap:0.5rem; padding:0.5rem; border-radius:0.5rem; text-align:left; width:100%; margin-bottom:0.25rem;
                        {{ $isActive ? 'background:#F5E0D5; border:1px solid #D4896E;' : 'background:#FFFCF7; border:1px solid #E0D5C5;' }}
                        {{ !$isUnlocked ? 'opacity:0.4; cursor:default;' : 'cursor:pointer;' }}"
                >
                    {{-- Thumbnail --}}
                    <div style="width:80px; height:45px; border-radius:0.375rem; overflow:hidden; flex-shrink:0; background:#EDE5D8; position:relative;">
                        @if($ytThumb)
                        <img src="{{ $ytThumb }}" alt="" style="width:100%; height:100%; object-fit:cover;">
                        @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
                            <span style="font-size:0.8rem; color:#999;">▶</span>
                        </div>
                        @endif
                        @if($isCompleted)
                        <div style="position:absolute; top:2px; right:2px; background:#059669; color:#FFF; border-radius:50%; width:16px; height:16px; display:flex; align-items:center; justify-content:center; font-size:0.55rem; font-weight:700;">✓</div>
                        @endif
                    </div>
                    <div style="flex:1; min-width:0;">
                        <p style="font-size:0.75rem; font-weight:700; color:{{ $isActive ? '#B8725A' : '#1A1A1A' }}; line-height:1.3; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">{{ $lesson->title }}</p>
                        @if($lesson->duration_minutes)
                        <p style="font-size:0.65rem; color:#4A4A4A; margin-top:0.125rem;">{{ $lesson->duration_minutes }} phút</p>
                        @endif
                    </div>
                </button>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($course->modules->isEmpty())
    <div class="card text-center py-8">
        <p style="color:#4A4A4A; font-weight:600;">Khóa học này chưa có nội dung.</p>
    </div>
    @endif
</div>
