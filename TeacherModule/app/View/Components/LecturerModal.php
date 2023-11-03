<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class LecturerModal extends Component
{
    /**
     * Create a new component instance.
     */
    public $faculties;
    public $users;

    public function __construct($faculties, $users)
    {
        $this->faculties = $faculties;
        $this->users = $users;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.lecturer-modal');
    }
}
