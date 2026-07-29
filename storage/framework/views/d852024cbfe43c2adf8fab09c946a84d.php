<!-- admin/partials/top-nav.blade.php -->
<header class="sticky top-0 z-40 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border-b border-gray-200 dark:border-gray-800">
    <div class="flex items-center justify-between px-4 h-16">
        <!-- Left side -->
        <div class="flex items-center gap-4">
            <!-- Mobile menu toggle -->
            <button @click="$dispatch('toggle-sidebar')" 
                    class="lg:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <i class="fas fa-bars text-gray-700 dark:text-gray-300 text-lg"></i>
            </button>
            
            <!-- Page Title -->
            <h1 class="text-lg font-semibold text-gray-900 dark:text-white hidden sm:block">
                <?php echo $__env->yieldContent('page-title', 'Dashboard'); ?>
            </h1>
        </div>
        
        <!-- Right side -->
        <div class="flex items-center gap-3">
            <!-- Search -->
            <?php echo $__env->make('admin.partials.search', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            
            <!-- Divider -->
            <div class="w-px h-8 bg-gray-200 dark:bg-gray-700 hidden sm:block"></div>
            
            <!-- Notifications -->
            <?php echo $__env->make('admin.partials.notifications', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            
            <!-- Quick Actions -->
            <?php echo $__env->make('admin.partials.quick-actions', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            
            <!-- User Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <img src="<?php echo e(Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name)); ?>" 
                         alt="<?php echo e(Auth::user()->name); ?>" 
                         class="w-8 h-8 rounded-full ring-2 ring-indigo-500/20">
                    <span class="hidden sm:block text-sm font-medium text-gray-700 dark:text-gray-300">
                        <?php echo e(Auth::user()->name); ?>

                    </span>
                    <i class="fas fa-chevron-down text-xs text-gray-500 dark:text-gray-400 hidden sm:block"></i>
                </button>
                
                <!-- Dropdown -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-800 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-800">
                        <p class="text-sm font-medium text-gray-900 dark:text-white"><?php echo e(Auth::user()->name); ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e(Auth::user()->email); ?></p>
                    </div>
                    <div class="py-1">
                        
                        
                        <div class="border-t border-gray-200 dark:border-gray-800 my-1"></div>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <i class="fas fa-sign-out-alt w-5 text-center"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Breadcrumb -->
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($breadcrumbs)): ?>
    <div class="bg-white/50 dark:bg-gray-900/50 backdrop-blur-sm border-b border-gray-200 dark:border-gray-800">
        <div class="px-4 py-2">
            <?php echo $__env->make('admin.partials.breadcrumb', ['breadcrumbs' => $breadcrumbs], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php /**PATH E:\Saas website\webcraft\resources\views/admin/partials/top-nav.blade.php ENDPATH**/ ?>