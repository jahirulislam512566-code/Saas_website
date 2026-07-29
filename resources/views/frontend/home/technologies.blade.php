{{-- resources/views/frontend/home/technologies.blade.php --}}
<x-sections.technologies 
    title="Technologies We Use"
    subtitle="Our Tech Stack"
    description="We leverage modern technologies to build cutting-edge solutions for our clients."
    layout="grid"
    :technologies="[
        ['icon' => '⚛️', 'name' => 'React', 'description' => 'Frontend Framework'],
        ['icon' => '🟢', 'name' => 'Node.js', 'description' => 'Backend Runtime'],
        ['icon' => '🐘', 'name' => 'PHP', 'description' => 'Backend Language'],
        ['icon' => '🎨', 'name' => 'Tailwind CSS', 'description' => 'CSS Framework'],
        ['icon' => '📱', 'name' => 'React Native', 'description' => 'Mobile Framework'],
        ['icon' => '☁️', 'name' => 'AWS', 'description' => 'Cloud Platform'],
        ['icon' => '🐍', 'name' => 'Python', 'description' => 'Programming Language'],
        ['icon' => '🗄️', 'name' => 'MySQL', 'description' => 'Database'],
        ['icon' => '🔄', 'name' => 'Laravel', 'description' => 'PHP Framework'],
        ['icon' => '🎯', 'name' => 'Vue.js', 'description' => 'Frontend Framework'],
        ['icon' => '📊', 'name' => 'MongoDB', 'description' => 'NoSQL Database'],
        ['icon' => '🔷', 'name' => 'TypeScript', 'description' => 'Programming Language'],
    ]"
/>