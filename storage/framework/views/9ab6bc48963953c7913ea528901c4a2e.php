
<?php if (isset($component)) { $__componentOriginal16c28d1b46b996c0327942a3c854016d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16c28d1b46b996c0327942a3c854016d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sections.stats','data' => ['title' => 'Our Impact in Numbers','subtitle' => 'Results','description' => 'We\'ve helped hundreds of businesses achieve their goals through innovative digital solutions.','stats' => [
        ['value' => '500+', 'label' => 'Projects Delivered', 'change' => 12],
        ['value' => '98%', 'label' => 'Client Satisfaction', 'change' => 2.5],
        ['value' => '50+', 'label' => 'Expert Team Members'],
        ['value' => '24/7', 'label' => 'Support Available'],
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sections.stats'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Our Impact in Numbers','subtitle' => 'Results','description' => 'We\'ve helped hundreds of businesses achieve their goals through innovative digital solutions.','stats' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['value' => '500+', 'label' => 'Projects Delivered', 'change' => 12],
        ['value' => '98%', 'label' => 'Client Satisfaction', 'change' => 2.5],
        ['value' => '50+', 'label' => 'Expert Team Members'],
        ['value' => '24/7', 'label' => 'Support Available'],
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
<?php endif; ?><?php /**PATH E:\Saas website\webcraft\resources\views/frontend/home/stats.blade.php ENDPATH**/ ?>