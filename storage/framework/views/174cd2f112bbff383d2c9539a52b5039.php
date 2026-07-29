
<?php
    $pageTitle = 'Blog - AgencyPro';
    $pageDescription = 'Insights, tips, and stories from our team. Stay updated with the latest trends in digital design, development, and strategy.';
?>



<?php $__env->startSection('title', $pageTitle); ?>
<?php $__env->startSection('description', $pageDescription); ?>

<?php $__env->startSection('schema'); ?>
    <?php if (isset($component)) { $__componentOriginalded78f06848237162dd881d8c2772d5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalded78f06848237162dd881d8c2772d5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo.schema','data' => ['type' => 'WebSite','data' => [
        'name' => 'AgencyPro Blog',
        'url' => url('/blog'),
        'description' => $pageDescription,
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seo.schema'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'WebSite','data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        'name' => 'AgencyPro Blog',
        'url' => url('/blog'),
        'description' => $pageDescription,
    ])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalded78f06848237162dd881d8c2772d5e)): ?>
<?php $attributes = $__attributesOriginalded78f06848237162dd881d8c2772d5e; ?>
<?php unset($__attributesOriginalded78f06848237162dd881d8c2772d5e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalded78f06848237162dd881d8c2772d5e)): ?>
<?php $component = $__componentOriginalded78f06848237162dd881d8c2772d5e; ?>
<?php unset($__componentOriginalded78f06848237162dd881d8c2772d5e); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    
    <?php if (isset($component)) { $__componentOriginal4743781065990dfe96029737c4f06097 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4743781065990dfe96029737c4f06097 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.page-header','data' => ['title' => 'Blog & Insights','subtitle' => 'Latest Articles','breadcrumb' => [['label' => 'Blog']]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Blog & Insights','subtitle' => 'Latest Articles','breadcrumb' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'Blog']])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

         <?php $__env->slot('actions', null, []); ?> 
            <form action="<?php echo e(route('blog.search')); ?>" method="GET" class="flex gap-2">
                <input type="text" name="q" placeholder="Search articles..." 
                       class="px-4 py-2.5 rounded-xl border-2 border-gray-200 focus:border-[#6c5ce7] focus:ring-[#6c5ce7]/20 transition-all outline-none text-sm">
                <button type="submit" class="px-4 py-2.5 bg-[#6c5ce7] text-white rounded-xl hover:bg-[#5a4bd1] transition-colors text-sm font-medium">
                    Search
                </button>
            </form>
         <?php $__env->endSlot(); ?>
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

    
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-5xl mx-auto">
                <?php if (isset($component)) { $__componentOriginal099deadc7cd53bb5c0d398f78ab4b2e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal099deadc7cd53bb5c0d398f78ab4b2e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cards.blog-card','data' => ['image' => ''.e(asset('images/blog/featured.jpg')).'','title' => '10 Web Design Trends That Will Dominate 2024','excerpt' => 'Discover the latest design trends that are shaping the future of the web. From AI-powered interfaces to immersive experiences, here\'s what you need to know.','author' => 'Jane Smith','date' => 'Dec 18, 2024','category' => 'Design','readingTime' => '10 min read','tags' => ['Web Design', 'Trends', 'UI/UX'],'link' => ''.e(route('blog.show', 'web-design-trends-2024')).'','featured' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cards.blog-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => ''.e(asset('images/blog/featured.jpg')).'','title' => '10 Web Design Trends That Will Dominate 2024','excerpt' => 'Discover the latest design trends that are shaping the future of the web. From AI-powered interfaces to immersive experiences, here\'s what you need to know.','author' => 'Jane Smith','date' => 'Dec 18, 2024','category' => 'Design','readingTime' => '10 min read','tags' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Web Design', 'Trends', 'UI/UX']),'link' => ''.e(route('blog.show', 'web-design-trends-2024')).'','featured' => 'true']); ?>
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
            </div>
        </div>
    </section>

    
    <section class="py-8 bg-gray-50 border-y border-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-center gap-3">
                <a href="<?php echo e(route('blog.index')); ?>" class="px-4 py-2 bg-[#6c5ce7] text-white rounded-full text-sm font-medium transition-colors hover:bg-[#5a4bd1]">
                    All
                </a>
                <a href="<?php echo e(route('blog.category', 'design')); ?>" class="px-4 py-2 bg-white text-[#4a4a4a] rounded-full text-sm font-medium hover:bg-[#6c5ce7] hover:text-white transition-colors">
                    Design
                </a>
                <a href="<?php echo e(route('blog.category', 'development')); ?>" class="px-4 py-2 bg-white text-[#4a4a4a] rounded-full text-sm font-medium hover:bg-[#6c5ce7] hover:text-white transition-colors">
                    Development
                </a>
                <a href="<?php echo e(route('blog.category', 'marketing')); ?>" class="px-4 py-2 bg-white text-[#4a4a4a] rounded-full text-sm font-medium hover:bg-[#6c5ce7] hover:text-white transition-colors">
                    Marketing
                </a>
                <a href="<?php echo e(route('blog.category', 'strategy')); ?>" class="px-4 py-2 bg-white text-[#4a4a4a] rounded-full text-sm font-medium hover:bg-[#6c5ce7] hover:text-white transition-colors">
                    Strategy
                </a>
                <a href="<?php echo e(route('blog.category', 'technology')); ?>" class="px-4 py-2 bg-white text-[#4a4a4a] rounded-full text-sm font-medium hover:bg-[#6c5ce7] hover:text-white transition-colors">
                    Technology
                </a>
            </div>
        </div>
    </section>

    
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = range(1, 9); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal099deadc7cd53bb5c0d398f78ab4b2e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal099deadc7cd53bb5c0d398f78ab4b2e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cards.blog-card','data' => ['image' => ''.e(asset('images/blog/post' . $i . '.jpg')).'','title' => ''.e(['Building Scalable Web Applications', 'The Future of AI in Design', 'SEO Strategies That Work', 'Mobile-First Design Principles', 'How to Build a Brand Identity', 'The Rise of Headless CMS', 'Performance Optimization Tips', 'User Research Best Practices', 'The Psychology of Color in Design'][$i-1]).'','excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','author' => ''.e(['John Doe', 'Jane Smith', 'Mike Johnson', 'Sarah Chen', 'Alex Rivera', 'Maria Santos', 'James Park', 'Emily Watson', 'David Kim'][$i-1]).'','date' => ''.e(date('M d, Y', strtotime('-' . $i . ' days'))).'','category' => ''.e(['Design', 'Development', 'Marketing', 'Design', 'Strategy', 'Technology', 'Development', 'Design', 'Marketing'][$i-1]).'','readingTime' => ''.e(rand(4, 12)).' min read','tags' => [['Design', 'Trends'], ['Development', 'Scalability'], ['SEO', 'Marketing'], ['Design', 'Mobile'], ['Branding', 'Strategy'], ['CMS', 'Technology'], ['Performance', 'Optimization'], ['Research', 'UX'], ['Design', 'Psychology']][$i-1],'link' => ''.e(route('blog.show', 'post-' . $i)).'','featured' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cards.blog-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => ''.e(asset('images/blog/post' . $i . '.jpg')).'','title' => ''.e(['Building Scalable Web Applications', 'The Future of AI in Design', 'SEO Strategies That Work', 'Mobile-First Design Principles', 'How to Build a Brand Identity', 'The Rise of Headless CMS', 'Performance Optimization Tips', 'User Research Best Practices', 'The Psychology of Color in Design'][$i-1]).'','excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','author' => ''.e(['John Doe', 'Jane Smith', 'Mike Johnson', 'Sarah Chen', 'Alex Rivera', 'Maria Santos', 'James Park', 'Emily Watson', 'David Kim'][$i-1]).'','date' => ''.e(date('M d, Y', strtotime('-' . $i . ' days'))).'','category' => ''.e(['Design', 'Development', 'Marketing', 'Design', 'Strategy', 'Technology', 'Development', 'Design', 'Marketing'][$i-1]).'','readingTime' => ''.e(rand(4, 12)).' min read','tags' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['Design', 'Trends'], ['Development', 'Scalability'], ['SEO', 'Marketing'], ['Design', 'Mobile'], ['Branding', 'Strategy'], ['CMS', 'Technology'], ['Performance', 'Optimization'], ['Research', 'UX'], ['Design', 'Psychology']][$i-1]),'link' => ''.e(route('blog.show', 'post-' . $i)).'','featured' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.pagination','data' => ['current' => 1,'total' => 100,'perPage' => 9,'url' => ''.e(route('blog.index')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['current' => 1,'total' => 100,'per-page' => 9,'url' => ''.e(route('blog.index')).'']); ?>
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
        </div>
    </section>

    
    <?php if (isset($component)) { $__componentOriginal18a0ec3e0580bff97cbbe3c339f815c9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18a0ec3e0580bff97cbbe3c339f815c9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sections.newsletter','data' => ['title' => 'Subscribe to Our Newsletter','subtitle' => 'Stay Updated','description' => 'Get the latest insights and updates delivered straight to your inbox.','formAction' => '#','buttonText' => 'Subscribe']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sections.newsletter'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Subscribe to Our Newsletter','subtitle' => 'Stay Updated','description' => 'Get the latest insights and updates delivered straight to your inbox.','form-action' => '#','button-text' => 'Subscribe']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18a0ec3e0580bff97cbbe3c339f815c9)): ?>
<?php $attributes = $__attributesOriginal18a0ec3e0580bff97cbbe3c339f815c9; ?>
<?php unset($__attributesOriginal18a0ec3e0580bff97cbbe3c339f815c9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18a0ec3e0580bff97cbbe3c339f815c9)): ?>
<?php $component = $__componentOriginal18a0ec3e0580bff97cbbe3c339f815c9; ?>
<?php unset($__componentOriginal18a0ec3e0580bff97cbbe3c339f815c9); ?>
<?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginal3059bb6f234c7f6929934634de0e93cd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3059bb6f234c7f6929934634de0e93cd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sections.cta','data' => ['title' => 'Want to Contribute to Our Blog?','subtitle' => 'Write for Us','description' => 'We\'re always looking for talented writers and industry experts to share their insights.','primaryText' => 'Contact Us','primaryLink' => '#contact','secondaryText' => 'View All Posts','secondaryLink' => ''.e(route('blog.index')).'','variant' => 'default']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sections.cta'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Want to Contribute to Our Blog?','subtitle' => 'Write for Us','description' => 'We\'re always looking for talented writers and industry experts to share their insights.','primary-text' => 'Contact Us','primary-link' => '#contact','secondary-text' => 'View All Posts','secondary-link' => ''.e(route('blog.index')).'','variant' => 'default']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3059bb6f234c7f6929934634de0e93cd)): ?>
<?php $attributes = $__attributesOriginal3059bb6f234c7f6929934634de0e93cd; ?>
<?php unset($__attributesOriginal3059bb6f234c7f6929934634de0e93cd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3059bb6f234c7f6929934634de0e93cd)): ?>
<?php $component = $__componentOriginal3059bb6f234c7f6929934634de0e93cd; ?>
<?php unset($__componentOriginal3059bb6f234c7f6929934634de0e93cd); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Saas website\webcraft\resources\views/frontend/blog/index.blade.php ENDPATH**/ ?>