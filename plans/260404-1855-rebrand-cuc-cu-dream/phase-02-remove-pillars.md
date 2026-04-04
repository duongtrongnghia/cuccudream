# Phase 2: Remove Pillar System

**Priority:** P1 | **Status:** Pending | **Effort:** 1h

## Overview
Remove the 5-pillar taxonomy (offer, traffic, conversion, delivery, continuity) from posts, feeds, and UI. Posts become uncategorized (topics remain).

## Files to Modify

### Models
- **`app/Models/Post.php`**: Remove `pillar` from `$fillable`, remove `getPillarLabelAttribute`, `getPillarColorAttribute`, `scopeByPillar`
- **`app/Models/PillarStat.php`**: Delete entire file

### Livewire Components
- **`app/Livewire/Feed.php`**: Remove `$pillar` property, `setPillar()` method, pillar tab cases from query switch
- **`app/Livewire/ComposePost.php`**: Remove `$pillar` property, `$pillars` array, pillar from post creation data
- **`app/Livewire/CotPage.php`**: Remove pillar filter if present
- **`app/Livewire/QaPage.php`**: Remove pillar references
- **`app/Livewire/SignalsPage.php`**: Remove pillar references
- **`app/Livewire/ProfilePage.php`**: Remove pillar display/stats
- **`app/Livewire/SidebarBurningZone.php`**: Remove pillar references
- **`app/Livewire/AcademyPage.php`**: Remove pillar filter if present
- **`app/Livewire/MarketplacePage.php`**: Remove pillar references
- **`app/Livewire/AdminProducts.php`**: Remove pillar references

### Blade Views
- **`resources/views/livewire/feed.blade.php`**: Remove pillar filter tabs/buttons
- **`resources/views/livewire/compose-post.blade.php`**: Remove pillar dropdown
- **`resources/views/livewire/post-card.blade.php`**: Remove pillar badge display

### Services
- **`app/Services/XpService.php`**: Remove pillar-related logic if any
- **`app/Services/PowerSymbolService.php`**: Remove pillar references

### Commands
- **`app/Console/Commands/RecalcPillarStats.php`**: Delete entire file

### CSS
- **`resources/css/app.css`**: Remove `--color-pillar-*` theme vars, `.badge-pillar-*` classes

### Migration
- Create new migration: `drop_pillar_from_posts` — drop `pillar` column, make nullable first if not already

## Todo
- [ ] P2-1: Remove pillar from Post model (fillable, accessors, scope)
- [ ] P2-2: Delete PillarStat model and RecalcPillarStats command
- [ ] P2-3: Clean Feed.php — remove pillar tab/filter logic
- [ ] P2-4: Clean ComposePost.php — remove pillar dropdown data
- [ ] P2-5: Clean other Livewire components (CotPage, QaPage, SignalsPage, ProfilePage, SidebarBurningZone, AcademyPage, MarketplacePage, AdminProducts)
- [ ] P2-6: Clean blade views (feed, compose-post, post-card)
- [ ] P2-7: Remove pillar CSS from app.css
- [ ] P2-8: Create migration to drop pillar column from posts

## Success Criteria
- No references to `pillar` in app/ or resources/ (except migration files)
- Feed loads without pillar tabs
- Post creation works without pillar selection
