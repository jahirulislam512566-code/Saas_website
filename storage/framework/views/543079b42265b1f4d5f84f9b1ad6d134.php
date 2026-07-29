
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => 'Choose Your Plan',
    'subtitle' => 'Pricing that scales with you',
    'description' => 'Select the perfect plan for your business needs.',
    'plans' => [],
    'billing' => true,
    'defaultBilling' => 'monthly',
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
    'title' => 'Choose Your Plan',
    'subtitle' => 'Pricing that scales with you',
    'description' => 'Select the perfect plan for your business needs.',
    'plans' => [],
    'billing' => true,
    'defaultBilling' => 'monthly',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<section class="py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider"><?php echo e($subtitle); ?></span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]"><?php echo e($title); ?></h2>
            <p class="mt-4 text-lg text-[#4a4a4a]"><?php echo e($description); ?></p>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($billing): ?>
            <div class="flex items-center justify-center gap-4 mb-12" x-data="{ billing: '<?php echo e($defaultBilling); ?>' }">
                <span class="text-sm font-medium" x-bind:class="billing === 'monthly' ? 'text-[#0a0a0a]' : 'text-[#4a4a4a]'">Monthly</span>
                <button class="w-14 h-8 bg-gray-200 rounded-full relative transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-[#6c5ce7]/20"
                        x-on:click="billing = billing === 'monthly' ? 'yearly' : 'monthly'">
                    <span class="absolute top-1 left-1 w-6 h-6 bg-white rounded-full shadow-md transition-transform duration-300"
                          x-bind:class="billing === 'yearly' ? 'translate-x-6' : ''"></span>
                </button>
                <span class="text-sm font-medium" x-bind:class="billing === 'yearly' ? 'text-[#0a0a0a]' : 'text-[#4a4a4a]'">
                    Yearly
                    <span class="ml-1.5 px-2 py-0.5 bg-emerald-100 text-emerald-700 text-xs rounded-full">Save 20%</span>
                </span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php if (isset($component)) { $__componentOriginala58d49d3e6d32e1e3c52da475aeb81b5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala58d49d3e6d32e1e3c52da475aeb81b5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cards.pricing-card','data' => ['name' => $plan['name'],'price' => $plan['price'],'currency' => $plan['currency'] ?? '$','period' => $plan['period'] ?? 'month','description' => $plan['description'] ?? '','features' => $plan['features'] ?? [],'ctaText' => $plan['ctaText'] ?? 'Get Started','ctaLink' => $plan['ctaLink'] ?? '#','highlighted' => $plan['highlighted'] ?? false,'badge' => $plan['badge'] ?? null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cards.pricing-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($plan['name']),'price' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($plan['price']),'currency' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($plan['currency'] ?? '$'),'period' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($plan['period'] ?? 'month'),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($plan['description'] ?? ''),'features' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($plan['features'] ?? []),'cta-text' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($plan['ctaText'] ?? 'Get Started'),'cta-link' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($plan['ctaLink'] ?? '#'),'highlighted' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($plan['highlighted'] ?? false),'badge' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($plan['badge'] ?? null)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala58d49d3e6d32e1e3c52da475aeb81b5)): ?>
<?php $attributes = $__attributesOriginala58d49d3e6d32e1e3c52da475aeb81b5; ?>
<?php unset($__attributesOriginala58d49d3e6d32e1e3c52da475aeb81b5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala58d49d3e6d32e1e3c52da475aeb81b5)): ?>
<?php $component = $__componentOriginala58d49d3e6d32e1e3c52da475aeb81b5; ?>
<?php unset($__componentOriginala58d49d3e6d32e1e3c52da475aeb81b5); ?>
<?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </div>
</section><?php /**PATH E:\Saas website\webcraft\resources\views/components/sections/pricing.blade.php ENDPATH**/ ?>