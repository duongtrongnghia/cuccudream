<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;

class AdminCourses extends Component
{
    public function togglePublish(int $id): void
    {
        $course = Course::findOrFail($id);
        $course->update(['is_published' => !$course->is_published]);
    }

    public function deleteCourse(int $id): void
    {
        Course::findOrFail($id)->delete();
    }

    public function render()
    {
        $courses = Course::withCount(['modules', 'enrollments'])->latest()->get();
        return view('livewire.admin-courses', ['courses' => $courses])
            ->layout('layouts.app', ['title' => 'Quản lý khóa học — Admin']);
    }
}
