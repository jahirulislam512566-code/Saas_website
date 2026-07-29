
<?php if (isset($component)) { $__componentOriginal3c60356ca7bfc9a60eb5905961d180db = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3c60356ca7bfc9a60eb5905961d180db = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sections.services','data' => ['title' => 'Our Services','subtitle' => 'What We Do Best','description' => 'We provide end-to-end digital solutions that help businesses grow and succeed in the modern digital landscape.','background' => 'white','columns' => '3','services' => [
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
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sections.services'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Our Services','subtitle' => 'What We Do Best','description' => 'We provide end-to-end digital solutions that help businesses grow and succeed in the modern digital landscape.','background' => 'white','columns' => '3','services' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
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
    ])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3c60356ca7bfc9a60eb5905961d180db)): ?>
<?php $attributes = $__attributesOriginal3c60356ca7bfc9a60eb5905961d180db; ?>
<?php unset($__attributesOriginal3c60356ca7bfc9a60eb5905961d180db); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3c60356ca7bfc9a60eb5905961d180db)): ?>
<?php $component = $__componentOriginal3c60356ca7bfc9a60eb5905961d180db; ?>
<?php unset($__componentOriginal3c60356ca7bfc9a60eb5905961d180db); ?>
<?php endif; ?><?php /**PATH E:\Saas website\webcraft\resources\views/frontend/home/services.blade.php ENDPATH**/ ?>