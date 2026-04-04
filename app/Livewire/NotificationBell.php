<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBell extends Component
{
    public bool $showDropdown = false;

    public function toggleDropdown(): void
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function markAllRead(): void
    {
        Auth::user()?->unreadNotifications->markAsRead();
        $this->showDropdown = false;
    }

    public function openNotification(string $id): void
    {
        $notification = Auth::user()->notifications()->find($id);
        if (!$notification) return;

        $notification->markAsRead();
        $this->showDropdown = false;

        // If notification has a post_id, open modal instead of redirect
        $postId = $notification->data['post_id'] ?? null;
        if ($postId) {
            $this->dispatch('open-post', postId: (int) $postId);
            return;
        }

        $url = $notification->data['url'] ?? null;
        if ($url) {
            $this->redirect($url);
        }
    }

    public function render()
    {
        $user = Auth::user();
        $count = $user?->unreadNotifications()->count() ?? 0;
        $notifications = $this->showDropdown
            ? $user?->notifications()->latest()->take(20)->get() ?? collect()
            : collect();

        return view('livewire.notification-bell', [
            'count' => $count,
            'notifications' => $notifications,
        ]);
    }
}
