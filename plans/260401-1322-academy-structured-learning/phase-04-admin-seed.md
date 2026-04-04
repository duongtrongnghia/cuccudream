# Phase 04: Admin Course Builder + Seed Sample Course

## Priority: MEDIUM
## Status: pending
## Depends on: Phase 03

## Overview
Create admin UI to build structured courses (add modules, lessons, tasks, prerequisites). Seed a sample "Agent SEE" style course to demo the full flow.

## Key Files
- `app/Livewire/AdminCourses.php` (CREATE)
- `app/Livewire/AdminCourseBuilder.php` (CREATE)
- `resources/views/livewire/admin-courses.blade.php` (CREATE)
- `resources/views/livewire/admin-course-builder.blade.php` (CREATE)
- `database/seeders/StructuredCourseSeeder.php` (CREATE)
- `routes/web.php` (MODIFY — add admin routes)

## Implementation Steps

### Admin Course List
1. `AdminCourses` — CRUD list of all courses, toggle published, delete
2. Route: `/admin/courses` → AdminCourses (->can('admin'))

### Admin Course Builder
3. `AdminCourseBuilder` — single-page builder for a course:
   - Edit course title, description, pillar, difficulty, rewards
   - Add/reorder modules (drag or up/down buttons)
   - Add/reorder lessons within modules
   - Set lesson type (lecture/practice)
   - Add tasks within lessons (title, description, type, is_required)
   - Set prerequisite: dropdown "Unlock after lesson X"
   - Toggle is_locked_by_default per lesson
4. Route: `/admin/courses/{id}/build` → AdminCourseBuilder (->can('admin'))

### Sample Structured Course Seeder
5. Create `StructuredCourseSeeder` with:
   - Course: "Nền tảng tư duy AI" (pillar: offer, difficulty: basic)
   - Module 1: "Tuần 1 — Fundamentals" with 5 lessons
     - Lesson 1 (unlocked): 3 tasks (text submissions)
     - Lesson 2 (locked, requires lesson 1): 2 tasks
     - Lesson 3-5: similar pattern
     - Practice lesson (locked, requires lesson 5): 7 challenge tasks
   - Module 2: "Tuần 2 — Advanced" with 4 lessons
   - Each lesson has prerequisite = previous lesson

## Success Criteria
- [ ] Admin can create/edit courses with modules, lessons, tasks
- [ ] Admin can set prerequisites between lessons
- [ ] Sample structured course seeded with 2 modules, 10 lessons, 25+ tasks
- [ ] `php artisan db:seed --class=StructuredCourseSeeder` works
- [ ] Students can navigate the seeded course with lock/unlock flow
