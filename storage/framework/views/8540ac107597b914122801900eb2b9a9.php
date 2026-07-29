
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => 'Frequently Asked Questions',
    'subtitle' => 'FAQ',
    'description' => 'Find answers to common questions about our services and process.',
    'items' => [],
    'columns' => 1,
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
    'title' => 'Frequently Asked Questions',
    'subtitle' => 'FAQ',
    'description' => 'Find answers to common questions about our services and process.',
    'items' => [],
    'columns' => 1,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $colClasses = [
        1 => 'max-w-3xl mx-auto',
        2 => 'grid grid-cols-1 lg:grid-cols-2 gap-8',
    ];
    $colClass = $colClasses[$columns] ?? $colClasses[1];
?>

<section class="py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider"><?php echo e($subtitle); ?></span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]"><?php echo e($title); ?></h2>
            <p class="mt-4 text-lg text-[#4a4a4a]"><?php echo e($description); ?></p>
        </div>

        
        <div class="<?php echo e($colClass); ?>">
            <?php if (isset($component)) { $__componentOriginal1a0608b9cb1936c8f0622614a6370508 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1a0608b9cb1936c8f0622614a6370508 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.accordion','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.accordion'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal4242ddd42dbc0001d0bbde6bdc38a5a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4242ddd42dbc0001d0bbde6bdc38a5a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.accordion-item','data' => ['index' => $index,'title' => $item['question']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.accordion-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['index' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($index),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['question'])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        <?php echo e($item['answer']); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4242ddd42dbc0001d0bbde6bdc38a5a5)): ?>
<?php $attributes = $__attributesOriginal4242ddd42dbc0001d0bbde6bdc38a5a5; ?>
<?php unset($__attributesOriginal4242ddd42dbc0001d0bbde6bdc38a5a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4242ddd42dbc0001d0bbde6bdc38a5a5)): ?>
<?php $component = $__componentOriginal4242ddd42dbc0001d0bbde6bdc38a5a5; ?>
<?php unset($__componentOriginal4242ddd42dbc0001d0bbde6bdc38a5a5); ?>
<?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1a0608b9cb1936c8f0622614a6370508)): ?>
<?php $attributes = $__attributesOriginal1a0608b9cb1936c8f0622614a6370508; ?>
<?php unset($__attributesOriginal1a0608b9cb1936c8f0622614a6370508); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1a0608b9cb1936c8f0622614a6370508)): ?>
<?php $component = $__componentOriginal1a0608b9cb1936c8f0622614a6370508; ?>
<?php unset($__componentOriginal1a0608b9cb1936c8f0622614a6370508); ?>
<?php endif; ?>
        </div>
    </div>
</section><?php /**PATH E:\Saas website\webcraft\resources\views/components/sections/faq.blade.php ENDPATH**/ ?>