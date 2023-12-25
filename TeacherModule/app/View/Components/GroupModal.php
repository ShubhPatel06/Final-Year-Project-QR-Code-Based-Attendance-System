<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class GroupModal extends Component
{
    /**
     * Create a new component instance.
     */
    public $divisions;

    public function __construct($divisions)
    {
        $this->divisions = $divisions;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.group-modal');
    }
}
