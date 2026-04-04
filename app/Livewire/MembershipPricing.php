<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class MembershipPricing extends Component
{
    public const PLANS = [
        1  => ['weeks' => 1,  'price_per_week' => 500000, 'label' => '1 tuần',   'save' => 0],
        4  => ['weeks' => 4,  'price_per_week' => 350000, 'label' => '4 tuần',   'save' => 30],
        5  => ['weeks' => 5,  'price_per_week' => 300000, 'label' => '5 tuần',   'save' => 40],
        52 => ['weeks' => 52, 'price_per_week' => 250000, 'label' => '52 tuần (1 năm)', 'save' => 50],
    ];

    public ?int $selectedPlan = null;

    public function selectPlan(int $weeks): void
    {
        $this->selectedPlan = $weeks;
    }

    public function render()
    {
        $user = Auth::user();
        $membership = $user?->membership;

        return view('livewire.membership-pricing', [
            'plans' => self::PLANS,
            'membership' => $membership,
        ])->layout('layouts.app', ['title' => 'Gói thành viên — Cúc Cu Dream™']);
    }
}
