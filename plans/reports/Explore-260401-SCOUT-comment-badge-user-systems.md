# Scout Report: Comment System, Badge System, User Level/Class System

**Date:** 2026-04-01 | **Thoroughness:** Very Thorough

---

## 1. COMMENT SYSTEM

### Comment Model & Database
- **Model:** `/Users/mdm/Desktop/the-all-in-plan/app/Models/Comment.php` (lines 1-21)
  - Fields: `post_id`, `user_id`, `parent_id`, `content`, `is_rune_winner`
  - Relationships: `user()`, `post()`, `parent()`, `replies()` (self-referential for nested replies)
  - Has polymorphic `likes()` relationship (MorphMany → Like model)
  - Uses SoftDeletes (supports restore)

- **Database Schema:** `/Users/mdm/Desktop/the-all-in-plan/database/migrations/2026_01_01_000002_create_posts_table.php` (lines 44-53)
  - Table: `comments`
  - Columns: id, post_id (FK), user_id (FK), parent_id (self-referential FK), content, is_rune_winner, timestamps, soft_deletes

### Comment Display Component
- **Blade Template:** `/Users/mdm/Desktop/the-all-in-plan/resources/views/livewire/post-card.blade.php` (lines 132-207)
  - Comments section toggled via `$showComments` state (line 133)
  - Displays top-level comments (line 156) with nested replies (lines 187-201)
  - Reply structure: parent comment in main box (background: #F7F5F3), nested replies indented with darker background (#F0EEE9)

### Comment Rendering Details
**Top-level Comments (lines 157-204):**
- Avatar (line 158)
- User name badge with class emoji (lines 162-164):
  - Uses: `badge badge-class-{{ $comment->user->class_color }}` (CSS class)
  - Shows: `{{ $comment->user->class_emoji }} {{ $comment->user->name }}`
- Rune winner badge (lines 166-168): Shows when `is_rune_winner` = true
- Timestamp (line 169): `diffForHumans()`
- Comment content (line 171)

**Actions Below Each Comment (lines 173-184):**
- Heart like button (lines 176-181)
  - Uses: `toggleCommentLike($commentId)` method
  - Shows: `❤️ {{ $comment->likes()->count() }}`
  - Only visible if NOT your own comment (line 175)
  - Logic in PostCard component (line 89-111)
- Reply button (line 182): Calls `replyTo($commentId, $name)`
  - Sets `$replyToId` and `$replyToName` for nested reply UI

**Nested Replies (lines 187-201):**
- Same structure but smaller sizing (smaller avatar, text)
- Indented in a margin-left: 1rem container
- No action buttons on nested replies currently

### Comment Creation Flow
- **Livewire Component:** `/Users/mdm/Desktop/the-all-in-plan/app/Livewire/PostCard.php` (lines 113-162)
  - Method: `addComment()` (lines 113-162)
  - Validation: max 2000 chars
  - Anti-spam: max 20 comments per hour per user
  - Rune mechanic: First commenter on rune-active post gets 2x EXP and `is_rune_winner = true`
  - XP awards:
    - Commenter: 1 XP (or 2x if rune winner)
    - Post owner: 1 XP per unique commenter (first comment only)
  - Notification: GenericNotification sent to post owner

### Comment Like System
- **Model:** `/Users/mdm/Desktop/the-all-in-plan/app/Models/Like.php` (lines 1-11)
  - Polymorphic: Works with Post, Comment, (and theoretically other models)
  - Fields: `likeable_type`, `likeable_id`, `user_id`
  - Unique constraint: `['likeable_type', 'likeable_id', 'user_id']`

- **Implementation in PostCard.php (lines 89-111):**
  - `toggleCommentLike($commentId)` method
  - Creates/deletes Like record
  - Awards 1 XP to comment owner (line 109)
  - Cannot like own comment (line 93)

### Existing Mechanisms (No Comment Edit/Delete Currently)
- **Post edit/delete:** Exists in post-card (lines 62-65, 177-208)
  - Edit requires auth + owner match (line 190)
  - Delete requires auth + (owner OR admin) (line 180)
- **Comment edit/delete:** NOT IMPLEMENTED — needs to be added

### Existing Report Mechanism (Post only)
- **Report Model:** `/Users/mdm/Desktop/the-all-in-plan/app/Models/Report.php` (lines 1-22)
  - Polymorphic: `reportable_type`, `reportable_id`
  - Fields: `user_id`, `reason`, `status` (enum: pending|reviewed|dismissed)
  - Designed to support any reportable entity (Post, Comment, User, etc.)

- **Post reporting:** Implemented in PostCard.php (lines 228-251)
  - `reportPost()` method
  - Duplicate check: prevents double-reporting same post
  - Creates Report with reason "Spam / Vi phạm"
  - Notification to admin system
- **Comment reporting:** NOT IMPLEMENTED — can extend same Report model

### 3-Dot Menu Pattern (Alpine.js)
- **Location:** post-card.blade.php (lines 56-71)
- **Pattern:** x-data with x-show dropdown
```blade
<div x-data="{ open: false }" class="relative">
  <button @click="open = !open">🔘</button>
  <div x-show="open" @click.away="open = false" x-transition>
    <!-- menu items -->
  </div>
</div>
```
- This pattern can be replicated for comment actions

---

## 2. BADGE SYSTEM

### Badge Model & Database
- **Model:** `/Users/mdm/Desktop/the-all-in-plan/app/Models/Badge.php` (lines 1-16)
  - Fields: `name`, `description`, `icon`, `rarity`, `condition_type`, `condition_value`
  - Relationship: `userBadges()` (HasMany)

- **Database Schema:** `/Users/mdm/Desktop/the-all-in-plan/database/migrations/2026_01_01_000004_create_gamification_table.php` (lines 59-75)
  - Table: `badges`
  - Enum: `rarity` (common, rare, epic, unique, legendary)
  - Fields: condition_type, condition_value (for earning logic)

- **User-Badge Junction:** `/Users/mdm/Desktop/the-all-in-plan/app/Models/UserBadge.php` (lines 1-25)
  - Fields: `user_id`, `badge_id`, `earned_at` (timestamp)
  - Relationships: `user()`, `badge()`
  - No timestamps (line 10)

### Badge Seeding & Initial Data
- **BadgeSeeder:** `/Users/mdm/Desktop/the-all-in-plan/database/seeders/BadgeSeeder.php` (lines 1-37)

**All Badges (28 total):**
1. "Tân binh" (Beginner) - icon: 🌱, rarity: common, condition: level_gte:1
2. "Cây bút" (First Post) - icon: ✍️, common, post_count_gte:1
3. "Nhà văn" (10 Posts) - icon: 📝, rare, post_count_gte:10
4. "Bình luận gia" (10 Comments) - icon: 💬, common, comment_count_gte:10
5. "Thảo luận viên" (50 Comments) - icon: 🗣️, rare, comment_count_gte:50
6. "Thuyền trưởng" (Create Expedition) - icon: ⚔️, rare, expedition_created:1
7. "Streak 7" - icon: 🔥, common, streak_gte:7
8. "Streak 30" - icon: 🔥🔥, rare, streak_gte:30
9. "Streak 90" - icon: 🔥🔥🔥, epic, streak_gte:90
10. "GD1 — Freelancer" - icon: ⭐, common, level_gte:10
11. "GD2 — Growing" - icon: ⭐⭐, rare, level_gte:30
12. "GD3 — Chuyên gia" - icon: ⭐⭐⭐, epic, level_gte:60
13. "GD4 — Business Owner" - icon: 👑, legendary, level_gte:100
14. "Collector" (50 Bookmarks) - icon: 📚, rare, bookmark_count_gte:50
15. "Đá Không Cực" (1 Da Stone) - icon: 💎, legendary, da_count_gte:1

### Badge Display Location
- **Profile Page:** `/Users/mdm/Desktop/the-all-in-plan/resources/views/livewire/profile-page.blade.php` (lines 65-83)
  - Section: "HUY HIỆU" (Badges)
  - Loads via: `$badges = $this->profileUser->userBadges()->with('badge')->get()` (line 55 of ProfilePage.php)
  - Displayed only if count > 0 (line 66)

### Badge Styling & Rarity Colors
- **CSS:** `/Users/mdm/Desktop/the-all-in-plan/resources/css/app.css` (no dedicated badge rarity styles visible in snippet)
- **Inline Styling in profile-page.blade.php (lines 71-77):**
```blade
{{ match($ub->badge->rarity) {
    'legendary' => 'background:#EDE9FE; color:#4C1D95; border:1px solid #7C3AED;',
    'epic'      => 'background:#EDE9FE; color:#5B21B6; border:1px solid #C4B5FD;',
    'rare'      => 'background:#DBEAFE; color:#1E40AF; border:1px solid #93C5FD;',
    default     => 'background:#F7F5F3; color:#5C5C66; border:1px solid #E1E1E1;',
} }}
```

**Color Mapping:**
- **Legendary:** Purple (dark) bg + purple text + purple border
- **Epic:** Purple (light) bg + dark purple text + light purple border
- **Rare:** Blue bg + dark blue text + light blue border
- **Common:** Light gray bg + gray text + gray border

### Badge Earning Logic
- **Service:** `/Users/mdm/Desktop/the-all-in-plan/app/Services/BadgeService.php` (not fully read but called in XpService line 76)
  - Called after every XP award
  - Checks conditions and creates UserBadge records

- **Example Condition Types:**
  - `level_gte` → checks user.level >= condition_value
  - `post_count_gte` → counts posts by user_id
  - `comment_count_gte` → counts comments by user_id
  - `streak_gte` → checks user.streak >= condition_value
  - `expedition_created` → checks if user created expeditions
  - `bookmark_count_gte` → counts bookmarks by user_id
  - `da_count_gte` → checks user.da_count (da_khong_cuc.total_count)

### Badge Styling for "Beginner" (Level < 10)
- **User Model:** `/Users/mdm/Desktop/the-all-in-plan/app/Models/User.php` (lines 128-139)
  - Method: `getClassLabelAttribute()` (line 128)
  - Returns: 'Beginner' when level < 10
  - Shows: '🌱' emoji
  - Color: 'gray'

- **"Tân binh" Badge:** First badge, earned at level 1, emoji: 🌱 (sprout - matches!)

---

## 3. USER LEVEL/CLASS SYSTEM

### User Model Fields
- **Model:** `/Users/mdm/Desktop/the-all-in-plan/app/Models/User.php` (lines 1-189)
  - Fields: `class`, `level`, `xp`, `aip`, `streak`, `class_changed_at`
  - Fillable (lines 17-21)
  - Casts (lines 25-35): class_changed_at to datetime

- **Database Schema:** `/Users/mdm/Desktop/the-all-in-plan/database/migrations/0001_01_01_000000_create_users_table.php` (lines 11-37)
  - `class` enum: offer_architect, traffic_mage, conversion_ranger, delivery_assassin, continuity_captain (nullable)
  - `level` unsignedSmallInteger, default 1
  - `xp` unsignedBigInteger, default 0
  - `aip` unsignedInteger, default 0
  - `streak` unsignedSmallInteger, default 0
  - `class_changed_at` timestamp nullable

### Class Selection & Onboarding Flow
- **Livewire Component:** `/Users/mdm/Desktop/the-all-in-plan/app/Livewire/Auth/ClassSelection.php` (lines 1-87)
  - State: `$selectedClass` (empty string initially)
  - Method: `selectClass($class)` (line 65)
  - Method: `confirm()` (lines 70-80)
    - Updates user: `$user->update(['class' => $this->selectedClass])`
    - Redirects to feed
    - No timestamp update to `class_changed_at` (potential issue?)

- **Blade Template:** `/Users/mdm/Desktop/the-all-in-plan/resources/views/livewire/auth/class-selection.blade.php` (lines 1-60)
  - Page title: "Chọn Class của bạn"
  - Description: "Class phản ánh chuyên môn cốt lõi. Không đổi được (trừ khi dùng AIP)."
  - Warning: "Chỉ đổi được 1 lần/năm với 1,000 AIP"
  - Shows 5 class cards in grid (lines 8-46)
  - Selected class has: checkmark icon, colored border/bg, shadow (lines 23-26)
  - Confirm button disabled if no selection (line 54)

### Class Definition & Styling
- **Classes Array in ClassSelection.php (lines 12-63):**

| Key | Emoji | Name | Pillar | Color | BG | Bonus |
|-----|-------|------|--------|-------|----|----|
| offer_architect | 🔥 | Offer Architect | Offer | #f59e0b (amber) | rgba(245,158,11,0.1) | +20% XP when reviewing offer |
| traffic_mage | ✨ | Traffic Mage | Thu hút (Traffic) | #8b5cf6 (purple) | rgba(139,92,246,0.1) | Posts get +20% reach priority |
| conversion_ranger | 🎯 | Conversion Ranger | Chuyển đổi (Conversion) | #10b981 (emerald) | rgba(16,185,129,0.1) | 5-star reviews → XP doubled |
| delivery_assassin | ⚙️ | Delivery Assassin | Cung ứng (Delivery) | #3b82f6 (blue) | rgba(59,130,246,0.1) | Complete Challenge on time → +30% XP |
| continuity_captain | 🔗 | Continuity Captain | Continuity | #ef4444 (red) | rgba(239,68,68,0.1) | Affiliate 25% + event 10 people → XP × 3 |

### Computed Attributes (User Model)
- **getClassLabelAttribute() (lines 128-139):**
  - If level < 10: returns 'Beginner'
  - Otherwise: matches class key to name (e.g., 'offer_architect' → 'Offer Architect')
  - Default: 'Beginner'

- **getClassColorAttribute() (lines 141-152):**
  - If level < 10: 'gray'
  - Otherwise: matches class → Tailwind color name
    - offer_architect → amber, traffic_mage → purple, conversion_ranger → emerald, delivery_assassin → blue, continuity_captain → red

- **getClassEmojiAttribute() (lines 154-165):**
  - If level < 10: '🌱' (sprout - matches Beginner badge!)
  - Otherwise: matches class emoji (🔥, ✨, 🎯, ⚙️, 🔗)

- **getJobStageAttribute() (lines 116-126):**
  - Based on level thresholds:
    - ≤ 10: 'Tân binh' (Beginner)
    - ≤ 30: 'Freelancer'
    - ≤ 60: 'Growing'
    - ≤ 100: 'Chuyên gia' (Expert)
    - ≤ 200: 'Business Owner'
    - > 200: 'Empire Builder'

### Level System & XP
- **XpService:** `/Users/mdm/Desktop/the-all-in-plan/app/Services/XpService.php`
  - Method: `award($user, $type, $multiplier, $description, $reference)` (lines 40-79)
  - Method: `checkLevelUp($user)` (lines 81-100)
    - Cumulative XP thresholds checked
    - Automatic level increment when XP threshold met
    - Notification sent on level up
    - Max level: 300

- **Streak System:**
  - Tracked in `user.streak` field
  - Used as XP multiplier:
    - 90+ days: 1.5x
    - 30-89 days: 1.2x
    - 7-29 days: 1.1x
    - 0-6 days: 1.0x

### Class Change Restrictions
- **Field:** `class_changed_at` timestamp (nullable)
- **Logic:** Not fully visible in read files, but UI hints:
  - Cannot change first time after selection (implicit)
  - Once per year (UI message: "Chỉ đổi được 1 lần/năm với 1,000 AIP")
  - Costs: 1,000 AIP
  - Likely handled in AipService or controller not yet read

### Display in Post/Comment Cards
- **Class Badge in post-card.blade.php:**
  - Line 41: `<span class="badge badge-class-{{ $post->user->class_color }}">{{ $post->user->class_emoji }} {{ $post->user->class_label }}</span>`
  - Line 164: Same for comments
  - CSS classes generated dynamically based on class value

- **CSS Classes in app.css (lines 91-97):**
```css
.badge-class-offer_architect    { background: #EDE9FE; color: #4C1D95; }
.badge-class-traffic_mage       { background: #EDE9FE; color: #5B21B6; }
.badge-class-conversion_ranger  { background: #D1FAE5; color: #065F46; }
.badge-class-delivery_assassin  { background: #DBEAFE; color: #1E40AF; }
.badge-class-continuity_captain { background: #FEE2E2; color: #991B1B; }
.badge-class-gray               { background: #F7F5F3; color: #5C5C66; border: 1px solid #E1E1E1; }
```

---

## KEY FINDINGS & UNRESOLVED QUESTIONS

### Existing Patterns
✅ Comment system fully functional with likes, replies, nested rendering
✅ Badge system with 15 seeded badges + rarity-based styling
✅ User level progression with auto-level-up logic
✅ Class selection at signup with computed attributes for display
✅ Polymorphic Like model supports Posts & Comments (extensible)
✅ Polymorphic Report model ready for Comments (not yet wired)
✅ Alpine.js 3-dot menu pattern already in use
✅ XP service with multipliers (streak, class bonuses)

### Not Yet Implemented
❌ Comment edit functionality
❌ Comment delete functionality  
❌ Comment reporting (Report model exists, just needs UI)
❌ Comment-specific action menu (can use existing 3-dot pattern)
❌ Badge display in other locations (currently profile-only)

### Questions for Clarification
1. **Class Change Logic:** Where is the 1,000 AIP cost enforced? Which file/method checks `class_changed_at` for the 1-year limit?
2. **Badge Earning Triggers:** When exactly are badges checked? (After every XP award? Via cron? Via BadgeService?)
3. **Comment Actions:** Should comment edit/delete/report appear in a 3-dot menu like posts, or inline buttons?
4. **Nested Reply Actions:** Should nested replies have edit/delete? Currently they don't show user actions at all.
5. **XP for Comments:** Should editing a comment also affect XP? Currently only creation awards XP.
6. **Badge Notifications:** Are users notified when they earn a badge? (Not visible in current code)
7. **Power Symbols:** How do they relate to class? Are they separate progression tracks?
8. **DaKhongCuc Stones:** What's the full mechanic? Visible in model but not core to comment/badge/class systems.

---

## FILE INDEX

### Models
- Comment: `/Users/mdm/Desktop/the-all-in-plan/app/Models/Comment.php`
- Badge: `/Users/mdm/Desktop/the-all-in-plan/app/Models/Badge.php`
- UserBadge: `/Users/mdm/Desktop/the-all-in-plan/app/Models/UserBadge.php`
- User: `/Users/mdm/Desktop/the-all-in-plan/app/Models/User.php`
- Like: `/Users/mdm/Desktop/the-all-in-plan/app/Models/Like.php`
- Report: `/Users/mdm/Desktop/the-all-in-plan/app/Models/Report.php`
- Post: `/Users/mdm/Desktop/the-all-in-plan/app/Models/Post.php`

### Livewire Components
- PostCard: `/Users/mdm/Desktop/the-all-in-plan/app/Livewire/PostCard.php`
- ClassSelection: `/Users/mdm/Desktop/the-all-in-plan/app/Livewire/Auth/ClassSelection.php`
- ProfilePage: `/Users/mdm/Desktop/the-all-in-plan/app/Livewire/ProfilePage.php`

### Blade Templates
- post-card: `/Users/mdm/Desktop/the-all-in-plan/resources/views/livewire/post-card.blade.php`
- class-selection: `/Users/mdm/Desktop/the-all-in-plan/resources/views/livewire/auth/class-selection.blade.php`
- profile-page: `/Users/mdm/Desktop/the-all-in-plan/resources/views/livewire/profile-page.blade.php`

### Services
- XpService: `/Users/mdm/Desktop/the-all-in-plan/app/Services/XpService.php`
- BadgeService: `/Users/mdm/Desktop/the-all-in-plan/app/Services/BadgeService.php`
- AipService: `/Users/mdm/Desktop/the-all-in-plan/app/Services/AipService.php`

### Migrations
- users: `/Users/mdm/Desktop/the-all-in-plan/database/migrations/0001_01_01_000000_create_users_table.php`
- posts/comments: `/Users/mdm/Desktop/the-all-in-plan/database/migrations/2026_01_01_000002_create_posts_table.php`
- gamification: `/Users/mdm/Desktop/the-all-in-plan/database/migrations/2026_01_01_000004_create_gamification_table.php`
- community: `/Users/mdm/Desktop/the-all-in-plan/database/migrations/2026_01_01_000006_create_community_features_table.php`
- reports: `/Users/mdm/Desktop/the-all-in-plan/database/migrations/2026_04_01_160000_create_reports_table.php`

### Seeders
- BadgeSeeder: `/Users/mdm/Desktop/the-all-in-plan/database/seeders/BadgeSeeder.php`

### Styles
- CSS: `/Users/mdm/Desktop/the-all-in-plan/resources/css/app.css`

