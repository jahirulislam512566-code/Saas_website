{{-- resources/views/frontend/home/features.blade.php --}}
<x-sections.features 
    title="Powerful Features for Your Business"
    subtitle="What You Get"
    description="Our platform comes packed with features designed to help you succeed in the digital landscape."
    layout="grid"
    :features="[
        [
            'icon' => '⚡',
            'title' => 'Lightning Fast Performance',
            'description' => 'Optimized for speed with CDN integration and caching strategies.'
        ],
        [
            'icon' => '📱',
            'title' => 'Mobile Responsive',
            'description' => 'Beautiful experiences on every device, from desktop to mobile.'
        ],
        [
            'icon' => '🔍',
            'title' => 'SEO Optimized',
            'description' => 'Built with SEO best practices to help you rank higher in search results.'
        ],
        [
            'icon' => '🛡️',
            'title' => 'Enterprise Security',
            'description' => 'Advanced security features to protect your data and your customers.'
        ],
        [
            'icon' => '📊',
            'title' => 'Analytics Dashboard',
            'description' => 'Real-time insights into your performance and user behavior.'
        ],
        [
            'icon' => '🤝',
            'title' => 'Collaboration Tools',
            'description' => 'Work seamlessly with your team and stakeholders.'
        ],
    ]"
/>