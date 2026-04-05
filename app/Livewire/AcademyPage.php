<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;

class AcademyPage extends Component
{
    public function render()
    {
        $courses = Course::where('is_published', true)
            ->withCount('enrollments')
            ->orderByDesc('created_at')
            ->get();

        return view('livewire.academy-page', ['courses' => $courses])
            ->layout('layouts.app', ['title' => 'Khóa học — Cúc Cu Dream™']);
    }
}
