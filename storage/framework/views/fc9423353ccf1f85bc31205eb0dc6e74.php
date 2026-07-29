<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'href' => '#',
    'icon' => null,
    'active' => false,
    'badge' => null,
    'badgeColor' => 'danger',
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
    'href' => '#',
    'icon' => null,
    'active' => false,
    'badge' => null,
    'badgeColor' => 'danger',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<a href="<?php echo e($href); ?>" 
   class="flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 <?php echo e($active 
           ? 'bg-slate-800 text-white' 
           : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?>"
   <?php echo e($attributes->merge(['class' => ''])); ?>>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($icon): ?>
        <i class="fas fa-<?php echo e($icon); ?> w-5"></i>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
    <span class="<?php echo e($icon ? 'ml-2' : ''); ?>"><?php echo e($slot); ?></span>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($badge): ?>
        <span class="ml-auto bg-<?php echo e($badgeColor); ?>-500 text-white text-xs px-2 py-0.5 rounded-full">
            <?php echo e($badge); ?>

        </span>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</a><?php /**PATH E:\Saas website\webcraft\resources\views/components/sidebar-link.blade.php ENDPATH**/ ?>