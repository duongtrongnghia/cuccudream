# Phase 7: Route & Navigation Cleanup

**Priority:** P2 | **Status:** Pending | **Effort:** 30m  
**Depends on:** Phases 3, 4

## Overview
Remove routes for deleted features and clean up navigation sidebar.

## File: `routes/web.php`

### Remove routes
- `/onboarding` (ClassSelection) — phase 3
- `/cot` (CotPage)
- `/tin-hieu` (SignalsPage)
- `/leaderboard` (LeaderboardPage)
- `/challenge` and `/challenge/{id}`
- `/admin/cot-review` (AdminCotReview)

### Remove imports
- `use App\Livewire\Auth\ClassSelection`
- `use App\Livewire\CotPage`
- `use App\Livewire\SignalsPage`
- `use App\Livewire\LeaderboardPage`
- `use App\Livewire\ChallengePage`
- `use App\Livewire\ChallengeDetail`

### Keep routes
- `/feed`, `/hoi-dap`, `/khoa-hoc`, `/marketplace`, `/affiliate`, `/messages`, `/search`, `/@{username}`
- All admin routes except cot-review
- Auth routes (login, register, logout)
- Membership routes

## File: `resources/views/layouts/app.blade.php`
- Remove nav items for: CỐT, Tín hiệu, Leaderboard, Challenge/Expedition
- Keep nav items for: Feed, Hoi Dap, Khoa Hoc, Messages, Marketplace

## Todo
- [ ] P7-1: Remove deleted feature routes from web.php
- [ ] P7-2: Remove unused imports from web.php
- [ ] P7-3: Clean navigation sidebar in app.blade.php
- [ ] P7-4: Verify no broken links in remaining views

## Success Criteria
- `php artisan route:list` shows no routes for deleted features
- Navigation has no dead links
