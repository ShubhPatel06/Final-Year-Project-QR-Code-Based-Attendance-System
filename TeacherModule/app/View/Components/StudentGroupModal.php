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
    public $students;

    public function __construct($students)
    {
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
