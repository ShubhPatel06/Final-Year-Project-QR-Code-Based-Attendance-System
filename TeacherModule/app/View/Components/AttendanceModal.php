<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class AttendanceModal extends Component
{
    /**
     * Create a new component instance.
     */
    // public $groups;
    public $lectures;

    public function __construct($lectures)
    {
        $this->lectures = $lectures;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.attendance-modal');
    }
}
