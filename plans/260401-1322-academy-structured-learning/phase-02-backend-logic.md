# Phase 02: Backend — Prerequisite Unlock + Submission CRUD

## Priority: HIGH
## Status: pending
## Depends on: Phase 01

## Overview
Implement unlock logic, submission handling, and auto-completion detection in AcademyDetail component.

## Key Files
- `app/Livewire/AcademyDetail.php` (MODIFY — major rewrite)
- `app/Models/Lesson.php` (MODIFY — add isUnlockedFor method)

## Implementation Steps

### 1. Lesson.isUnlockedFor(User $user)
```php
public function isUnlockedFor(User $user): bool
{
    if (!$this->is_locked_by_default) return true;
    
    // First lesson in module (order_index 0) is always unlocked
    if ($this->order_index === 0 && !$this->prerequisites()->exists()) return true;
    
    // Check explicit prerequisites
    $requiredIds = $this->prerequisites()->pluck('required_lesson_id');
    if ($requiredIds->isEmpty()) {
        // No explicit prerequisites — check previous lesson in same module
        $prev = Lesson::where('module_id', $this->module_id)
            ->where('order_index', '<', $this->order_index)
            ->orderByDesc('order_index')
            ->first();
        if (!$prev) return true;
        $requiredIds = collect([$prev->id]);
    }
    
    $completedCount = LessonProgress::where('user_id', $user->id)
        ->whereIn('lesson_id', $requiredIds)
        ->whereNotNull('completed_at')
        ->count();
    
    return $completedCount >= $requiredIds->count();
}
```

### 2. Lesson auto-complete logic
A lesson is "completed" when ALL required tasks have approved/pending submissions OR lesson has no tasks and user clicks complete.

```php
// In AcademyDetail
public function checkLessonAutoComplete(Lesson $lesson): void
{
    $requiredTasks = $lesson->tasks()->where('is_required', true)->count();
    if ($requiredTasks === 0) return; // No tasks = manual complete
    
    $submittedCount = TaskSubmission::where('user_id', Auth::id())
        ->whereIn('lesson_task_id', $lesson->tasks()->where('is_required', true)->pluck('id'))
        ->whereIn('status', ['pending', 'approved'])
        ->count();
    
    if ($submittedCount >= $requiredTasks) {
        // Auto-mark lesson as completed
        $this->completeLesson($lesson->id);
    }
}
```

### 3. AcademyDetail new methods
- `submitTask(int $taskId, string $content)` — create TaskSubmission, check auto-complete
- `completeLesson(int $lessonId)` — existing, add prerequisite unlock check
- Remove manual "Hoàn thành" button for lessons that have tasks

### 4. Task unlock within lesson
Tasks within a lesson unlock sequentially: task N unlocks when task N-1 has a submission.

## Success Criteria
- [ ] Lessons unlock sequentially based on previous lesson completion
- [ ] Tasks unlock sequentially within a lesson
- [ ] Submitting all required tasks auto-completes the lesson
- [ ] First lesson in each module is always unlocked
