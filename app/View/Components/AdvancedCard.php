<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AdvancedCard extends Component
{
    public $title;
    public $image;
    public $content;
    public $contentBlocks;
    public $leftButtonLink;
    public $leftButtonText;
    public $rightButtonLink;
    public $rightButtonText;

    public function __construct($title, $image = '',$content = '', $contentBlocks = [], $leftButtonLink = '#', $leftButtonText = 'Left Button', $rightButtonLink = '#', $rightButtonText = 'Right Button')
    {
        $this->title = $title;
        $this->image = $image;
        $this->content = $content;
        $this->contentBlocks = $contentBlocks;
        $this->leftButtonLink = $leftButtonLink;
        $this->leftButtonText = $leftButtonText;
        $this->rightButtonLink = $rightButtonLink;
        $this->rightButtonText = $rightButtonText;
    }

    public function render()
    {
        return view('components.advanced-card');
    }
}