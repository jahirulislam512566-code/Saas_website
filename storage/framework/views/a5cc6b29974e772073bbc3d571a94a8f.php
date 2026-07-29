
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'current' => 1,
    'total' => 1,
    'perPage' => 10,
    'url' => '#',
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
    'current' => 1,
    'total' => 1,
    'perPage' => 10,
    'url' => '#',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $totalPages = ceil($total / $perPage);
    $showPages = 5;
    $half = floor($showPages / 2);
    $start = max(1, $current - $half);
    $end = min($totalPages, $current + $half);
    
    if ($end - $start + 1 < $showPages) {
        if ($start === 1) {
            $end = min($totalPages, $start + $showPages - 1);
        } else {
            $start = max(1, $end - $showPages + 1);
        }
    }
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($totalPages > 1): ?>
    <nav class="flex items-center justify-between gap-4 flex-wrap" aria-label="Pagination">
        <div class="flex items-center gap-2">
            
            <a href="<?php echo e($current > 1 ? $url . '?page=' . ($current - 1) : '#'); ?>" 
               class="px-3 py-2 text-sm rounded-lg border border-gray-200 hover:border-[#6c5ce7] hover:text-[#6c5ce7] transition-colors <?php echo e($current <= 1 ? 'opacity-50 pointer-events-none' : ''); ?>">
                ← Previous
            </a>
            
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($start > 1): ?>
                <a href="<?php echo e($url); ?>?page=1" class="px-3 py-2 text-sm rounded-lg hover:bg-[#6c5ce7]/10 transition-colors">1</a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($start > 2): ?>
                    <span class="px-2">…</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php for($i = $start; $i <= $end; $i++): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <a href="<?php echo e($url); ?>?page=<?php echo e($i); ?>" 
                   class="px-3.5 py-2 text-sm rounded-lg transition-colors <?php echo e($i === $current ? 'bg-[#6c5ce7] text-white' : 'hover:bg-[#6c5ce7]/10'); ?>">
                    <?php echo e($i); ?>

                </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($end < $totalPages): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($end < $totalPages - 1): ?>
                    <span class="px-2">…</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <a href="<?php echo e($url); ?>?page=<?php echo e($totalPages); ?>" class="px-3 py-2 text-sm rounded-lg hover:bg-[#6c5ce7]/10 transition-colors"><?php echo e($totalPages); ?></a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <a href="<?php echo e($current < $totalPages ? $url . '?page=' . ($current + 1) : '#'); ?>" 
               class="px-3 py-2 text-sm rounded-lg border border-gray-200 hover:border-[#6c5ce7] hover:text-[#6c5ce7] transition-colors <?php echo e($current >= $totalPages ? 'opacity-50 pointer-events-none' : ''); ?>">
                Next →
            </a>
        </div>
        <span class="text-sm text-[#4a4a4a]">
            Page <?php echo e($current); ?> of <?php echo e($totalPages); ?>

        </span>
    </nav>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php /**PATH E:\Saas website\webcraft\resources\views/components/ui/pagination.blade.php ENDPATH**/ ?>