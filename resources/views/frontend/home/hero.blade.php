{{-- resources/views/frontend/home/hero.blade.php --}}
<x-sections.hero 
    title="We Build <span class='text-[#6c5ce7]'>Digital Experiences</span> That Drive Growth"
    subtitle="Strategy, design, and development for forward-thinking brands. We combine creativity with technology to deliver exceptional results."
    image="{{ asset('images/hero-illustration.svg') }}"
    primary-text="Start Your Project"
    primary-link="#contact"
    secondary-text="Explore Our Work"
    secondary-link="#portfolio"
    badge="🚀 Trusted by 500+ Companies Worldwide"
    alignment="left"
    :stats="[
        ['value' => '500+', 'label' => 'Projects Delivered'],
        ['value' => '98%', 'label' => 'Client Satisfaction'],
        ['value' => '4.9/5', 'label' => 'Average Rating'],
        ['value' => '50+', 'label' => 'Team Members'],
    ]"
/>