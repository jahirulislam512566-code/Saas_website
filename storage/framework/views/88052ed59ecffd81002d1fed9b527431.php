
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => 'Our Services',
    'subtitle' => 'We deliver exceptional digital solutions',
    'description' => 'From strategy to execution, we provide comprehensive services to help your business thrive.',
    'services' => [],
    'columns' => 3,
    'background' => 'white',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'title' => 'Our Services',
    'subtitle' => 'We deliver exceptional digital solutions',
    'description' => 'From strategy to execution, we provide comprehensive services to help your business thrive.',
    'services' => [],
    'columns' => 3,
    'background' => 'white',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $bgClasses = [
        'white' => 'bg-white',
        'gray' => 'bg-gray-50',
        'dark' => 'bg-[#0a0a0a] text-white',
        'gradient' => 'bg-gradient-to-br from-[#f8f7ff] to-white',
    ];
    $bgClass = $bgClasses[$background] ?? $bgClasses['white'];
    $textColor = $background === 'dark' ? 'text-white' : 'text-[#0a0a0a]';
    $textSecondary = $background === 'dark' ? 'text-gray-300' : 'text-[#4a4a4a]';
    $colClasses = [
        1 => 'grid-cols-1',
        2 => 'grid-cols-1 md:grid-cols-2',
        3 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
        4 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
    ];
    $colClass = $colClasses[$columns] ?? $colClasses[3];
?>

<section class="py-20 <?php echo e($bgClass); ?>">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider"><?php echo e($subtitle); ?></span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 <?php echo e($textColor); ?>"><?php echo e($title); ?></h2>
            <p class="mt-4 text-lg <?php echo e($textSecondary); ?>"><?php echo e($description); ?></p>
        </div>

        
        <div class="grid <?php echo e($colClass); ?> gap-6 md:gap-8">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal64b9e1510064e474ac3129d9628dc13f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal64b9e1510064e474ac3129d9628dc13f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cards.service-card','data' => ['icon' => $service['icon'] ?? '🚀','title' => $service['title'],'description' => $service['description'],'features' => $service['features'] ?? [],'link' => $service['link'] ?? '#','color' => $service['color'] ?? 'primary','featured' => $service['featured'] ?? false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cards.service-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($service['icon'] ?? '🚀'),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($service['title']),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($service['description']),'features' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($service['features'] ?? []),'link' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($service['link'] ?? '#'),'color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($service['color'] ?? 'primary'),'featured' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($service['featured'] ?? false)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal64b9e1510064e474ac3129d9628dc13f)): ?>
<?php $attributes = $__attributesOriginal64b9e1510064e474ac3129d9628dc13f; ?>
<?php unset($__attributesOriginal64b9e1510064e474ac3129d9628dc13f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal64b9e1510064e474ac3129d9628dc13f)): ?>
<?php $component = $__componentOriginal64b9e1510064e474ac3129d9628dc13f; ?>
<?php unset($__componentOriginal64b9e1510064e474ac3129d9628dc13f); ?>
<?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </div>
</section><?php /**PATH E:\Saas website\webcraft\resources\views/components/sections/services.blade.php ENDPATH**/ ?>