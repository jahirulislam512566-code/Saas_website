{{-- resources/views/frontend/home/process.blade.php --}}
<x-sections.process 
    title="Our Process"
    subtitle="How We Work"
    description="A transparent and collaborative approach to deliver exceptional results."
    layout="horizontal"
    :steps="[
        [
            'icon' => '🔍',
            'title' => 'Discovery',
            'description' => 'We learn about your business, goals, and challenges to create a tailored strategy.'
        ],
        [
            'icon' => '📋',
            'title' => 'Planning',
            'description' => 'We develop a detailed plan with clear milestones and timelines.'
        ],
        [
            'icon' => '🎨',
            'title' => 'Design & Development',
            'description' => 'We create and iterate on your solution, keeping you involved every step of the way.'
        ],
        [
            'icon' => '🚀',
            'title' => 'Launch & Support',
            'description' => 'We deploy your solution and provide ongoing support to ensure success.'
        ],
    ]"
/>