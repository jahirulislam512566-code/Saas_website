
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'active' => false,
    'href' => '#',
    'icon' => '',
    'badge' => null,
    'badgeColor' => 'blue'
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
    'active' => false,
    'href' => '#',
    'icon' => '',
    'badge' => null,
    'badgeColor' => 'blue'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $isActive = $active || request()->routeIs($active);
    $badgeColors = [
        'blue' => 'bg-blue-500',
        'green' => 'bg-green-500',
        'yellow' => 'bg-yellow-500',
        'red' => 'bg-red-500',
        'purple' => 'bg-purple-500',
        'gray' => 'bg-gray-500',
        'indigo' => 'bg-indigo-500',
    ];
    $badgeClass = $badgeColors[$badgeColor] ?? 'bg-blue-500';
?>

<a href="<?php echo e($href); ?>" 
   <?php echo e($attributes->merge(['class' => 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 group relative ' . ($isActive ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5')])); ?>

   @click="if (!$store.sidebar?.isDesktop) $store.sidebar?.closeSidebar()"
   <?php if($isActive): ?> aria-current="page" <?php endif; ?>>
    
    <i class="fas <?php echo e($icon); ?> w-5 text-center text-base transition-colors <?php echo e($isActive ? 'text-indigo-400' : 'text-slate-500 group-hover:text-white'); ?>"></i>
    
    <span x-show="!$store.sidebar?.isCollapsed || $store.sidebar?.isHovering" 
          x-cloak
          class="flex-1 truncate">
        <?php echo e($slot); ?>

    </span>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($badge) || $badge === 0): ?>
        <span x-show="!$store.sidebar?.isCollapsed || $store.sidebar?.isHovering"
              x-cloak
              class="flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-bold text-white rounded-full <?php echo e($badgeClass); ?>">
            <?php echo e($badge); ?>

        </span>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isActive): ?>
        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-6 bg-indigo-400 rounded-r-full"></span>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</a><?php /**PATH E:\Saas website\webcraft\resources\views/components/admin/nav-item.blade.php ENDPATH**/ ?>