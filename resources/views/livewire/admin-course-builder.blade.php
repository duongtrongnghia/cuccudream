<div>
    <div class="flex items-center gap-3 mb-4">
        <a href="{{ route('admin.courses') }}" style="font-size:0.85rem; color:#6B6158;">← Khóa học</a>
        <h1 style="font-size:1.1rem; font-weight:800; color:#2D2926;">🔧 {{ $course->title }}</h1>
    </div>

    {{-- Course info --}}
    <div class="card mb-4">
        <h2 style="font-size:0.9rem; font-weight:700; color:#2D2926; margin-bottom:0.75rem;">Thông tin khóa học</h2>
        <div class="grid gap-3">
            <div>
                <label style="font-size:0.75rem; font-weight:700; color:#6B6158;">Tên khóa học</label>
                <input wire:model="courseTitle" class="input" style="font-size:0.85rem;">
            </div>
            <div>
                <label style="font-size:0.75rem; font-weight:700; color:#6B6158;">Mô tả</label>
                <textarea wire:model="courseDescription" class="input" rows="3" style="font-size:0.85rem;"></textarea>
            </div>
            <button wire:click="saveCourse" class="btn btn-gold" style="font-size:0.8rem; width:fit-content;">Lưu thông tin</button>
        </div>
    </div>

    {{-- Modules --}}
    @foreach($course->modules->sortBy('order_index') as $module)
    <div class="card mb-4">
        <div class="flex items-center justify-between mb-3">
            @if($editingModuleId === $module->id)
            <div class="flex items-center gap-2 flex-1">
                <input wire:model="editModuleTitle" class="input" style="font-size:0.85rem; flex:1;">
                <button wire:click="saveModule" class="btn btn-gold" style="font-size:0.75rem; padding:0.3rem 0.6rem;">Lưu</button>
                <button wire:click="$set('editingModuleId', null)" class="btn btn-ghost" style="font-size:0.75rem; padding:0.3rem 0.6rem;">Hủy</button>
            </div>
            @else
            <h2 style="font-size:0.95rem; font-weight:700; color:#2D2926; cursor:pointer;" wire:click="editModule({{ $module->id }})">
                {{ $module->title }} <span style="font-size:0.65rem; color:#6B6158;">✎</span>
            </h2>
            @endif
            <div class="flex gap-2">
                <button wire:click="$set('addLessonToModule', {{ $module->id }})" class="btn btn-secondary" style="font-size:0.75rem; padding:0.3rem 0.6rem;">+ Bài học</button>
                <button wire:click="deleteModule({{ $module->id }})" wire:confirm="Xóa module này và tất cả bài học?" class="btn btn-danger" style="font-size:0.75rem; padding:0.3rem 0.6rem;">Xóa</button>
            </div>
        </div>

        {{-- Lessons --}}
        @foreach($module->lessons->sortBy('order_index') as $lesson)
        <div style="margin-left:0.75rem; padding:0.75rem; border-left:2px solid #D4896E; margin-bottom:0.5rem; background:#FFFCF7; border-radius:0 0.5rem 0.5rem 0;">
            @if($editingLessonId === $lesson->id)
            {{-- Editing lesson --}}
            <div class="grid gap-3">
                <div>
                    <label style="font-size:0.75rem; font-weight:700; color:#6B6158;">Tên bài học</label>
                    <input wire:model="editLessonTitle" class="input" style="font-size:0.85rem;">
                </div>
                <div>
                    <label style="font-size:0.75rem; font-weight:700; color:#6B6158;">Mô tả / Nội dung bài học</label>
                    <textarea wire:model="editLessonDescription" class="input" rows="3" style="font-size:0.85rem;" placeholder="Mô tả nội dung bài học..."></textarea>
                </div>
                <div>
                    <label style="font-size:0.75rem; font-weight:700; color:#6B6158;">Video embed (Bunny / YouTube)</label>
                    <textarea wire:model="editLessonVideoUrl" class="input" rows="2" style="font-size:0.8rem; font-family:monospace;" placeholder='Dán embed code hoặc URL video, VD:&#10;<iframe src="https://iframe.mediadelivery.net/embed/..." ...></iframe>&#10;hoặc: https://www.youtube.com/watch?v=...'></textarea>
                    <p style="font-size:0.7rem; color:#6B6158; margin-top:0.25rem;">Hỗ trợ: Bunny embed code, YouTube URL, hoặc bất kỳ iframe embed</p>
                </div>
                <div>
                    <label style="font-size:0.75rem; font-weight:700; color:#6B6158;">Thời lượng (phút)</label>
                    <input wire:model="editLessonDuration" type="number" class="input" style="font-size:0.85rem; width:100px;">
                </div>
                <div class="flex gap-2">
                    <button wire:click="saveLesson" class="btn btn-gold" style="font-size:0.8rem;">Lưu bài học</button>
                    <button wire:click="cancelEditLesson" class="btn btn-ghost" style="font-size:0.8rem;">Hủy</button>
                </div>
            </div>
            @else
            {{-- Display lesson --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 flex-1 cursor-pointer" wire:click="editLesson({{ $lesson->id }})">
                    <span style="font-size:0.7rem; font-weight:700; color:#6B6158;">{{ $lesson->order_index + 1 }}.</span>
                    <span style="font-size:0.85rem; font-weight:600; color:#2D2926;">{{ $lesson->title }}</span>
                    @if($lesson->video_url)
                    <span style="font-size:0.7rem; color:#7B8B6F;">🎬</span>
                    @endif
                    @if($lesson->duration_minutes)
                    <span style="font-size:0.7rem; color:#6B6158;">({{ $lesson->duration_minutes }}p)</span>
                    @endif
                    <span style="font-size:0.6rem; color:#6B6158;">✎</span>
                </div>
                <button wire:click="deleteLesson({{ $lesson->id }})" wire:confirm="Xóa bài học này?" class="btn btn-ghost" style="font-size:0.7rem; padding:0.2rem 0.4rem; color:#991B1B;">×</button>
            </div>
            @if($lesson->content)
            <p style="font-size:0.75rem; color:#6B6158; margin-top:0.25rem; margin-left:1.5rem;">{{ Str::limit($lesson->content, 80) }}</p>
            @endif
            @endif
        </div>
        @endforeach

        {{-- Add lesson form --}}
        @if($addLessonToModule === $module->id)
        <div style="margin-left:0.75rem; padding:0.75rem; background:#FFFBEB; border:1px solid #FDE68A; border-radius:0.5rem;">
            <div class="flex gap-2">
                <input wire:model="lessonTitle" class="input flex-1" placeholder="Tên bài học" style="font-size:0.85rem;" wire:keydown.enter="addLesson">
                <button wire:click="addLesson" class="btn btn-primary" style="font-size:0.8rem;">Thêm</button>
                <button wire:click="$set('addLessonToModule', null)" class="btn btn-ghost" style="font-size:0.8rem;">Hủy</button>
            </div>
            @error('lessonTitle') <p style="color:#991B1B; font-size:0.7rem; margin-top:0.25rem;">{{ $message }}</p> @enderror
        </div>
        @endif
    </div>
    @endforeach

    {{-- Add module --}}
    @if($showAddModule)
    <div class="card mb-4" style="background:#FFFBEB; border-color:#FDE68A;">
        <div class="flex gap-2">
            <input wire:model="moduleName" class="input flex-1" placeholder="Tên module (VD: Buổi 1 — Nhân vật Chibi)" style="font-size:0.85rem;" wire:keydown.enter="addModule">
            <button wire:click="addModule" class="btn btn-primary" style="font-size:0.85rem;">Thêm</button>
            <button wire:click="$set('showAddModule', false)" class="btn btn-ghost" style="font-size:0.85rem;">Hủy</button>
        </div>
        @error('moduleName') <p style="color:#991B1B; font-size:0.7rem; margin-top:0.25rem;">{{ $message }}</p> @enderror
    </div>
    @else
    <button wire:click="$set('showAddModule', true)" class="btn btn-secondary w-full justify-center" style="font-size:0.85rem;">+ Thêm Module</button>
    @endif
</div>
