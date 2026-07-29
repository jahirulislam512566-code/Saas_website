
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'query' => '',
    'results' => [],
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
    'query' => '',
    'results' => [],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>



<?php $__env->startSection('title', 'Search Results - AgencyPro'); ?>
<?php $__env->startSection('description', 'Search results for "' . $query . '" on the AgencyPro blog.'); ?>

<?php $__env->startSection('content'); ?>
    
    <?php if (isset($component)) { $__componentOriginal4743781065990dfe96029737c4f06097 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4743781065990dfe96029737c4f06097 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.page-header','data' => ['title' => 'Search Results','subtitle' => 'Search','breadcrumb' => [
            ['label' => 'Blog', 'url' => route('blog.index')],
            ['label' => 'Search']
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Search Results'),'subtitle' => 'Search','breadcrumb' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['label' => 'Blog', 'url' => route('blog.index')],
            ['label' => 'Search']
        ])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4743781065990dfe96029737c4f06097)): ?>
<?php $attributes = $__attributesOriginal4743781065990dfe96029737c4f06097; ?>
<?php unset($__attributesOriginal4743781065990dfe96029737c4f06097); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4743781065990dfe96029737c4f06097)): ?>
<?php $component = $__componentOriginal4743781065990dfe96029737c4f06097; ?>
<?php unset($__componentOriginal4743781065990dfe96029737c4f06097); ?>
<?php endif; ?>

    
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto mb-12">
                <form action="<?php echo e(route('blog.search')); ?>" method="GET" class="flex gap-3">
                    <input type="text" name="q" placeholder="Search articles..." value="<?php echo e($query); ?>"
                           class="flex-1 px-5 py-3.5 text-lg rounded-xl border-2 border-gray-200 focus:border-[#6c5ce7] focus:ring-[#6c5ce7]/20 transition-all outline-none">
                    <button type="submit" class="px-8 py-3.5 bg-[#6c5ce7] text-white text-lg font-medium rounded-xl hover:bg-[#5a4bd1] transition-colors shadow-lg shadow-[#6c5ce7]/30">
                        Search
                    </button>
                </form>
            </div>

            <div class="text-center max-w-3xl mx-auto mb-12">
                <h2 class="text-2xl font-bold text-[#0a0a0a]">
                    <?php echo e(count($results)); ?> <?php echo e(count($results) === 1 ? 'result' : 'results'); ?> for "<?php echo e($query); ?>"
                </h2>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($results) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal099deadc7cd53bb5c0d398f78ab4b2e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal099deadc7cd53bb5c0d398f78ab4b2e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cards.blog-card','data' => ['image' => $post['image'] ?? null,'title' => $post['title'],'excerpt' => $post['excerpt'] ?? '','author' => $post['author'] ?? '','date' => $post['date'] ?? date('M d, Y'),'category' => $post['category'] ?? '','readingTime' => $post['readingTime'] ?? '5 min read','tags' => $post['tags'] ?? [],'link' => $post['link'] ?? '#','featured' => $post['featured'] ?? false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cards.blog-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post['image'] ?? null),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post['title']),'excerpt' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post['excerpt'] ?? ''),'author' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post['author'] ?? ''),'date' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post['date'] ?? date('M d, Y')),'category' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post['category'] ?? ''),'reading-time' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post['readingTime'] ?? '5 min read'),'tags' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post['tags'] ?? []),'link' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post['link'] ?? '#'),'featured' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post['featured'] ?? false)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal099deadc7cd53bb5c0d398f78ab4b2e2)): ?>
<?php $attributes = $__attributesOriginal099deadc7cd53bb5c0d398f78ab4b2e2; ?>
<?php unset($__attributesOriginal099deadc7cd53bb5c0d398f78ab4b2e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal099deadc7cd53bb5c0d398f78ab4b2e2)): ?>
<?php $component = $__componentOriginal099deadc7cd53bb5c0d398f78ab4b2e2; ?>
<?php unset($__componentOriginal099deadc7cd53bb5c0d398f78ab4b2e2); ?>
<?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
                <div class="mt-12">
                    <?php if (isset($component)) { $__componentOriginal4d04f29578652eb91560cfbf2ab48c57 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4d04f29578652eb91560cfbf2ab48c57 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.pagination','data' => ['current' => 1,'total' => 100,'perPage' => 9,'url' => ''.e(route('blog.search')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['current' => 1,'total' => 100,'per-page' => 9,'url' => ''.e(route('blog.search')).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4d04f29578652eb91560cfbf2ab48c57)): ?>
<?php $attributes = $__attributesOriginal4d04f29578652eb91560cfbf2ab48c57; ?>
<?php unset($__attributesOriginal4d04f29578652eb91560cfbf2ab48c57); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4d04f29578652eb91560cfbf2ab48c57)): ?>
<?php $component = $__componentOriginal4d04f29578652eb91560cfbf2ab48c57; ?>
<?php unset($__componentOriginal4d04f29578652eb91560cfbf2ab48c57); ?>
<?php endif; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-16">
                    <div class="text-6xl mb-4">🔍</div>
                    <h3 class="text-2xl font-bold text-[#0a0a0a]">No Results Found</h3>
                    <p class="text-[#4a4a4a] mt-2">We couldn't find any articles matching "<?php echo e($query); ?>".</p>
                    <a href="<?php echo e(route('blog.index')); ?>" class="inline-flex items-center gap-2 mt-4 text-[#6c5ce7] font-medium hover:gap-3 transition-all">
                        Browse All Articles →
                    </a>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Saas website\webcraft\resources\views/frontend/blog/search.blade.php ENDPATH**/ ?>