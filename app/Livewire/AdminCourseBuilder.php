<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use Livewire\Attributes\Rule;
use Livewire\Component;

class AdminCourseBuilder extends Component
{
    public Course $course;

    // Course editing
    public string $courseTitle = '';
    public string $courseDescription = '';

    // Module form
    public bool $showAddModule = false;
    #[Rule('required|min:3|max:100')]
    public string $moduleName = '';

    // Lesson form
    public ?int $addLessonToModule = null;
    #[Rule('required|min:3|max:150')]
    public string $lessonTitle = '';

    // Lesson editing
    public ?int $editingLessonId = null;
    public string $editLessonTitle = '';
    public string $editLessonDescription = '';
    public string $editLessonVideoUrl = '';
    public int $editLessonDuration = 0;

    // Module editing
    public ?int $editingModuleId = null;
    public string $editModuleTitle = '';

    public function mount(int $id): void
    {
        $this->course = Course::with(['modules.lessons'])->findOrFail($id);
        $this->courseTitle = $this->course->title;
        $this->courseDescription = $this->course->description ?? '';
    }

    // ─── Course ─────────────────────────────────────────────
    public function saveCourse(): void
    {
        $this->course->update([
            'title' => $this->courseTitle,
            'description' => $this->courseDescription,
        ]);
        $this->dispatch('toast', message: 'Đã lưu thông tin khóa học', type: 'success');
    }

    // ─── Module ─────────────────────────────────────────────
    public function addModule(): void
    {
        $this->validate(['moduleName' => 'required|min:3|max:100']);
        $maxOrder = $this->course->modules()->max('order_index') ?? -1;
        Module::create([
            'course_id' => $this->course->id,
            'title' => $this->moduleName,
            'order_index' => $maxOrder + 1,
        ]);
        $this->reset(['moduleName', 'showAddModule']);
        $this->course->refresh();
    }

    public function editModule(int $id): void
    {
        $module = Module::findOrFail($id);
        $this->editingModuleId = $id;
        $this->editModuleTitle = $module->title;
    }

    public function saveModule(): void
    {
        if (!$this->editingModuleId) return;
        Module::findOrFail($this->editingModuleId)->update(['title' => $this->editModuleTitle]);
        $this->editingModuleId = null;
        $this->course->refresh();
    }

    public function deleteModule(int $id): void
    {
        Module::where('id', $id)->where('course_id', $this->course->id)->delete();
        $this->course->refresh();
    }

    // ─── Lesson ─────────────────────────────────────────────
    public function addLesson(): void
    {
        $this->validate(['lessonTitle' => 'required|min:3|max:150']);
        if (!$this->addLessonToModule) return;

        $maxOrder = Lesson::where('module_id', $this->addLessonToModule)->max('order_index') ?? -1;
        Lesson::create([
            'module_id' => $this->addLessonToModule,
            'title' => $this->lessonTitle,
            'lesson_type' => 'lecture',
            'order_index' => $maxOrder + 1,
        ]);
        $this->reset(['lessonTitle', 'addLessonToModule']);
        $this->course->refresh();
    }

    public function editLesson(int $id): void
    {
        $lesson = Lesson::findOrFail($id);
        $this->editingLessonId = $id;
        $this->editLessonTitle = $lesson->title;
        $this->editLessonDescription = $lesson->content ?? '';
        $this->editLessonVideoUrl = $lesson->video_url ?? '';
        $this->editLessonDuration = $lesson->duration_minutes ?? 0;
    }

    public function saveLesson(): void
    {
        if (!$this->editingLessonId) return;
        Lesson::findOrFail($this->editingLessonId)->update([
            'title' => $this->editLessonTitle,
            'content' => $this->editLessonDescription ?: null,
            'video_url' => $this->editLessonVideoUrl ?: null,
            'duration_minutes' => $this->editLessonDuration,
        ]);
        $this->editingLessonId = null;
        $this->course->refresh();
        $this->dispatch('toast', message: 'Đã lưu bài học', type: 'success');
    }

    public function cancelEditLesson(): void
    {
        $this->editingLessonId = null;
    }

    public function deleteLesson(int $id): void
    {
        Lesson::findOrFail($id)->delete();
        $this->course->refresh();
    }

    public function render()
    {
        return view('livewire.admin-course-builder')
            ->layout('layouts.app', ['title' => 'Xây dựng: ' . $this->course->title . ' — Admin']);
    }
}
