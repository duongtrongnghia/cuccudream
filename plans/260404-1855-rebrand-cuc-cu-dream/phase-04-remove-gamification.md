# Phase 4: Remove Gamification Features

**Priority:** P1 | **Status:** Pending | **Effort:** 1h

## Overview
Remove expedition, rune, COT, signals, XP/AIP, Da Khong Cuc, power symbols, leaderboard — everything beyond simple post/comment/like/bookmark. Keep Topics (admin-managed tags).

## Features to Remove

### Expedition System
- **Models**: `Expedition.php`, `ExpeditionMember.php`, `ExpeditionCheckin.php` — delete
- **Components**: Find ExpeditionPage/ExpeditionDetail if they exist — delete
- **Views**: Any expedition blade views — delete

### Rune System
- **`app/Models/Post.php`**: Remove `rune_active`, `rune_expires_at`, `rune_first_comment_user_id` from fillable/casts, remove `isRuneActive()` method
- **Views**: Remove rune banner display from post-card

### COT System
- **`app/Models/Post.php`**: Remove `is_cot`, `cot_at`, `cot_by` from fillable/casts, remove `scopeCot`, `cotBy()` relationship
- **Components**: `app/Livewire/CotPage.php`, `app/Livewire/AdminCotReview.php` — delete
- **Views**: `cot-page.blade.php`, `admin-cot-review.blade.php` — delete

### Signals System
- **`app/Models/Post.php`**: Remove `is_signal` from fillable/casts, remove `scopeSignal`
- **Components**: `app/Livewire/SignalsPage.php` — delete
- **Views**: `signals-page.blade.php` — delete

### XP/AIP/Level System
- **Models**: `XpTransaction.php`, `AipTransaction.php` — delete
- **`app/Models/User.php`**: Remove `level`, `xp`, `aip`, `streak` from fillable. Remove `getJobStageAttribute`. Remove `xpTransactions()`, `aipTransactions()` relationships.
- **Services**: `app/Services/XpService.php` — delete
- **Components**: `app/Livewire/LeaderboardPage.php` — delete
- **Views**: `leaderboard-page.blade.php`, `sidebar-my-xp.blade.php` — delete
- **CSS**: Remove `.xp-bar`, `.xp-bar-fill`, `.level-badge` classes

### Da Khong Cuc / Power Symbols
- **Models**: `DaKhongCuc.php`, `DaKhongCucLog.php`, `PowerSymbol.php` — delete
- **`app/Models/User.php`**: Remove `daKhongCuc()`, `powerSymbols()` relationships, `getDaCountAttribute`
- **Services**: `app/Services/PowerSymbolService.php` — delete
- **CSS**: Remove `.da-gem` class

### Leaderboard
- **Models**: `LeaderboardSnapshot.php` — delete
- **Routes**: Remove `/leaderboard` route

### Challenge System
- **Components**: `app/Livewire/ChallengePage.php`, `app/Livewire/ChallengeDetail.php`, `app/Livewire/SidebarChallenge.php` — delete
- **Models**: `CommunityChallenge.php`, `ChallengeTask.php` — delete  
- **Views**: `challenge-page.blade.php`, `challenge-detail.blade.php`, `sidebar-challenge.blade.php`, `sidebar-challenges.blade.php` — delete

### Sidebar Widgets
- **Delete**: `SidebarBurningZone` component + view, `SidebarChallenge` + view

### Other
- **CSS**: Remove `.rune-banner`, `.cot-badge`, `.burning-indicator`, `.difficulty-*`, related animations
- **`config/exp_table.php`**: Delete if exists

## Todo
- [ ] P4-1: Delete expedition models and components
- [ ] P4-2: Clean Post model — remove rune, cot, signal fields
- [ ] P4-3: Delete CotPage, AdminCotReview, SignalsPage components + views
- [ ] P4-4: Delete XP/AIP models, XpService, leaderboard model/component/view
- [ ] P4-5: Delete DaKhongCuc, PowerSymbol models + services
- [ ] P4-6: Clean User model — remove xp/aip/level/streak fields and relationships
- [ ] P4-7: Delete challenge components, models, views
- [ ] P4-8: Delete sidebar widgets (BurningZone, Challenge, MyXp)
- [ ] P4-9: Remove gamification CSS from app.css
- [ ] P4-10: Create migration to drop gamification columns from users and posts

## Risk
- **PostCard.php (350 lines)** heavily references rune/cot/signal — needs careful editing, not deletion
- **Feed.php** tab logic references cot/signal — remove those tabs but keep latest/popular

## Success Criteria
- No references to expedition, rune, cot, signal, xp, aip, da_khong, power_symbol in app code
- Feed shows only latest/popular tabs
- Post creation is simple: title + content + topic + images
