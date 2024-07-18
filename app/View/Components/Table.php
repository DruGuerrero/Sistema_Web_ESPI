<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Table extends Component
{
    public $headers;
    public $rows;

    public function __construct($headers, $rows)
    {
        $this->headers = $headers;
        $this->rows = $rows;
    }

    public function render()
    {
        return view('components.table');
    }
}
