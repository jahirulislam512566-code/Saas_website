


<?php $__env->startSection('title', 'Page Not Found - AgencyPro'); ?>

<?php $__env->startSection('content'); ?>
    <div class="text-center max-w-lg mx-auto">
        <div class="text-8xl mb-6 text-[#6c5ce7]">🔍</div>
        <h1 class="text-4xl font-bold text-[#0a0a0a] mb-4">404</h1>
        <h2 class="text-2xl font-semibold text-[#0a0a0a] mb-4">Page Not Found</h2>
        <p class="text-lg text-[#4a4a4a] mb-8">
            <?php echo e($exception->getMessage() ?: "We couldn't find the page you were looking for. It might have been moved or deleted."); ?>

        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center px-6 py-3 bg-[#6c5ce7] text-white font-medium rounded-xl hover:bg-[#5a4bd1] transition-all duration-300 shadow-lg shadow-[#6c5ce7]/30">
                <i class="fas fa-home mr-2"></i>
                Go Home
            </a>
            <a href="<?php echo e(route('services.index')); ?>" class="inline-flex items-center px-6 py-3 bg-white border-2 border-gray-200 text-[#0a0a0a] font-medium rounded-xl hover:border-[#6c5ce7] hover:text-[#6c5ce7] transition-all duration-300">
                <i class="fas fa-bolt mr-2"></i>
                Our Services
            </a>
        </div>

        
        <div class="mt-12 pt-8 border-t border-gray-100">
            <h3 class="text-sm font-semibold text-[#4a4a4a] uppercase tracking-wider mb-4">You might be looking for</h3>
            <div class="flex flex-wrap justify-center gap-3">
                <a href="<?php echo e(route('portfolio.index')); ?>" class="text-sm text-[#6c5ce7] hover:underline">Portfolio</a>
                <span class="text-[#4a4a4a]">•</span>
                <a href="<?php echo e(route('blog.index')); ?>" class="text-sm text-[#6c5ce7] hover:underline">Blog</a>
                <span class="text-[#4a4a4a]">•</span>
                <a href="<?php echo e(route('pricing.index')); ?>" class="text-sm text-[#6c5ce7] hover:underline">Pricing</a>
                <span class="text-[#4a4a4a]">•</span>
                <a href="<?php echo e(route('contact.index')); ?>" class="text-sm text-[#6c5ce7] hover:underline">Contact</a>
            </div>
        </div>

        <div class="mt-6">
            <a href="<?php echo e(route('contact.index')); ?>" class="text-sm text-[#6c5ce7] hover:underline">
                <i class="fas fa-envelope mr-1"></i>
                Report this issue
            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('errors.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Saas website\webcraft\resources\views/errors/404.blade.php ENDPATH**/ ?>