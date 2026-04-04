# Phase 5: Brand Name Replacement

**Priority:** P1 | **Status:** Pending | **Effort:** 30m  
**Depends on:** Phases 2, 3, 4 (fewer files to touch after cleanup)

## Overview
Global search-and-replace of brand text across all remaining files.

## Replacements

| Old | New |
|-----|-----|
| `The All In Plan` | `Cuc Cu Dream` |
| `All In Plan` | `Cuc Cu Dream` |
| `TAIP` (as brand ref) | `CCD` |
| `All In Plan™` | `Cuc Cu Dream™` |
| Slogan references | `Danh thuc giac mo nguyen thuy qua nghe thuat` |

## Files to Modify (grep results — 26 files)

### Layouts
- `resources/views/layouts/app.blade.php` — title, meta tags, og:site_name
- `resources/views/layouts/guest.blade.php` — title, meta tags

### Pages
- `resources/views/pages/coming-soon.blade.php`
- `resources/views/pages/membership-expired.blade.php`

### Livewire Views
- `resources/views/livewire/compose-post.blade.php`
- `resources/views/livewire/auth/register-form.blade.php`
- `resources/views/livewire/membership-pricing.blade.php`

### CSS
- `resources/css/app.css` — theme comment "All In Plan"

### Livewire Components (title properties)
- `app/Livewire/Feed.php`, `AdminDashboard.php`, `MembershipPricing.php`, `SignalsPage.php`, `SearchResults.php`, `ProfilePage.php`, `AffiliatePage.php`, `QaPage.php`, `MessagesPage.php`, `Auth/LoginForm.php`, `Auth/RegisterForm.php`, `AcademyPage.php`, `ChallengePage.php`, `LeaderboardPage.php`, `CotPage.php`, `MarketplacePage.php`

*Note: Many of these will be deleted in phases 2-4. Only modify what survives.*

### Seeders
- `database/seeders/BadgeSeeder.php` — update brand references

## Todo
- [ ] P5-1: Update layout files (app.blade.php, guest.blade.php) — title, meta, og tags
- [ ] P5-2: Update remaining page views with new brand name
- [ ] P5-3: Update remaining Livewire component titles/descriptions
- [ ] P5-4: Update CSS theme comment
- [ ] P5-5: Update seeders

## Success Criteria
- `grep -r "All In Plan" resources/ app/` returns zero matches
- Page titles show "Cuc Cu Dream"
