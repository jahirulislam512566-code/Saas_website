
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => '',
    'id' => null,
    'label' => null,
    'accept' => 'image/*',
    'multiple' => false,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'maxSize' => null,
    'preview' => false,
    'existing' => null,
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
    'accept' => 'image/*',
    'multiple' => false,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'maxSize' => null,
    'preview' => false,
    'existing' => null,
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
    $multipleAttr = $multiple ? 'multiple' : '';
    $acceptAttr = $accept ? 'accept="' . $accept . '"' : '';
    $disabledClass = $disabled ? 'opacity-60 cursor-not-allowed' : '';
    $errorClass = $error ? 'border-red-500' : 'border-gray-300 hover:border-[#6c5ce7]';
    $existingFiles = is_array($existing) ? $existing : [$existing];
?>

<div class="w-full" x-data="{ 
    files: [], 
    dragOver: false,
    previews: <?php echo json_encode($existing ? array_filter($existingFiles) : [], 15, 512) ?>,
    removeFile(index) {
        this.previews.splice(index, 1);
        this.files.splice(index, 1);
        const input = document.getElementById('<?php echo e($id); ?>');
        const dt = new DataTransfer();
        this.files.forEach(f => dt.items.add(f));
        input.files = dt.files;
        input.dispatchEvent(new Event('change'));
    }
}">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($label): ?>
        <label for="<?php echo e($id); ?>" class="block text-sm font-medium text-[#0a0a0a] mb-1.5">
            <?php echo e($label); ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($required): ?>
                <span class="text-red-500 ml-0.5">*</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($maxSize): ?>
                <span class="text-xs text-[#4a4a4a] font-normal">(Max <?php echo e($maxSize); ?>MB)</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </label>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($existing && $preview): ?>
        <div class="flex flex-wrap gap-3 mb-3">
            <template x-for="(preview, index) in previews" :key="index">
                <div class="relative group">
                    <img :src="preview" alt="Preview" class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                    <button type="button" 
                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600 transition-colors"
                            x-on:click="removeFile(index)">
                        ✕
                    </button>
                </div>
            </template>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="relative">
        <div class="border-2 border-dashed <?php echo e($errorClass); ?> rounded-xl p-8 text-center transition-all duration-200 <?php echo e($disabledClass); ?>"
             x-bind:class="{ 'border-[#6c5ce7] bg-[#6c5ce7]/5': dragOver }"
             x-on:dragover.prevent="dragOver = true"
             x-on:dragleave.prevent="dragOver = false"
             x-on:drop.prevent="dragOver = false; $refs.input.files = $event.dataTransfer.files; $el.dispatchEvent(new Event('change')); files = $event.dataTransfer.files">
            
            <input type="file"
                   name="<?php echo e($name); ?><?php echo e($multiple ? '[]' : ''); ?>"
                   id="<?php echo e($id); ?>"
                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer <?php echo e($disabled ? 'cursor-not-allowed' : ''); ?>"
                   <?php echo e($required ? 'required' : ''); ?>

                   <?php echo e($disabled ? 'disabled' : ''); ?>

                   <?php echo e($multipleAttr); ?>

                   <?php echo $acceptAttr; ?>

                   x-ref="input"
                   x-on:change="files = $event.target.files; previews = []; for(let file of files) { const reader = new FileReader(); reader.onload = (e) => { previews.push(e.target.result); }; reader.readAsDataURL(file); }"
                   <?php echo e($attributes->except(['class', 'name', 'id', 'accept', 'multiple', 'required', 'disabled'])); ?>

            >

            <div class="flex flex-col items-center gap-2">
                <span class="text-4xl">📁</span>
                <div>
                    <span class="text-[#6c5ce7] font-semibold">Click to upload</span>
                    <span class="text-[#4a4a4a]"> or drag and drop</span>
                </div>
                <p class="text-sm text-[#4a4a4a]">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($accept): ?>
                        <?php echo e(str_replace('*', '', $accept)); ?>

                    <?php else: ?>
                        Any file type
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($maxSize): ?>
                        • Max <?php echo e($maxSize); ?>MB
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </p>
                <p class="text-xs text-[#4a4a4a]" x-show="files.length > 0">
                    <span x-text="files.length + ' file(s) selected'"></span>
                </p>
            </div>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($error): ?>
        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
            <span>⚠️</span> <?php echo e($error); ?>

        </p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($help && !$error): ?>
        <p class="mt-1.5 text-sm text-[#4a4a4a]"><?php echo e($help); ?></p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH E:\Saas website\webcraft\resources\views/components/forms/upload.blade.php ENDPATH**/ ?>