<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class CourseModal extends Component
{
    /**
     * Create a new component instance.
     */
    public $faculties;

    public function __construct($faculties)
    {
        $this->faculties = $faculties;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.course-modal');
    }
}
