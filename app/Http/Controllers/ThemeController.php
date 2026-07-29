<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function home()
    {
        $theme = config('themes.active');
        
        $data = match($theme) {
            'restaurant' => [
                'featuredDishes' => [
                    ['name' => 'Grilled Salmon', 'price' => '$28', 'description' => 'Fresh Atlantic salmon with seasonal vegetables', 'image' => '/images/salmon.jpg'],
                    ['name' => 'Beef Tenderloin', 'price' => '$34', 'description' => 'Premium beef tenderloin with truffle mash', 'image' => '/images/steak.jpg'],
                    ['name' => 'Lobster Bisque', 'price' => '$18', 'description' => 'Rich and creamy lobster bisque', 'image' => '/images/bisque.jpg'],
                ],
                'testimonials' => [
                    ['name' => 'John Smith', 'text' => 'Best dining experience I\'ve had in years. The attention to detail is remarkable.', 'position' => 'Food Critic'],
                    ['name' => 'Emily Davis', 'text' => 'The ambiance and service were exceptional. Will definitely come back.', 'position' => 'Regular Customer'],
                    ['name' => 'Michael Brown', 'text' => 'Incredible food and wonderful staff. A true gem in the city.', 'position' => 'VIP Guest'],
                ],
                'navigation' => config('themes.themes.restaurant.navigation'),
                'footerLinks' => config('themes.themes.restaurant.footer'),
            ],
            'portfolio' => [
                'featuredProjects' => [
                    ['title' => 'E-commerce Platform', 'category' => 'Web Development', 'description' => 'Full-featured online store with payment integration', 'image' => '/images/project1.jpg', 'link' => '/projects/1'],
                    ['title' => 'Brand Identity', 'category' => 'Design', 'description' => 'Complete brand redesign and identity system', 'image' => '/images/project2.jpg', 'link' => '/projects/2'],
                    ['title' => 'Mobile App', 'category' => 'Mobile Development', 'description' => 'Cross-platform mobile application', 'image' => '/images/project3.jpg', 'link' => '/projects/3'],
                ],
            ],
            'agency' => [
                'clients' => [
                    ['name' => 'Google', 'logo' => '/images/google-logo.png'],
                    ['name' => 'Microsoft', 'logo' => '/images/microsoft-logo.png'],
                    ['name' => 'Amazon', 'logo' => '/images/amazon-logo.png'],
                    ['name' => 'Apple', 'logo' => '/images/apple-logo.png'],
                ],
                'services' => [
                    ['title' => 'Branding', 'description' => 'Create a strong, memorable brand identity', 'icon' => 'user', 'link' => '/services/branding'],
                    ['title' => 'Marketing', 'description' => 'Strategic marketing campaigns that deliver results', 'icon' => 'cog', 'link' => '/services/marketing'],
                    ['title' => 'Development', 'description' => 'Custom web and mobile development', 'icon' => 'layout-dashboard', 'link' => '/services/development'],
                ],
            ],
            'gym' => [
                'todayClasses' => [
                    ['name' => 'Yoga Flow', 'instructor' => 'Sarah Johnson', 'time' => '9:00 AM', 'spots' => 8],
                    ['name' => 'HIIT Training', 'instructor' => 'Mike Thompson', 'time' => '10:30 AM', 'spots' => 12],
                    ['name' => 'Spinning', 'instructor' => 'Lisa Anderson', 'time' => '12:00 PM', 'spots' => 5],
                ],
            ],
            'school' => [
                'events' => [
                    ['title' => 'Open House', 'day' => '15', 'date' => 'March 15, 2024', 'description' => 'Visit our campus and meet our teachers'],
                    ['title' => 'Science Fair', 'day' => '22', 'date' => 'March 22, 2024', 'description' => 'Student science projects and experiments'],
                    ['title' => 'Sports Day', 'day' => '29', 'date' => 'March 29, 2024', 'description' => 'Annual sports competition and activities'],
                ],
            ],
            default => [],
        };
        
        $data['navigation'] = config("themes.themes.{$theme}.navigation", []);
        $data['footerLinks'] = config("themes.themes.{$theme}.footer", []);
        
        return view("{$theme}.home", $data);
    }
    
    // Add similar methods for about, contact, and other pages
}