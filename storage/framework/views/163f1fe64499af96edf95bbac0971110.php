
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'disabled' => false,
    'loading' => false,
    'fullWidth' => false,
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
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'disabled' => false,
    'loading' => false,
    'fullWidth' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $variants = [
        'primary' => 'bg-[#6c5ce7] text-white hover:bg-[#5a4bd1] shadow-lg shadow-[#6c5ce7]/30',
        'secondary' => 'bg-white text-[#0a0a0a] border-2 border-gray-200 hover:border-[#6c5ce7] hover:text-[#6c5ce7]',
        'outline' => 'border-2 border-[#6c5ce7] text-[#6c5ce7] hover:bg-[#6c5ce7] hover:text-white',
        'ghost' => 'text-[#4a4a4a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/10',
        'danger' => 'bg-red-500 text-white hover:bg-red-600 shadow-lg shadow-red-500/30',
        'success' => 'bg-emerald-500 text-white hover:bg-emerald-600 shadow-lg shadow-emerald-500/30',
        'dark' => 'bg-[#0a0a0a] text-white hover:bg-[#1a1a1a]',
    ];

    $sizes = [
        'sm' => 'px-4 py-2 text-sm',
        'md' => 'px-6 py-3 text-base',
        'lg' => 'px-8 py-4 text-lg',
        'xl' => 'px-10 py-5 text-xl',
        'icon' => 'p-2.5',
    ];

    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#6c5ce7]/50 disabled:opacity-50 disabled:cursor-not-allowed gap-2';
    $widthClass = $fullWidth ? 'w-full' : '';
    $variantClasses = $variants[$variant] ?? $variants['primary'];
    $sizeClasses = $sizes[$size] ?? $sizes['md'];
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($href): ?>
    <a href="<?php echo e($href); ?>" class="<?php echo e($baseClasses); ?> <?php echo e($variantClasses); ?> <?php echo e($sizeClasses); ?> <?php echo e($widthClass); ?>" <?php echo e($attributes); ?>>
        <?php echo e($slot); ?>

    </a>
<?php else: ?>
    <button type="<?php echo e($type); ?>" class="<?php echo e($baseClasses); ?> <?php echo e($variantClasses); ?> <?php echo e($sizeClasses); ?> <?php echo e($widthClass); ?>" <?php echo e($disabled ? 'disabled' : ''); ?> <?php echo e($attributes); ?>>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($loading): ?>
            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php echo e($slot); ?>

    </button>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php /**PATH E:\Saas website\webcraft\resources\views/components/ui/button.blade.php ENDPATH**/ ?>