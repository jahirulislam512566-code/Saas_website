
<div class="fixed top-0 right-[-100%] w-[320px] max-w-[80vw] h-screen bg-white z-[1001] p-6 transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] flex flex-col overflow-y-auto mobile-menu" role="dialog" aria-modal="true">
    
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
        <span class="font-bold text-2xl">Agency
           
      </span>
        <button class="text-2xl bg-transparent border-none cursor-pointer hover:text-[#6c5ce7] transition-colors mobile-menu-close" aria-label="Close menu">✕</button>
    </div>

    
    <nav class="flex-1">
        <ul class="space-y-1 list-none p-0 m-0">
            
            <li>
                <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-3 px-4 py-3 text-base font-medium text-[#1a1a1a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline active:text-[#6c5ce7] active:bg-[#6c5ce7]/10">
                    <span>🏠</span> Home
                </a>
            </li>

            
            <li>
                <a href="<?php echo e(route('pages.about')); ?>" class="flex items-center gap-3 px-4 py-3 text-base font-medium text-[#1a1a1a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline">
                    <span>👤</span> About
                </a>
            </li>

            
            <li x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-3 text-base font-medium text-[#1a1a1a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl bg-transparent border-none cursor-pointer">
                    <span class="flex items-center gap-3">
                        <span>⚡</span> Services
                    </span>
                    <svg class="w-4 h-4 transition-transform duration-200" x-bind:class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-collapse class="ml-4 space-y-1">
                    <a href="<?php echo e(route('services.web-design')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#4a4a4a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline">
                        <span>🎨</span> Website Design
                    </a>
                    <a href="<?php echo e(route('services.web-development')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#4a4a4a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline">
                        <span>💻</span> Web Development
                    </a>
                    <a href="<?php echo e(route('services.ecommerce')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#4a4a4a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline">
                        <span>🛒</span> E-Commerce
                    </a>
                    <a href="<?php echo e(route('services.seo')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#4a4a4a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline">
                        <span>🔍</span> SEO
                    </a>
                    <a href="<?php echo e(route('services.uiux-design')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#4a4a4a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline">
                        <span>✨</span> UI/UX Design
                    </a>
                    <a href="<?php echo e(route('services.maintenance')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#4a4a4a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline">
                        <span>🔧</span> Maintenance
                    </a>
                    <a href="<?php echo e(route('services.hosting')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#4a4a4a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline">
                        <span>☁️</span> Hosting
                    </a>
                </div>
            </li>

            
            <li>
                <a href="<?php echo e(route('portfolio.index')); ?>" class="flex items-center gap-3 px-4 py-3 text-base font-medium text-[#1a1a1a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline">
                    <span>💼</span> Portfolio
                </a>
            </li>

            
            <li>
                <a href="<?php echo e(route('pricing.index')); ?>" class="flex items-center gap-3 px-4 py-3 text-base font-medium text-[#1a1a1a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline">
                    <span>💰</span> Pricing
                </a>
            </li>

            
            <li>
                <a href="<?php echo e(route('blog.index')); ?>" class="flex items-center gap-3 px-4 py-3 text-base font-medium text-[#1a1a1a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline">
                    <span>📝</span> Blog
                </a>
            </li>

            
            <li x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-3 text-base font-medium text-[#1a1a1a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl bg-transparent border-none cursor-pointer">
                    <span class="flex items-center gap-3">
                        <span>📚</span> Resources
                    </span>
                    <svg class="w-4 h-4 transition-transform duration-200" x-bind:class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-collapse class="ml-4 space-y-1">
                    <a href="<?php echo e(route('pages.faq')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#4a4a4a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline">
                        <span>❓</span> FAQ
                    </a>
                    <a href="<?php echo e(route('pages.documentation')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#4a4a4a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline">
                        <span>📖</span> Documentation
                    </a>
                    <a href="<?php echo e(route('pages.case-studies')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#4a4a4a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline">
                        <span>📊</span> Case Studies
                    </a>
                    <a href="<?php echo e(route('pages.downloads')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#4a4a4a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline">
                        <span>📥</span> Downloads
                    </a>
                </div>
            </li>

            
            <li>
                <a href="<?php echo e(route('pages.careers')); ?>" class="flex items-center gap-3 px-4 py-3 text-base font-medium text-[#1a1a1a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline">
                    <span>💼</span> Careers
                </a>
            </li>

            
            <li x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-3 text-base font-medium text-[#1a1a1a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl bg-transparent border-none cursor-pointer">
                    <span class="flex items-center gap-3">
                        <span>📧</span> Contact
                    </span>
                    <svg class="w-4 h-4 transition-transform duration-200" x-bind:class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-collapse class="ml-4 space-y-1">
                    <a href="<?php echo e(route('contact.index')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#4a4a4a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline">
                        <span>📧</span> Contact Us
                    </a>
                    <a href="<?php echo e(route('contact.quote')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#4a4a4a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline">
                        <span>📄</span> Get a Quote
                    </a>
                    <a href="<?php echo e(route('contact.book-meeting')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#4a4a4a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline">
                        <span>📅</span> Book a Meeting
                    </a>
                </div>
            </li>

            
            <li class="my-2 border-t border-gray-100"></li>


            
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                <li>
                    <div class="px-4 py-3 bg-[#6c5ce7]/5 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#6c5ce7] to-[#a78bfa] flex items-center justify-center text-white font-bold">
                                <?php echo e(strtoupper(substr(Auth::user()->name ?? 'U', 0, 2))); ?>

                            </div>
                            <div>
                                <p class="text-sm font-medium text-[#0a0a0a]"><?php echo e(Auth::user()->name ?? 'User'); ?></p>
                                <p class="text-xs text-[#4a4a4a]"><?php echo e(Auth::user()->email ?? ''); ?></p>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <a href="<?php echo e(route('profile.edit')); ?>" class="flex items-center gap-3 px-4 py-3 text-base font-medium text-[#1a1a1a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline">
                        <span>👤</span> Profile
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-3 px-4 py-3 text-base font-medium text-[#1a1a1a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline">
                        <span>📊</span> Dashboard
                    </a>
                </li>
                <li>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-base font-medium text-red-600 hover:bg-red-50 transition-colors rounded-xl bg-transparent border-none cursor-pointer">
                            <span>🚪</span> Logout
                        </button>
                    </form>
                </li>
            <?php else: ?>
                <li>
                    <a href="<?php echo e(route('login')); ?>" class="flex items-center gap-3 px-4 py-3 text-base font-medium text-[#1a1a1a] hover:text-[#6c5ce7] hover:bg-[#6c5ce7]/5 transition-colors rounded-xl no-underline">
                        <span>🔑</span> Login
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('register')); ?>" class="flex items-center justify-center gap-3 px-4 py-3 text-base font-medium text-white bg-[#6c5ce7] hover:bg-[#5a4bd1] transition-colors rounded-xl no-underline shadow-lg shadow-[#6c5ce7]/30">
                        <span>✍️</span> Register
                    </a>
                </li>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </ul>
    </nav>

    
    <div class="pt-4 mt-4 border-t border-gray-100">
        <div class="flex justify-center gap-4 text-2xl">
            <a href="#" class="hover:text-[#6c5ce7] transition-colors" aria-label="Twitter">🐦</a>
            <a href="#" class="hover:text-[#6c5ce7] transition-colors" aria-label="LinkedIn">🔗</a>
            <a href="#" class="hover:text-[#6c5ce7] transition-colors" aria-label="Dribbble">🏀</a>
            <a href="#" class="hover:text-[#6c5ce7] transition-colors" aria-label="GitHub">🐙</a>
        </div>
        <p class="text-xs text-center text-[#4a4a4a] mt-3">&copy; <?php echo e(date('Y')); ?> Agency. All rights reserved.</p>
    </div>
</div>


<div class="fixed inset-0 bg-black/50 z-[1000] opacity-0 pointer-events-none transition-opacity duration-300 mobile-overlay"></div>


<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.querySelector('.mobile-menu-toggle');
    const menu = document.querySelector('.mobile-menu');
    const overlay = document.querySelector('.mobile-overlay');
    const close = document.querySelector('.mobile-menu-close');

    function openMenu() {
        menu.style.right = '0';
        overlay.style.opacity = '1';
        overlay.style.pointerEvents = 'auto';
        document.body.style.overflow = 'hidden';
        toggle?.classList.add('active');
    }

    function closeMenu() {
        menu.style.right = '-100%';
        overlay.style.opacity = '0';
        overlay.style.pointerEvents = 'none';
        document.body.style.overflow = '';
        toggle?.classList.remove('active');
    }

    toggle?.addEventListener('click', openMenu);
    close?.addEventListener('click', closeMenu);
    overlay?.addEventListener('click', closeMenu);
    
    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeMenu();
    });
});
</script>
<?php $__env->stopPush(); ?><?php /**PATH E:\Saas website\webcraft\resources\views/components/layout/mobile-menu.blade.php ENDPATH**/ ?>