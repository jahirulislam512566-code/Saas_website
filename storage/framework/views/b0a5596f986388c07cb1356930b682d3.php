
<?php
    $pageTitle = 'About Us - AgencyPro';
    $pageDescription = 'Learn about AgencyPro, our mission, vision, history, team, and values. We\'re a digital agency dedicated to creating exceptional experiences.';
?>



<?php $__env->startSection('title', $pageTitle); ?>
<?php $__env->startSection('description', $pageDescription); ?>

<?php $__env->startSection('schema'); ?>
    <?php if (isset($component)) { $__componentOriginalded78f06848237162dd881d8c2772d5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalded78f06848237162dd881d8c2772d5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo.schema','data' => ['type' => 'Organization','data' => [
        'name' => 'AgencyPro',
        'url' => url('/'),
        'description' => $pageDescription,
        'logo' => asset('images/logo.png'),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => '123 Innovation Drive',
            'addressLocality' => 'San Francisco',
            'addressRegion' => 'CA',
            'postalCode' => '94105',
            'addressCountry' => 'US',
        ],
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'telephone' => '+1-555-123-4567',
            'contactType' => 'Sales',
            'availableLanguage' => ['English'],
        ],
        'social' => [
            'https://twitter.com/agencypro',
            'https://linkedin.com/company/agencypro',
            'https://dribbble.com/agencypro',
            'https://github.com/agencypro',
        ],
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seo.schema'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'Organization','data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        'name' => 'AgencyPro',
        'url' => url('/'),
        'description' => $pageDescription,
        'logo' => asset('images/logo.png'),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => '123 Innovation Drive',
            'addressLocality' => 'San Francisco',
            'addressRegion' => 'CA',
            'postalCode' => '94105',
            'addressCountry' => 'US',
        ],
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'telephone' => '+1-555-123-4567',
            'contactType' => 'Sales',
            'availableLanguage' => ['English'],
        ],
        'social' => [
            'https://twitter.com/agencypro',
            'https://linkedin.com/company/agencypro',
            'https://dribbble.com/agencypro',
            'https://github.com/agencypro',
        ],
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.page-header','data' => ['title' => 'About AgencyPro','subtitle' => 'Our Story','breadcrumb' => [['label' => 'About']]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'About AgencyPro','subtitle' => 'Our Story','breadcrumb' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'About']])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

         <?php $__env->slot('actions', null, []); ?> 
            <a href="#team" class="inline-flex items-center px-6 py-3 bg-[#6c5ce7] text-white rounded-xl hover:bg-[#5a4bd1] transition-colors shadow-lg shadow-[#6c5ce7]/30">
                Meet Our Team
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

    
    <?php echo $__env->make('frontend.about.mission', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    
    <?php echo $__env->make('frontend.about.values', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    
    <?php echo $__env->make('frontend.about.history', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    
    <?php if (isset($component)) { $__componentOriginal16c28d1b46b996c0327942a3c854016d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16c28d1b46b996c0327942a3c854016d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sections.stats','data' => ['title' => 'Our Journey in Numbers','subtitle' => 'Impact','description' => 'Over the years, we\'ve grown and evolved, but our commitment to excellence has never wavered.','stats' => [
            ['value' => '8+', 'label' => 'Years in Business'],
            ['value' => '500+', 'label' => 'Projects Delivered'],
            ['value' => '98%', 'label' => 'Client Satisfaction'],
            ['value' => '50+', 'label' => 'Team Members'],
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sections.stats'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Our Journey in Numbers','subtitle' => 'Impact','description' => 'Over the years, we\'ve grown and evolved, but our commitment to excellence has never wavered.','stats' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['value' => '8+', 'label' => 'Years in Business'],
            ['value' => '500+', 'label' => 'Projects Delivered'],
            ['value' => '98%', 'label' => 'Client Satisfaction'],
            ['value' => '50+', 'label' => 'Team Members'],
        ])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal16c28d1b46b996c0327942a3c854016d)): ?>
<?php $attributes = $__attributesOriginal16c28d1b46b996c0327942a3c854016d; ?>
<?php unset($__attributesOriginal16c28d1b46b996c0327942a3c854016d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal16c28d1b46b996c0327942a3c854016d)): ?>
<?php $component = $__componentOriginal16c28d1b46b996c0327942a3c854016d; ?>
<?php unset($__componentOriginal16c28d1b46b996c0327942a3c854016d); ?>
<?php endif; ?>
    
    
    <?php echo $__env->make('frontend.about.team', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    
    <?php if (isset($component)) { $__componentOriginal3059bb6f234c7f6929934634de0e93cd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3059bb6f234c7f6929934634de0e93cd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sections.cta','data' => ['title' => 'Want to Join Our Team?','subtitle' => 'Careers','description' => 'We\'re always looking for talented individuals who share our passion for innovation and excellence.','primaryText' => 'View Open Positions','primaryLink' => '#careers','secondaryText' => 'Contact Us','secondaryLink' => '#contact','variant' => 'default']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sections.cta'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Want to Join Our Team?','subtitle' => 'Careers','description' => 'We\'re always looking for talented individuals who share our passion for innovation and excellence.','primary-text' => 'View Open Positions','primary-link' => '#careers','secondary-text' => 'Contact Us','secondary-link' => '#contact','variant' => 'default']); ?>
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Saas website\webcraft\resources\views/frontend/about/index.blade.php ENDPATH**/ ?>