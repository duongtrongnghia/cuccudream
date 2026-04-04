# Phase 04: Scheduled Jobs

## Priority: MEDIUM
## Status: pending
## Depends on: Phase 03 (expedition lifecycle methods)

## Overview
Create Artisan commands + schedule for recurring tasks: leaderboard snapshots, pillar stats recalculation, community challenge progress, expedition auto-transitions, streak reset, kick inactive members.

## Key Files
- `app/Console/Commands/SnapshotLeaderboard.php` (CREATE)
- `app/Console/Commands/RecalcPillarStats.php` (CREATE)
- `app/Console/Commands/UpdateChallengeProgress.php` (CREATE)
- `app/Console/Commands/ProcessExpeditions.php` (CREATE)
- `app/Console/Commands/ResetStreaks.php` (CREATE)
- `routes/console.php` (MODIFY — register schedule)

## Implementation Steps

1. **SnapshotLeaderboard** (daily at midnight):
   - Calculate XP earned per user for current week/month
   - Upsert into `leaderboard_snapshots` with rank + rank_change

2. **RecalcPillarStats** (hourly):
   - Count posts per pillar in last 7 days
   - Calculate percentages, detect burning zone (lowest pillar)
   - Update `pillar_stats` table

3. **UpdateChallengeProgress** (every 15 min):
   - Query active challenge, count relevant actions since week_start
   - Update `current_value`, set `completed_at` if target reached

4. **ProcessExpeditions** (daily):
   - Active expeditions past `ends_at` → call `complete()` or `fail()`
   - Kick members with `consecutive_missed_days >= 3`
   - Increment `consecutive_missed_days` for members who didn't check in yesterday

5. **ResetStreaks** (daily at 1am):
   - Users whose `last_active_at` is older than 48 hours → reset streak to 0

6. Register all in `routes/console.php` via `Schedule`

## Success Criteria
- [ ] 5 commands created and registered
- [ ] `php artisan schedule:list` shows all jobs
- [ ] Each command runs without error
- [ ] Leaderboard snapshots populate
- [ ] Pillar stats recalculate
- [ ] Expired expeditions auto-transition
