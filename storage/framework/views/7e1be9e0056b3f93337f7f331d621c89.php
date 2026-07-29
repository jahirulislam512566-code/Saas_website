


<?php $__env->startSection('title', 'AgencyPro - Digital Agency'); ?>
<?php $__env->startSection('description', 'We build digital experiences that drive growth for forward-thinking brands.'); ?>

<?php $__env->startSection('schema'); ?>
    <?php if (isset($component)) { $__componentOriginalded78f06848237162dd881d8c2772d5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalded78f06848237162dd881d8c2772d5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo.schema','data' => ['type' => 'Organization','data' => [
        'name' => 'AgencyPro',
        'url' => url('/'),
        'description' => 'We build digital experiences that drive growth for forward-thinking brands.',
        'social' => [
            'https://twitter.com/agencypro',
            'https://linkedin.com/company/agencypro',
            'https://dribbble.com/agencypro',
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
        'description' => 'We build digital experiences that drive growth for forward-thinking brands.',
        'social' => [
            'https://twitter.com/agencypro',
            'https://linkedin.com/company/agencypro',
            'https://dribbble.com/agencypro',
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
    
    <?php echo $__env->make('frontend.home.hero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('frontend.home.partners', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('frontend.home.services', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('frontend.home.why-choose-us', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('frontend.home.features', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('frontend.home.pricing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    

    
    <?php echo $__env->make('frontend.home.process', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('frontend.home.stats', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('frontend.home.portfolio', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('frontend.home.technologies', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($testimonials) && $testimonials->count() > 0): ?>
        <?php if (isset($component)) { $__componentOriginalbc7b4f0c2c9047f644458dd33f7c67ae = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbc7b4f0c2c9047f644458dd33f7c67ae = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sections.testimonials','data' => ['testimonials' => $testimonials,'title' => 'What Our Clients Say','subtitle' => 'Testimonials from our satisfied clients']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sections.testimonials'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['testimonials' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($testimonials),'title' => 'What Our Clients Say','subtitle' => 'Testimonials from our satisfied clients']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbc7b4f0c2c9047f644458dd33f7c67ae)): ?>
<?php $attributes = $__attributesOriginalbc7b4f0c2c9047f644458dd33f7c67ae; ?>
<?php unset($__attributesOriginalbc7b4f0c2c9047f644458dd33f7c67ae); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbc7b4f0c2c9047f644458dd33f7c67ae)): ?>
<?php $component = $__componentOriginalbc7b4f0c2c9047f644458dd33f7c67ae; ?>
<?php unset($__componentOriginalbc7b4f0c2c9047f644458dd33f7c67ae); ?>
<?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php echo $__env->make('frontend.home.faq', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('frontend.home.blog', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('frontend.home.newsletter', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('frontend.home.cta', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Saas website\webcraft\resources\views/frontend/home/index.blade.php ENDPATH**/ ?>