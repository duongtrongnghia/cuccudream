<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FamilyPage extends Component
{
    public function render()
    {
        $user = Auth::user();
        $children = $user->children()->get();

        return view('livewire.family-page', [
            'children' => $children,
            'canAddMore' => $children->count() < 5,
        ])->layout('layouts.app', ['title' => 'Gia đình — Cúc Cu Dream™']);
    }
}
