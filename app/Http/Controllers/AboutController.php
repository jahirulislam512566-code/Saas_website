<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use App\Models\Milestone;

class AboutController extends Controller
{
    public function index()
    {
        $teamMembers = TeamMember::where('is_active', true)->get();
        $milestones = Milestone::orderBy('year', 'asc')->get();
        $stats = [
            ['value' => '8+', 'label' => 'Years in Business'],
            ['value' => '500+', 'label' => 'Projects Delivered'],
            ['value' => '98%', 'label' => 'Client Satisfaction'],
            ['value' => '50+', 'label' => 'Team Members'],
        ];

        return view('frontend.about.index', compact('teamMembers', 'milestones', 'stats'));
    }

    public function mission()
    {
        return view('frontend.about.mission');
    }

    public function vision()
    {
        return view('frontend.about.vision');
    }

    public function history()
    {
        $milestones = Milestone::orderBy('year', 'asc')->get();
        return view('frontend.about.history', compact('milestones'));
    }

    public function team()
    {
        $teamMembers = TeamMember::where('is_active', true)->get();
        return view('frontend.about.team', compact('teamMembers'));
    }

    public function values()
    {
        return view('frontend.about.values');
    }
}