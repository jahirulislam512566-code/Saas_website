<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $data = [
            'philosophy' => [
                ['icon' => 'heart', 'title' => 'Passion for Food', 'description' => 'Every dish is created with love and attention to detail'],
                ['icon' => 'leaf', 'title' => 'Fresh Ingredients', 'description' => 'We source only the freshest, highest quality ingredients'],
                ['icon' => 'star', 'title' => 'Perfection', 'description' => 'Striving for excellence in every plate we serve']
            ]
        ];
        
        return view('restaurant.about', $data);
    }
}