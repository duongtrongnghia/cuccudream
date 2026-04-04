# Phase 03: Frontend — AcademyDetail UI Upgrade

## Priority: HIGH
## Status: pending
## Depends on: Phase 02

## Overview
Redesign academy-detail.blade.php to match the structured learning UX from screenshots:
- Lock/unlock visual states per lesson
- Expandable lesson cards with tasks inside
- Task submission form (textarea/file input)
- "Bài làm của cả lớp" section
- Practice section with challenge grid

## Key Files
- `resources/views/livewire/academy-detail.blade.php` (REWRITE)

## UI Structure

### Lesson Card States
```
[UNLOCKED + COMPLETED]  ✅ green check, "Done" badge, clickable to review
[UNLOCKED + IN PROGRESS] 🔵 numbered circle, expanded by default, tasks visible
[LOCKED]                 🔒 gray, lock icon, "Hoàn thành bài X để mở khóa"
```

### Expanded Lesson Layout
```
┌─────────────────────────────────────────────┐
│ ✅ Bài 1 — Tập ra lệnh cho Coding Agent  Done ▾ │
├─────────────────────────────────────────────┤
│ ✅ Task 1: "Yêu cầu bài số 1 là gì?"           │
│    [Student's submitted answer]                   │
│                                                   │
│ 🔵 Task 2: "Agent đã sử dụng công nghệ gì?"    │
│    [Textarea for answer] [Gửi bài]               │
│                                                   │
│ 🔒 Task 3: "Tính năng chụp hình..."             │
│    Trả lời câu trước để mở khoá                  │
├─────────────────────────────────────────────┤
│ 📝 Bài làm của cả lớp                    Xem thêm│
│ [Avatar] [Avatar] [Avatar] [Avatar]               │
└─────────────────────────────────────────────┘
```

### Practice Section (lesson type = 'practice')
```
┌─────────────────────────────────────────────┐
│ 🗺️ Thực hành cộng tác với Agent    0/7    │
│ ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐       │
│ │ #1   │ │ 🔒#2 │ │ 🔒#3 │ │ 🔒#4 │       │
│ │Làm   │ │Bí mật│ │Giải  │ │Hiểm  │       │
│ │web   │ │trong │ │mã nội│ │hoạ   │       │
│ └──────┘ └──────┘ └──────┘ └──────┘       │
│ Bài làm của cả lớp              Xem chi tiết│
└─────────────────────────────────────────────┘
```

## Implementation Steps

1. Add Alpine.js `x-data` for expandable lesson cards (track `openLesson` ID)
2. Render each lesson with lock/unlock state based on `$unlockedIds`
3. Inside expanded lesson: render tasks sequentially with submission forms
4. Task submission: textarea + "Gửi bài" button, wire:click="submitTask(taskId)"
5. Show submitted answers inline (readonly) for completed tasks
6. "Bài làm của cả lớp": avatar row of students who submitted, link to class view
7. Practice lessons: horizontal scroll grid of challenge cards with lock states
8. Pass `$unlockedIds`, `$submittedTaskIds`, `$classSubmissions` from component

## CSS Classes Needed
Add to app.css:
- `.lesson-card.locked` — opacity 0.6, cursor not-allowed
- `.lesson-card.completed` — left border green
- `.lesson-card.active` — left border gold
- `.task-item.locked` — gray text, lock icon
- `.challenge-grid` — horizontal scroll flex container

## Success Criteria
- [ ] Locked lessons show gray with lock icon + message
- [ ] Unlocked lessons expandable with tasks inside
- [ ] Task submission form works (textarea → submit → shows answer)
- [ ] Tasks unlock sequentially within lesson
- [ ] Practice section shows challenge grid
- [ ] "Bài làm của cả lớp" shows student avatars
