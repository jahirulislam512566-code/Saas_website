<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'icon' => null,
    'label' => null,
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
    'icon' => null,
    'label' => null,
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

<?php
    $dropdownId = 'dropdown-' . \Illuminate\Support\Str::random(6);
?>

<div x-data="{ open: <?php echo e($active ? 'true' : 'false'); ?> }" class="sidebar-dropdown">
    
    <button 
        @click="open = !open" 
        class="flex items-center w-full px-3 py-2 text-sm rounded-lg transition-all duration-200 <?php echo e($active 
                ? 'bg-slate-800 text-white' 
                : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?>"
        :class="{ 'bg-slate-800 text-white': open }"
    >
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($icon): ?>
            <i class="fas fa-<?php echo e($icon); ?> w-5"></i>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        
        <span class="<?php echo e($icon ? 'ml-2' : ''); ?> flex-1 text-left"><?php echo e($label ?? $slot); ?></span>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($badge): ?>
            <span class="bg-<?php echo e($badgeColor); ?>-500 text-white text-xs px-2 py-0.5 rounded-full mr-2">
                <?php echo e($badge); ?>

            </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        
        <i class="fas fa-chevron-right text-xs transition-transform duration-200" 
           :class="{ 'rotate-90': open }"></i>
    </button>

    
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="ml-4 mt-1 space-y-1"
         x-cloak>
        <?php echo e($slot); ?>

    </div>
</div><?php /**PATH E:\Saas website\webcraft\resources\views/components/sidebar-dropdown.blade.php ENDPATH**/ ?>