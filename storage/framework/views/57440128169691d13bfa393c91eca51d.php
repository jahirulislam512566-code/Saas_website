
<header class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-b border-black/5 h-[70px] flex items-center transition-all duration-300">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="flex items-center justify-between gap-8">
            
            <div class="flex-shrink-0">
                <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2 no-underline font-bold text-2xl text-[#0a0a0a]">
                    <span class="text-3xl text-[#6c5ce7]">⚡</span>
                    <span class="logo-text">Agency<span class="text-[#6c5ce7]">Pro</span></span>
                </a>
            </div>

            
            <?php if (isset($component)) { $__componentOriginal7a1851460580b016997ecb03412ebcac = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7a1851460580b016997ecb03412ebcac = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.navbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7a1851460580b016997ecb03412ebcac)): ?>
<?php $attributes = $__attributesOriginal7a1851460580b016997ecb03412ebcac; ?>
<?php unset($__attributesOriginal7a1851460580b016997ecb03412ebcac); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7a1851460580b016997ecb03412ebcac)): ?>
<?php $component = $__componentOriginal7a1851460580b016997ecb03412ebcac; ?>
<?php unset($__componentOriginal7a1851460580b016997ecb03412ebcac); ?>
<?php endif; ?>

            
            <div class="flex items-center gap-3 ml-auto">
                
                <button 
                    class="search-toggle p-2 text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors cursor-pointer bg-transparent border-none rounded-lg hover:bg-[#6c5ce7]/5" 
                    aria-label="Toggle search"
                    x-data
                    @click="$dispatch('open-search')"
                >
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="M21 21l-4.35-4.35"/>
                    </svg>
                </button>
                
                
                <button class="flex-col gap-1 p-2 bg-transparent border-none cursor-pointer lg:hidden mobile-menu-toggle" aria-label="Toggle mobile menu">
                    <span class="block w-6 h-0.5 bg-[#1a1a1a] transition-all duration-300 rounded"></span>
                    <span class="block w-6 h-0.5 bg-[#1a1a1a] transition-all duration-300 rounded"></span>
                    <span class="block w-6 h-0.5 bg-[#1a1a1a] transition-all duration-300 rounded"></span>
                </button>
            </div>
        </div>
    </div>
</header><?php /**PATH E:\Saas website\webcraft\resources\views/components/layout/header.blade.php ENDPATH**/ ?>