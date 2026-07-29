
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => 'Subscribe to Our Newsletter',
    'subtitle' => 'Stay Updated',
    'description' => 'Get the latest insights, tips, and updates delivered to your inbox.',
    'formAction' => '#',
    'formMethod' => 'POST',
    'buttonText' => 'Subscribe',
    'placeholder' => 'Enter your email',
    'privacyText' => 'We respect your privacy. Unsubscribe at any time.',
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
    'title' => 'Subscribe to Our Newsletter',
    'subtitle' => 'Stay Updated',
    'description' => 'Get the latest insights, tips, and updates delivered to your inbox.',
    'formAction' => '#',
    'formMethod' => 'POST',
    'buttonText' => 'Subscribe',
    'placeholder' => 'Enter your email',
    'privacyText' => 'We respect your privacy. Unsubscribe at any time.',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<section class="py-20 bg-gradient-to-r from-[#6c5ce7] to-[#a78bfa]">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center">
            <span class="text-sm font-semibold text-white/80 uppercase tracking-wider"><?php echo e($subtitle); ?></span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-white"><?php echo e($title); ?></h2>
            <p class="mt-4 text-lg text-white/80"><?php echo e($description); ?></p>

            <form action="<?php echo e($formAction); ?>" method="<?php echo e($formMethod); ?>" class="mt-8 flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                <input type="email" 
                       name="email" 
                       placeholder="<?php echo e($placeholder); ?>" 
                       required
                       class="flex-1 px-5 py-3.5 bg-white/10 border border-white/20 rounded-xl text-white placeholder:text-white/70 focus:outline-none focus:ring-2 focus:ring-white/30 transition-all">
                <button type="submit" class="px-8 py-3.5 bg-white text-[#6c5ce7] font-semibold rounded-xl hover:bg-gray-100 transition-all duration-300 shadow-lg shadow-[#0a0a0a]/20">
                    <?php echo e($buttonText); ?>

                </button>
            </form>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($privacyText): ?>
                <p class="mt-4 text-sm text-white/60"><?php echo e($privacyText); ?></p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section><?php /**PATH E:\Saas website\webcraft\resources\views/components/sections/newsletter.blade.php ENDPATH**/ ?>