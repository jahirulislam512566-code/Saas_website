<!-- admin/partials/notifications.blade.php -->
<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" 
            class="relative p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
        <i class="fas fa-bell text-gray-700 dark:text-gray-300 text-lg"></i>
        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white dark:ring-gray-900 animate-pulse"></span>
    </button>
    
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-900 rounded-xl shadow-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        
        <!-- Header -->
        <div class="p-3 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <span class="font-semibold text-gray-900 dark:text-white">Notifications</span>
            <button class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Mark all read</button>
        </div>
        
        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = Auth::user()->notifications->take(5) ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors cursor-pointer group">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/20 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-<?php echo e($notification->data['icon'] ?? 'bell'); ?> text-indigo-600 dark:text-indigo-400 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                <?php echo e($notification->data['message'] ?? 'New notification'); ?>

                            </p>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                <?php echo e($notification->created_at->diffForHumans()); ?>

                            </span>
                        </div>
                        <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="p-8 text-center">
                    <i class="fas fa-bell-slash text-3xl text-gray-300 dark:text-gray-700 mb-2"></i>
                    <p class="text-sm text-gray-500 dark:text-gray-400">No notifications</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        
        <!-- Footer -->
        <div class="p-2 border-t border-gray-200 dark:border-gray-800 text-center">
            
        </div>
    </div>
</div><?php /**PATH E:\Saas website\webcraft\resources\views/admin/partials/notifications.blade.php ENDPATH**/ ?>