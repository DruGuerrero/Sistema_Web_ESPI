<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AdvancedButton extends Component
{
    public $image;
    public $text;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($image, $text)
    {
        $this->image = $image;
        $this->text = $text;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.advanced-button');
    }
}