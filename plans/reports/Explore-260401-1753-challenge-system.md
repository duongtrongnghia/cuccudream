# Challenge/Expedition System Scout Report
**Generated:** April 1, 2026 | **Thoroughness:** Very Thorough
**Report Location:** `/Users/mdm/Desktop/the-all-in-plan/plans/reports/Explore-260401-1753-challenge-system.md`

---

## 1. CHALLENGE DETAIL COMPONENT & VIEW

### **Livewire Component: ChallengeDetail.php**
**File Path:** `/Users/mdm/Desktop/the-all-in-plan/app/Livewire/ChallengeDetail.php` (206 lines)

#### Mount Method (Lines 24-27)
- Loads expedition with creator, members, and tasks relations
- Single entry point: `Expedition::with(['creator', 'members.user', 'tasks'])->findOrFail($id)`

#### Key Public Methods:

**`checkin()` (Lines 29-68)**
- Validates check-in content (5-1000 chars, via #[Rule] attribute at line 22)
- Checks user is authenticated and active member (no kicked_at)
- Prevents multiple check-ins per day
- Creates `ExpeditionCheckin` record with content
- Updates member's `last_checkin_at` and resets `consecutive_missed_days` to 0
- Awards XP via `XpService::award()` with expedition bonus multiplier
- Resets form and refreshes expedition

**`completeTask(int $taskId)` (Lines 70-102)**
- Validates user is authenticated member
- Checks if task is unlocked (day_number <= currentDay)
- Prevents duplicate completions
- Attaches user to task via `challenge_task_completions` pivot table
- Awards XP as "expedition_checkin" type (with 1.0 multiplier)
- Dispatches toast success message

**`joinExpedition()` (Lines 104-137)**
- Validates user not already a member
- Enforces max 2 concurrent "open" or "active" expeditions per user
- Checks expedition hasn't reached max_members cap
- Creates `ExpeditionMember` record with `class_at_join` and `joined_at`
- Notifies expedition creator if joiner is not the creator
- Uses `GenericNotification` with emoji and expedition URL

**`startExpedition()` (Lines 139-152)**
- Only creator can start (auth check + created_by match)
- Only works if status = 'open'
- Requires minimum 2 active members
- Calls `Expedition::start()` method (see Models section)
- Refreshes and toasts success

**`getCurrentDay()` (Lines 154-161)**
- Returns 1 if expedition not started (starts_at is null)
- Calculates days elapsed: `(starts_at.startOfDay).diffInDays(now.startOfDay) + 1`
- Caps at `required_days` to prevent overflow
- **Used to unlock daily tasks progressively**

#### Render Method (Lines 163-205)
**Data Passed to View:**
- `challenge`: Full expedition object
- `members`: Active members (not kicked) with user relations
- `checkins`: Paginated (20 per page) latest check-ins with user relations
- `isMember`: Boolean - current user is active member
- `checkedInToday`: Boolean - current user checked in today
- `tasks`: All tasks ordered by day_number
- `completedTaskIds`: Array of task IDs user has completed (from `challenge_task_completions` pivot)
- `completedTaskCount`: Count of completed tasks for progress bar
- `currentDay`: Output from `getCurrentDay()`

---

### **Blade View: challenge-detail.blade.php**
**File Path:** `/Users/mdm/Desktop/the-all-in-plan/resources/views/livewire/challenge-detail.blade.php` (189 lines)

#### Header Section (Lines 1-73)
- **Back button** (Line 3): Routes to `challenge` (listing page)
- **Title & Metadata** (Lines 9-14):
  - Displays `$expedition->title`
  - Shows difficulty badge (color-coded: normal/emerald, hard/amber, chaos/red)
  - Shows status badge (pending_approval, open, active, completed, failed, cancelled)
- **Boss Goal** (Lines 17-20): Highlighted box with emoji + `$expedition->boss_name`
- **Stats Row** (Lines 26-52):
  - Leader: Avatar + link to creator profile
  - Members: `activeMembersCount() / max_members`
  - Duration: `required_days` in days
  - Class Diversity: `uniqueClassCount() / 5` (shows EXP multiplier: 1.5x for ≥5 classes, 1.2x for ≥3)
  - AIP Deposit: Shown only if `deposit_aip > 0` (red text)
- **Action Buttons** (Lines 54-64):
  - "Tham gia Challenge" - Shows if `status === 'open' && !isMember` (authenticated users only)
  - "Bắt đầu Challenge" (gold btn) - Shows if `status === 'open' && auth.id === created_by`
- **Countdown Timer** (Lines 66-72): If active, shows days remaining and end date

#### Daily Tasks Section (Lines 75-120)
**Only renders if `$tasks->count() > 0`**
- **Progress Bar** (Lines 84-86): 
  - Purple bar width = `round(completedTaskCount / total * 100)%`
  - Dynamic calculation via percentage
- **Task List** (Lines 88-118):
  - For each task, checks:
    - `$isUnlocked = $task->day_number <= $currentDay`
    - `$isCompleted = in_array($task->id, $completedTaskIds)`
  - **States:**
    - ✅ Completed: Green checkmark (line 96)
    - 🔓 Unlocked + Member: Clickable checkbox (line 98) → `wire:click="completeTask({{ $task->id }})"`
    - 🔒 Locked: Lock icon (line 100)
    - Non-member unlocked: Disabled checkbox (line 102)
  - **Display Elements:**
    - Day badge: "Ngày N" in purple pill
    - Status: "Hoàn thành" in green (if completed)
    - Title: Bold, dark gray
    - Description: Only shown if unlocked, smaller text

#### Members Section (Lines 122-150)
- Lists all `$members` with active filter (kicked_at is null)
- Displays avatar, name, class badge with emoji, "Leader" label if creator
- Shows last check-in time via `$member->last_checkin_at->diffForHumans()` or "Chưa check-in"

#### Check-in Form (Lines 152-169)
**Only rendered if `$isMember && status in ['open', 'active']`**
- Shows success message if already checked in today
- Otherwise: 
  - Textarea with auto-height (x-data script for resize)
  - 5-1000 char validation (errors displayed in red)
  - "Check-in" button → `wire:click="checkin"`

#### Check-in History (Lines 171-187)
- Paginated feed (default 20 per page)
- For each check-in: Avatar + name link + time ago + content
- Empty state message

---

## 2. CHALLENGE LISTING PAGE

### **Livewire Component: ChallengePage.php**
**File Path:** `/Users/mdm/Desktop/the-all-in-plan/app/Livewire/ChallengePage.php` (75 lines)

#### Properties & Rules (Lines 14-23)
- `#[Url] public string $filter = 'open'` - Persists in URL (open/active/completed/all)
- `#[Url] public string $difficulty = ''` - Persists in URL (normal/hard/chaos/"")
- `bool $showCreate = false` - Modal toggle
- **Create form validation:**
  - `title`: required, 5-100 chars
  - `boss_name`: required, 10-500 chars
  - `description`: optional, max 1000 chars
  - `difficulty_create`: required, in (normal/hard/chaos)
  - `required_days`: required, in (21/30)
  - `max_members`: required, 5-10 range

#### Methods:

**`createChallenge()` (Lines 27-43)**
- **Access:** Level >= 30 OR da_count > 0 (special item)
- **Status & Deposit Logic:**
  - chaos → status='pending_approval', deposit_aip=300
  - normal/hard → status='open', deposit_aip=0
- Creates expedition with all form fields + timestamps
- Resets all form fields

**`joinChallenge(int $id)` (Lines 45-65)**
- Same validation as `ChallengeDetail::joinExpedition()` (duplicate logic)
- No messaging here (uses parent or redirects)

**`render()` (Lines 67-73)**
- Queries with `['creator','members']` relations and member count
- Applies filter (if not 'all')
- Applies difficulty filter (if set)
- Paginates 9 per page, sorted latest first

---

### **Blade View: challenge-page.blade.php**
**File Path:** `/Users/mdm/Desktop/the-all-in-plan/resources/views/livewire/challenge-page.blade.php` (137 lines)

#### Header (Lines 1-12)
- Title: "🏆 Challenge"
- Subtitle: "Thử thách thực chiến · Cùng nhau chinh phục mục tiêu"
- "+ Tạo Challenge" button (auth + level check)

#### Create Modal (Lines 14-57)
- Grid form with fields: title, boss_name, difficulty, duration, max_members
- Textarea for description
- "Chaos" option shows deposit warning (300 AIP)
- Cancel / Create buttons

#### Filter Tabs (Lines 59-70)
- **Status tabs:** Đang mở / Đang chạy / Hoàn thành / Tất cả
- **Difficulty badges:** Tất cả / Normal / Hard / Chaos (toggle, opacity 0.5 if inactive)

#### Challenge Grid (Lines 72-135)
- 3-column grid, 280px min width
- For each challenge card:
  - Title + difficulty badge
  - Boss goal in highlighted box
  - Description (truncated to 80 chars)
  - Stats: Member count, duration, class diversity (if > 0), AIP deposit (if > 0)
  - Creator avatar + name
  - "Chi tiết" (details) button + "Tham gia" (join) button (if status='open')
- Empty state: 🏆 emoji + "Không có challenge nào"

---

## 3. MODELS

### **Expedition Model**
**File Path:** `/Users/mdm/Desktop/the-all-in-plan/app/Models/Expedition.php` (52 lines)

**Fillable Fields:**
- `title`, `description`, `boss_name`
- `difficulty` (enum: normal/hard/chaos)
- `required_days`, `max_members` (unsigned tiny ints)
- `created_by` (foreign key → User)
- `status` (enum: pending_approval/open/active/completed/failed/cancelled)
- `deposit_aip` (unsigned int, default 0)
- `starts_at`, `ends_at` (nullable timestamps)

**Relationships:**
- `creator()`: BelongsTo User (via created_by)
- `members()`: HasMany ExpeditionMember
- `checkins()`: HasMany ExpeditionCheckin
- `tasks()`: HasMany ChallengeTask

**Methods:**
- `activeMembersCount()`: Count members where kicked_at is null
- `uniqueClassCount()`: Distinct class_at_join count (for XP bonus calculation)
- `getXpBonusMultiplier()`: Returns 1.5 (≥5 classes), 1.2 (≥3 classes), 1.0 (default)
- `getDifficultyLabelAttribute()`: Returns "Normal"/"Hard"/"Chaos" string
- `getDifficultyColorAttribute()`: Returns "emerald"/"amber"/"red" for Tailwind
- `start()`: Updates status → 'active', sets starts_at & ends_at (+ required_days)
- `complete()`: Updates status → 'completed' (only if currently 'active')
- `fail()`: Updates status → 'failed' (only if currently 'active')

---

### **ExpeditionMember Model**
**File Path:** `/Users/mdm/Desktop/the-all-in-plan/app/Models/ExpeditionMember.php` (15 lines)

**No timestamps**

**Fillable Fields:**
- `expedition_id`, `user_id` (foreign keys)
- `class_at_join` (snapshot of user's class at join time)
- `joined_at` (timestamp)
- `completed_at`, `kicked_at`, `last_checkin_at` (nullable timestamps)
- `consecutive_missed_days` (unsigned tiny int, default 0)
- `revenue_share_pct` (decimal 5,2, default 0) **[for future revenue splits]**

**Unique Constraint:** (expedition_id, user_id)

**Relationships:**
- `expedition()`: BelongsTo
- `user()`: BelongsTo

---

### **ExpeditionCheckin Model**
**File Path:** `/Users/mdm/Desktop/the-all-in-plan/app/Models/ExpeditionCheckin.php` (10 lines)

**Fillable Fields:**
- `expedition_id`, `user_id` (foreign keys)
- `content` (text - the check-in message)
- `created_at`, `updated_at` (timestamps)

**Relationships:**
- `expedition()`: BelongsTo
- `user()`: BelongsTo

---

### **ChallengeTask Model**
**File Path:** `/Users/mdm/Desktop/the-all-in-plan/app/Models/ChallengeTask.php` (23 lines)

**Fillable Fields:**
- `expedition_id` (foreign key)
- `day_number` (integer - which day task unlocks)
- `title`, `description` (text, nullable)
- Timestamps

**Unique Constraint:** (expedition_id, day_number)

**Relationships:**
- `expedition()`: BelongsTo
- `completedByUsers()`: BelongsToMany User via `challenge_task_completions` pivot table (with timestamps)

---

### **CommunityChallenge Model** (Different System)
**File Path:** `/Users/mdm/Desktop/the-all-in-plan/app/Models/CommunityChallenge.php` (13 lines)

**Purpose:** Weekly community-wide challenges (not per-expedition)

**Fields:**
- `title`, `description`
- `target_type` (post_count/comment_count/expedition_checkin)
- `target_value`, `current_value` (integers)
- `reward_xp`, `reward_aip` (unsigned ints)
- `week_start`, `week_end` (dates)
- `completed_at` (nullable datetime)

**Methods:**
- `getProgressPctAttribute()`: Returns min(100, round(current/target * 100, 1))
- `isCompleted()`: Checks if completed_at is not null

---

## 4. DATABASE MIGRATIONS

### **Expeditions/Members/Checkins Table**
**File Path:** `/Users/mdm/Desktop/the-all-in-plan/database/migrations/2026_01_01_000005_create_expedition_table.php` (58 lines)

**expeditions Table:**
- id (primary)
- title, description, boss_name (strings/text)
- difficulty enum (normal/hard/chaos, default normal)
- required_days, max_members (unsigned tinyint, defaults 21/10)
- created_by (foreign → users, cascade delete)
- status enum (pending_approval/open/active/completed/failed/cancelled, default open)
- deposit_aip (unsigned int, default 0)
- starts_at, ends_at (nullable timestamps)
- created_at, updated_at (timestamps)

**expedition_members Table:**
- id (primary)
- expedition_id, user_id (foreign keys, cascade delete)
- class_at_join (string, nullable)
- joined_at (timestamp)
- completed_at, kicked_at, last_checkin_at (nullable timestamps)
- consecutive_missed_days (unsigned tinyint, default 0)
- revenue_share_pct (decimal 5,2, default 0)
- **Unique constraint:** (expedition_id, user_id)

**expedition_checkins Table:**
- id (primary)
- expedition_id, user_id (foreign keys, cascade delete)
- content (text)
- created_at, updated_at (timestamps)

---

### **Challenge Tasks Table**
**File Path:** `/Users/mdm/Desktop/the-all-in-plan/database/migrations/2026_04_01_170000_create_challenge_tasks_table.php` (30 lines)

**challenge_tasks Table:**
- id (primary)
- expedition_id (foreign → expeditions, cascade delete)
- day_number (integer)
- title (string)
- description (text, nullable)
- created_at, updated_at (timestamps)
- **Unique constraint:** (expedition_id, day_number)

**challenge_task_completions Table:**
- id (primary)
- challenge_task_id (foreign → challenge_tasks, cascade delete)
- user_id (foreign → users, cascade delete)
- created_at, updated_at (timestamps)
- **Unique constraint:** (challenge_task_id, user_id)

---

## 5. ROUTES

**File Path:** `/Users/mdm/Desktop/the-all-in-plan/routes/web.php` (Lines 67-68)

```php
Route::get('/challenge',           ChallengePage::class)->name('challenge');
Route::get('/challenge/{id}',    ChallengeDetail::class)->name('challenge.show');
```

**Middleware:** `RequireActiveMembership` (requires active member subscription)

---

## 6. CONSOLE COMMANDS

### **ProcessChallenges**
**File Path:** `/Users/mdm/Desktop/the-all-in-plan/app/Console/Commands/ProcessChallenges.php` (66 lines)

**Signature:** `aip:process-challenges` | **Schedule:** Daily at 02:00 AM (via routes/console.php)

**Logic:**
1. **Find expired expeditions** (status='active', ends_at < now())
2. **For each expired:**
   - Check if all active members have < 3 consecutive missed days
   - If yes: Mark as 'completed', award each member XP (with bonus multiplier), mark completed_at
   - If no: Mark as 'failed'
3. **Kick inactive members** (3+ consecutive missed days):
   - Check each active member's check-ins for yesterday
   - If no check-in: increment `consecutive_missed_days`
   - If check-in: reset to 0
   - If >= 3: set kicked_at, notify user

---

### **UpdateChallengeProgress**
**File Path:** `/Users/mdm/Desktop/the-all-in-plan/app/Console/Commands/UpdateChallengeProgress.php` (46 lines)

**Signature:** `aip:update-challenge` | **Schedule:** Every 15 minutes (via routes/console.php)

**Purpose:** Updates `CommunityChallenge` (weekly community challenges, NOT per-expedition)

---

## 7. SIDEBAR COMPONENTS

### **SidebarChallenge.php**
**File Path:** `/Users/mdm/Desktop/the-all-in-plan/app/Livewire/SidebarChallenge.php` (12 lines)

**Purpose:** Display current week's community challenge widget
- Loads active CommunityChallenge (completed_at is null, week_end >= today)
- Shows progress bar, target, reward XP

**View:** `/Users/mdm/Desktop/the-all-in-plan/resources/views/livewire/sidebar-challenge.blade.php` (19 lines)

---

### **SidebarChallenges.php**
**File Path:** `/Users/mdm/Desktop/the-all-in-plan/app/Livewire/SidebarChallenges.php` (10 lines)

**Purpose:** Show 3 latest open expeditions in sidebar
- Queries `status='open'`, orders latest, takes 3

**View:** `/Users/mdm/Desktop/the-all-in-plan/resources/views/livewire/sidebar-challenges.blade.php` (18 lines)
- Displays challenge cards with link to details page

---

## 8. XP REWARD SYSTEM

**File Path:** `/Users/mdm/Desktop/the-all-in-plan/app/Services/XpService.php` (Lines 31-33)

**Expedition-related XP types:**
- `expedition_checkin`: 5 XP base (+ bonus multiplier from class diversity)
- `expedition_complete`: 100 XP base (+ bonus multiplier)
- `expedition_captain`: 200 XP base (awarded to creator)

**Award method signature:**
```php
award(User $user, string $type, float $multiplier, string $description, Model $model = null)
```

---

## 9. USER MODEL INTEGRATION

**File Path:** `/Users/mdm/Desktop/the-all-in-plan/app/Models/User.php` (Line 89-92)

```php
public function expeditionMembers(): HasMany {
    return $this->hasMany(ExpeditionMember::class);
}
```

---

## 10. SEEDER: AI Agent Challenge

**File Path:** `/Users/mdm/Desktop/the-all-in-plan/database/seeders/AiAgentChallengeSeeder.php` (76 lines)

**Creates:**
- 1 Expedition: "AI Agent Challenge 21 Day"
  - Status: active (immediately starts_at = now())
  - Difficulty: normal, 21 days, 999 max members, no deposit
  - Auto-joins admin user as member
- 21 ChallengeTask records (one per day)
  - Each day has title, description about AI agent concepts
  - Days 7 and 14 are review days, day 21 is demo day

---

## 11. PAYMENT/SEPAY INTEGRATION

**Status:** ❌ NO INTEGRATION FOUND

- No SePay SDK imports in codebase
- No payment-related files in app/Services or app/Http/Controllers
- `deposit_aip` field exists in Expedition model (Chaos difficulty: 300 AIP)
- BUT: No code to actually charge/validate the deposit
- **This is a PLACEHOLDER for future implementation**

---

## 12. ADMIN PAGES

**Status:** ❌ NO ADMIN CHALLENGE MANAGEMENT PAGE

**Current Admin Pages:**
- `/admin` → AdminDashboard
- `/admin/topics` → AdminTopics
- `/admin/courses` → AdminCourses
- `/admin/courses/{id}/build` → AdminCourseBuilder
- `/admin/cot-review` → AdminCotReview
- `/admin/reports` → AdminReports
- `/admin/users` → AdminUsers

**No admin page for approving chaos difficulty challenges or managing expeditions**

---

## 13. KEY FEATURES SUMMARY

| Feature | Status | Details |
|---------|--------|---------|
| Challenge Creation | ✅ Complete | Level 30+ or with da_count > 0 |
| Join Challenge | ✅ Complete | Max 2 concurrent per user, max_members cap enforced |
| Daily Check-in | ✅ Complete | One per day, with content, consecutive miss tracking |
| Daily Tasks | ✅ Complete | Day-locked, progress tracked, XP awarded |
| XP Bonus (Diversity) | ✅ Complete | 1.5x (≥5 classes), 1.2x (≥3 classes) |
| Auto-complete/fail | ✅ Complete | Scheduled daily, based on consecutive miss threshold |
| Member Kicking | ✅ Complete | 3+ consecutive missed days → kicked_at set, notified |
| Revenue Share Field | ✅ Schema Only | `revenue_share_pct` column exists, not used yet |
| Payment/Deposit | ❌ Planned | Chaos difficulty shows 300 AIP, no validation |
| Admin Approval | ❌ Planned | Chaos status = 'pending_approval', no admin interface |
| Leaderboards | ❌ Not Found | No completion rankings or stats tracked |

---

## 14. UNRESOLVED QUESTIONS

1. **Deposit handling:** How is the 300 AIP deposit for Chaos difficulty actually collected? Currently no payment integration.
2. **Chaos approval workflow:** Who approves pending_approval challenges? Is there an admin interface planned?
3. **Revenue share:** `revenue_share_pct` field in ExpeditionMember exists but never populated. Is this for captain-to-member splits?
4. **Multiple expeditions:** Max 2 concurrent per user—is this a hard business rule or configurable?
5. **Task unlocking:** Currently day-based only—should tasks have prerequisites or alternative unlock conditions?
6. **Leaderboards:** No per-challenge completion stats or rankings visible. Are these planned?
7. **Badge/achievement system:** Seeder mentions "badge đặc biệt" but no Badge model referenced in challenge code.
8. **Notification broadcast:** Check-in history is paginated—should it be real-time/WebSocket?

