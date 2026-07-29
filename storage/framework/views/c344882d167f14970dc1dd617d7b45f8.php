
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'placeholder' => 'Search for services, articles, or projects...',
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
    'placeholder' => 'Search for services, articles, or projects...',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="search-overlay fixed inset-0 bg-black/60 z-[2000] opacity-0 pointer-events-none transition-opacity duration-300" 
     role="dialog" 
     aria-modal="true"
     x-data="{ open: false }"
     x-init="
        window.addEventListener('open-search', () => { open = true; document.body.style.overflow = 'hidden'; });
        window.addEventListener('close-search', () => { open = false; document.body.style.overflow = ''; });
        $watch('open', value => {
            if (value) {
                $el.style.opacity = '1';
                $el.style.pointerEvents = 'auto';
                document.getElementById('search-container').style.transform = 'scale(1)';
                setTimeout(() => document.getElementById('search-input')?.focus(), 100);
            } else {
                $el.style.opacity = '0';
                $el.style.pointerEvents = 'none';
                document.getElementById('search-container').style.transform = 'scale(0.95)';
                document.body.style.overflow = '';
            }
        });
     "
     x-show="open"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    
    <div class="flex items-center justify-center min-h-screen p-4">
        <div id="search-container" 
             class="bg-white rounded-2xl w-full max-w-2xl p-6 sm:p-8 shadow-2xl transform scale-95 transition-transform duration-300"
             @click.away="open = false; document.body.style.overflow = '';">
            
            
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-[#0a0a0a]">Search</h3>
                <button class="text-3xl text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors bg-transparent border-none cursor-pointer"
                        @click="open = false; document.body.style.overflow = '';"
                        aria-label="Close search">
                    ✕
                </button>
            </div>

            
            <form action="<?php echo e(route('blog.search')); ?>" method="GET" class="flex flex-col sm:flex-row gap-3">
                <input 
                    id="search-input"
                    type="text" 
                    name="q" 
                    placeholder="<?php echo e($placeholder); ?>"
                    autofocus
                    class="flex-1 px-5 py-3.5 text-lg border-2 border-gray-200 rounded-xl focus:outline-none focus:border-[#6c5ce7] focus:ring-2 focus:ring-[#6c5ce7]/20 transition-all duration-200"
                    aria-label="Search"
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

            
            <div class="mt-6 pt-6 border-t border-gray-100">
                <p class="text-sm text-[#4a4a4a] mb-3">Popular searches:</p>
                <div class="flex flex-wrap gap-2">
                    <a href="<?php echo e(route('blog.search')); ?>?q=web+design" class="px-4 py-1.5 text-sm bg-gray-100 hover:bg-[#6c5ce7] hover:text-white rounded-full transition-all duration-200 no-underline text-[#4a4a4a]">
                        Web Design
                    </a>
                    <a href="<?php echo e(route('blog.search')); ?>?q=development" class="px-4 py-1.5 text-sm bg-gray-100 hover:bg-[#6c5ce7] hover:text-white rounded-full transition-all duration-200 no-underline text-[#4a4a4a]">
                        Development
                    </a>
                    <a href="<?php echo e(route('blog.search')); ?>?q=strategy" class="px-4 py-1.5 text-sm bg-gray-100 hover:bg-[#6c5ce7] hover:text-white rounded-full transition-all duration-200 no-underline text-[#4a4a4a]">
                        Strategy
                    </a>
                    <a href="<?php echo e(route('blog.search')); ?>?q=branding" class="px-4 py-1.5 text-sm bg-gray-100 hover:bg-[#6c5ce7] hover:text-white rounded-full transition-all duration-200 no-underline text-[#4a4a4a]">
                        Branding
                    </a>
                    <a href="<?php echo e(route('blog.search')); ?>?q=ecommerce" class="px-4 py-1.5 text-sm bg-gray-100 hover:bg-[#6c5ce7] hover:text-white rounded-full transition-all duration-200 no-underline text-[#4a4a4a]">
                        E-Commerce
                    </a>
                    <a href="<?php echo e(route('blog.search')); ?>?q=ui+ux" class="px-4 py-1.5 text-sm bg-gray-100 hover:bg-[#6c5ce7] hover:text-white rounded-full transition-all duration-200 no-underline text-[#4a4a4a]">
                        UI/UX
                    </a>
                </div>
            </div>

            
            <div class="mt-4 text-xs text-[#4a4a4a] text-center">
                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 rounded">
                    <kbd class="px-1.5 py-0.5 bg-white rounded border border-gray-200 text-xs font-mono">Ctrl</kbd>
                    <span>+</span>
                    <kbd class="px-1.5 py-0.5 bg-white rounded border border-gray-200 text-xs font-mono">K</kbd>
                </span>
                <span class="ml-2">to open search</span>
                <span class="mx-2">•</span>
                <span class="inline-flex items-center gap-1">
                    <kbd class="px-1.5 py-0.5 bg-white rounded border border-gray-200 text-xs font-mono">Esc</kbd>
                    <span>to close</span>
                </span>
            </div>
        </div>
    </div>
</div>


<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search toggle button
        const searchToggle = document.querySelector('.search-toggle');
        
        // Open search
        function openSearch() {
            window.dispatchEvent(new CustomEvent('open-search'));
        }
        
        // Close search
        function closeSearch() {
            window.dispatchEvent(new CustomEvent('close-search'));
        }

        // Toggle button click
        searchToggle?.addEventListener('click', openSearch);

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+K or Cmd+K to open
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                openSearch();
            }
            // Escape to close
            if (e.key === 'Escape') {
                closeSearch();
            }
        });
    });
</script>
<?php $__env->stopPush(); ?><?php /**PATH E:\Saas website\webcraft\resources\views/components/layout/search.blade.php ENDPATH**/ ?>