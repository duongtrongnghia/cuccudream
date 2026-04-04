# Academy/Course System Scout Report
**Date:** April 1, 2026 | **Scope:** Full Academy system architecture

---

## Summary
The Laravel/Livewire Academy system implements a comprehensive course management platform with:
- **Courses** → **Modules** → **Lessons** → **Tasks** hierarchical structure
- Sequential lesson unlocking with prerequisite support
- Task submission and manual review workflow
- XP-based reward system
- Student enrollment and progress tracking

---

## Database Schema

### Core Tables (2026_01_01_000007_create_academy_table.php)

#### `courses` (Lines 11–23)
- `id` (PK)
- `title` (string)
- `description` (text, nullable)
- `pillar` (enum: offer/traffic/conversion/delivery/continuity)
- `difficulty` (enum: basic/advanced/expert, default basic)
- `min_level` (unsigned smallint, default 1)
- `xp_reward` (unsigned int)
- `aip_reward` (unsigned int)
- `thumbnail` (string, nullable)
- `is_published` (boolean, default false)
- `created_at`, `updated_at` (timestamps)

#### `modules` (Lines 25–31)
- `id` (PK)
- `course_id` (FK → courses, cascadeOnDelete)
- `title` (string)
- `order_index` (unsigned tinyint, default 0)
- `created_at`, `updated_at` (timestamps)

#### `lessons` (Lines 33–44)
- `id` (PK)
- `module_id` (FK → modules, cascadeOnDelete)
- `title` (string)
- `lesson_type` (string, default 'lecture') — Added by migration 2026_04_01_000001, Line 44
- `video_url` (string, nullable)
- `content` (longText, nullable)
- `xp_reward` (unsigned int)
- `aip_reward` (unsigned int)
- `duration_minutes` (unsigned smallint)
- `order_index` (unsigned tinyint)
- `is_locked_by_default` (boolean, default true) — Added by migration 2026_04_01_000001, Line 45
- `created_at`, `updated_at` (timestamps)

#### `lesson_progress` (Lines 46–52)
- `id` (PK)
- `user_id` (FK → users, cascadeOnDelete)
- `lesson_id` (FK → lessons, cascadeOnDelete)
- `completed_at` (timestamp, nullable)
- **Constraint:** unique(user_id, lesson_id)

#### `course_enrollments` (Lines 54–61)
- `id` (PK)
- `user_id` (FK → users, cascadeOnDelete)
- `course_id` (FK → courses, cascadeOnDelete)
- `enrolled_at` (timestamp)
- `completed_at` (timestamp, nullable)
- **Constraint:** unique(user_id, course_id)

### Extended Tables (2026_04_01_000001_create_structured_learning_tables.php)

#### `lesson_tasks` (Lines 11–20)
- `id` (PK)
- `lesson_id` (FK → lessons, cascadeOnDelete)
- `title` (string)
- `description` (text, nullable)
- `type` (enum: text/link/file/quiz, default text)
- `order_index` (unsigned tinyint)
- `is_required` (boolean, default true)
- `created_at`, `updated_at` (timestamps)

#### `task_submissions` (Lines 22–34)
- `id` (PK)
- `lesson_task_id` (FK → lesson_tasks, cascadeOnDelete)
- `user_id` (FK → users, cascadeOnDelete)
- `content` (text, nullable) — Student's answer
- `file_url` (string, nullable)
- `status` (enum: pending/approved/rejected, default pending)
- `reviewed_by` (FK → users, nullable, nullOnDelete)
- `reviewed_at` (timestamp, nullable)
- `submitted_at` (timestamp)
- `created_at`, `updated_at` (timestamps)
- **Constraint:** unique(lesson_task_id, user_id) — Only one submission per task per user

#### `lesson_prerequisites` (Lines 36–41)
- `id` (PK)
- `lesson_id` (FK → lessons, cascadeOnDelete)
- `required_lesson_id` (FK → lessons, cascadeOnDelete)
- **Constraint:** unique(lesson_id, required_lesson_id) — Define explicit prerequisite chains

---

## Models & Relationships

### Course.php (app/Models/)
```
Course::has(modules) — OrderBy('order_index')
Course::has(enrollments)
```

### Module.php (app/Models/)
```
Module::belongs_to(course)
Module::has(lessons) — OrderBy('order_index')
```

### Lesson.php (app/Models/, Lines 1–81)
**Key Methods:**
- `module()` → BelongsTo Module
- `progress()` → HasMany LessonProgress
- `tasks()` → HasMany LessonTask (ordered by order_index)
- `prerequisites()` → HasMany LessonPrerequisite
- **`isUnlockedFor(User $user): bool`** (Lines 39–72):
  - Returns TRUE if `is_locked_by_default` is FALSE
  - Otherwise, checks explicit prerequisites via LessonPrerequisite
  - Falls back to implicit: previous lesson in same module (Line 54–64)
  - First lesson (`order_index === 0`) always unlocked
  - Returns TRUE only if user completed all required prerequisites
- **`isCompletedBy(User $user): bool`** (Lines 74–80):
  - Checks if user has LessonProgress record with non-null `completed_at`

### LessonTask.php (app/Models/)
```
LessonTask::belongs_to(lesson)
LessonTask::has(submissions)
```

### TaskSubmission.php (app/Models/, Lines 1–34)
```
TaskSubmission::belongs_to(task) [via lesson_task_id]
TaskSubmission::belongs_to(user)
TaskSubmission::belongs_to(reviewer) [via reviewed_by]
```

### CourseEnrollment.php (app/Models/)
```
CourseEnrollment::belongs_to(user)
CourseEnrollment::belongs_to(course)
Timestamps OFF: Manage enrolled_at/completed_at manually
```

### LessonProgress.php (app/Models/)
```
LessonProgress::belongs_to(user)
LessonProgress::belongs_to(lesson)
Table: 'lesson_progress' (not 'lesson_progresses')
Timestamps OFF
```

### LessonPrerequisite.php (app/Models/)
```
LessonPrerequisite::belongs_to(lesson)
LessonPrerequisite::belongs_to(requiredLesson) [via required_lesson_id]
Timestamps OFF
```

---

## Livewire Components

### AcademyDetail.php (app/Livewire/, Lines 1–222)
**Props:**
- `course: Course` (with modules.lessons.tasks eager loaded)
- `enrolled: bool`
- `completedLessons: int`, `totalLessons: int`
- `openLessonId: ?int` — Currently expanded lesson
- `taskAnswer: string` (#[Rule])
- `activeTaskId: ?int` — Currently editing task

**Key Methods:**
- `mount(int $id)` (Lines 28–46):
  - Load course with eager relations
  - Count total lessons across modules
  - Check enrollment status (if authenticated)
  - If enrolled, count completed lessons via LessonProgress

- `enroll()` (Lines 48–66):
  - Validate user level >= course.min_level
  - Create CourseEnrollment record
  - Dispatch toast notification

- `toggleLesson(int $lessonId)` (Lines 68–72):
  - Expand/collapse lesson details
  - Reset task form on toggle

- `startTask(int $taskId)` (Lines 74–78):
  - Set active task for submission form

- `submitTask()` (Lines 80–97):
  - Validate taskAnswer (required, min:3, max:5000)
  - Create/update TaskSubmission with status='pending'
  - Auto-check if lesson completes (via checkLessonAutoComplete)

- **`checkLessonAutoComplete(Lesson $lesson)`** (Lines 138–151):
  - If lesson has no required tasks, return early
  - Count user's submissions with status in [pending, approved]
  - If count >= required tasks, auto-complete lesson

- `completeLesson(int $lessonId)` (Lines 99–136):
  - Verify user is enrolled & lesson is unlocked
  - Prevent double-completion
  - Create LessonProgress with completed_at
  - Award XP if lesson.xp_reward > 0
  - Increment completedLessons counter
  - **Check if course is now complete** (Lines 125–135):
    - If completedLessons >= totalLessons, mark CourseEnrollment.completed_at
    - Award course XP reward

- `render()` (Lines 160–221):
  - Build `completedIds` array: lessons user completed
  - Build `unlockedIds` array: lessons unlocked for user
  - Build `submissions` collection: keyed by task ID
  - **Class submissions** (Lines 199–212):
    - For currently open lesson, fetch up to 10 other students' task submissions
    - Used to display "Class submissions" avatars in view

**View: academy-detail.blade.php (resources/views/livewire/, Lines 1–231)**

### Layout Structure:
1. **Course Header** (Lines 6–46):
   - Badges: pillar, difficulty, min_level
   - Title, description
   - XP reward & progress bar
   - Enrollment button (if not enrolled) or "✅ Enrolled" status

2. **Modules Loop** (Lines 49–224):
   - For each module, display lessons

3. **Lesson Card** (Lines 56–221):
   - **Always-visible header** (Lines 68–107):
     - Status icon: ✅ (completed), numbered circle (unlocked), 🔒 (locked)
     - Title with 🗺️ emoji if practice lesson
     - Progress text if unlocked: "X/Y tasks" (Lines 92–95)
     - Locked message if not unlocked
     - Expand/collapse chevron (if unlocked)

   - **Expanded Content** (Lines 110–219, shown if `isOpen && isUnlocked && enrolled`):
     - Lesson content (pre-formatted text, Line 115)
     - Video link button (Line 119–124)
     - **Tasks section** (Lines 128–198):
       - For each task (ordered):
         - Status: ✅ (submitted), 🔵 (unlocked), 🔒 (locked)
         - Task title & description
         - Show submitted answer if exists (Lines 157–164):
           - Displays content in white box
           - Shows submission timestamp & status badge
         - Task form if unlocked and not submitted (Lines 167–187):
           - Textarea with auto-height (x-init + @input)
           - Submit button with loading state
           - Cancel button
         - "Answer" link if unlocked and not submitted (Line 184)
         - Unlock message if locked (Line 190)
         - **Sequential unlock:** `prevTaskSubmitted` tracks if previous task was submitted (Lines 129, 196)

     - Manual complete button (Lines 201–205): Only shown if lesson has NO tasks and not completed
     - Class submissions avatars (Lines 208–217): Shows up to 10 students who submitted

### AcademyPage.php (app/Livewire/, Lines 1–41)
**Props:**
- `#[Url] pillar: string` — URL filter
- `#[Url] difficulty: string` — URL filter

**Methods:**
- `setPillar(string $p)` (Lines 17–20):
  - Toggle pillar filter (click again to deselect)

- `render()` (Lines 22–40):
  - Filter published courses by pillar/difficulty
  - Load enrollments count via withCount
  - Return view with sorted courses

---

## Admin Components

### AdminCourses.php (app/Livewire/, Lines 1–27)
**Methods:**
- `togglePublish(int $id)` (Lines 10–14):
  - Toggle course is_published status

- `deleteCourse(int $id)` (Lines 16–19):
  - Delete course (cascades to modules, lessons, etc.)

- `render()` (Lines 21–26):
  - Load all courses with module count & enrollment count
  - Order by latest created

**View: admin-courses.blade.php (Lines 1–35)**
- List all courses with publish status
- Show module count, enrollment count, XP reward
- Action buttons: Build, Toggle Publish, Delete

### AdminCourseBuilder.php (app/Livewire/, Lines 1–119)
**Props:**
- `course: Course`
- Module form: `showAddModule`, `moduleName`
- Lesson form: `addLessonToModule`, `lessonTitle`, `lessonType`, `lessonXp`, `lessonLocked`
- Task form: `addTaskToLesson`, `taskTitle`, `taskDescription`, `taskType`, `taskRequired`

**Methods:**
- `mount(int $id)` (Lines 37–40):
  - Load course with eager relations

- `addModule()` (Lines 42–53):
  - Validate moduleName
  - Create Module with order_index = max + 1

- `deleteModule(int $id)` (Lines 55–59):
  - Delete and refresh

- `addLesson()` (Lines 61–80):
  - Validate lessonTitle
  - Create Lesson with order_index = max + 1
  - Set lesson_type, lessonXp (xp_reward), is_locked_by_default

- `deleteLesson(int $id)` (Lines 82–86):
  - Delete and refresh

- `addTask()` (Lines 88–106):
  - Validate taskTitle
  - Create LessonTask with order_index = max + 1
  - Set type, description, is_required

- `deleteTask(int $id)` (Lines 108–112):
  - Delete and refresh

- `render()` (Lines 114–118):
  - Return builder view

**View: admin-course-builder.blade.php (Lines 1–112)**
- **Modules** (Lines 8–97):
  - For each module, show lessons (Lines 19–76)
  - Lesson card (Lines 20–54):
    - Shows order_index + 1, title, type badge, locked indicator, XP
    - Add task button, delete button
    - Tasks list (Lines 38–54): Order_index, title, type, required asterisk, delete
    - Add task form (Lines 57–74): Title input, type select, required checkbox, add button
  - Add lesson form (Lines 79–95): Title, type select, XP input, locked checkbox
- **Add module button or form** (Lines 99–111)

---

## Lesson Structure & Display Flow

### Data Flow (AcademyDetail):
1. **Mount phase:**
   - Load course with `modules.lessons.tasks` (eager)
   - Count total lessons: `course.modules.sum(fn($m) => m.lessons.count())`
   - Check enrollment: CourseEnrollment exists?
   - If enrolled, count completed lessons via LessonProgress

2. **Render phase:**
   - Build `completedIds`: LessonProgress records with completed_at for enrolled user
   - Build `unlockedIds`: Call lesson.isUnlockedFor(user) for each lesson
   - Build `submissions`: TaskSubmission keyed by task_id
   - Build `classSubmissions`: Other students' submissions for open lesson

3. **View rendering:**
   - **Lesson visibility:**
     - Locked lessons show 🔒 icon, disabled click, opacity 0.7
     - Unlocked lessons show numbered circle (order_index + 1), clickable
     - Completed lessons show ✅ and "Done" status
   
   - **Content reveals on click:**
     - Lesson content (pre-formatted text)
     - Video link (if lesson.video_url exists)
     - Tasks (ordered by order_index)
   
   - **Task display logic (Lines 128–198):**
     - Each task has status indicator based on:
       - Submitted? ✅ (show submission in white box)
       - Unlocked & not submitted? 🔵 (show answer button → expand to textarea)
       - Locked? 🔒 (show lock message)
     - **Sequential unlock:** prevTaskSubmitted variable tracks completion
     - First task always unlocked if lesson is unlocked
     - Subsequent tasks unlock only if previous task submitted

   - **Lesson completion:**
     - Auto-complete: If all required tasks have submissions (status: pending or approved)
     - Manual complete: Button shown for lessons WITHOUT tasks
     - Award XP on completion
     - Check course completion: If all lessons done, mark course.completed_at

### Unlock Mechanism (Lesson.isUnlockedFor):
- **Default behavior:** is_locked_by_default = false → always unlocked
- **Locked by default:**
  - Check explicit prerequisites (LessonPrerequisite)
  - If none, use implicit: previous lesson in same module
  - First lesson (order_index = 0) always unlocked
  - User must complete all required lessons

---

## Key Design Patterns

1. **Hierarchical Structure:**
   - Course → Modules (ordered) → Lessons (ordered) → Tasks (ordered)
   - Each level has order_index for display sequence

2. **Sequential Unlocking:**
   - Lessons locked until prerequisites completed
   - Implicit prerequisites: previous lesson in module
   - Explicit prerequisites: LessonPrerequisite table

3. **Task Sequencing:**
   - Tasks within lesson unlock sequentially
   - Can mark as required or optional
   - Submissions tracked per user+task (unique constraint)

4. **Completion Tracking:**
   - Lessons: LessonProgress with completed_at
   - Courses: CourseEnrollment with completed_at
   - Tasks: TaskSubmission with status (pending/approved/rejected)

5. **XP Rewards:**
   - Per-lesson: lesson.xp_reward
   - Per-course: course.xp_reward (awarded on full completion)
   - Awarded via XpService.award() on completion

6. **Enrollment:**
   - CourseEnrollment gates access
   - Min level check on enroll (course.min_level vs user.level)
   - Unique constraint: one enrollment per user per course

---

## File Paths with Line Numbers

### Livewire Components:
- `/Users/mdm/Desktop/the-all-in-plan/app/Livewire/AcademyDetail.php` (Lines 1–222)
- `/Users/mdm/Desktop/the-all-in-plan/app/Livewire/AcademyPage.php` (Lines 1–41)
- `/Users/mdm/Desktop/the-all-in-plan/app/Livewire/AdminCourses.php` (Lines 1–27)
- `/Users/mdm/Desktop/the-all-in-plan/app/Livewire/AdminCourseBuilder.php` (Lines 1–119)

### Views:
- `/Users/mdm/Desktop/the-all-in-plan/resources/views/livewire/academy-detail.blade.php` (Lines 1–231)
- `/Users/mdm/Desktop/the-all-in-plan/resources/views/livewire/admin-courses.blade.php` (Lines 1–35)
- `/Users/mdm/Desktop/the-all-in-plan/resources/views/livewire/admin-course-builder.blade.php` (Lines 1–112)

### Models:
- `/Users/mdm/Desktop/the-all-in-plan/app/Models/Course.php` (Lines 1–26)
- `/Users/mdm/Desktop/the-all-in-plan/app/Models/Module.php` (Lines 1–22)
- `/Users/mdm/Desktop/the-all-in-plan/app/Models/Lesson.php` (Lines 1–81)
- `/Users/mdm/Desktop/the-all-in-plan/app/Models/LessonTask.php` (Lines 1–24)
- `/Users/mdm/Desktop/the-all-in-plan/app/Models/TaskSubmission.php` (Lines 1–34)
- `/Users/mdm/Desktop/the-all-in-plan/app/Models/CourseEnrollment.php` (Lines 1–25)
- `/Users/mdm/Desktop/the-all-in-plan/app/Models/LessonProgress.php` (Lines 1–27)
- `/Users/mdm/Desktop/the-all-in-plan/app/Models/LessonPrerequisite.php` (Lines 1–23)

### Migrations:
- `/Users/mdm/Desktop/the-all-in-plan/database/migrations/2026_01_01_000007_create_academy_table.php` (Lines 1–72)
- `/Users/mdm/Desktop/the-all-in-plan/database/migrations/2026_04_01_000001_create_structured_learning_tables.php` (Lines 1–59)

---

## Unresolved Questions
- None at medium thoroughness level

---

**Generated:** 2026-04-01 | **Report ID:** Explore-260401-2028-academy-system
