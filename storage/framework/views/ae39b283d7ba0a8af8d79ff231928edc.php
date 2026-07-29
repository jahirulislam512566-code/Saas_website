
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    
    <title><?php echo e($title ?? 'AgencyPro - Digital Agency'); ?></title>
    
    
    <meta name="description" content="<?php echo e($description ?? 'We build digital experiences that drive growth for forward-thinking brands.'); ?>">
    <meta name="keywords" content="<?php echo e($keywords ?? 'digital agency, web design, development, strategy, marketing'); ?>">
    <meta name="robots" content="<?php echo e($robots ?? 'index, follow'); ?>">
    <meta name="author" content="AgencyPro">
    <meta name="theme-color" content="#6c5ce7">
    
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($canonical)): ?>
        <link rel="canonical" href="<?php echo e($canonical); ?>">
    <?php else: ?>
        <link rel="canonical" href="<?php echo e(url()->current()); ?>">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($alternates)): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $alternates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <link rel="alternate" hreflang="<?php echo e($lang); ?>" href="<?php echo e($url); ?>">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
    
    <meta property="og:title" content="<?php echo e($ogTitle ?? ($title ?? 'AgencyPro - Digital Agency')); ?>">
    <meta property="og:description" content="<?php echo e($ogDescription ?? ($description ?? 'We build digital experiences that drive growth for forward-thinking brands.')); ?>">
    <meta property="og:image" content="<?php echo e($ogImage ?? asset('images/og-image.jpg')); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url" content="<?php echo e($ogUrl ?? url()->current()); ?>">
    <meta property="og:type" content="<?php echo e($ogType ?? 'website'); ?>">
    <meta property="og:site_name" content="<?php echo e($ogSiteName ?? 'AgencyPro'); ?>">
    <meta property="og:locale" content="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    
    
    <meta name="twitter:card" content="<?php echo e($twitterCard ?? 'summary_large_image'); ?>">
    <meta name="twitter:site" content="<?php echo e($twitterSite ?? '@AgencyPro'); ?>">
    <meta name="twitter:creator" content="<?php echo e($twitterCreator ?? '@AgencyPro'); ?>">
    <meta name="twitter:title" content="<?php echo e($twitterTitle ?? ($title ?? 'AgencyPro - Digital Agency')); ?>">
    <meta name="twitter:description" content="<?php echo e($twitterDescription ?? ($description ?? 'We build digital experiences that drive growth for forward-thinking brands.')); ?>">
    <meta name="twitter:image" content="<?php echo e($twitterImage ?? asset('images/twitter-image.jpg')); ?>">
    
    
    <?php echo $__env->yieldContent('schema'); ?>
    
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    
    
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('images/favicon.svg')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('images/apple-touch-icon.png')); ?>">
    <link rel="manifest" href="<?php echo e(asset('site.webmanifest')); ?>">
    <link rel="mask-icon" href="<?php echo e(asset('images/safari-pinned-tab.svg')); ?>" color="#6c5ce7">
    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" as="style">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    
    
    <?php echo $__env->yieldPushContent('styles'); ?>
    
    
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($criticalCss)): ?>
        <style><?php echo e($criticalCss); ?></style>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</head>
<body class="font-sans antialiased bg-white text-[#0a0a0a] min-h-screen flex flex-col <?php echo e($bodyClass ?? ''); ?>">
    
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-[#6c5ce7] focus:text-white focus:rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6c5ce7]/50">
        <?php echo e(__('Skip to main content')); ?>

    </a>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($showPreloader) && $showPreloader): ?>
        <div id="preloader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white transition-opacity duration-500">
            <div class="flex flex-col items-center gap-4">
                <div class="w-16 h-16 border-4 border-[#6c5ce7]/20 border-t-[#6c5ce7] rounded-full animate-spin"></div>
                <span class="text-sm font-medium text-[#4a4a4a]">Loading...</span>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!isset($hideHeader) || !$hideHeader): ?>
        <?php if (isset($component)) { $__componentOriginale30b2855ee1e4ae30e50fcbbc76a33ff = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale30b2855ee1e4ae30e50fcbbc76a33ff = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale30b2855ee1e4ae30e50fcbbc76a33ff)): ?>
<?php $attributes = $__attributesOriginale30b2855ee1e4ae30e50fcbbc76a33ff; ?>
<?php unset($__attributesOriginale30b2855ee1e4ae30e50fcbbc76a33ff); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale30b2855ee1e4ae30e50fcbbc76a33ff)): ?>
<?php $component = $__componentOriginale30b2855ee1e4ae30e50fcbbc76a33ff; ?>
<?php unset($__componentOriginale30b2855ee1e4ae30e50fcbbc76a33ff); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginala2568f40b19a532547b6c54a13ae4183 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala2568f40b19a532547b6c54a13ae4183 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.mobile-menu','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.mobile-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala2568f40b19a532547b6c54a13ae4183)): ?>
<?php $attributes = $__attributesOriginala2568f40b19a532547b6c54a13ae4183; ?>
<?php unset($__attributesOriginala2568f40b19a532547b6c54a13ae4183); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala2568f40b19a532547b6c54a13ae4183)): ?>
<?php $component = $__componentOriginala2568f40b19a532547b6c54a13ae4183; ?>
<?php unset($__componentOriginala2568f40b19a532547b6c54a13ae4183); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginalc9a852ea82221faa45c7546e247b615a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc9a852ea82221faa45c7546e247b615a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.search','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.search'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc9a852ea82221faa45c7546e247b615a)): ?>
<?php $attributes = $__attributesOriginalc9a852ea82221faa45c7546e247b615a; ?>
<?php unset($__attributesOriginalc9a852ea82221faa45c7546e247b615a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc9a852ea82221faa45c7546e247b615a)): ?>
<?php $component = $__componentOriginalc9a852ea82221faa45c7546e247b615a; ?>
<?php unset($__componentOriginalc9a852ea82221faa45c7546e247b615a); ?>
<?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <main id="main-content" class="flex-grow pt-[70px]">
        <?php if (! empty(trim($__env->yieldContent('content')))): ?>
            <?php echo $__env->yieldContent('content'); ?>
        <?php else: ?>
            <?php echo e($slot ?? ''); ?>

        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </main>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!isset($hideFooter) || !$hideFooter): ?>
        <?php if (isset($component)) { $__componentOriginal4766510e0268a7a5917e77b146281554 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4766510e0268a7a5917e77b146281554 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4766510e0268a7a5917e77b146281554)): ?>
<?php $attributes = $__attributesOriginal4766510e0268a7a5917e77b146281554; ?>
<?php unset($__attributesOriginal4766510e0268a7a5917e77b146281554); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4766510e0268a7a5917e77b146281554)): ?>
<?php $component = $__componentOriginal4766510e0268a7a5917e77b146281554; ?>
<?php unset($__componentOriginal4766510e0268a7a5917e77b146281554); ?>
<?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($showCookieConsent) && $showCookieConsent): ?>
        <?php echo $__env->make('partials.cookie-consent', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($showScrollTop) && $showScrollTop): ?>
        <button id="scroll-top" class="fixed bottom-8 right-8 z-50 p-3 bg-[#6c5ce7] text-white rounded-full shadow-lg hover:bg-[#5a4bd1] transition-all duration-300 opacity-0 invisible hover:scale-110" aria-label="Scroll to top">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
            </svg>
        </button>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
    
    
    
    
    
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    
    
    <?php echo $__env->yieldPushContent('scripts'); ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($inlineScripts)): ?>
        <script><?php echo $inlineScripts; ?></script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($showScrollTop) && $showScrollTop): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const scrollBtn = document.getElementById('scroll-top');
                if (!scrollBtn) return;
                
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 300) {
                        scrollBtn.classList.remove('opacity-0', 'invisible');
                        scrollBtn.classList.add('opacity-100', 'visible');
                    } else {
                        scrollBtn.classList.add('opacity-0', 'invisible');
                        scrollBtn.classList.remove('opacity-100', 'visible');
                    }
                });
                
                scrollBtn.addEventListener('click', function() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            });
        </script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</body>
</html><?php /**PATH E:\Saas website\webcraft\resources\views/layouts/app.blade.php ENDPATH**/ ?>