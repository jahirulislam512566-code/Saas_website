<!-- admin/partials/footer.blade.php -->
<footer class="border-t border-gray-200 dark:border-gray-800 bg-white/50 dark:bg-gray-900/50 backdrop-blur-sm mt-auto">
    <div class="px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-600 dark:text-gray-400">
        <span>
            &copy; {{ date('Y') }} {{ config('app.name', 'SaaS Admin') }}. All rights reserved.
        </span>
        <div class="flex items-center gap-6">
            <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                <i class="fas fa-shield-alt mr-1.5"></i> Privacy
            </a>
            <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                <i class="fas fa-file-contract mr-1.5"></i> Terms
            </a>
            <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                <i class="fas fa-headset mr-1.5"></i> Support
            </a>
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                <span class="text-xs">System Online</span>
            </span>
        </div>
    </div>
</footer>