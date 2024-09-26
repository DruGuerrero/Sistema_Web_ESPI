<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SimpleCard extends Component
{
    public $title;
    public $subtitle;
    public $content;
    public $link;
    public $linkText;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $subtitle, $content, $link, $linkText)
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->content = $content;
        $this->link = $link;
        $this->linkText = $linkText;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.simple-card');
    }
}
