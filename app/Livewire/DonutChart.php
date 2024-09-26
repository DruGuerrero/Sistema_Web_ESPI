<?php

namespace App\Livewire;

use Livewire\Component;

class DonutChart extends Component
{
    public $series;
    public $labels;
    public $id;

    /**
     * Create a new component instance.
     *
     * @param array $series
     * @param array $labels
     * @param string $id
     */
    public function __construct($series = [], $labels = [], $id = 'default-id')
    {
        $this->series = $series;
        $this->labels = $labels;
        $this->id = $id;
    }

    public function render()
    {
        return view('livewire.donut-chart');
    }
}