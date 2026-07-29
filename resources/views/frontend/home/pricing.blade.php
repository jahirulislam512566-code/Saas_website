{{-- resources/views/frontend/home/pricing.blade.php --}}
<x-sections.pricing 
    title="Choose Your Plan"
    subtitle="Pricing That Scales"
    description="Select the perfect plan for your business needs. All plans include a 14-day free trial."
    billing="true"
    default-billing="monthly"
    :plans="[
        [
            'name' => 'Starter',
            'price' => '29',
            'currency' => '$',
            'period' => 'month',
            'description' => 'Perfect for small businesses and startups.',
            'features' => [
                '1 Project',
                'Basic Support',
                '5GB Storage',
                'Analytics Dashboard',
                'Email Support',
            ],
            'ctaText' => 'Start Free Trial',
            'ctaLink' => '#',
            'highlighted' => false,
        ],
        [
            'name' => 'Professional',
            'price' => '99',
            'currency' => '$',
            'period' => 'month',
            'description' => 'Ideal for growing teams and businesses.',
            'features' => [
                '5 Projects',
                'Priority Support',
                '50GB Storage',
                'Advanced Analytics',
                'API Access',
                'Team Collaboration',
                '24/7 Support',
            ],
            'ctaText' => 'Start Free Trial',
            'ctaLink' => '#',
            'highlighted' => true,
            'badge' => 'Most Popular',
        ],
        [
            'name' => 'Enterprise',
            'price' => '299',
            'currency' => '$',
            'period' => 'month',
            'description' => 'For large organizations with complex needs.',
            'features' => [
                'Unlimited Projects',
                'Dedicated Support',
                '500GB Storage',
                'Custom Analytics',
                'Advanced Security',
                'Custom Development',
                'SLA Guarantee',
                'Training & Onboarding',
            ],
            'ctaText' => 'Contact Sales',
            'ctaLink' => '#',
            'highlighted' => false,
        ],
    ]"
/>