<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class RegisterLectureModal extends Component
{
    /**
     * Create a new component instance.
     */
    public $courseLectures;


    public function __construct($courseLectures)
    {
        $this->courseLectures = $courseLectures;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.register-lecture-modal');
    }
}
