# Remaining References to Update - Rebrand cuc-cu-dream

**Date:** 2026-04-04  
**Task:** Find and report remaining references that need updating during rebrand from old to new terminology

---

## 1. CỐT References (Display Labels & Database Fields)

### Blade Views with "CỐT" display text:

| File | Line | Content | Type |
|------|------|---------|------|
| `resources/views/layouts/app.blade.php` | 122 | `<a href="{{ route('cot') }}"...` | Navigation link to route |
| `resources/views/layouts/app.blade.php` | 124 | `CỐT` | Navigation label |
| `resources/views/livewire/post-card.blade.php` | 1 | `{{ $post->is_cot ? 'is-cot' : '' }}` | CSS class binding |
| `resources/views/livewire/post-card.blade.php` | 23-26 | `@if($post->is_cot)` + `<span class="cot-badge">★ CỐT</span>` | Badge display |
| `resources/views/livewire/post-card.blade.php` | 130-135 | "Đề cử CỐT" button | Nomination button |
| `resources/views/livewire/cot-page.blade.php` | 6 | `<h1>CỐT</h1>` | Page heading |
| `resources/views/livewire/cot-page.blade.php` | 14 | `placeholder="Tìm trong CỐT..."` | Search placeholder |
| `resources/views/livewire/cot-page.blade.php` | 28 | `<p>Chưa có bài CỐT nào` | Empty state message |
| `resources/views/livewire/admin-cot-review.blade.php` | 2 | `<h1>★ Duyệt CỐT</h1>` | Admin page heading |
| `resources/views/livewire/admin-cot-review.blade.php` | 22 | `<button>Duyệt CỐT</button>` | Approval button |
| `resources/views/livewire/admin-cot-review.blade.php` | 27 | `Không có bài viết nào chờ duyệt CỐT` | Empty state |
| `resources/views/livewire/admin-dashboard.blade.php` | 20 | `CỐT chờ duyệt` | Dashboard widget label |
| `resources/views/livewire/admin-dashboard.blade.php` | 66 | `Duyệt CỐT` | Card title |
| `resources/views/livewire/admin-dashboard.blade.php` | 67 | `Approve/reject bài viết CỐT` | Card description |
| `resources/views/livewire/feed.blade.php` | 42 | `★ CỐT` | Tab label |
| `resources/views/livewire/profile-page.blade.php` | 76 | `★ CỐT` | Stats label |
| `resources/views/livewire/profile-page.blade.php` | 267 | `★ CỐT` | Tab label |
| `resources/views/livewire/profile-page.blade.php` | 285 | `Chưa có bài CỐT nào` | Empty state |

### Blade Views with "CỐT" in shared content:
- `resources/views/livewire/membership-pricing.blade.php` (line 110): Includes in benefit list: "Truy cập toàn bộ nội dung Feed, CỐT, Tín hiệu"

---

## 2. Tín hiệu (Signal) References

### Blade Views with "Tín hiệu" display text:

| File | Line | Content | Type |
|------|------|---------|------|
| `resources/views/layouts/app.blade.php` | 126 | `<a href="{{ route('signals') }}"...` | Navigation link |
| `resources/views/layouts/app.blade.php` | 128 | `Tín hiệu` | Navigation label |
| `resources/views/livewire/compose-post.blade.php` | 92 | `title="Tín hiệu ngắn <500 từ"` | Button title/tooltip |
| `resources/views/livewire/feed.blade.php` | 44 | `⚡ Tín hiệu` | Tab label |
| `resources/views/livewire/signals-page.blade.php` | 6 | `<h1>Tín hiệu</h1>` | Page heading |
| `resources/views/livewire/membership-pricing.blade.php` | 110 | Part of benefit list | Shared content |

---

## 3. Offer, Traffic, Conversion, Delivery, Continuity References

### Key locations with 5 Pillar labels:

| File | Line | Context |
|------|------|---------|
| `resources/views/livewire/admin-products.blade.php` | 37-41 | Dropdown options: "offer", "traffic", "conversion", "delivery", "continuity" |
| `resources/views/livewire/marketplace-page.blade.php` | 11 | Pillar filter buttons with emojis |
| `resources/views/livewire/academy-page.blade.php` | 12 | **Pillar filter buttons with emojis** |

**These are the 5 core business pillars - they appear in:**
- Admin product creation/editing dropdown
- Marketplace filtering interface
- Academy course filtering interface
- Course card display (with badge styling like `badge-pillar-offer`, etc.)

---

## 4. "trụ cột" (Pillar) References

| File | Line | Content |
|------|------|---------|
| `resources/views/livewire/marketplace-page.blade.php` | 93 | `Chưa có sản phẩm nào{{ $pillar ? ' cho trụ cột này' : '' }}` |
| `resources/views/livewire/academy-page.blade.php` | 5 | `Khóa học chuyên sâu theo từng trụ cột` |
| `resources/views/livewire/academy-page.blade.php` | 78 | `Chưa có khóa học nào{{ $pillar ? ' cho trụ cột này' : '' }}` |

---

## 5. "Chuyên gia" (Expert) References

### Blade Views:
| File | Line | Content |
|------|------|---------|
| `resources/views/livewire/academy-page.blade.php` | 17 | `'expert'=>'Chuyên gia'` in difficulty filter dropdown |

**Context:** This is a course difficulty level option (alongside "Cơ bản" and "Nâng cao")

### PHP Files:
No matches found in `app/` directory

---

## 6. Full File Contents

### File: resources/views/livewire/academy-page.blade.php
✓ **Full content provided** - 82 lines total  
**Key elements:**
- Lines 12: Pillar filter with 5 options (offer, traffic, conversion, delivery, continuity)
- Line 5: "Khóa học chuyên sâu theo từng trụ cột" subtitle
- Line 17: Difficulty dropdown with "Chuyên gia" as expert level option
- Line 38: Displays pillar badge with `badge-pillar-{{ $course->pillar }}`
- Line 39: Displays difficulty badge with styling logic for each level
- Line 78: Empty state with "cho trụ cột này" text

### File: app/Livewire/AcademyPage.php
✓ **Full content provided** - 42 lines total  
**Key elements:**
- Line 12: `public string $pillar = ''` - URL parameter for pillar filtering
- Line 15: `public string $difficulty = ''` - URL parameter for difficulty filtering
- Lines 26-28: Filters courses by pillar if set
- Lines 30-32: Filters courses by difficulty if set
- Line 24: Only fetches published courses

---

## Summary of Findings

### Display Text Requiring Updates:
1. **"CỐT"** - 18+ locations across navigation, pages, admin panels, profile (display label, badges, buttons, headings)
2. **"Tín hiệu"** - 6 locations (navigation, compose UI, feed tab, signals page heading)
3. **"Chuyên gia"** - 1 location (difficulty dropdown in academy page)
4. **"trụ cột"** - 3 locations (empty state messages, section subtitle)
5. **5 Pillars** - "Offer", "Traffic", "Conversion", "Delivery", "Continuity" appear in 3 key files with emoji prefixes

### Database Fields (from Blade syntax):
- `$post->is_cot` - boolean field
- `$post->is_signal` - boolean field  
- `$course->pillar` - string field (values: offer, traffic, conversion, delivery, continuity)
- `$course->difficulty` - string field (values: '', basic, advanced, expert)

### Routes Present:
- `route('cot')` - View CỐT page
- `route('signals')` - View signals page
- `route('admin.cot')` - Admin CỐT review page
- `route('academy.show', $course->id)` - Course detail page

---

**Note:** All hard-coded display text and UI labels listed above will need updating during the rebrand. Database field names (is_cot, is_signal, pillar, difficulty) and routing names (cot, signals) are structural and handled separately.
