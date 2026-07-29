
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => '',
    'id' => null,
    'label' => null,
    'value' => '1',
    'checked' => false,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'size' => 'md',
    'inline' => false,
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
    'name' => '',
    'id' => null,
    'label' => null,
    'value' => '1',
    'checked' => false,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'size' => 'md',
    'inline' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $id = $id ?? $name;
    $sizes = [
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $checkedAttr = old($name, $checked) ? 'checked' : '';
    $errorClass = $error ? 'border-red-500 focus:ring-red-500/20' : 'border-gray-300 focus:ring-[#6c5ce7]/20';
    $disabledClass = $disabled ? 'bg-gray-100 cursor-not-allowed' : '';
    $inlineClass = $inline ? 'inline-flex' : 'flex';
?>

<div class="w-full">
    <label for="<?php echo e($id); ?>" class="<?php echo e($inlineClass); ?> items-start gap-3 cursor-pointer <?php echo e($disabled ? 'cursor-not-allowed opacity-60' : ''); ?>">
        <input
            type="checkbox"
            name="<?php echo e($name); ?>"
            id="<?php echo e($id); ?>"
            value="<?php echo e($value); ?>"
            <?php echo e($checkedAttr); ?>

            <?php echo e($required ? 'required' : ''); ?>

            <?php echo e($disabled ? 'disabled' : ''); ?>

            class="<?php echo e($sizeClass); ?> rounded border-2 <?php echo e($errorClass); ?> <?php echo e($disabledClass); ?> transition-all duration-200 text-[#6c5ce7] focus:ring-2 focus:ring-offset-2 focus:ring-[#6c5ce7]/20 focus:ring-offset-white <?php echo e($attributes->get('class')); ?>"
            <?php echo e($attributes->except(['class', 'name', 'id', 'value', 'required', 'disabled', 'checked'])); ?>

        >
        <div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($label): ?>
                <span class="block text-sm font-medium text-[#0a0a0a]"><?php echo e($label); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($help && !$error): ?>
                <span class="block text-xs text-[#4a4a4a] mt-0.5"><?php echo e($help); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($error): ?>
                <span class="block text-xs text-red-600 mt-0.5"><?php echo e($error); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </label>
</div><?php /**PATH E:\Saas website\webcraft\resources\views/components/forms/checkbox.blade.php ENDPATH**/ ?>