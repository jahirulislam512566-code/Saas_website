
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => 'Our Portfolio',
    'subtitle' => 'Featured Projects',
    'description' => 'Explore our latest work and see how we help brands achieve their goals.',
    'projects' => [],
    'filter' => true,
    'columns' => 3,
    'showMore' => true,
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
    'title' => 'Our Portfolio',
    'subtitle' => 'Featured Projects',
    'description' => 'Explore our latest work and see how we help brands achieve their goals.',
    'projects' => [],
    'filter' => true,
    'columns' => 3,
    'showMore' => true,
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
        2 => 'md:grid-cols-2',
        3 => 'md:grid-cols-2 lg:grid-cols-3',
        4 => 'md:grid-cols-2 lg:grid-cols-4',
    ];
    $colClass = $colClasses[$columns] ?? $colClasses[3];
    $categories = array_unique(array_column($projects, 'category'));
?>

<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider"><?php echo e($subtitle); ?></span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]"><?php echo e($title); ?></h2>
            <p class="mt-4 text-lg text-[#4a4a4a]"><?php echo e($description); ?></p>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($filter && count($categories) > 1): ?>
            <div class="flex flex-wrap justify-center gap-2 mb-12" x-data="{ active: 'all' }">
                <button class="px-4 py-2 text-sm font-medium rounded-full transition-all duration-300" 
                        x-bind:class="active === 'all' ? 'bg-[#6c5ce7] text-white' : 'bg-white text-[#4a4a4a] hover:bg-gray-100'"
                        x-on:click="active = 'all'">
                    All
                </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <button class="px-4 py-2 text-sm font-medium rounded-full transition-all duration-300"
                            x-bind:class="active === '<?php echo e($category); ?>' ? 'bg-[#6c5ce7] text-white' : 'bg-white text-[#4a4a4a] hover:bg-gray-100'"
                            x-on:click="active = '<?php echo e($category); ?>'">
                        <?php echo e($category); ?>

                    </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="grid grid-cols-1 <?php echo e($colClass); ?> gap-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalafd7cc30e71712a590a00d477ece43fc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalafd7cc30e71712a590a00d477ece43fc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cards.portfolio-card','data' => ['image' => $project['image'] ?? null,'title' => $project['title'],'category' => $project['category'],'tags' => $project['tags'] ?? [],'link' => $project['link'] ?? '#','lightbox' => $project['lightbox'] ?? false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cards.portfolio-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($project['image'] ?? null),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($project['title']),'category' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($project['category']),'tags' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($project['tags'] ?? []),'link' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($project['link'] ?? '#'),'lightbox' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($project['lightbox'] ?? false)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalafd7cc30e71712a590a00d477ece43fc)): ?>
<?php $attributes = $__attributesOriginalafd7cc30e71712a590a00d477ece43fc; ?>
<?php unset($__attributesOriginalafd7cc30e71712a590a00d477ece43fc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalafd7cc30e71712a590a00d477ece43fc)): ?>
<?php $component = $__componentOriginalafd7cc30e71712a590a00d477ece43fc; ?>
<?php unset($__componentOriginalafd7cc30e71712a590a00d477ece43fc); ?>
<?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showMore): ?>
            <div class="text-center mt-12">
                <a href="#portfolio" class="inline-flex items-center gap-2 text-[#6c5ce7] font-medium hover:gap-3 transition-all">
                    View All Projects
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</section><?php /**PATH E:\Saas website\webcraft\resources\views/components/sections/portfolio.blade.php ENDPATH**/ ?>