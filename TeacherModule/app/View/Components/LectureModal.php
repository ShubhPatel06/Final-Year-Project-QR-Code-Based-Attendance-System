<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class LectureModal extends Component
{
    /**
     * Create a new component instance.
     */
    public $courses;
    public $lecturers;

    public function __construct($courses, $lecturers)
    {
        $this->courses = $courses;
        $this->lecturers = $lecturers;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.lecture-modal');
    }
}
