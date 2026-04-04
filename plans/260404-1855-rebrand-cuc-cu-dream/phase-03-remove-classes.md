# Phase 3: Remove Class System

**Priority:** P1 | **Status:** Pending | **Effort:** 45m

## Overview
Remove character class system (offer_architect, traffic_mage, etc.) from users, onboarding, and UI.

## Files to Modify

### Models
- **`app/Models/User.php`**: Remove `class`, `class_changed_at` from `$fillable` and `$casts`. Remove `getClassLabelAttribute`, `getClassColorAttribute`, `getClassEmojiAttribute` accessors.

### Livewire Components
- **`app/Livewire/Auth/ClassSelection.php`**: Delete entire file
- **`app/Livewire/ProfilePage.php`**: Remove class badge display
- **`app/Livewire/SidebarBurningZone.php`**: Remove class references

### Blade Views
- **`resources/views/livewire/auth/class-selection.blade.php`**: Delete entire file
- **`resources/views/livewire/sidebar-class-ratio.blade.php`**: Delete entire file
- **`resources/views/livewire/profile-page.blade.php`**: Remove class badge rendering

### Routes
- **`routes/web.php`**: Remove `/onboarding` route (ClassSelection)

### CSS
- **`resources/css/app.css`**: Remove `.badge-class-*` classes

### Sidebar Component (if exists)
- Find and remove SidebarClassRatio Livewire component if it exists

### Migration
- Create migration to drop `class` and `class_changed_at` columns from users table

## Todo
- [ ] P3-1: Clean User model — remove class fields and accessors
- [ ] P3-2: Delete ClassSelection component + view
- [ ] P3-3: Delete sidebar-class-ratio view + component
- [ ] P3-4: Remove class display from profile-page view
- [ ] P3-5: Remove /onboarding route from web.php
- [ ] P3-6: Remove class badge CSS from app.css
- [ ] P3-7: Create migration to drop class columns from users

## Success Criteria
- No references to `class_label`, `class_color`, `class_emoji`, `ClassSelection` in codebase
- Registration flow skips class selection
- Profile page renders without class badge
