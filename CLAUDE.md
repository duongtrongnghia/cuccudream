# Cúc Cu Dream™ — Project Context

## Stack
- **Laravel 12** + **Livewire 3** (full-stack, no separate API)
- **Alpine.js** (client-side reactivity, dropdowns, toggling)
- **Tailwind CSS v4** (utility classes + custom component classes in `app.css`)
- **PostgreSQL 17** (database: `cuc_cu_dream`)
- **PHP 8.2+**

## Project Purpose
Vietnamese-language community platform for parents & children learning art and English. Simple community model — users post, comment, like, bookmark, and ask questions.

## Key Domain Concepts
- **Topics**: Admin-managed post format tags stored in `topics` table
- **Membership**: Required to access the platform; checked by `RequireActiveMembership` middleware
- **Q&A**: Question & answer section for community discussions

## Directory Structure
```
app/
  Http/Middleware/     RequireActiveMembership.php
  Livewire/
    Auth/              LoginForm, RegisterForm
    AdminTopics.php    — Admin CRUD for topics (no Filament)
    ComposePost.php    — Post composer
    Feed.php           — Main feed with tabs (latest/popular)
    PostCard.php       — Individual post with likes/comments/bookmarks
    ProfilePage.php
    QaPage.php         — Q&A section
    NotificationBell.php
    MessagesPage.php
    AffiliatePage.php
    AdminDashboard.php, AdminUsers.php, AdminReports.php
  Models/
    User, Post, Comment, Like, Bookmark
    Topic, Membership, Question, Answer
    Setting
  Providers/
    AppServiceProvider.php  — Gate::define('admin', fn($u) => $u->is_admin)

resources/views/
  layouts/app.blade.php       — Main layout
  layouts/guest.blade.php     — Guest layout (login/register)
  livewire/
    compose-post.blade.php    — Post composer UI
    post-card.blade.php       — Post display card
    admin-topics.blade.php    — Admin topic management
    feed.blade.php
    ...

routes/web.php
  — Guest: /login, /register
  — Auth: /logout, /membership/expired, /membership/pricing
  — Auth + ActiveMembership: /feed, /hoi-dap, /affiliate, /messages, /search, /@{username}
  — Admin (->can('admin')): /admin, /admin/topics, /admin/reports, /admin/users
```

## Models — Key Fields

### User
- `name`, `email`, `username`, `avatar`
- `is_admin` (bool), `is_moderator` (bool)
- `referred_by`, `last_active_at`
- Accessors: `avatar_url`

### Post
- `user_id`, `title` (nullable), `content`
- `topic_id` (nullable FK → topics)

### Topic
- `name`, `emoji`, `slug` (unique), `sort_order`, `is_active`
- Scope: `active()` → where is_active=true, orderBy sort_order

### Membership
- `user_id`, `plan`, `status` (active/expired/cancelled), `expires_at`

## Brand
- **Name**: Cúc Cu Dream™
- **Slogan**: "Đánh thức giấc mơ nguyên thuỷ qua nghệ thuật"
- **Colors**: Primary `#FF6B6B` (Coral), Secondary `#4ECDC4` (Teal), Accent `#A78BFA` (Lavender), BG `#FFF9F0` (Cream), Text `#2D3436`, Muted `#636E72`
- **Font**: Nunito (Google Fonts)

## CSS Conventions
Custom component classes defined in `resources/css/app.css`:
- `.card` — white rounded card with shadow
- `.btn`, `.btn-primary`, `.btn-ghost`
- `.badge`
- `.avatar` — circular image
- `.input` — form input style
- `.post-card`

## Important Patterns

### Livewire Component
```php
// Always use #[Rule] attributes for validation
#[Rule('required|min:5|max:10000')]
public string $content = '';

// Dispatch events for cross-component communication
$this->dispatch('post-created');

// Listen with #[On] in other components
#[On('post-created')]
public function refreshFeed() { ... }
```

### Alpine Dropdown Pattern
```html
<div x-data="{ open: false }" style="position:relative;">
    <button @click="open = !open">Label ▾</button>
    <div x-show="open" @click.away="open = false" x-transition
         style="position:absolute; right:0; top:calc(100% + 6px); z-index:50; ...">
        ...items...
    </div>
</div>
```

### Auto-expanding Textarea
```html
<textarea x-data
    x-init="$el.style.height = $el.scrollHeight + 'px'"
    @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"
    style="overflow:hidden; resize:none;">
</textarea>
```

### Admin Gate
```php
// Check in blade
@can('admin') ... @endcan
// Check in routes
Route::get('/admin/...', Component::class)->can('admin');
```

## Do NOT
- Do NOT install or use Filament for admin UI — use custom Livewire components instead
- Do NOT use `Route::middleware(closure)` — use named middleware strings or `->can()`
- Do NOT add scrollbars to textareas — use auto-expand pattern above
- Do NOT break single-row toolbar in compose-post — all icons + dropdowns + actions on one line

## Dev Server
- Laravel: `php artisan serve --port=9090`
- Assets: `npm run dev` (Vite)
- Database: `createdb cuc_cu_dream` then `php artisan migrate` then `php artisan db:seed` then `php artisan db:seed --class=TopicSeeder`
