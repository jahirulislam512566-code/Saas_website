
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'icon' => '🚀',
    'title' => 'Service Title',
    'description' => 'Service description goes here.',
    'link' => null,
    'linkText' => 'Learn More',
    'color' => 'primary',
    'featured' => false,
    'features' => [],
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
    'icon' => '🚀',
    'title' => 'Service Title',
    'description' => 'Service description goes here.',
    'link' => null,
    'linkText' => 'Learn More',
    'color' => 'primary',
    'featured' => false,
    'features' => [],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $colors = [
        'primary' => 'bg-[#6c5ce7]/10 text-[#6c5ce7] group-hover:bg-[#6c5ce7] group-hover:text-white',
        'blue' => 'bg-blue-100 text-blue-600 group-hover:bg-blue-600 group-hover:text-white',
        'emerald' => 'bg-emerald-100 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white',
        'amber' => 'bg-amber-100 text-amber-600 group-hover:bg-amber-600 group-hover:text-white',
        'rose' => 'bg-rose-100 text-rose-600 group-hover:bg-rose-600 group-hover:text-white',
        'indigo' => 'bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white',
    ];
    $colorClass = $colors[$color] ?? $colors['primary'];
    $featuredClass = $featured ? 'ring-2 ring-[#6c5ce7] shadow-xl shadow-[#6c5ce7]/10 -translate-y-4' : '';
?>

<div class="group relative bg-white rounded-2xl p-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 <?php echo e($featuredClass); ?>">
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featured): ?>
        <div class="absolute -top-3 right-6 bg-[#6c5ce7] text-white text-xs font-semibold px-3 py-1 rounded-full">
            Featured
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="mb-5 w-16 h-16 rounded-2xl flex items-center justify-center text-2xl transition-all duration-300 <?php echo e($colorClass); ?>">
        <?php echo e($icon); ?>

    </div>

    
    <h3 class="text-xl font-bold text-[#0a0a0a] mb-3"><?php echo e($title); ?></h3>
    <p class="text-[#4a4a4a] leading-relaxed mb-4"><?php echo e($description); ?></p>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($features) > 0): ?>
        <ul class="space-y-2 mb-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <li class="flex items-center gap-2 text-sm text-[#4a4a4a]">
                    <svg class="w-4 h-4 text-[#6c5ce7] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <?php echo e($feature); ?>

                </li>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </ul>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($link): ?>
        <a href="<?php echo e($link); ?>" class="inline-flex items-center gap-2 text-[#6c5ce7] font-medium hover:gap-3 transition-all">
            <?php echo e($linkText); ?>

            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH E:\Saas website\webcraft\resources\views/components/cards/service-card.blade.php ENDPATH**/ ?>