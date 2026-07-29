
<?php if (isset($component)) { $__componentOriginal6ba66857502b2c621a48ed5da8100e7b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6ba66857502b2c621a48ed5da8100e7b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sections.features','data' => ['title' => 'Powerful Features for Your Business','subtitle' => 'What You Get','description' => 'Our platform comes packed with features designed to help you succeed in the digital landscape.','layout' => 'grid','features' => [
        [
            'icon' => '⚡',
            'title' => 'Lightning Fast Performance',
            'description' => 'Optimized for speed with CDN integration and caching strategies.'
        ],
        [
            'icon' => '📱',
            'title' => 'Mobile Responsive',
            'description' => 'Beautiful experiences on every device, from desktop to mobile.'
        ],
        [
            'icon' => '🔍',
            'title' => 'SEO Optimized',
            'description' => 'Built with SEO best practices to help you rank higher in search results.'
        ],
        [
            'icon' => '🛡️',
            'title' => 'Enterprise Security',
            'description' => 'Advanced security features to protect your data and your customers.'
        ],
        [
            'icon' => '📊',
            'title' => 'Analytics Dashboard',
            'description' => 'Real-time insights into your performance and user behavior.'
        ],
        [
            'icon' => '🤝',
            'title' => 'Collaboration Tools',
            'description' => 'Work seamlessly with your team and stakeholders.'
        ],
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sections.features'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Powerful Features for Your Business','subtitle' => 'What You Get','description' => 'Our platform comes packed with features designed to help you succeed in the digital landscape.','layout' => 'grid','features' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        [
            'icon' => '⚡',
            'title' => 'Lightning Fast Performance',
            'description' => 'Optimized for speed with CDN integration and caching strategies.'
        ],
        [
            'icon' => '📱',
            'title' => 'Mobile Responsive',
            'description' => 'Beautiful experiences on every device, from desktop to mobile.'
        ],
        [
            'icon' => '🔍',
            'title' => 'SEO Optimized',
            'description' => 'Built with SEO best practices to help you rank higher in search results.'
        ],
        [
            'icon' => '🛡️',
            'title' => 'Enterprise Security',
            'description' => 'Advanced security features to protect your data and your customers.'
        ],
        [
            'icon' => '📊',
            'title' => 'Analytics Dashboard',
            'description' => 'Real-time insights into your performance and user behavior.'
        ],
        [
            'icon' => '🤝',
            'title' => 'Collaboration Tools',
            'description' => 'Work seamlessly with your team and stakeholders.'
        ],
    ])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6ba66857502b2c621a48ed5da8100e7b)): ?>
<?php $attributes = $__attributesOriginal6ba66857502b2c621a48ed5da8100e7b; ?>
<?php unset($__attributesOriginal6ba66857502b2c621a48ed5da8100e7b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6ba66857502b2c621a48ed5da8100e7b)): ?>
<?php $component = $__componentOriginal6ba66857502b2c621a48ed5da8100e7b; ?>
<?php unset($__componentOriginal6ba66857502b2c621a48ed5da8100e7b); ?>
<?php endif; ?><?php /**PATH E:\Saas website\webcraft\resources\views/frontend/home/features.blade.php ENDPATH**/ ?>