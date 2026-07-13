{{-- resources/views/admin/partials/footer.blade.php --}}
<footer class="bg-white border-t border-gray-200 flex-shrink-0">
    <div class="px-4 sm:px-6 py-3 flex flex-col sm:flex-row items-center justify-between text-sm text-gray-500">
        <div class="flex items-center space-x-2">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <span class="hidden sm:inline text-gray-300">|</span>
            <span class="text-xs text-gray-400">v{{ config('app.version', '1.0.0') }}</span>
        </div>
        
        <div class="flex items-center space-x-4 mt-2 sm:mt-0">
            <a href="#" class="hover:text-gray-700 transition-colors text-xs">
                <i class="fas fa-shield-alt mr-1"></i> Privacy
            </a>
            <a href="#" class="hover:text-gray-700 transition-colors text-xs">
                <i class="fas fa-file-contract mr-1"></i> Terms
            </a>
            <a href="#" class="hover:text-gray-700 transition-colors text-xs">
                <i class="fas fa-life-ring mr-1"></i> Support
            </a>
            <a href="#" class="hover:text-gray-700 transition-colors text-xs">
                <i class="fas fa-code mr-1"></i> Docs
            </a>
            <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                    class="text-gray-400 hover:text-gray-600 transition-colors" 
                    title="Back to top">
                <i class="fas fa-arrow-up text-xs"></i>
            </button>
        </div>
    </div>
</footer>