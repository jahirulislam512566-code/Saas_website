<?php
// app/Http/Controllers/Website/AboutController.php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\Milestone;
use App\Models\Partner;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Display the about page.
     */
    public function index()
    {
        // Team members
        $teamMembers = TeamMember::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Testimonials
        $testimonials = Testimonial::where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        // Company milestones
      //   $milestones = Milestone::where('is_active', true)
      //       ->orderBy('year')
      //       ->get();

        // Partners/Clients
      //   $partners = Partner::where('is_active', true)
      //       ->orderBy('sort_order')
      //       ->get();

        // Company stats
        $stats = [
            'years_experience' => 8,
            'projects_completed' => 500,
            'happy_clients' => 350,
            'team_members' => 45,
        ];

        // Core values
        $values = [
            (object) [
                'icon' => 'fa-star',
                'title' => 'Excellence',
                'description' => 'We strive for excellence in everything we do.'
            ],
            (object) [
                'icon' => 'fa-handshake',
                'title' => 'Integrity',
                'description' => 'We believe in transparency and honesty.'
            ],
            (object) [
                'icon' => 'fa-lightbulb',
                'title' => 'Innovation',
                'description' => 'We push boundaries to solve real problems.'
            ],
            (object) [
                'icon' => 'fa-users',
                'title' => 'Teamwork',
                'description' => 'We work together to achieve great results.'
            ],
        ];

        // Mission & Vision
        $mission = "To democratize access to enterprise-grade SaaS tools and empower businesses of all sizes to compete in the digital economy.";
        $vision = "To become the world's most trusted SaaS platform, enabling businesses to build, scale, and innovate without limits.";

        return view('website.about', compact(
            'teamMembers',
            'testimonials',
            // 'milestones',
            // 'partners',
            'stats',
            'values',
            'mission',
            'vision'
        ));
    }

    /**
     * Display the team page.
     */
    public function team()
    {
        $teamMembers = TeamMember::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $departments = $teamMembers->groupBy('department');

        return view('website.team', compact('teamMembers', 'departments'));
    }

    /**
     * Display a specific team member.
     */
    public function teamMember($slug)
    {
        $member = TeamMember::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('website.team-member', compact('member'));
    }
}