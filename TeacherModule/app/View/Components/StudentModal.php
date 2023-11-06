<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class StudentModal extends Component
{
    /**
     * Create a new component instance.
     */
    public $courses;
    public $users;
    public $groups;

    public function __construct($courses, $users, $groups)
    {
        $this->courses = $courses;
        $this->users = $users;
        $this->groups = $groups;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.student-modal');
    }
}
