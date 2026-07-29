{{-- resources/views/frontend/home/newsletter.blade.php --}}
<x-sections.newsletter 
    title="Subscribe to Our Newsletter"
    subtitle="Stay Updated"
    description="Join 10,000+ subscribers and get weekly insights, tips, and updates delivered to your inbox."
    form-action="{{ route('api.newsletter.subscribe') }}"
    form-method="POST"
    button-text="Subscribe Now"
    placeholder="Enter your email address"
    privacy-text="We respect your privacy. No spam, unsubscribe anytime."
/>