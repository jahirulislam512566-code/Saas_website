
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => 'We Build Digital Experiences That Drive Growth',
    'subtitle' => 'Strategy, design, and development for forward-thinking brands.',
    'image' => null,
    'video' => null,
    'primaryText' => 'Get Started',
    'primaryLink' => '#contact',
    'secondaryText' => 'View Our Work',
    'secondaryLink' => '#work',
    'badge' => null,
    'stats' => [],
    'animation' => true,
    'alignment' => 'left',
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
    'title' => 'We Build Digital Experiences That Drive Growth',
    'subtitle' => 'Strategy, design, and development for forward-thinking brands.',
    'image' => null,
    'video' => null,
    'primaryText' => 'Get Started',
    'primaryLink' => '#contact',
    'secondaryText' => 'View Our Work',
    'secondaryLink' => '#work',
    'badge' => null,
    'stats' => [],
    'animation' => true,
    'alignment' => 'left',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $alignClasses = [
        'left' => 'text-left',
        'center' => 'text-center',
        'right' => 'text-right',
    ];
    $alignClass = $alignClasses[$alignment] ?? $alignClasses['left'];
    $statsAlignment = $alignment === 'center' ? 'justify-center' : 'justify-start';
?>

<section class="relative overflow-hidden pt-32 pb-20 md:pt-40 md:pb-28 bg-gradient-to-br from-[#f8f7ff] via-white to-[#faf8ff]">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            
            <div class="<?php echo e($alignClass); ?> <?php echo e($alignment === 'center' ? 'lg:col-span-2' : ''); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($badge): ?>
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-[#6c5ce7]/10 text-[#6c5ce7] rounded-full text-sm font-medium mb-6">
                        <span class="w-2 h-2 bg-[#6c5ce7] rounded-full animate-pulse"></span>
                        <?php echo e($badge); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[#0a0a0a] leading-tight">
                    <?php echo $title; ?>

                </h1>
                
                <p class="text-lg md:text-xl text-[#4a4a4a] mt-6 max-w-2xl <?php echo e($alignment === 'center' ? 'mx-auto' : ''); ?>">
                    <?php echo e($subtitle); ?>

                </p>

                <div class="flex flex-wrap gap-4 mt-8 <?php echo e($alignment === 'center' ? 'justify-center' : ''); ?>">
                    <a href="<?php echo e($primaryLink); ?>" class="inline-flex items-center px-8 py-4 bg-[#6c5ce7] text-white font-medium rounded-xl hover:bg-[#5a4bd1] transition-all duration-300 shadow-lg shadow-[#6c5ce7]/30 hover:shadow-xl hover:-translate-y-0.5">
                        <?php echo e($primaryText); ?>

                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <a href="<?php echo e($secondaryLink); ?>" class="inline-flex items-center px-8 py-4 bg-white border-2 border-gray-200 text-[#0a0a0a] font-medium rounded-xl hover:border-[#6c5ce7] hover:text-[#6c5ce7] transition-all duration-300">
                        <?php echo e($secondaryText); ?>

                    </a>
                </div>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($stats) > 0): ?>
                    <div class="flex flex-wrap gap-8 mt-12 <?php echo e($statsAlignment); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div>
                                <p class="text-2xl font-bold text-[#0a0a0a]"><?php echo e($stat['value']); ?></p>
                                <p class="text-sm text-[#4a4a4a]"><?php echo e($stat['label']); ?></p>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($image || $video): ?>
                <div class="relative lg:col-span-1">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($video): ?>
                            <video class="w-full h-auto" autoplay muted loop playsinline>
                                <source src="<?php echo e($video); ?>" type="video/mp4">
                            </video>
                        <?php elseif($image): ?>
                            <img src="<?php echo e($image); ?>" alt="Hero Image" class="w-full h-auto object-cover">
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                        
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-[#6c5ce7]/10 rounded-full blur-2xl"></div>
                        <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-[#a78bfa]/10 rounded-full blur-2xl"></div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    
    <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-[#6c5ce7]/5 to-transparent pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-1/4 h-1/2 bg-gradient-to-tr from-[#a78bfa]/5 to-transparent pointer-events-none"></div>
</section><?php /**PATH E:\Saas website\webcraft\resources\views/components/sections/hero.blade.php ENDPATH**/ ?>