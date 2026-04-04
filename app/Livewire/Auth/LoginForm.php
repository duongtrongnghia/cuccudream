<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;

class LoginForm extends Component
{
    #[Rule('required')]
    public string $login = '';

    #[Rule('required|min:6')]
    public string $password = '';

    public bool $remember = false;
    public string $error = '';

    public function authenticate(): void
    {
        $this->validate();

        // Support both email and username login
        $field = filter_var($this->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (!Auth::attempt([$field => $this->login, 'password' => $this->password], $this->remember)) {
            $this->error = 'Thông tin đăng nhập không đúng.';
            return;
        }

        session()->regenerate();

        $user = Auth::user();

        // Kid accounts: check parent's membership
        if ($user->isKid()) {
            $this->redirect(route('feed'), navigate: true);
            return;
        }

        // Check membership status
        $membership = $user->membership;
        if (!$membership || $membership->status === 'expired') {
            $this->redirect(route('membership.expired'), navigate: true);
            return;
        }
        if ($membership->status === 'banned') {
            Auth::logout();
            $this->error = 'Tài khoản của bạn đã bị khóa.';
            return;
        }

        $this->redirect(route('feed'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.login-form')
            ->layout('layouts.guest', ['title' => 'Đăng nhập — Cúc Cu Dream™']);
    }
}
