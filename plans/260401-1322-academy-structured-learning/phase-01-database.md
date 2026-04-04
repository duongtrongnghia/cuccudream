# Phase 01: Database — Migration + Models

## Priority: HIGH
## Status: pending

## Overview
Create 3 new tables + modify lessons table. Create models with fillable, casts, relationships.

## New Migration: `2026_04_01_000001_create_structured_learning_tables.php`

### lesson_tasks
```php
Schema::create('lesson_tasks', function (Blueprint $table) {
    $table->id();
    $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
    $table->string('title');
    $table->text('description')->nullable();
    $table->enum('type', ['text', 'link', 'file', 'quiz'])->default('text');
    $table->unsignedTinyInteger('order_index')->default(0);
    $table->boolean('is_required')->default(true);
    $table->timestamps();
});
```

### task_submissions
```php
Schema::create('task_submissions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('lesson_task_id')->constrained('lesson_tasks')->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->text('content')->nullable();
    $table->string('file_url')->nullable();
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('reviewed_at')->nullable();
    $table->timestamp('submitted_at');
    $table->timestamps();
    $table->unique(['lesson_task_id', 'user_id']);
});
```

### lesson_prerequisites
```php
Schema::create('lesson_prerequisites', function (Blueprint $table) {
    $table->id();
    $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
    $table->foreignId('required_lesson_id')->constrained('lessons')->cascadeOnDelete();
    $table->unique(['lesson_id', 'required_lesson_id']);
});
```

### Modify lessons table
```php
Schema::table('lessons', function (Blueprint $table) {
    $table->enum('type', ['lecture', 'practice'])->default('lecture')->after('title');
    $table->boolean('is_locked_by_default')->default(true)->after('order_index');
});
```

## New Models

### LessonTask
- fillable: lesson_id, title, description, type, order_index, is_required
- Relations: lesson(), submissions()

### TaskSubmission
- fillable: lesson_task_id, user_id, content, file_url, status, reviewed_by, reviewed_at, submitted_at
- Casts: reviewed_at→datetime, submitted_at→datetime
- Relations: task(), user(), reviewer()

### LessonPrerequisite
- fillable: lesson_id, required_lesson_id
- Relations: lesson(), requiredLesson()

### Modify Lesson model
- Add to fillable: type, is_locked_by_default
- Add relations: tasks(), prerequisites()
- Add method: `isUnlockedFor(User $user): bool`

## Success Criteria
- [ ] Migration runs without error on PostgreSQL
- [ ] All 3 new models have fillable + relationships
- [ ] Lesson model updated with new fields + unlock method
