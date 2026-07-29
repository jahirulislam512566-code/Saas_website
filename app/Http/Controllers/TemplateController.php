<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = $this->getTemplateData();
        return view('frontend.templates.index', compact('templates'));
    }

    public function show($slug)
    {
        // Get all templates
        $allTemplates = $this->getTemplateData();
        $template = null;
        
        // Find template by slug or ID
        foreach ($allTemplates as $t) {
            // Check if slug matches or if numeric ID matches
            if (Str::slug($t['name']) == $slug || $t['id'] == $slug) {
                $template = $t;
                break;
            }
        }
        
        // If no template found, abort with 404
        if (!$template) {
            abort(404);
        }
        
        // Pass both template and templates (for related section)
        return view('frontend.templates.show', [
            'template' => $template,
            'templates' => $allTemplates
        ]);
    }

    public function category($category)
    {
        $allTemplates = $this->getTemplateData();
        $templates = array_filter($allTemplates, function($template) use ($category) {
            return $template['category_slug'] === $category;
        });
        
        return view('frontend.templates.index', ['templates' => $templates]);
    }

    public function useTemplate(Request $request, $id)
    {
        // Find template
        $templates = $this->getTemplateData();
        $template = null;
        
        foreach ($templates as $t) {
            if ($t['id'] == $id) {
                $template = $t;
                break;
            }
        }
        
        if (!$template) {
            abort(404);
        }
        
        // Logic to use template
        $user = $request->user();
        
        // Check if user can create more websites
        $subscription = $user->subscriptions()->where('status', 'active')->first();
        $maxWebsites = $subscription?->plan->max_websites ?? 1;
        $currentWebsites = $user->websites()->count();
        
        if ($currentWebsites >= $maxWebsites) {
            return redirect()->back()->with('error', 'You have reached the maximum number of websites allowed for your plan.');
        }
        
        // Create website
        $website = $user->websites()->create([
            'name' => $template['name'] . ' Website',
            'template_id' => $template['id'],
            'theme' => Str::slug($template['name']),
            'status' => 'draft',
        ]);
        
        return redirect()->route('builder', ['website' => $website->id])
            ->with('success', 'Template applied successfully!');
    }

    private function getTemplateData()
    {
        return [
            [
                'id' => 1,
                'name' => 'Business Pro',
                'category' => 'Business',
                'category_slug' => 'business',
                'description' => 'Modern business template with professional design and advanced features.',
                'image' => 'https://placehold.co/600x400/4F46E5/white?text=Business+Pro',
                'pages' => 12,
                'price' => 'Free',
                'popular' => true,
                'is_free' => true,
                'is_new' => false,
                'features' => ['Responsive', 'SEO Optimized', 'Blog Ready'],
                'rating' => 4.8,
                'reviews' => 156,
                'used_by' => 1234,
                'colors' => ['#4F46E5', '#7C3AED', '#EC4899']
            ],
            [
                'id' => 2,
                'name' => 'Restaurant Deluxe',
                'category' => 'Restaurant',
                'category_slug' => 'restaurant',
                'description' => 'Beautiful restaurant template with menu showcase and reservation system.',
                'image' => 'https://placehold.co/600x400/DC2626/white?text=Restaurant+Deluxe',
                'pages' => 8,
                'price' => '$49',
                'popular' => true,
                'is_free' => false,
                'is_new' => false,
                'features' => ['Menu Builder', 'Reservation', 'Gallery'],
                'rating' => 4.7,
                'reviews' => 89,
                'used_by' => 567,
                'colors' => ['#DC2626', '#F59E0B', '#92400E']
            ],
            [
                'id' => 3,
                'name' => 'Portfolio Creative',
                'category' => 'Portfolio',
                'category_slug' => 'portfolio',
                'description' => 'Stunning portfolio template for creative professionals and artists.',
                'image' => 'https://placehold.co/600x400/EC4899/white?text=Portfolio+Creative',
                'pages' => 6,
                'price' => 'Free',
                'popular' => false,
                'is_free' => true,
                'is_new' => true,
                'features' => ['Gallery', 'Filter', 'Lightbox'],
                'rating' => 4.9,
                'reviews' => 203,
                'used_by' => 890,
                'colors' => ['#EC4899', '#8B5CF6', '#F59E0B']
            ],
            [
                'id' => 4,
                'name' => 'School Academy',
                'category' => 'School',
                'category_slug' => 'school',
                'description' => 'Complete school website template with course management and events.',
                'image' => 'https://placehold.co/600x400/3B82F6/white?text=School+Academy',
                'pages' => 10,
                'price' => '$79',
                'popular' => false,
                'is_free' => false,
                'is_new' => false,
                'features' => ['Courses', 'Events', 'Staff Directory'],
                'rating' => 4.6,
                'reviews' => 67,
                'used_by' => 345,
                'colors' => ['#3B82F6', '#06B6D4', '#10B981']
            ],
            [
                'id' => 5,
                'name' => 'Doctor Health',
                'category' => 'Doctor',
                'category_slug' => 'doctor',
                'description' => 'Professional medical template with appointment booking and patient management.',
                'image' => 'https://placehold.co/600x400/0891B2/white?text=Doctor+Health',
                'pages' => 9,
                'price' => '$59',
                'popular' => true,
                'is_free' => false,
                'is_new' => false,
                'features' => ['Appointments', 'Services', 'Patient Portal'],
                'rating' => 4.8,
                'reviews' => 112,
                'used_by' => 678,
                'colors' => ['#0891B2', '#0EA5E9', '#10B981']
            ],
            [
                'id' => 6,
                'name' => 'Agency Modern',
                'category' => 'Agency',
                'category_slug' => 'agency',
                'description' => 'Modern agency template with project showcase and team management.',
                'image' => 'https://placehold.co/600x400/7C3AED/white?text=Agency+Modern',
                'pages' => 14,
                'price' => 'Free',
                'popular' => true,
                'is_free' => true,
                'is_new' => false,
                'features' => ['Projects', 'Team', 'Client Portal'],
                'rating' => 4.9,
                'reviews' => 234,
                'used_by' => 1234,
                'colors' => ['#7C3AED', '#4F46E5', '#EC4899']
            ],
            [
                'id' => 7,
                'name' => 'Construction Pro',
                'category' => 'Construction',
                'category_slug' => 'construction',
                'description' => 'Professional construction template with project portfolio and service showcase.',
                'image' => 'https://placehold.co/600x400/F59E0B/white?text=Construction+Pro',
                'pages' => 10,
                'price' => '$49',
                'popular' => false,
                'is_free' => false,
                'is_new' => true,
                'features' => ['Projects', 'Services', 'Gallery'],
                'rating' => 4.5,
                'reviews' => 45,
                'used_by' => 234,
                'colors' => ['#F59E0B', '#92400E', '#B45309']
            ],
            [
                'id' => 8,
                'name' => 'Law Firm',
                'category' => 'Law Firm',
                'category_slug' => 'law-firm',
                'description' => 'Elegant law firm template with practice areas and attorney profiles.',
                'image' => 'https://placehold.co/600x400/1E293B/white?text=Law+Firm',
                'pages' => 8,
                'price' => '$69',
                'popular' => false,
                'is_free' => false,
                'is_new' => false,
                'features' => ['Practice Areas', 'Attorney Profiles', 'Case Studies'],
                'rating' => 4.7,
                'reviews' => 78,
                'used_by' => 456,
                'colors' => ['#1E293B', '#475569', '#94A3B8']
            ],
            [
                'id' => 9,
                'name' => 'Travel Explorer',
                'category' => 'Travel',
                'category_slug' => 'travel',
                'description' => 'Adventure travel template with destination showcase and booking system.',
                'image' => 'https://placehold.co/600x400/06B6D4/white?text=Travel+Explorer',
                'pages' => 12,
                'price' => '$59',
                'popular' => false,
                'is_free' => false,
                'is_new' => true,
                'features' => ['Destinations', 'Booking', 'Gallery'],
                'rating' => 4.6,
                'reviews' => 56,
                'used_by' => 345,
                'colors' => ['#06B6D4', '#0891B2', '#0EA5E9']
            ],
            [
                'id' => 10,
                'name' => 'Photography Studio',
                'category' => 'Photography',
                'category_slug' => 'photography',
                'description' => 'Stunning photography template with portfolio gallery and client booking.',
                'image' => 'https://placehold.co/600x400/8B5CF6/white?text=Photography+Studio',
                'pages' => 8,
                'price' => 'Free',
                'popular' => true,
                'is_free' => true,
                'is_new' => false,
                'features' => ['Gallery', 'Booking', 'Client Portal'],
                'rating' => 4.8,
                'reviews' => 167,
                'used_by' => 789,
                'colors' => ['#8B5CF6', '#6D28D9', '#4C1D95']
            ],
            [
                'id' => 11,
                'name' => 'Gym Fitness',
                'category' => 'Gym',
                'category_slug' => 'gym',
                'description' => 'Modern gym template with class schedules, membership, and trainer profiles.',
                'image' => 'https://placehold.co/600x400/EF4444/white?text=Gym+Fitness',
                'pages' => 10,
                'price' => '$39',
                'popular' => false,
                'is_free' => false,
                'is_new' => false,
                'features' => ['Class Schedule', 'Membership', 'Trainers'],
                'rating' => 4.4,
                'reviews' => 34,
                'used_by' => 123,
                'colors' => ['#EF4444', '#DC2626', '#B91C1C']
            ],
        ];
    }
}