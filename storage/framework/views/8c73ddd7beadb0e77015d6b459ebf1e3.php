
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => 'Our Process',
    'subtitle' => 'How We Work',
    'description' => 'A transparent and collaborative approach to deliver exceptional results.',
    'steps' => [],
    'layout' => 'vertical',
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
    'title' => 'Our Process',
    'subtitle' => 'How We Work',
    'description' => 'A transparent and collaborative approach to deliver exceptional results.',
    'steps' => [],
    'layout' => 'vertical',
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
        'vertical' => 'space-y-12',
        'horizontal' => 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8',
        'timeline' => 'relative space-y-8',
    ];
    $layoutClass = $layoutClasses[$layout] ?? $layoutClasses['vertical'];
?>

<section class="py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider"><?php echo e($subtitle); ?></span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]"><?php echo e($title); ?></h2>
            <p class="mt-4 text-lg text-[#4a4a4a]"><?php echo e($description); ?></p>
        </div>

        
        <div class="max-w-5xl mx-auto <?php echo e($layoutClass); ?>">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="flex <?php echo e($layout === 'horizontal' ? 'flex-col' : 'gap-6'); ?> group">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($layout === 'timeline'): ?>
                        <div class="flex gap-6">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 rounded-full bg-[#6c5ce7] text-white flex items-center justify-center font-bold text-lg flex-shrink-0">
                                    <?php echo e($index + 1); ?>

                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$loop->last): ?>
                                    <div class="w-0.5 flex-1 bg-[#6c5ce7]/20 mt-2"></div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="pb-8">
                                <h4 class="text-xl font-bold text-[#0a0a0a]"><?php echo e($step['title']); ?></h4>
                                <p class="text-[#4a4a4a] mt-2"><?php echo e($step['description']); ?></p>
                            </div>
                        </div>
                    <?php elseif($layout === 'horizontal'): ?>
                        <div class="bg-gray-50 p-6 rounded-2xl text-center group-hover:shadow-lg transition-all duration-300">
                            <div class="w-16 h-16 mx-auto bg-[#6c5ce7]/10 rounded-2xl flex items-center justify-center text-3xl mb-4 group-hover:bg-[#6c5ce7] group-hover:text-white transition-colors duration-300">
                                <?php echo e($step['icon'] ?? '📋'); ?>

                            </div>
                            <span class="inline-block text-xs font-semibold text-[#6c5ce7] bg-[#6c5ce7]/10 px-2.5 py-1 rounded-full mb-3">Step <?php echo e($index + 1); ?></span>
                            <h4 class="text-lg font-bold text-[#0a0a0a]"><?php echo e($step['title']); ?></h4>
                            <p class="text-sm text-[#4a4a4a] mt-2"><?php echo e($step['description']); ?></p>
                        </div>
                    <?php else: ?>
                        <div class="flex gap-6 items-start">
                            <div class="flex-shrink-0 w-14 h-14 bg-[#6c5ce7]/10 rounded-2xl flex items-center justify-center text-3xl group-hover:bg-[#6c5ce7] group-hover:text-white transition-colors duration-300">
                                <?php echo e($step['icon'] ?? '📋'); ?>

                            </div>
                            <div>
                                <span class="text-sm font-semibold text-[#6c5ce7]">Step <?php echo e($index + 1); ?></span>
                                <h4 class="text-xl font-bold text-[#0a0a0a]"><?php echo e($step['title']); ?></h4>
                                <p class="text-[#4a4a4a] mt-2"><?php echo e($step['description']); ?></p>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </div>
</section><?php /**PATH E:\Saas website\webcraft\resources\views/components/sections/process.blade.php ENDPATH**/ ?>