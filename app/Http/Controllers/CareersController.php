<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class CareersController extends Controller
{
    /**
     * Display the careers page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $openings = $this->getJobOpenings();
        $benefits = $this->getBenefits();
        $culture = $this->getCulture();

        return view('frontend.pages.careers', compact('openings', 'benefits', 'culture'));
    }

    /**
     * Display all job openings.
     *
     * @return \Illuminate\View\View
     */
    public function openings()
    {
        $openings = $this->getJobOpenings();
        
        return view('frontend.pages.careers-openings', compact('openings'));
    }

    /**
     * Display a specific job opening.
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $openings = $this->getJobOpenings();
        $job = collect($openings)->firstWhere('slug', $slug);

        if (!$job) {
            abort(404, 'Job opening not found.');
        }

        return view('frontend.pages.careers-show', compact('job'));
    }

    /**
     * Handle job application submission.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function apply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'position' => 'required|string|max:255',
            'message' => 'nullable|string',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120', // Max 5MB
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Handle file upload
        $resumePath = $request->file('resume')->store('resumes', 'public');

        // Send email notification
        try {
            Mail::to('careers@agencypro.com')->send(new \App\Mail\JobApplicationMail([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'position' => $request->position,
                'message' => $request->message,
                'resume_path' => $resumePath,
            ]));
        } catch (\Exception $e) {
            // Log error but continue
            \Log::error('Job application email failed: ' . $e->getMessage());
        }

        return redirect()->route('careers.index')
            ->with('success', 'Your application has been submitted successfully! We will review it and get back to you soon.');
    }

    /**
     * Get job openings data.
     *
     * @return array
     */
    private function getJobOpenings()
    {
        return [
            [
                'id' => 1,
                'slug' => 'senior-full-stack-developer',
                'title' => 'Senior Full-Stack Developer',
                'department' => 'Engineering',
                'location' => 'Remote',
                'type' => 'Full-Time',
                'salary' => '$120k - $150k',
                'description' => 'We\'re looking for an experienced Full-Stack Developer to join our engineering team. You\'ll be building scalable web applications using modern technologies.',
                'requirements' => [
                    '5+ years of experience in full-stack development',
                    'Expertise in React, Vue.js, or similar frameworks',
                    'Strong backend experience with Laravel, Node.js, or Python',
                    'Experience with cloud services (AWS, Azure, GCP)',
                    'Knowledge of database design and optimization',
                    'Excellent problem-solving skills',
                    'Strong communication and teamwork abilities',
                ],
                'nice_to_have' => [
                    'Experience with Docker and Kubernetes',
                    'Knowledge of TypeScript',
                    'Open source contributions',
                    'Experience with microservices architecture',
                ],
                'benefits' => [
                    'Competitive salary',
                    'Remote work flexibility',
                    'Health insurance',
                    'Learning budget',
                    'Paid time off',
                ],
                'posted_at' => '2024-01-15',
                'icon' => 'fa-code',
            ],
            [
                'id' => 2,
                'slug' => 'ui-ux-designer',
                'title' => 'UI/UX Designer',
                'department' => 'Design',
                'location' => 'Remote',
                'type' => 'Full-Time',
                'salary' => '$90k - $120k',
                'description' => 'We\'re seeking a talented UI/UX Designer to create beautiful, user-centered designs for our clients. You\'ll work closely with our development team to bring designs to life.',
                'requirements' => [
                    '3+ years of experience in UI/UX design',
                    'Proficiency in Figma, Sketch, or Adobe XD',
                    'Strong portfolio demonstrating design skills',
                    'Experience with design systems',
                    'Understanding of user research and testing',
                    'Knowledge of accessibility standards',
                ],
                'nice_to_have' => [
                    'Experience with HTML/CSS',
                    'Knowledge of animation tools (After Effects, Lottie)',
                    'Experience with user research',
                    'Design system creation experience',
                ],
                'benefits' => [
                    'Competitive salary',
                    'Remote work flexibility',
                    'Health insurance',
                    'Learning budget',
                    'Paid time off',
                ],
                'posted_at' => '2024-01-20',
                'icon' => 'fa-paint-brush',
            ],
            [
                'id' => 3,
                'slug' => 'digital-marketing-specialist',
                'title' => 'Digital Marketing Specialist',
                'department' => 'Marketing',
                'location' => 'Remote',
                'type' => 'Full-Time',
                'salary' => '$80k - $110k',
                'description' => 'We\'re looking for a Digital Marketing Specialist to drive growth through SEO, content marketing, and social media strategies. You\'ll help our clients achieve their marketing goals.',
                'requirements' => [
                    '3+ years of experience in digital marketing',
                    'Expertise in SEO and content strategy',
                    'Experience with Google Analytics and SEO tools',
                    'Strong understanding of social media marketing',
                    'Excellent writing and communication skills',
                    'Data-driven mindset',
                ],
                'nice_to_have' => [
                    'Experience with paid advertising (Google Ads, Facebook Ads)',
                    'Knowledge of email marketing platforms',
                    'Experience with marketing automation tools',
                    'Certifications in Google Analytics, HubSpot, etc.',
                ],
                'benefits' => [
                    'Competitive salary',
                    'Remote work flexibility',
                    'Health insurance',
                    'Learning budget',
                    'Paid time off',
                ],
                'posted_at' => '2024-01-25',
                'icon' => 'fa-chart-line',
            ],
            [
                'id' => 4,
                'slug' => 'project-manager',
                'title' => 'Project Manager',
                'department' => 'Operations',
                'location' => 'Remote',
                'type' => 'Full-Time',
                'salary' => '$95k - $130k',
                'description' => 'We\'re looking for an experienced Project Manager to lead client projects and ensure successful delivery.',
                'requirements' => [
                    '5+ years of project management experience',
                    'PMP certification preferred',
                    'Experience with agile methodologies',
                    'Strong leadership and communication skills',
                    'Experience with project management tools (Jira, Trello, Asana)',
                    'Client-facing experience',
                ],
                'nice_to_have' => [
                    'Experience in digital agency environment',
                    'Knowledge of software development lifecycle',
                    'Scrum Master certification',
                ],
                'benefits' => [
                    'Competitive salary',
                    'Remote work flexibility',
                    'Health insurance',
                    'Learning budget',
                    'Paid time off',
                ],
                'posted_at' => '2024-02-01',
                'icon' => 'fa-tasks',
            ],
        ];
    }

    /**
     * Get company benefits.
     *
     * @return array
     */
    private function getBenefits()
    {
        return [
            [
                'icon' => '🏖️',
                'title' => 'Flexible Work',
                'description' => 'Remote-first culture with flexible hours and work arrangements.',
            ],
            [
                'icon' => '📚',
                'title' => 'Learning Budget',
                'description' => 'Annual budget for courses, conferences, and professional development.',
            ],
            [
                'icon' => '🩺',
                'title' => 'Health Benefits',
                'description' => 'Comprehensive health, dental, and vision coverage for you and your family.',
            ],
            [
                'icon' => '🚀',
                'title' => 'Growth Opportunities',
                'description' => 'Clear career progression paths and mentorship programs.',
            ],
            [
                'icon' => '🌴',
                'title' => 'Paid Time Off',
                'description' => 'Generous vacation policy and paid holidays.',
            ],
            [
                'icon' => '🎉',
                'title' => 'Team Events',
                'description' => 'Regular team building activities and social events.',
            ],
            [
                'icon' => '💻',
                'title' => 'Tech Stipend',
                'description' => 'Stipend for home office setup and equipment.',
            ],
            [
                'icon' => '🌟',
                'title' => 'Recognition Program',
                'description' => 'Monthly recognition and rewards for outstanding performance.',
            ],
        ];
    }

    /**
     * Get company culture values.
     *
     * @return array
     */
    private function getCulture()
    {
        return [
            [
                'title' => 'Innovation First',
                'description' => 'We encourage creative thinking and embrace new ideas.',
                'icon' => '💡',
            ],
            [
                'title' => 'Collaborative Spirit',
                'description' => 'We work together, share knowledge, and support each other.',
                'icon' => '🤝',
            ],
            [
                'title' => 'Continuous Learning',
                'description' => 'We believe in lifelong learning and professional growth.',
                'icon' => '📖',
            ],
            [
                'title' => 'Client Success',
                'description' => 'We\'re committed to delivering exceptional value to our clients.',
                'icon' => '⭐',
            ],
        ];
    }
}