{{-- resources/views/frontend/home/contact.blade.php --}}
<x-sections.contact 
    title="Let's Talk About Your Project"
    subtitle="Get In Touch"
    description="Have a project in mind? We'd love to hear from you. Fill out the form below and we'll get back to you within 24 hours."
    form-action="#"
    form-method="POST"
    button-text="Send Message →"
    :info="[
        [
            'icon' => '📧',
            'title' => 'Email Us',
            'value' => 'hello@agencypro.com',
        ],
        [
            'icon' => '📍',
            'title' => 'Visit Us',
            'value' => '123 Innovation Drive, San Francisco, CA 94105',
        ],
        [
            'icon' => '📞',
            'title' => 'Call Us',
            'value' => '+1 (555) 123-4567',
        ],
        [
            'icon' => '🕐',
            'title' => 'Working Hours',
            'value' => 'Mon - Fri: 9:00 AM - 6:00 PM PST',
        ],
    ]"
/>