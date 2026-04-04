<?php

namespace App\Livewire;

use App\Models\Membership;
use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class AdminUsers extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public function toggleAdmin(int $id): void
    {
        $user = User::findOrFail($id);
        $user->update(['is_admin' => !$user->is_admin]);
    }

    public function toggleModerator(int $id): void
    {
        $user = User::findOrFail($id);
        $user->update(['is_moderator' => !$user->is_moderator]);
    }

    public function banUser(int $id): void
    {
        if ($id === auth()->id()) return; // Can't ban yourself
        $user = User::findOrFail($id);
        $membership = $user->membership;
        if ($membership) {
            $membership->update(['status' => 'banned']);
            $this->dispatch('toast', message: $user->name . ' đã bị ban', type: 'success');
        }
    }

    public function unbanUser(int $id): void
    {
        $user = User::findOrFail($id);
        $membership = $user->membership;
        if ($membership && $membership->status === 'banned') {
            $membership->update(['status' => 'active']);
        }
    }

    public function render()
    {
        $query = User::with('membership')->withCount('posts');

        if ($this->search) {
            $term = '%' . $this->search . '%';
            $query->where(fn($q) => $q->where('name', 'ilike', $term)->orWhere('email', 'ilike', $term)->orWhere('username', 'ilike', $term));
        }

        return view('livewire.admin-users', ['users' => $query->latest()->paginate(20)])
            ->layout('layouts.app', ['title' => 'Quản lý người dùng — Admin']);
    }
}
