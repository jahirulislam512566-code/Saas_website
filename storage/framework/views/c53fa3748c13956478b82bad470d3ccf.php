
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => 'Pro Plan',
    'price' => '49',
    'currency' => '$',
    'period' => 'month',
    'description' => 'Perfect for growing teams',
    'features' => [],
    'ctaText' => 'Get Started',
    'ctaLink' => '#',
    'highlighted' => false,
    'badge' => null,
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
    'name' => 'Pro Plan',
    'price' => '49',
    'currency' => '$',
    'period' => 'month',
    'description' => 'Perfect for growing teams',
    'features' => [],
    'ctaText' => 'Get Started',
    'ctaLink' => '#',
    'highlighted' => false,
    'badge' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $highlightedClass = $highlighted ? 'ring-2 ring-[#6c5ce7] shadow-2xl shadow-[#6c5ce7]/20 scale-105' : '';
    $ctaClass = $highlighted 
        ? 'bg-[#6c5ce7] text-white hover:bg-[#5a4bd1] shadow-lg shadow-[#6c5ce7]/30' 
        : 'bg-gray-100 text-[#0a0a0a] hover:bg-[#6c5ce7] hover:text-white';
?>

<div class="relative bg-white rounded-2xl p-8 transition-all duration-300 hover:shadow-xl <?php echo e($highlightedClass); ?>">
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($badge): ?>
        <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-[#6c5ce7] text-white text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">
            <?php echo e($badge); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="text-center mb-8">
        <h3 class="text-lg font-semibold text-[#4a4a4a] uppercase tracking-wide"><?php echo e($name); ?></h3>
        <div class="mt-4">
            <span class="text-4xl font-bold text-[#0a0a0a]"><?php echo e($currency); ?><?php echo e($price); ?></span>
            <span class="text-[#4a4a4a]">/<?php echo e($period); ?></span>
        </div>
        <p class="mt-2 text-[#4a4a4a]"><?php echo e($description); ?></p>
    </div>

    
    <ul class="space-y-3 mb-8">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <li class="flex items-start gap-3 text-[#4a4a4a]">
                <svg class="w-5 h-5 text-[#6c5ce7] flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <?php echo e($feature); ?>

            </li>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </ul>

    
    <a href="<?php echo e($ctaLink); ?>" class="block w-full text-center px-6 py-3.5 rounded-xl font-medium transition-all duration-300 <?php echo e($ctaClass); ?>">
        <?php echo e($ctaText); ?>

    </a>
</div><?php /**PATH E:\Saas website\webcraft\resources\views/components/cards/pricing-card.blade.php ENDPATH**/ ?>