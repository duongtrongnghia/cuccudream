# Rebrand Scope Exploration — The All In Plan

**Date:** April 4, 2026  
**Project:** The All In Plan (Vietnamese Marketer Community)  
**Stack:** Laravel 12 + Livewire 3 + Alpine.js + Tailwind CSS v4 + PostgreSQL  

---

## 1. Brand Name References: "The All In Plan"

### 1.1 Layout & Meta Tags

| File | Line | Content |
|------|------|---------|
| `/resources/views/layouts/app.blade.php` | 10 | `<title>{{ $title ?? 'The All In Plan™' }}</title>` |
| `/resources/views/layouts/app.blade.php` | 11 | `<meta name="description" content="The All In Plan™ — Công Thức Kiếm Tiền">` |
| `/resources/views/layouts/app.blade.php` | 24-26 | Logo HTML: `THE ALL IN` (black) + `PLAN` (green #2E7D32) + `™` (gray) |
| `/resources/views/layouts/guest.blade.php` | 7 | `<title>{{ $title ?? 'The All In Plan™' }}</title>` |
| `/resources/views/layouts/guest.blade.php` | 17-19 | Logo: `THE ALL IN PLAN™` with inline color styling |

### 1.2 Livewire Component Page Titles

**Pattern:** All pages use consistent layout title pattern `page_name — The All In Plan™`

| File | Lines | Title |
|------|-------|-------|
| `/app/Livewire/Feed.php` | 84 | `'Bảng tin — The All In Plan™'` |
| `/app/Livewire/CotPage.php` | 25 | `'CỐT — The All In Plan™'` |
| `/app/Livewire/LeaderboardPage.php` | 41 | `'Leaderboard — The All In Plan™'` |
| `/app/Livewire/ChallengePage.php` | 17 | `'Challenge — The All In Plan™'` |
| `/app/Livewire/AcademyPage.php` | 39 | `'Khóa học — The All In Plan™'` |
| `/app/Livewire/Auth/RegisterForm.php` | 81 | `'Đăng ký — The All In Plan™'` |
| `/app/Livewire/Auth/LoginForm.php` | 57 | `'Đăng nhập — The All In Plan™'` |
| `/app/Livewire/Auth/ClassSelection.php` | 85 | `'Chọn Class — The All In Plan™'` |
| `/app/Livewire/MessagesPage.php` | 103 | `'Tin nhắn — The All In Plan™'` |
| `/app/Livewire/QaPage.php` | 76 | `'Hỏi đáp — The All In Plan™'` |
| `/app/Livewire/AffiliatePage.php` | 29 | `'Affiliate — The All In Plan™'` |
| `/app/Livewire/ProfilePage.php` | 121 | `username . ' — The All In Plan™'` |
| `/app/Livewire/SearchResults.php` | 51 | `'Tìm kiếm: ' . query . ' — The All In Plan™'` |
| `/app/Livewire/SignalsPage.php` | 17 | `'Tín hiệu — The All In Plan™'` |
| `/app/Livewire/MembershipPricing.php` | 32 | `'Gói thành viên — The All In Plan™'` |
| `/app/Livewire/AdminDashboard.php` | 19 | `'Admin — The All In Plan™'` |
| `/app/Livewire/MarketplacePage.php` | 77 | `'Marketplace — The All In Plan™'` |

**Total: 18 Livewire pages with brand title references**

### 1.3 View Blade References

| File | Line | Content |
|------|------|---------|
| `/resources/views/livewire/membership-pricing.blade.php` | 5 | `Tham gia cộng đồng The All In Plan™ để tiếp cận kiến thức, challenge và networking` |
| `/resources/views/livewire/auth/register-form.blade.php` | 37 | `Khi đăng ký, bạn đồng ý với Điều khoản sử dụng của The All In Plan™` |
| `/resources/views/livewire/compose-post.blade.php` | 23 | `<span style="font-weight:700; color:#1A1A1A;">The All In Plan™</span>` |
| `/resources/views/pages/membership-expired.blade.php` | 5 | `<title>Membership hết hạn — The All In Plan™</title>` |
| `/resources/views/pages/membership-expired.blade.php` | 15 | `Cộng đồng The All In Plan tiếp tục phát triển mỗi ngày` |
| `/resources/views/pages/coming-soon.blade.php` | 1 | `:title="$page . ' — The All In Plan™'"` |

### 1.4 Database Seeder

| File | Line | Content |
|------|------|---------|
| `/database/seeders/BadgeSeeder.php` | 13 | Badge description: `'Chào mừng đến The All In Plan'` |

### 1.5 Deployment & Config

| File | Line | Content |
|------|------|---------|
| `/deploy.sh` | 10 | `APP="/var/www/the-all-in-plan"` |
| `/deploy.sh` | 38 | `echo "🌐 https://taip.io"` (Production domain) |

---

## 2. Five Pillars: Offer, Traffic, Conversion, Delivery, Continuity

### 2.1 Pillar Definitions

**Location:** Database enum and models

| Pillar | Database Name | Vietnamese Label | Color (Tailwind) | Hex Color |
|--------|---------------|------------------|------------------|-----------|
| Offer | `offer` | Offer | amber | #D97706 |
| Traffic | `traffic` | Thu hút | purple | #7C3AED |
| Conversion | `conversion` | Chuyển đổi | emerald | #059669 |
| Delivery | `delivery` | Cung ứng | blue | #2563EB |
| Continuity | `continuity` | Continuity | red | #DC2626 |

**Source Files:**
- `/database/migrations/2026_01_01_000002_create_posts_table.php` line 15: `enum('pillar', ['offer', 'traffic', 'conversion', 'delivery', 'continuity'])`
- `/app/Models/Post.php` lines 42-54: `getPillarLabelAttribute()` and `getPillarColorAttribute()` methods
- `/app/Models/PillarStat.php` lines 8-12: Pillar label mapping

### 2.2 Pillar CSS Classes

**File:** `/resources/css/app.css` lines 32-37 (color definitions) and 84-89 (badge styles)

```css
/* Pillar accent colors */
--color-pillar-offer:      #D97706;
--color-pillar-traffic:    #7C3AED;
--color-pillar-conversion: #059669;
--color-pillar-delivery:   #2563EB;
--color-pillar-continuity: #DC2626;

/* Pillar badges */
.badge-pillar-offer      { background: #FEF3C7; color: #92400E; }
.badge-pillar-traffic    { background: #EDE9FE; color: #5B21B6; }
.badge-pillar-conversion { background: #D1FAE5; color: #065F46; }
.badge-pillar-delivery   { background: #DBEAFE; color: #1E40AF; }
.badge-pillar-continuity { background: #FEE2E2; color: #991B1B; }
```

### 2.3 Pillar References in Code

| File | Type | References |
|------|------|-----------|
| `/database/migrations/2026_01_01_000004_create_gamification_table.php` | Migration | Pillar tracking |
| `/database/migrations/2026_03_31_200000_add_performance_indexes.php` | Migration | Performance indexes on pillar |
| `/database/migrations/2026_04_03_031326_make_pillar_nullable_on_posts.php` | Migration | Nullable pillar on posts |
| `/app/Console/Commands/RecalcPillarStats.php` | Command | Pillar statistics recalculation |
| `/app/Models/PillarStat.php` | Model | Pillar statistics tracking |
| `/app/Services/PowerSymbolService.php` | Service | Pillar-based logic |
| `/resources/views/livewire/feed.blade.php` | View | Pillar filtering/display |
| `/resources/views/livewire/sidebar-burning-zone.blade.php` | View | Pillar burning indicators |
| `/database/factories/PostFactory.php` | Factory | Pillar seeding |
| `/database/seeders/DatabaseSeeder.php` | Seeder | Pillar initialization |

---

## 3. User Classes: The Five Archetypes

### 3.1 Class Definitions

**Location:** `/database/migrations/0001_01_01_000000_create_users_table.php` lines 20-26

```php
enum('class', [
    'offer_architect',
    'traffic_mage',
    'conversion_ranger',
    'delivery_assassin',
    'continuity_captain',
])->nullable();
```

### 3.2 Class Details with Colors, Emojis & Bonuses

**File:** `/app/Livewire/Auth/ClassSelection.php` lines 12-63

| Class | Display Name | Pillar | Emoji | Color (Hex) | Description | Bonus |
|-------|--------------|--------|-------|-------------|-------------|-------|
| `offer_architect` | Offer Architect | Offer | ◆ | #f59e0b | Thiết kế sản phẩm, định giá, xây value proposition | +20% XP khi review offer |
| `traffic_mage` | Traffic Mage | Thu hút | ◇ | #8b5cf6 | Content marketing, SEO, paid ads, viral growth | Bài viết được ưu tiên +20% reach |
| `conversion_ranger` | Conversion Ranger | Chuyển đổi | ▲ | #10b981 | Copywriting, landing page, funnel, A/B test | Review 5 sao → XP nhân đôi |
| `delivery_assassin` | Delivery Assassin | Cung ứng | ■ | #3b82f6 | Vận hành, tự động hóa, hệ thống, fulfilment | Hoàn thành Challenge đúng hạn → +30% XP |
| `continuity_captain` | Continuity Captain | Continuity | ◎ | #ef4444 | Retention, affiliate, xây cộng đồng, LTV | Affiliate rate 25% + event 10 người → XP × 3 |

### 3.3 Class Accessors in User Model

**File:** `/app/Models/User.php` lines 128-165

| Accessor | Method | Lines |
|----------|--------|-------|
| `class_label` | `getClassLabelAttribute()` | 128-139 |
| `class_color` | `getClassColorAttribute()` | 141-152 |
| `class_emoji` | `getClassEmojiAttribute()` | 154-165 |

**Key:** Level < 10 returns "Beginner" / 🐔 (chicken), otherwise returns class-specific values.

### 3.4 Class CSS Classes

**File:** `/resources/css/app.css` lines 91-96

```css
.badge-class-offer_architect    { background: #FEF3C7; color: #92400E; }
.badge-class-traffic_mage       { background: #EDE9FE; color: #5B21B6; }
.badge-class-conversion_ranger  { background: #D1FAE5; color: #065F46; }
.badge-class-delivery_assassin  { background: #DBEAFE; color: #1E40AF; }
.badge-class-continuity_captain { background: #FEE2E2; color: #991B1B; }
.badge-class-gray               { background: #FAF8F5; color: #5C5C66; border: 1px solid #E8E4DE; }
```

### 3.5 Class References in Code

| File | Type | Content |
|------|------|---------|
| `/app/Livewire/SidebarClassRatio.php` | Livewire | Iterates over all 5 classes for ratio display |
| `/database/migrations/0001_01_01_000000_create_users_table.php` | Migration | User class enum definition |
| `/app/Models/User.php` | Model | Class label/color/emoji accessors + class change tracking |
| `/CLAUDE.md` | Documentation | Class definitions and accessors documented |

---

## 4. Color Scheme in CSS

### 4.1 Core Theme Colors

**File:** `/resources/css/app.css` lines 10-38

| Color Name | Hex | Usage |
|------------|-----|-------|
| `--color-bg-base` | #FFFFFF | Background (white) |
| `--color-bg-subtle` | #FAF8F5 | Subtle bg (off-white) |
| `--color-bg-muted` | #F0EDE8 | Muted bg |
| `--color-border` | #E8E4DE | Standard border |
| `--color-border-strong` | #C8C3BA | Strong border |
| `--color-text-primary` | #1A1A1A | Primary text (dark) |
| `--color-text-secondary` | #2E2E2E | Secondary text |
| `--color-text-muted` | #6B6B6B | Muted text |
| `--color-text-inverse` | #FFFFFF | Inverse text (white on dark) |
| `--color-accent` | #2E7D32 | Green accent (primary brand) |
| `--color-accent-light` | #E8F5E9 | Light green |
| `--color-accent-dark` | #1B5E20 | Dark green |
| `--color-green` | #2E7D32 | Green (same as accent) |
| `--color-green-light` | #E8F5E9 | Light green |
| `--color-salmon` | #C4956A | Salmon (warm) |
| `--color-salmon-light` | #F5E6D3 | Light salmon |

### 4.2 Pillar Colors

| Pillar | Hex | Tailwind |
|--------|-----|----------|
| Offer | #D97706 | amber |
| Traffic | #7C3AED | purple |
| Conversion | #059669 | emerald |
| Delivery | #2563EB | blue |
| Continuity | #DC2626 | red |

### 4.3 Button Styles

**File:** `/resources/css/app.css` lines 111-126

- `.btn-primary`: #1A1A1A bg, white text
- `.btn-secondary`: white bg, dark border
- `.btn-gold`: #2E7D32 (green)
- `.btn-ghost`: transparent, muted text
- `.btn-danger`: #FEE2E2 (light red)
- `.btn-success`: #078A48 (dark green)

### 4.4 Special Components

| Component | Colors | Lines |
|-----------|--------|-------|
| `.xp-bar` | Linear gradient green | 148-155 |
| `.progress-bar` | Linear gradient green/red | 157-161 |
| `.rune-banner` | Salmon gradient with pulse animation | 163-174 |
| `.cot-badge` | Green bg (#E8F5E9), dark green text | 176-185 |
| `.burning-indicator` | Red gradient with pulse | 187-198 |
| `.nav-item.active` | Light green bg (#E8F5E9) | 201-214 |
| `.post-card.is-cot` | 3px green left border | 216-229 |
| `.post-card.has-rune` | 3px salmon left border | 228 |
| `.post-card.is-signal` | 3px dark green left border | 229 |
| `.level-badge` | Green bg & border | 239-248 |
| `.da-gem` | Green text (#2E7D32) | 250-257 |

---

## 5. Environment Configuration

### 5.1 .env.example

**File:** `/Users/mdm/Desktop/cuc-cu-dream/.env.example`

Key variables affecting deployment:
- `APP_NAME=Laravel` (Generic — should be replaced with "The All In Plan" for branding)
- `DB_DATABASE=the_all_in_plan` (Database name includes brand)
- `VITE_APP_NAME="${APP_NAME}"` (Uses APP_NAME env var)

---

## 6. Deployment Script

**File:** `/deploy.sh`

| Line | Content |
|------|---------|
| 2 | `# Deploy The All In Plan to production VPS` |
| 10 | `APP="/var/www/the-all-in-plan"` (Server directory) |
| 12 | `echo "🚀 Deploying to taip.io..."` (Domain reference) |
| 38 | `echo "🌐 https://taip.io"` (Production domain) |

**Domain:** taip.io (The All In Plan acronym)

---

## 7. Configuration Files

### 7.1 `/config/app.php`

**File:** `/Users/mdm/Desktop/cuc-cu-dream/config/app.php`

- `'name' => env('APP_NAME', 'Laravel')` (Line 16) — Uses env var, defaults to 'Laravel'
- Currently generic, no hardcoded brand reference

### 7.2 `/config/exp_table.php`

**File:** `/Users/mdm/Desktop/cuc-cu-dream/config/exp_table.php`

- EXP requirements per level (1-60 defined, levels 61-300 via formula)
- Inspired by MapleStory
- No brand references

---

## 8. Summary: Rebrand Touchpoints

### High Priority (Brand Identity)
1. **Layout Meta Tags** (2 files: app.blade.php, guest.blade.php)
   - Page titles: 18 Livewire components + 6 views = **24 references**
   
2. **Logo Display** (2 files: app.blade.php, guest.blade.php)
   - Inline HTML with color styling
   
3. **Descriptions** (Membership pricing, register form, compose post, coming-soon, membership-expired)
   - 5 user-facing copy references

### Medium Priority (System Configuration)
1. **Database (Migrations)**
   - Pillar enum: 5 values (no changes needed to enum names, just labels)
   - Class enum: 5 values (no changes needed to enum names, just labels)
   
2. **Models & Services**
   - Pillar labels/colors: `/app/Models/Post.php`, `/app/Models/PillarStat.php`
   - Class labels/colors/emojis: `/app/Models/User.php`, `/app/Livewire/Auth/ClassSelection.php`

3. **CSS Color Scheme**
   - 5 pillar badge colors
   - 5 class badge colors
   - Core brand color: #2E7D32 (green)

### Low Priority (Infrastructure)
1. **Deployment** (deploy.sh, .env.example)
   - Directory names, domain references
   
2. **Documentation** (CLAUDE.md, plan files, reports)
   - Context and planning docs

---

## 9. File Count Summary

- **Brand name references:** 41 files
- **Pillar references:** 51 files  
- **Class name references:** 34 files
- **CSS custom component classes:** app.css (1 file, ~300 lines)

---

## Unresolved Questions

1. What is the new brand name?
2. Should pillar names change or just labels (Vietnamese translations)?
3. Should class names change or just display labels?
4. Should class emojis change?
5. Is the color scheme staying the same or changing?
6. Is the domain (taip.io) staying the same?
7. Should the favicon/logo change?
