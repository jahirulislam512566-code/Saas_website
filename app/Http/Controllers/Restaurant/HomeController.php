<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'featuredDishes' => [
                ['name' => 'Grilled Salmon', 'price' => '$28', 'description' => 'Fresh Atlantic salmon with seasonal vegetables', 'image' => '/assets/images/salmon.jpg'],
                ['name' => 'Beef Tenderloin', 'price' => '$34', 'description' => 'Premium beef tenderloin with truffle mash', 'image' => '/assets/images/steak.jpg'],
                ['name' => 'Lobster Bisque', 'price' => '$18', 'description' => 'Rich and creamy lobster bisque', 'image' => '/assets/images/bisque.jpg']
            ],
            'testimonials' => [
                ['name' => 'John Smith', 'text' => 'Best dining experience I\'ve had in years.', 'position' => 'Food Critic'],
                ['name' => 'Emily Davis', 'text' => 'The ambiance and service were exceptional.', 'position' => 'Regular Customer'],
                ['name' => 'Michael Brown', 'text' => 'Incredible food and wonderful staff.', 'position' => 'VIP Guest']
            ]
        ];
        
        return view('restaurant.home', $data);
    }
}