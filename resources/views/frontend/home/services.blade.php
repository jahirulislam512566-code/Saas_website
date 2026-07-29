{{-- resources/views/frontend/home/services.blade.php --}}
<x-sections.services 
    title="Our Services"
    subtitle="What We Do Best"
    description="We provide end-to-end digital solutions that help businesses grow and succeed in the modern digital landscape."
    background="white"
    columns="3"
    :services="[
        [
            'icon' => '🎨',
            'title' => 'UI/UX Design',
            'description' => 'Create beautiful, intuitive interfaces that users love. We design experiences that are both functional and visually stunning.',
            'features' => ['User Research', 'Wireframing', 'Prototyping', 'User Testing', 'Design Systems'],
            'color' => 'primary',
            'link' => '#',
        ],
        [
            'icon' => '💻',
            'title' => 'Web Development',
            'description' => 'Build fast, secure, and scalable web applications using the latest technologies and best practices.',
            'features' => ['React/Vue', 'Laravel/Node', 'API Development', 'E-commerce', 'CMS Integration'],
            'color' => 'blue',
            'link' => '#',
            'featured' => true,
        ],
        [
            'icon' => '📱',
            'title' => 'Mobile Development',
            'description' => 'Create native and cross-platform mobile experiences that engage users and drive results.',
            'features' => ['iOS Development', 'Android Development', 'React Native', 'Flutter', 'App Store Deployment'],
            'color' => 'emerald',
            'link' => '#',
        ],
        [
            'icon' => '📊',
            'title' => 'Digital Strategy',
            'description' => 'Data-driven strategies that align with your business goals and deliver measurable results.',
            'features' => ['Market Research', 'Competitive Analysis', 'Growth Strategy', 'Performance Marketing', 'Analytics'],
            'color' => 'amber',
            'link' => '#',
        ],
        [
            'icon' => '🔍',
            'title' => 'SEO & Marketing',
            'description' => 'Increase your visibility and attract more customers with our proven SEO and marketing strategies.',
            'features' => ['SEO Optimization', 'Content Marketing', 'Social Media', 'Email Campaigns', 'PPC Advertising'],
            'color' => 'rose',
            'link' => '#',
        ],
        [
            'icon' => '🛡️',
            'title' => 'Cybersecurity',
            'description' => 'Protect your business with our comprehensive cybersecurity solutions and best practices.',
            'features' => ['Security Audit', 'Vulnerability Testing', 'Data Protection', 'Compliance', 'Training'],
            'color' => 'indigo',
            'link' => '#',
        ],
    ]"
/>