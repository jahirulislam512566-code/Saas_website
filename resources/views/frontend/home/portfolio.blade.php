{{-- resources/views/frontend/home/portfolio.blade.php --}}
<x-sections.portfolio 
    title="Our Portfolio"
    subtitle="Featured Projects"
    description="Explore some of our recent work and see how we've helped brands achieve their digital goals."
    columns="3"
    filter="true"
    :projects="[
        [
            'image' => asset('images/portfolio/project1.jpg'),
            'title' => 'E-Commerce Platform for Retail Brand',
            'category' => 'Web Development',
            'tags' => ['Laravel', 'Vue.js', 'Stripe', 'AWS'],
            'link' => route('portfolio.show', 1),
            'lightbox' => true,
        ],
        [
            'image' => asset('images/portfolio/project2.jpg'),
            'title' => 'Mobile Banking App',
            'category' => 'Mobile Development',
            'tags' => ['React Native', 'Node.js', 'MongoDB', 'Firebase'],
            'link' => route('portfolio.show', 2),
            'lightbox' => true,
        ],
        [
            'image' => asset('images/portfolio/project3.jpg'),
            'title' => 'Brand Identity & Website Redesign',
            'category' => 'UI/UX Design',
            'tags' => ['Branding', 'Figma', 'Design System', 'Tailwind'],
            'link' => route('portfolio.show', 3),
            'lightbox' => true,
        ],
        [
            'image' => asset('images/portfolio/project4.jpg'),
            'title' => 'Healthcare Management Dashboard',
            'category' => 'Web Development',
            'tags' => ['React', 'Django', 'PostgreSQL', 'Docker'],
            'link' => route('portfolio.show', 4),
            'lightbox' => true,
        ],
        [
            'image' => asset('images/portfolio/project5.jpg'),
            'title' => 'Social Media Campaign Strategy',
            'category' => 'Digital Strategy',
            'tags' => ['Social Media', 'Content Strategy', 'Analytics', 'Growth'],
            'link' => route('portfolio.show', 5),
            'lightbox' => true,
        ],
        [
            'image' => asset('images/portfolio/project6.jpg'),
            'title' => 'E-Learning Platform',
            'category' => 'Mobile Development',
            'tags' => ['Flutter', 'Firebase', 'Video Streaming', 'Payment'],
            'link' => route('portfolio.show', 6),
            'lightbox' => true,
        ],
    ]"
/>