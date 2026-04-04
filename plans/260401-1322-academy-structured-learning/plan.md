# Academy: Structured Learning Paths

## Status: READY
## Priority: HIGH
## Estimated Phases: 4

## Problem
Academy hiện tại chỉ có Course→Module→Lesson đơn giản, click "Hoàn thành" là xong. Cần nâng cấp thành hệ thống học có cấu trúc như screenshot tham khảo (trangden.vn):

- Bài unlock tuần tự (phải hoàn thành bài trước)
- Mỗi bài có nhiều tasks/câu hỏi tuần tự
- Student nộp bài (text/link/screenshot)
- Practice section với mini-challenges
- Progress tracking chi tiết
- "Bài làm của cả lớp" — xem submissions của nhau

## Data Model Changes

### Existing (keep as-is)
```
courses → modules → lessons → lesson_progress
course_enrollments
```

### New Tables
```
lesson_tasks          — Sub-tasks/câu hỏi trong mỗi lesson
  id, lesson_id, title, description, type (text|link|file|quiz),
  order_index, is_required

task_submissions      — Bài nộp của student
  id, lesson_task_id, user_id, content (text/link),
  file_url (nullable), status (pending|approved|rejected),
  reviewed_by (nullable FK→users), reviewed_at, submitted_at

lesson_prerequisites  — Unlock logic
  lesson_id, required_lesson_id
  (lesson chỉ unlock khi required_lesson completed)
```

### Modified Tables
```
lessons — ADD columns:
  type ENUM('lecture','practice') DEFAULT 'lecture'
  is_locked_by_default BOOLEAN DEFAULT true
```

## Architecture

```
Course: "Agent SEE K01"
├── Module: "Tuần 1 — Nền tảng tư duy AI"
│   ├── Lesson 1: "Tập ra lệnh cho Coding Agent" [lecture, unlocked]
│   │   ├── Task 1: "Yêu cầu bài số 1 là gì?" [text submission]
│   │   ├── Task 2: "Agent đã sử dụng công nghệ gì?" [text]
│   │   └── Task 3: "Tính năng chụp hình..." [text, locked until task 2 done]
│   ├── Lesson 2: "Tích luỹ kiến thức..." [locked until lesson 1 complete]
│   │   └── Tasks...
│   ├── Lesson 5: "Vòng lặp Agent" [locked until lesson 4]
│   │   └── "Nhiệm vụ chưa mở" message
│   └── Practice: "Thực hành cộng tác" [practice type, locked until lesson 5]
│       ├── Challenge 1: "Làm web tặng bạn bè" [link submission]
│       ├── Challenge 2: "Bí mật trong Prompt" [text]
│       └── ... (7 challenges)
└── Module: "Tuần 2 — ..."
```

## Phase Overview

| Phase | Description | Status |
|-------|-------------|--------|
| 01 | Database: migration + models (lesson_tasks, task_submissions, lesson_prerequisites) | pending |
| 02 | Backend: prerequisite unlock logic + submission CRUD | pending |
| 03 | Frontend: AcademyDetail UI upgrade (lock/unlock, tasks, submissions) | pending |
| 04 | Admin: course builder + seed sample structured course | pending |

## Dependencies
- Builds on existing Academy (Course/Module/Lesson models)
- Related to comprehensive plan phase-01 (badges — "Hoàn thành khóa học" badge)

## Cook Command
```
/ck:cook plans/260401-1322-academy-structured-learning/plan.md --auto
```
