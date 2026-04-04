# Phase 03: CỐT Nomination + Expedition Lifecycle

## Priority: HIGH
## Status: pending
## Depends on: Phase 02 (notifications)

## Overview
Implement CỐT nomination flow (GD3+ nominates → admin approves). Implement expedition state machine (open → active → completed/failed).

## Key Files
- `app/Livewire/PostCard.php` (MODIFY — real nominateCot logic)
- `app/Livewire/Feed.php` (MODIFY — show pending CỐT for admins)
- `app/Livewire/ExpeditionDetail.php` (MODIFY — start/complete expedition)
- `app/Models/Expedition.php` (MODIFY — status transition methods)
- `app/Livewire/AdminCotReview.php` (CREATE — admin CỐT approval page)
- `resources/views/livewire/admin-cot-review.blade.php` (CREATE)
- `routes/web.php` (MODIFY — add admin/cot route)

## Implementation Steps

### CỐT Nomination
1. `PostCard.nominateCot()`: Set `cot_by = auth()->id()`, `is_cot = false` (pending). Dispatch notification to admins.
2. Create `AdminCotReview` component: list pending nominations, approve/reject buttons.
3. On approve: set `is_cot = true`, `cot_at = now()`. Award XP to post author + nominator.
4. Add admin route: `/admin/cot` → AdminCotReview

### Expedition Lifecycle
1. Add methods to Expedition model:
   - `start()`: status open→active, set starts_at, set ends_at (starts_at + required_days)
   - `complete()`: status→completed, award XP to all active members + captain bonus
   - `fail()`: status→failed
2. ExpeditionDetail: captain can click "Bắt đầu" (when enough members), shows countdown
3. Auto-transitions handled by Phase 04 scheduled jobs

## Success Criteria
- [ ] GD3+ users can nominate posts for CỐT
- [ ] Admins see pending CỐT and approve/reject
- [ ] Approved CỐT posts get XP reward
- [ ] Expedition captain can start expedition
- [ ] Expedition shows time remaining
- [ ] Expedition model has start/complete/fail methods
