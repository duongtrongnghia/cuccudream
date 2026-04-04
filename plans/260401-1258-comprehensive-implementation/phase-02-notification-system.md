# Phase 02: Notification System

## Priority: HIGH
## Status: pending

## Overview
Wire Laravel's built-in notification system. Dispatch notifications on key events, display in NotificationBell dropdown.

## Key Files
- `app/Notifications/` (CREATE directory + notification classes)
- `app/Livewire/NotificationBell.php` (MODIFY — add dropdown with notification list)
- `resources/views/livewire/notification-bell.blade.php` (MODIFY)
- `app/Livewire/PostCard.php` (MODIFY — dispatch on like/comment)
- `app/Livewire/ExpeditionDetail.php` (MODIFY — dispatch on join/checkin)
- `app/Livewire/QaPage.php` (MODIFY — dispatch on answer)

## Implementation Steps

1. Create notification classes:
   - `PostLikedNotification` — "X thích bài viết của bạn"
   - `PostCommentedNotification` — "X bình luận bài viết của bạn"
   - `ExpeditionJoinedNotification` — "X tham gia expedition của bạn"
   - `CotNominatedNotification` — "Bài viết được đề cử CỐT"
   - `BadgeEarnedNotification` — "Bạn nhận được huy hiệu X"
   - `LevelUpNotification` — "Chúc mừng lên Level X!"

2. Dispatch from components (notify post owner, expedition creator, etc.)

3. Update NotificationBell:
   - `$unreadCount` property
   - `markAsRead()` method
   - Dropdown showing recent 20 notifications
   - Mark all read button

## Success Criteria
- [ ] 6 notification types created
- [ ] Dispatched on like, comment, expedition join, badge earned, level up
- [ ] NotificationBell shows unread count + dropdown list
- [ ] Mark as read works
