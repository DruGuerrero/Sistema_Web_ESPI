<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SearchInputPreline extends Component
{
    public $placeholder;
    public $value;

    public function __construct($placeholder = 'Type a name', $value = '')
    {
        $this->placeholder = $placeholder;
        $this->value = $value;
    }

    public function render()
    {
        return view('components.search-input-preline');
    }
}