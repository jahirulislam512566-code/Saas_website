
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => 'Why Choose Us',
    'subtitle' => 'We deliver results that matter',
    'description' => 'Our approach combines creativity, strategy, and technology to drive real business growth.',
    'features' => [],
    'layout' => 'grid',
    'image' => null,
    'imagePosition' => 'right',
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
    'title' => 'Why Choose Us',
    'subtitle' => 'We deliver results that matter',
    'description' => 'Our approach combines creativity, strategy, and technology to drive real business growth.',
    'features' => [],
    'layout' => 'grid',
    'image' => null,
    'imagePosition' => 'right',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $layoutClasses = [
        'grid' => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8',
        'list' => 'space-y-6',
        'image' => 'grid grid-cols-1 lg:grid-cols-2 gap-12 items-center',
    ];
    $layoutClass = $layoutClasses[$layout] ?? $layoutClasses['grid'];
?>

<section class="py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($layout !== 'image'): ?>
            
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider"><?php echo e($subtitle); ?></span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]"><?php echo e($title); ?></h2>
                <p class="mt-4 text-lg text-[#4a4a4a]"><?php echo e($description); ?></p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="<?php echo e($layoutClass); ?>">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($layout === 'image' && $image): ?>
                <div class="<?php echo e($imagePosition === 'left' ? 'order-1' : 'order-2'); ?>">
                    <img src="<?php echo e($image); ?>" alt="Features" class="rounded-2xl shadow-xl w-full h-auto">
                </div>
                <div class="<?php echo e($imagePosition === 'left' ? 'order-2' : 'order-1'); ?>">
                    <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider"><?php echo e($subtitle); ?></span>
                    <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]"><?php echo e($title); ?></h2>
                    <p class="mt-4 text-lg text-[#4a4a4a]"><?php echo e($description); ?></p>
                    <div class="mt-8 space-y-6">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 w-12 h-12 bg-[#6c5ce7]/10 rounded-xl flex items-center justify-center text-2xl">
                                    <?php echo e($feature['icon'] ?? '✓'); ?>

                                </div>
                                <div>
                                    <h4 class="font-semibold text-[#0a0a0a]"><?php echo e($feature['title']); ?></h4>
                                    <p class="text-sm text-[#4a4a4a]"><?php echo e($feature['description']); ?></p>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div class="p-6 bg-gray-50 rounded-2xl hover:shadow-lg transition-all duration-300 group">
                        <div class="w-14 h-14 bg-[#6c5ce7]/10 rounded-xl flex items-center justify-center text-3xl mb-4 group-hover:bg-[#6c5ce7] group-hover:text-white transition-colors duration-300">
                            <?php echo e($feature['icon'] ?? '🎯'); ?>

                        </div>
                        <h4 class="text-xl font-bold text-[#0a0a0a] mb-2"><?php echo e($feature['title']); ?></h4>
                        <p class="text-[#4a4a4a]"><?php echo e($feature['description']); ?></p>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section><?php /**PATH E:\Saas website\webcraft\resources\views/components/sections/features.blade.php ENDPATH**/ ?>