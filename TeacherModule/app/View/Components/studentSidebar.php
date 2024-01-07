<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class studentSidebar extends Component
{
    /**
     * Create a new component instance.
     */
    public $focus;

    public function __construct($focus)
    {
        $this->focus = $focus;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.student-sidebar');
    }
}
