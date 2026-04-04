# Phase 07: Test Suite

## Priority: HIGH
## Status: pending
## Depends on: All previous phases

## Overview
Write unit tests for services, feature tests for auth/middleware/components. Target: core business logic covered.

## Key Files
- `tests/Unit/XpServiceTest.php` (CREATE)
- `tests/Unit/BadgeServiceTest.php` (CREATE)
- `tests/Unit/AipServiceTest.php` (CREATE)
- `tests/Feature/AuthTest.php` (CREATE)
- `tests/Feature/MembershipMiddlewareTest.php` (CREATE)
- `tests/Feature/PostTest.php` (CREATE)
- `tests/Feature/ExpeditionTest.php` (CREATE)
- `tests/Feature/LeaderboardTest.php` (CREATE)
- `tests/Feature/AcademyTest.php` (CREATE)
- `database/factories/PostFactory.php` (CREATE)
- `database/factories/ExpeditionFactory.php` (CREATE)
- `database/factories/CourseFactory.php` (CREATE)

## Implementation Steps

### Unit Tests
1. **XpServiceTest**: award calculates correctly, streak multipliers, level-up thresholds, unknown type throws
2. **BadgeServiceTest**: conditions evaluate correctly, no duplicate awards
3. **AipServiceTest**: earn/spend balance, insufficient funds throws

### Feature Tests
4. **AuthTest**: register creates user + trial membership + referral, login redirects, logout invalidates
5. **MembershipMiddlewareTest**: active passes, expired redirects, banned logs out, no class → onboarding
6. **PostTest**: create post awards XP, like/unlike toggles, bookmark, comment + rune XP
7. **ExpeditionTest**: create, join, duplicate join blocked, max members blocked, check-in awards XP
8. **LeaderboardTest**: week/month filter returns different data, DA tab joins correctly
9. **AcademyTest**: enroll, complete lesson awards XP, complete course awards bonus

### Factories
10. Create PostFactory, ExpeditionFactory, CourseFactory with realistic defaults

## Success Criteria
- [ ] `php artisan test` passes all tests
- [ ] Unit tests cover XpService, BadgeService, AipService
- [ ] Feature tests cover auth, middleware, post, expedition, leaderboard, academy
- [ ] Factories exist for Post, Expedition, Course
