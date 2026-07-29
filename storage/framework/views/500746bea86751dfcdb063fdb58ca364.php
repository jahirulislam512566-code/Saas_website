
<?php
    $pageTitle = 'Our Portfolio - AgencyPro';
    $pageDescription = 'Explore our portfolio of successful projects. See how we\'ve helped businesses achieve their digital goals through innovative solutions.';
?>



<?php $__env->startSection('title', $pageTitle); ?>
<?php $__env->startSection('description', $pageDescription); ?>

<?php $__env->startSection('schema'); ?>
    <?php if (isset($component)) { $__componentOriginalded78f06848237162dd881d8c2772d5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalded78f06848237162dd881d8c2772d5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo.schema','data' => ['type' => 'WebSite','data' => [
        'name' => 'AgencyPro Portfolio',
        'url' => url('/portfolio'),
        'description' => $pageDescription,
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seo.schema'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'WebSite','data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        'name' => 'AgencyPro Portfolio',
        'url' => url('/portfolio'),
        'description' => $pageDescription,
    ])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalded78f06848237162dd881d8c2772d5e)): ?>
<?php $attributes = $__attributesOriginalded78f06848237162dd881d8c2772d5e; ?>
<?php unset($__attributesOriginalded78f06848237162dd881d8c2772d5e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalded78f06848237162dd881d8c2772d5e)): ?>
<?php $component = $__componentOriginalded78f06848237162dd881d8c2772d5e; ?>
<?php unset($__componentOriginalded78f06848237162dd881d8c2772d5e); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    
    <?php if (isset($component)) { $__componentOriginal4743781065990dfe96029737c4f06097 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4743781065990dfe96029737c4f06097 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.page-header','data' => ['title' => 'Our Portfolio','subtitle' => 'Featured Work','breadcrumb' => [['label' => 'Portfolio']]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Our Portfolio','subtitle' => 'Featured Work','breadcrumb' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'Portfolio']])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

         <?php $__env->slot('actions', null, []); ?> 
            <a href="#contact" class="inline-flex items-center px-6 py-3 bg-[#6c5ce7] text-white rounded-xl hover:bg-[#5a4bd1] transition-colors shadow-lg shadow-[#6c5ce7]/30">
                Start Your Project
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4743781065990dfe96029737c4f06097)): ?>
<?php $attributes = $__attributesOriginal4743781065990dfe96029737c4f06097; ?>
<?php unset($__attributesOriginal4743781065990dfe96029737c4f06097); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4743781065990dfe96029737c4f06097)): ?>
<?php $component = $__componentOriginal4743781065990dfe96029737c4f06097; ?>
<?php unset($__componentOriginal4743781065990dfe96029737c4f06097); ?>
<?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginal984908ba21e43f244bc3214ef2562ada = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal984908ba21e43f244bc3214ef2562ada = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sections.portfolio','data' => ['title' => 'Selected Projects','subtitle' => 'Our Best Work','description' => 'Each project represents our commitment to quality, innovation, and client satisfaction.','columns' => '3','filter' => 'true','projects' => [
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
            [
                'image' => asset('images/portfolio/project7.jpg'),
                'title' => 'SaaS Product Dashboard',
                'category' => 'Web Development',
                'tags' => ['Next.js', 'GraphQL', 'Prisma', 'Vercel'],
                'link' => route('portfolio.show', 7),
                'lightbox' => true,
            ],
            [
                'image' => asset('images/portfolio/project8.jpg'),
                'title' => 'Restaurant POS System',
                'category' => 'UI/UX Design',
                'tags' => ['UX Research', 'Prototyping', 'Usability Testing'],
                'link' => route('portfolio.show', 8),
                'lightbox' => true,
            ],
            [
                'image' => asset('images/portfolio/project9.jpg'),
                'title' => 'Real Estate Platform',
                'category' => 'Web Development',
                'tags' => ['Ruby on Rails', 'React', 'Mapbox', 'Redis'],
                'link' => route('portfolio.show', 9),
                'lightbox' => true,
            ],
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sections.portfolio'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Selected Projects','subtitle' => 'Our Best Work','description' => 'Each project represents our commitment to quality, innovation, and client satisfaction.','columns' => '3','filter' => 'true','projects' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
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
            [
                'image' => asset('images/portfolio/project7.jpg'),
                'title' => 'SaaS Product Dashboard',
                'category' => 'Web Development',
                'tags' => ['Next.js', 'GraphQL', 'Prisma', 'Vercel'],
                'link' => route('portfolio.show', 7),
                'lightbox' => true,
            ],
            [
                'image' => asset('images/portfolio/project8.jpg'),
                'title' => 'Restaurant POS System',
                'category' => 'UI/UX Design',
                'tags' => ['UX Research', 'Prototyping', 'Usability Testing'],
                'link' => route('portfolio.show', 8),
                'lightbox' => true,
            ],
            [
                'image' => asset('images/portfolio/project9.jpg'),
                'title' => 'Real Estate Platform',
                'category' => 'Web Development',
                'tags' => ['Ruby on Rails', 'React', 'Mapbox', 'Redis'],
                'link' => route('portfolio.show', 9),
                'lightbox' => true,
            ],
        ])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal984908ba21e43f244bc3214ef2562ada)): ?>
<?php $attributes = $__attributesOriginal984908ba21e43f244bc3214ef2562ada; ?>
<?php unset($__attributesOriginal984908ba21e43f244bc3214ef2562ada); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal984908ba21e43f244bc3214ef2562ada)): ?>
<?php $component = $__componentOriginal984908ba21e43f244bc3214ef2562ada; ?>
<?php unset($__componentOriginal984908ba21e43f244bc3214ef2562ada); ?>
<?php endif; ?>

    
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">Client Success Stories</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">What Our Clients Say</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <?php if (isset($component)) { $__componentOriginal7054062ab3b9c978795f7c1656a1df64 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7054062ab3b9c978795f7c1656a1df64 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cards.testimonial-card','data' => ['quote' => 'AgencyPro transformed our digital presence completely. Our conversion rates increased by 40% within three months.','author' => 'Sarah Johnson','role' => 'CEO, TechCorp','rating' => '5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cards.testimonial-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['quote' => 'AgencyPro transformed our digital presence completely. Our conversion rates increased by 40% within three months.','author' => 'Sarah Johnson','role' => 'CEO, TechCorp','rating' => '5']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7054062ab3b9c978795f7c1656a1df64)): ?>
<?php $attributes = $__attributesOriginal7054062ab3b9c978795f7c1656a1df64; ?>
<?php unset($__attributesOriginal7054062ab3b9c978795f7c1656a1df64); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7054062ab3b9c978795f7c1656a1df64)): ?>
<?php $component = $__componentOriginal7054062ab3b9c978795f7c1656a1df64; ?>
<?php unset($__componentOriginal7054062ab3b9c978795f7c1656a1df64); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal7054062ab3b9c978795f7c1656a1df64 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7054062ab3b9c978795f7c1656a1df64 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cards.testimonial-card','data' => ['quote' => 'The team\'s attention to detail and commitment to quality is outstanding. They delivered exactly what we needed.','author' => 'Michael Chen','role' => 'Director, InnovateTech','rating' => '5','featured' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cards.testimonial-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['quote' => 'The team\'s attention to detail and commitment to quality is outstanding. They delivered exactly what we needed.','author' => 'Michael Chen','role' => 'Director, InnovateTech','rating' => '5','featured' => 'true']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7054062ab3b9c978795f7c1656a1df64)): ?>
<?php $attributes = $__attributesOriginal7054062ab3b9c978795f7c1656a1df64; ?>
<?php unset($__attributesOriginal7054062ab3b9c978795f7c1656a1df64); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7054062ab3b9c978795f7c1656a1df64)): ?>
<?php $component = $__componentOriginal7054062ab3b9c978795f7c1656a1df64; ?>
<?php unset($__componentOriginal7054062ab3b9c978795f7c1656a1df64); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal7054062ab3b9c978795f7c1656a1df64 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7054062ab3b9c978795f7c1656a1df64 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cards.testimonial-card','data' => ['quote' => 'Working with AgencyPro has been a game-changer for our business. Their strategic approach and execution are unmatched.','author' => 'Emily Rodriguez','role' => 'VP Marketing, GrowthLabs','rating' => '5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cards.testimonial-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['quote' => 'Working with AgencyPro has been a game-changer for our business. Their strategic approach and execution are unmatched.','author' => 'Emily Rodriguez','role' => 'VP Marketing, GrowthLabs','rating' => '5']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7054062ab3b9c978795f7c1656a1df64)): ?>
<?php $attributes = $__attributesOriginal7054062ab3b9c978795f7c1656a1df64; ?>
<?php unset($__attributesOriginal7054062ab3b9c978795f7c1656a1df64); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7054062ab3b9c978795f7c1656a1df64)): ?>
<?php $component = $__componentOriginal7054062ab3b9c978795f7c1656a1df64; ?>
<?php unset($__componentOriginal7054062ab3b9c978795f7c1656a1df64); ?>
<?php endif; ?>
            </div>
        </div>
    </section>

    
    <?php if (isset($component)) { $__componentOriginal3059bb6f234c7f6929934634de0e93cd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3059bb6f234c7f6929934634de0e93cd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sections.cta','data' => ['title' => 'Ready to Be Featured in Our Portfolio?','subtitle' => 'Let\'s Create Something Amazing','description' => 'Contact us today and let\'s start building your success story.','primaryText' => 'Start Your Project','primaryLink' => '#contact','secondaryText' => 'Explore Our Services','secondaryLink' => ''.e(route('services.index')).'','variant' => 'default']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sections.cta'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Ready to Be Featured in Our Portfolio?','subtitle' => 'Let\'s Create Something Amazing','description' => 'Contact us today and let\'s start building your success story.','primary-text' => 'Start Your Project','primary-link' => '#contact','secondary-text' => 'Explore Our Services','secondary-link' => ''.e(route('services.index')).'','variant' => 'default']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3059bb6f234c7f6929934634de0e93cd)): ?>
<?php $attributes = $__attributesOriginal3059bb6f234c7f6929934634de0e93cd; ?>
<?php unset($__attributesOriginal3059bb6f234c7f6929934634de0e93cd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3059bb6f234c7f6929934634de0e93cd)): ?>
<?php $component = $__componentOriginal3059bb6f234c7f6929934634de0e93cd; ?>
<?php unset($__componentOriginal3059bb6f234c7f6929934634de0e93cd); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Saas website\webcraft\resources\views/frontend/portfolio/index.blade.php ENDPATH**/ ?>