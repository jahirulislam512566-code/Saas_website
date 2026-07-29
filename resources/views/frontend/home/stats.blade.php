{{-- resources/views/frontend/home/stats.blade.php --}}
<x-sections.stats 
    title="Our Impact in Numbers"
    subtitle="Results"
    description="We've helped hundreds of businesses achieve their goals through innovative digital solutions."
    :stats="[
        ['value' => '500+', 'label' => 'Projects Delivered', 'change' => 12],
        ['value' => '98%', 'label' => 'Client Satisfaction', 'change' => 2.5],
        ['value' => '50+', 'label' => 'Expert Team Members'],
        ['value' => '24/7', 'label' => 'Support Available'],
    ]"
/>