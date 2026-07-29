<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="theme-color" content="#0f172a">
    <title><?php echo $__env->yieldContent('title', config('app.name')); ?> - Admin</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Vite Assets -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    
    <!-- Custom Styles -->
    <?php echo $__env->yieldPushContent('styles'); ?>
    
    <style>
        [x-cloak] { display: none !important; }
        html, body { 
            height: 100%; 
            margin: 0; 
            padding: 0; 
            overflow: hidden; 
        }
        .toast-container {
            pointer-events: none;
        }
        .toast-container .toast-item {
            pointer-events: auto;
        }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased h-full">

    <div x-data="sidebarComponent()" x-init="init()" class="flex h-screen overflow-hidden">
        
        <!-- Mobile Overlay -->
        <div x-show="isMobileOpen"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden"
             @click="closeMobileSidebar()">
        </div>
        
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 border-r border-slate-800 transition-transform duration-300 ease-in-out lg:translate-x-0 h-full overflow-y-auto"
             :class="{
                 'translate-x-0': isMobileOpen || (windowWidth >= 1024 && isOpen), 
                 '-translate-x-full': !isMobileOpen && !(windowWidth >= 1024 && isOpen)
             }">
            <?php echo $__env->make('admin.partials.sidebar', [
                'totalUsers' => $totalUsers ?? 0,
                'activeSubscriptions' => $activeSubscriptions ?? 0,
                'draftPosts' => $draftPosts ?? 0,
                'openTickets' => $openTickets ?? 0,
                'unreadNotifications' => $unreadNotifications ?? 0,
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-screen w-full min-w-0 transition-all duration-300 ease-in-out lg:ml-72 overflow-hidden">
            
            <!-- Top Navigation -->
            <div class="flex-shrink-0 bg-white border-b border-slate-200">
                <?php echo $__env->make('admin.partials.top-nav', [
                    'unreadNotifications' => $unreadNotifications ?? 0,
                    'notifications' => $notifications ?? [],
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            
            <!-- Breadcrumb -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!request()->routeIs('admin.dashboard') && !request()->routeIs('admin.dashboard.redirect')): ?>
                <div class="flex-shrink-0 px-4 sm:px-6 py-2 bg-slate-50/80 border-b border-slate-200/80">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2">
                            <li>
                                <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-xs text-slate-400 hover:text-slate-600">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <?php if (! empty(trim($__env->yieldContent('breadcrumb')))): ?>
                                <li class="flex items-center">
                                    <i class="fas fa-chevron-right text-slate-300 text-[10px] mx-2"></i>
                                    <?php echo $__env->yieldContent('breadcrumb'); ?>
                                </li>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </ol>
                    </nav>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto w-full p-4 sm:p-6 lg:p-8">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-4 rounded shadow-sm">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-emerald-500 mr-2"></i>
                            <span class="text-emerald-700"><?php echo e(session('success')); ?></span>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded shadow-sm">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                            <span class="text-red-700"><?php echo e(session('error')); ?></span>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('warning')): ?>
                    <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-4 rounded shadow-sm">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-amber-500 mr-2"></i>
                            <span class="text-amber-700"><?php echo e(session('warning')); ?></span>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('info')): ?>
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4 rounded shadow-sm">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            <span class="text-blue-700"><?php echo e(session('info')); ?></span>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <?php echo $__env->yieldContent('content'); ?>
            </main>
            
            <!-- Footer -->
            <div class="flex-shrink-0 bg-white border-t border-slate-200">
                <?php echo $__env->make('admin.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    </div>
    
    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-50 flex flex-col space-y-2 max-w-sm w-full pointer-events-none">
    </div>

    <!-- Vite Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
    
    <!-- Custom Scripts -->
    <script>
        /**
         * Show a toast notification
         */
        function showToast(message, type = 'success', duration = 5000) {
            const container = document.getElementById('toast-container');
            if (!container) return;
            
            const colors = {
                success: 'border-emerald-500 text-emerald-700',
                error: 'border-red-500 text-red-700',
                warning: 'border-amber-500 text-amber-700',
                info: 'border-blue-500 text-blue-700'
            };
            
            const icons = {
                success: 'fa-check-circle text-emerald-500',
                error: 'fa-exclamation-circle text-red-500',
                warning: 'fa-exclamation-triangle text-amber-500',
                info: 'fa-info-circle text-blue-500'
            };
            
            const toast = document.createElement('div');
            toast.className = `flex items-center p-3 bg-white rounded-lg shadow-lg border-l-4 ${colors[type]} transform transition-all duration-300 translate-x-full pointer-events-auto toast-item`;
            toast.innerHTML = `
                <i class="fas ${icons[type]} mr-2"></i>
                <span class="text-sm flex-1">${escapeHtml(message)}</span>
                <button onclick="this.parentElement.remove()" class="ml-3 text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            container.appendChild(toast);
            
            // Trigger animation
            requestAnimationFrame(() => {
                toast.classList.remove('translate-x-full');
            });
            
            // Auto remove after duration
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.remove();
                    }
                }, 300);
            }, duration);
        }
        
        /**
         * Escape HTML to prevent XSS
         */
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        /**
         * Confirm action with user
         */
        function confirmAction(message, callback) {
            if (confirm(message)) {
                callback();
            }
        }
        
        /**
         * Copy text to clipboard
         */
        function copyToClipboard(text) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(() => {
                    showToast('Copied to clipboard!', 'success');
                }).catch(() => {
                    showToast('Failed to copy', 'error');
                });
            } else {
                // Fallback
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                try {
                    document.execCommand('copy');
                    showToast('Copied to clipboard!', 'success');
                } catch (err) {
                    showToast('Failed to copy', 'error');
                }
                document.body.removeChild(textarea);
            }
        }
        
        /**
         * Show loading state
         */
        function showLoading() {
            document.dispatchEvent(new CustomEvent('show-loading'));
        }
        
        /**
         * Hide loading state
         */
        function hideLoading() {
            document.dispatchEvent(new CustomEvent('hide-loading'));
        }
        
        // Make functions globally available
        window.showToast = showToast;
        window.confirmAction = confirmAction;
        window.copyToClipboard = copyToClipboard;
        window.showLoading = showLoading;
        window.hideLoading = hideLoading;
        
        // Console info
        console.log('✅ Admin panel initialized');
        console.log('Environment:', '<?php echo e(app()->environment()); ?>');
        console.log('Version:', '<?php echo e(config("app.version", "1.0.0")); ?>');
        
        // Auto-show flash messages as toasts
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(session('success')): ?>
                showToast('<?php echo e(session('success')); ?>', 'success');
            <?php endif; ?>
            
            <?php if(session('error')): ?>
                showToast('<?php echo e(session('error')); ?>', 'error');
            <?php endif; ?>
            
            <?php if(session('warning')): ?>
                showToast('<?php echo e(session('warning')); ?>', 'warning');
            <?php endif; ?>
            
            <?php if(session('info')): ?>
                showToast('<?php echo e(session('info')); ?>', 'info');
            <?php endif; ?>
        });
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH E:\Saas website\webcraft\resources\views/admin/layouts/app.blade.php ENDPATH**/ ?>