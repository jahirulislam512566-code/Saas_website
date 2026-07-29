
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'index' => 0,
    'title' => 'Accordion Item',
    'open' => false,
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
    'index' => 0,
    'title' => 'Accordion Item',
    'open' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="border-b border-gray-200 last:border-0" 
     x-data="{ isOpen: <?php echo e($open ? 'true' : 'false'); ?> }"
     x-init="isOpen = openItems.includes(<?php echo e($index); ?>)">
    <button class="flex items-center justify-between w-full py-4 text-left bg-transparent border-none cursor-pointer hover:text-[#6c5ce7] transition-colors group"
            x-on:click="isOpen = !isOpen; if (isOpen) { openItems.push(<?php echo e($index); ?>) } else { openItems = openItems.filter(i => i !== <?php echo e($index); ?>) }">
        <span class="font-medium text-[#0a0a0a] group-hover:text-[#6c5ce7] transition-colors"><?php echo e($title); ?></span>
        <span class="text-xl transition-transform duration-300" 
              x-bind:class="isOpen ? 'rotate-180' : ''">▼</span>
    </button>
    <div x-show="isOpen" x-collapse>
        <div class="pb-4 text-[#4a4a4a] leading-relaxed">
            <?php echo e($slot); ?>

        </div>
    </div>
</div><?php /**PATH E:\Saas website\webcraft\resources\views/components/ui/accordion-item.blade.php ENDPATH**/ ?>