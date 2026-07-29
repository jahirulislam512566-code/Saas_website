<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FeatureCard extends Component
{
    public $icon;
    public $title;
    public $description;
    public $color;
    public $popular;
    public $stats;

    public function __construct(
        $icon,
        $title,
        $description,
        $color = 'indigo',
        $popular = false,
        $stats = null
    ) {
        $this->icon = $icon;
        $this->title = $title;
        $this->description = $description;
        $this->color = $color;
        $this->popular = $popular;
        $this->stats = $stats;
    }

    public function render()
    {
        return view('components.feature-card');
    }
}