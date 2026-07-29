
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'image' => null,
    'title' => 'Blog Post Title',
    'excerpt' => 'Blog post excerpt goes here...',
    'author' => 'Author Name',
    'authorAvatar' => null,
    'date' => 'Jan 1, 2024',
    'category' => 'Category',
    'readingTime' => '5 min read',
    'tags' => [],
    'link' => '#',
    'featured' => false,
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
    'image' => null,
    'title' => 'Blog Post Title',
    'excerpt' => 'Blog post excerpt goes here...',
    'author' => 'Author Name',
    'authorAvatar' => null,
    'date' => 'Jan 1, 2024',
    'category' => 'Category',
    'readingTime' => '5 min read',
    'tags' => [],
    'link' => '#',
    'featured' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $featuredClass = $featured ? 'ring-2 ring-[#6c5ce7] shadow-xl' : '';
    $imageHeight = $featured ? 'aspect-[16/9]' : 'aspect-[4/3]';
?>

<article class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group <?php echo e($featuredClass); ?>">
    
    <div class="relative overflow-hidden <?php echo e($imageHeight); ?> bg-gray-100">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($image): ?>
            <img src="<?php echo e($image); ?>" alt="<?php echo e($title); ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        <?php else: ?>
            <div class="w-full h-full flex items-center justify-center text-4xl text-gray-300 bg-gradient-to-br from-gray-50 to-gray-100">
                📝
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category): ?>
            <span class="absolute top-4 left-4 bg-[#6c5ce7] text-white text-xs font-semibold px-3 py-1 rounded-full">
                <?php echo e($category); ?>

            </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featured): ?>
            <span class="absolute top-4 right-4 bg-amber-500 text-white text-xs font-semibold px-3 py-1 rounded-full">
                ⭐ Featured
            </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div class="p-6">
        <div class="flex items-center gap-4 text-xs text-[#4a4a4a] mb-3">
            <span><?php echo e($date); ?></span>
            <span>•</span>
            <span><?php echo e($readingTime); ?></span>
        </div>

        <h3 class="text-xl font-bold text-[#0a0a0a] hover:text-[#6c5ce7] transition-colors mb-2">
            <a href="<?php echo e($link); ?>"><?php echo e($title); ?></a>
        </h3>

        <p class="text-[#4a4a4a] leading-relaxed mb-4"><?php echo e($excerpt); ?></p>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($tags) > 0): ?>
            <div class="flex flex-wrap gap-2 mb-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">#<?php echo e($tag); ?></span>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#6c5ce7] to-[#a78bfa] flex items-center justify-center text-white font-semibold text-sm">
                <?php echo e($authorAvatar ? '<img src="' . $authorAvatar . '" alt="' . $author . '" class="w-full h-full object-cover rounded-full">' : strtoupper(substr($author, 0, 2))); ?>

            </div>
            <div>
                <p class="text-sm font-medium text-[#0a0a0a]"><?php echo e($author); ?></p>
                <a href="<?php echo e($link); ?>" class="text-xs text-[#6c5ce7] hover:underline">Read full article →</a>
            </div>
        </div>
    </div>
</article><?php /**PATH E:\Saas website\webcraft\resources\views/components/cards/blog-card.blade.php ENDPATH**/ ?>