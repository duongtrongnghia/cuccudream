# Phase 06: Search + Admin Panel Expansion

## Priority: MEDIUM
## Status: pending

## Overview
Implement header search (posts, users, questions). Expand admin panel with user management, post moderation, expedition oversight.

## Key Files
- `app/Livewire/SearchResults.php` (CREATE)
- `resources/views/livewire/search-results.blade.php` (CREATE)
- `app/Livewire/AdminUsers.php` (CREATE)
- `app/Livewire/AdminPosts.php` (CREATE)
- `app/Livewire/AdminExpeditions.php` (CREATE)
- `resources/views/livewire/admin-users.blade.php` (CREATE)
- `resources/views/livewire/admin-posts.blade.php` (CREATE)
- `resources/views/livewire/admin-expeditions.blade.php` (CREATE)
- `resources/views/layouts/app.blade.php` (MODIFY — wire search input)
- `routes/web.php` (MODIFY — add search + admin routes)

## Implementation Steps

### Search
1. Create `SearchResults` component with `$query` property from URL
2. Search across: Posts (title + content), Users (name + username), Questions (title + body)
3. Wire header search input: on Enter → navigate to `/search?q=...`
4. Add route: `/search` → SearchResults

### Admin Panel
5. **AdminUsers**: list users, toggle admin/moderator, ban, view XP/level, reset password
6. **AdminPosts**: list posts, delete, toggle CỐT, toggle pin
7. **AdminExpeditions**: list expeditions, approve chaos, force complete/fail/cancel
8. Add admin routes: `/admin/users`, `/admin/posts`, `/admin/expeditions`
9. Add admin nav sidebar (only visible for admins)

## Success Criteria
- [ ] Search returns posts + users + questions
- [ ] Header search input navigates to results page
- [ ] Admin can manage users (ban, toggle roles)
- [ ] Admin can moderate posts (delete, pin, CỐT)
- [ ] Admin can manage expeditions (approve, force transitions)
