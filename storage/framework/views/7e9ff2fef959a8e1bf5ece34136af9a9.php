
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => 'Ready to Start Your Project?',
    'subtitle' => 'Let\'s Work Together',
    'description' => 'Contact us today and let\'s bring your vision to life.',
    'primaryText' => 'Get Started',
    'primaryLink' => '#contact',
    'secondaryText' => 'Learn More',
    'secondaryLink' => '#about',
    'variant' => 'default',
    'image' => null,
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
    'title' => 'Ready to Start Your Project?',
    'subtitle' => 'Let\'s Work Together',
    'description' => 'Contact us today and let\'s bring your vision to life.',
    'primaryText' => 'Get Started',
    'primaryLink' => '#contact',
    'secondaryText' => 'Learn More',
    'secondaryLink' => '#about',
    'variant' => 'default',
    'image' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $variants = [
        'default' => 'bg-gradient-to-r from-[#6c5ce7] to-[#a78bfa] text-white',
        'dark' => 'bg-[#0a0a0a] text-white',
        'light' => 'bg-gray-50 text-[#0a0a0a]',
    ];
    $variantClass = $variants[$variant] ?? $variants['default'];
    $textColor = $variant === 'light' ? 'text-[#0a0a0a]' : 'text-white';
    $descriptionColor = $variant === 'light' ? 'text-[#4a4a4a]' : 'text-white/80';
?>

<section class="py-20 <?php echo e($variantClass); ?>">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto">
            <span class="text-sm font-semibold uppercase tracking-wider <?php echo e($variant === 'light' ? 'text-[#6c5ce7]' : 'text-white/80'); ?>">
                <?php echo e($subtitle); ?>

            </span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 <?php echo e($textColor); ?>"><?php echo e($title); ?></h2>
            <p class="mt-4 text-lg <?php echo e($descriptionColor); ?>"><?php echo e($description); ?></p>
            <div class="flex flex-wrap justify-center gap-4 mt-8">
                <a href="<?php echo e($primaryLink); ?>" class="inline-flex items-center px-8 py-4 bg-white text-[#6c5ce7] font-semibold rounded-xl hover:bg-gray-100 transition-all duration-300 shadow-lg shadow-[#0a0a0a]/20">
                    <?php echo e($primaryText); ?>

                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
                <a href="<?php echo e($secondaryLink); ?>" class="inline-flex items-center px-8 py-4 border-2 <?php echo e($variant === 'light' ? 'border-gray-300 text-[#0a0a0a] hover:border-[#6c5ce7] hover:text-[#6c5ce7]' : 'border-white/30 text-white hover:border-white'); ?> font-medium rounded-xl transition-all duration-300">
                    <?php echo e($secondaryText); ?>

                </a>
            </div>
        </div>
    </div>
</section><?php /**PATH E:\Saas website\webcraft\resources\views/components/sections/cta.blade.php ENDPATH**/ ?>