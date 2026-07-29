<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $data = [
            'categories' => [
                ['name' => 'Appetizers', 'items' => [
                    ['name' => 'Bruschetta', 'price' => '$12', 'description' => 'Toasted bread with tomatoes, garlic, and basil', 'image' => '/assets/images/bruschetta.jpg', 'dietary' => ['Vegetarian']],
                    ['name' => 'Calamari', 'price' => '$14', 'description' => 'Crispy fried calamari with marinara sauce', 'image' => '/assets/images/calamari.jpg', 'dietary' => ['Gluten-Free']]
                ]],
                ['name' => 'Main Course', 'items' => [
                    ['name' => 'Grilled Salmon', 'price' => '$28', 'description' => 'Fresh Atlantic salmon with vegetables', 'image' => '/assets/images/salmon.jpg', 'dietary' => ['Gluten-Free']],
                    ['name' => 'Beef Tenderloin', 'price' => '$34', 'description' => 'Premium beef with truffle mash', 'image' => '/assets/images/steak.jpg', 'dietary' => []]
                ]]
            ]
        ];
        
        return view('restaurant.menu', $data);
    }
}