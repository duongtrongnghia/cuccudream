<?php

namespace App\Livewire\Auth;

use App\Models\Membership;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Rule;
use Livewire\Component;

class RegisterForm extends Component
{
    public string $accountType = '';
    public bool $showTypeSelection = true;

    #[Rule('required|min:2|max:50')]
    public string $name = '';

    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(): void
    {
        // If ref code in session, keep it
    }

    public function selectType(string $type): void
    {
        if (!in_array($type, ['parent', 'kid'])) {
            return;
        }
        $this->accountType = $type;
    }

    public function proceedToForm(): void
    {
        if (!in_array($this->accountType, ['parent', 'kid'])) {
            $this->addError('accountType', 'Vui lòng chọn loại tài khoản.');
            return;
        }
        $this->showTypeSelection = false;
    }

    public function register(): void
    {
        // Build dynamic validation rules based on account type
        $rules = [
            'name' => 'required|min:2|max:50',
        ];

        if ($this->accountType === 'parent') {
            $rules['email']    = 'required|email|unique:users,email';
            $rules['password'] = 'required|min:8|confirmed';
        } else {
            // Kid: no email, no password confirmation required
            $rules['password'] = 'required|min:6';
        }

        $this->validate($rules);

        // Transliterate Vietnamese → ASCII before creating username
        $ascii    = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', trim($this->name));
        $username = preg_replace('/\s+/', '.', $ascii);
        $username = preg_replace('/[^a-z0-9._]/', '', $username);
        $base     = $username;
        $i        = 1;
        while (User::where('username', $username)->exists()) {
            $username = $base . $i++;
        }

        $referrer    = null;
        $refUsername = session('referral');
        if ($refUsername) {
            $referrer = User::where('username', $refUsername)->first();
        }

        $userData = [
            'name'         => $this->name,
            'username'     => $username,
            'password'     => Hash::make($this->password),
            'account_type' => $this->accountType ?: 'parent',
            'level'        => 1,
            'xp'           => 0,
            'aip'          => 0,
            'streak'       => 0,
            'referred_by'  => $referrer?->id,
        ];

        if ($this->accountType === 'parent') {
            $userData['email'] = $this->email;
        }

        $user = User::create($userData);

        // Only parents get a trial membership; kids access via parent account
        if ($this->accountType !== 'kid') {
            Membership::create([
                'user_id'       => $user->id,
                'status'        => 'trial',
                'trial_ends_at' => now()->addDays(3),
                'referred_by'   => $referrer?->id,
            ]);
        }

        session()->forget('referral');

        Auth::login($user);

        $this->redirect(route('feed'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register-form')
            ->layout('layouts.guest', ['title' => 'Đăng ký — Cúc Cu Dream™']);
    }
}
