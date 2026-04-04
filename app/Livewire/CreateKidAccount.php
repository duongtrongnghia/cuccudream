<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\Component;

class CreateKidAccount extends Component
{
    #[Rule('required|min:2|max:50')]
    public string $name = '';

    #[Rule('required|min:3|max:30|unique:users,username|regex:/^[a-zA-Z0-9._]+$/')]
    public string $username = '';

    #[Rule('required|min:6')]
    public string $password = '';

    public function updatedName(): void
    {
        // Auto-generate username from name using ASCII transliteration
        $base = Str::ascii(Str::lower($this->name));
        $base = preg_replace('/[^a-z0-9]+/', '.', $base);
        $base = trim($base, '.');

        if (empty($base)) {
            return;
        }

        $username = $base;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $base . $counter;
            $counter++;
        }
        $this->username = $username;
    }

    public function createKid(): void
    {
        $this->validate();

        $parent = Auth::user();

        if ($parent->children()->count() >= 5) {
            $this->addError('name', 'Bạn đã tạo tối đa 5 tài khoản bé.');
            return;
        }

        User::create([
            'name'         => $this->name,
            'username'     => $this->username,
            'password'     => Hash::make($this->password),
            'account_type' => 'kid',
            'parent_id'    => $parent->id,
            'level'        => 1,
            'xp'           => 0,
            'aip'          => 0,
            'streak'       => 0,
        ]);

        session()->flash('message', 'Đã tạo tài khoản cho bé ' . $this->name . '!');
        $this->redirect(route('family'), navigate: true);
    }

    public function render()
    {
        return view('livewire.create-kid-account')
            ->layout('layouts.app', ['title' => 'Thêm bé — Cúc Cu Dream™']);
    }
}
