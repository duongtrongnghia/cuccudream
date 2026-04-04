# Phase 01: Badge System

## Priority: HIGH
## Status: pending

## Overview
Seed badges, implement auto-awarding logic, display on profile.

## Key Files
- `app/Models/Badge.php` (exists, has fillable+relationships)
- `app/Models/UserBadge.php` (exists, has fillable+relationships)
- `app/Services/BadgeService.php` (CREATE)
- `database/seeders/BadgeSeeder.php` (CREATE)
- `resources/views/livewire/profile-page.blade.php` (MODIFY — add badges section)

## Implementation Steps

1. Create `BadgeSeeder` with ~15 badges:
   - Tân binh (first login), Cây bút (first post), Bình luận gia (10 comments)
   - Thợ săn CỐT (first CỐT nomination), Thuyền trưởng (create expedition)
   - Streak 7, Streak 30, Streak 90
   - Level 10, Level 30, Level 60, Level 100
   - Collector (50 bookmarks), Helper (10 answers), Đá Không Cực owner

2. Create `BadgeService` with:
   - `check(User $user): void` — evaluate all badge conditions for user
   - `award(User $user, Badge $badge): void` — create UserBadge if not exists
   - Call `check()` from XpService after awarding XP

3. Add badges display to ProfilePage view (grid of earned badges)

## Success Criteria
- [ ] 15 badges seeded
- [ ] BadgeService.check() evaluates conditions
- [ ] Badges display on profile
- [ ] Auto-awarded when conditions met
