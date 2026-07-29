<!-- admin/partials/quick-actions.blade.php -->
<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" 
            class="p-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition-colors shadow-sm hover:shadow-md">
        <i class="fas fa-plus text-sm"></i>
    </button>
    
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-900 rounded-xl shadow-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        <div class="py-1">
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                <i class="fas fa-user-plus w-5 text-center text-gray-400"></i>
                New User
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                <i class="fas fa-file-alt w-5 text-center text-gray-400"></i>
                New Report
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                <i class="fas fa-envelope w-5 text-center text-gray-400"></i>
                Send Email
            </a>
            <div class="border-t border-gray-200 dark:border-gray-800 my-1"></div>
            <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                <i class="fas fa-cog w-5 text-center text-gray-400"></i>
                Settings
            </a>
        </div>
    </div>
</div><?php /**PATH E:\Saas website\webcraft\resources\views/admin/partials/quick-actions.blade.php ENDPATH**/ ?>