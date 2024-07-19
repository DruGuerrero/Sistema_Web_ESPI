<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AdvancedCard extends Component
{
    public $title;
    public $content;
    public $contentBlocks;
    public $leftButtonLink;
    public $rightButtonLink;

    public function __construct($title, $content = '', $contentBlocks = [], $leftButtonLink = '#', $rightButtonLink = '#')
    {
        $this->title = $title;
        $this->content = $content;
        $this->contentBlocks = $contentBlocks;
        $this->leftButtonLink = $leftButtonLink;
        $this->rightButtonLink = $rightButtonLink;
    }

    public function render()
    {
        return view('components.advanced-card');
    }
}