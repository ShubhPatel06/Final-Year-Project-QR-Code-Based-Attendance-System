<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class StudentGroupModal extends Component
{
    /**
     * Create a new component instance.
     */
    public $groups;
    public $lectures;
    public $students;

    public function __construct($lectures, $groups, $students)
    {
        $this->lectures = $lectures;
        $this->groups = $groups;
        $this->students = $students;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.student-group-modal');
    }
}
