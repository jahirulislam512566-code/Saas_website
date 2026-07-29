


<?php $__env->startSection('title', 'Search Results - AgencyPro'); ?>
<?php $__env->startSection('description', 'Search results for "' . ($query ?? '') . '" on AgencyPro'); ?>

<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginal4743781065990dfe96029737c4f06097 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4743781065990dfe96029737c4f06097 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.page-header','data' => ['title' => 'Search Results','subtitle' => 'Search','breadcrumb' => [['label' => 'Search']]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Search Results','subtitle' => 'Search','breadcrumb' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'Search']])]); ?>
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
            <div class="max-w-4xl mx-auto">
                
                <div class="mb-8">
                    <form action="<?php echo e(route('search')); ?>" method="GET" class="flex flex-col sm:flex-row gap-3">
                        <input 
                            type="text" 
                            name="q" 
                            value="<?php echo e($query); ?>"
                            placeholder="Search for services, articles, projects..."
                            class="flex-1 px-5 py-3.5 text-lg border-2 border-gray-200 rounded-xl focus:outline-none focus:border-[#6c5ce7] focus:ring-2 focus:ring-[#6c5ce7]/20 transition-all duration-200"
                            autofocus
                        >
                        <button type="submit" class="px-8 py-3.5 text-lg font-medium text-white bg-[#6c5ce7] rounded-xl hover:bg-[#5a4bd1] transition-all duration-200 shadow-lg shadow-[#6c5ce7]/30 whitespace-nowrap">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/>
                                    <path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="2"/>
                                </svg>
                                Search
                            </span>
                        </button>
                    </form>
                </div>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($query)): ?>
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                        <h2 class="text-xl font-bold text-[#0a0a0a]">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($totalResults > 0): ?>
                                <?php echo e($totalResults); ?> results for "<?php echo e($query); ?>"
                            <?php else: ?>
                                No results found for "<?php echo e($query); ?>"
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </h2>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($searchTime): ?>
                            <span class="text-sm text-[#4a4a4a]">Found in <?php echo e($searchTime); ?>ms</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($results->count() > 0): ?>
                    <div class="space-y-6">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 hover:border-[#6c5ce7]/20">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-12 h-12 bg-[#6c5ce7]/10 rounded-xl flex items-center justify-center text-2xl">
                                        <i class="fas <?php echo e($result->icon ?? 'fa-file'); ?> text-[#6c5ce7]"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <h3 class="text-xl font-bold text-[#0a0a0a] hover:text-[#6c5ce7] transition-colors">
                                                <a href="<?php echo e($result->url ?? '#'); ?>"><?php echo e($result->title ?? $result->name ?? 'Untitled'); ?></a>
                                            </h3>
                                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-[#6c5ce7]/10 text-[#6c5ce7]">
                                                <?php echo e($result->type_label ?? ucfirst($result->type ?? '')); ?>

                                            </span>
                                        </div>
                                        
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($result->description) || isset($result->excerpt)): ?>
                                            <p class="text-[#4a4a4a] mt-1">
                                                <?php echo e(Str::limit($result->description ?? $result->excerpt ?? '', 200)); ?>

                                            </p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        <div class="flex flex-wrap items-center gap-3 mt-3 text-sm text-[#4a4a4a]">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($result->client)): ?>
                                                <span class="flex items-center gap-1">
                                                    <i class="fas fa-user text-xs"></i>
                                                    <?php echo e($result->client); ?>

                                                </span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($result->category)): ?>
                                                <span class="flex items-center gap-1">
                                                    <i class="fas fa-folder text-xs"></i>
                                                    <?php echo e(is_object($result->category) ? $result->category->name : $result->category); ?>

                                                </span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($result->published_at) || isset($result->created_at)): ?>
                                                <span class="flex items-center gap-1">
                                                    <i class="fas fa-calendar text-xs"></i>
                                                    <?php echo e(($result->published_at ?? $result->created_at)->format('M d, Y')); ?>

                                                </span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>

                    
                    <div class="mt-12">
                        <?php echo e($results->appends(['q' => $query])->links()); ?>

                    </div>
                <?php elseif(!empty($query)): ?>
                    
                    <div class="text-center py-16">
                        <div class="text-6xl mb-4">🔍</div>
                        <h3 class="text-2xl font-bold text-[#0a0a0a]">No results found</h3>
                        <p class="text-[#4a4a4a] mt-2 max-w-md mx-auto">
                            We couldn't find any results for "<?php echo e($query); ?>". Try adjusting your search terms or browse our categories.
                        </p>
                        <div class="flex flex-wrap justify-center gap-4 mt-6">
                            <a href="<?php echo e(route('services.index')); ?>" class="inline-flex items-center gap-2 text-[#6c5ce7] font-medium hover:gap-3 transition-all">
                                Browse Services
                            </a>
                            <a href="<?php echo e(route('blog.index')); ?>" class="inline-flex items-center gap-2 text-[#6c5ce7] font-medium hover:gap-3 transition-all">
                                Read Blog
                            </a>
                            <a href="<?php echo e(route('portfolio.index')); ?>" class="inline-flex items-center gap-2 text-[#6c5ce7] font-medium hover:gap-3 transition-all">
                                View Portfolio
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    
                    <div class="text-center py-16">
                        <div class="text-6xl mb-4">🔎</div>
                        <h3 class="text-2xl font-bold text-[#0a0a0a]">Start Searching</h3>
                        <p class="text-[#4a4a4a] mt-2">Enter a search term above to find what you're looking for.</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Saas website\webcraft\resources\views/frontend/search/results.blade.php ENDPATH**/ ?>