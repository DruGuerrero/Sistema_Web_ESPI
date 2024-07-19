<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectFilterPreline extends Component
{
    public $options;
    public $placeholder;
    public $name;
    public $selected;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($options = [], $placeholder = 'Select option...', $name = 'filter', $selected = '')
    {
        $this->options = $options;
        $this->placeholder = $placeholder;
        $this->name = $name;
        $this->selected = $selected;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.select-filter-preline');
    }
}