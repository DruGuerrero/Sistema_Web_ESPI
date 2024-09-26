<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PriceInput extends Component
{
    public $id;
    public $name;
    public $label;
    public $placeholder;
    public $currencySymbol;
    public $currency;

    public function __construct($id, $name, $placeholder, $currencySymbol, $currency, $label = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->currencySymbol = $currencySymbol;
        $this->currency = $currency;
    }

    public function render()
    {
        return view('components.price-input');
    }
}