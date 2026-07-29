
<?php if (isset($component)) { $__componentOriginal7f4a738740168d1fdbd9f2c52859a0fb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7f4a738740168d1fdbd9f2c52859a0fb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sections.process','data' => ['title' => 'Our Process','subtitle' => 'How We Work','description' => 'A transparent and collaborative approach to deliver exceptional results.','layout' => 'horizontal','steps' => [
        [
            'icon' => '🔍',
            'title' => 'Discovery',
            'description' => 'We learn about your business, goals, and challenges to create a tailored strategy.'
        ],
        [
            'icon' => '📋',
            'title' => 'Planning',
            'description' => 'We develop a detailed plan with clear milestones and timelines.'
        ],
        [
            'icon' => '🎨',
            'title' => 'Design & Development',
            'description' => 'We create and iterate on your solution, keeping you involved every step of the way.'
        ],
        [
            'icon' => '🚀',
            'title' => 'Launch & Support',
            'description' => 'We deploy your solution and provide ongoing support to ensure success.'
        ],
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sections.process'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Our Process','subtitle' => 'How We Work','description' => 'A transparent and collaborative approach to deliver exceptional results.','layout' => 'horizontal','steps' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        [
            'icon' => '🔍',
            'title' => 'Discovery',
            'description' => 'We learn about your business, goals, and challenges to create a tailored strategy.'
        ],
        [
            'icon' => '📋',
            'title' => 'Planning',
            'description' => 'We develop a detailed plan with clear milestones and timelines.'
        ],
        [
            'icon' => '🎨',
            'title' => 'Design & Development',
            'description' => 'We create and iterate on your solution, keeping you involved every step of the way.'
        ],
        [
            'icon' => '🚀',
            'title' => 'Launch & Support',
            'description' => 'We deploy your solution and provide ongoing support to ensure success.'
        ],
    ])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7f4a738740168d1fdbd9f2c52859a0fb)): ?>
<?php $attributes = $__attributesOriginal7f4a738740168d1fdbd9f2c52859a0fb; ?>
<?php unset($__attributesOriginal7f4a738740168d1fdbd9f2c52859a0fb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7f4a738740168d1fdbd9f2c52859a0fb)): ?>
<?php $component = $__componentOriginal7f4a738740168d1fdbd9f2c52859a0fb; ?>
<?php unset($__componentOriginal7f4a738740168d1fdbd9f2c52859a0fb); ?>
<?php endif; ?><?php /**PATH E:\Saas website\webcraft\resources\views/frontend/home/process.blade.php ENDPATH**/ ?>