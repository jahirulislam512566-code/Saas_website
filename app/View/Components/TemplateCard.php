<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TemplateCard extends Component
{
    public $id;
    public $name;
    public $category;
    public $description;
    public $image;
    public $pages;
    public $colors;
    public $popular;
    public $price;
    public $features;

    public function __construct(
        $id,
        $name,
        $category,
        $description,
        $image,
        $pages = 6,
        $colors = [],
        $popular = false,
        $price = 'Free',
        $features = []
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->category = $category;
        $this->description = $description;
        $this->image = $image;
        $this->pages = $pages;
        $this->colors = $colors;
        $this->popular = $popular;
        $this->price = $price;
        $this->features = $features;
    }

    public function render()
    {
        return view('components.template-card');
    }
}