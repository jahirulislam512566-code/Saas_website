<!-- admin/partials/filters.blade.php -->
<div class="flex flex-wrap items-center gap-3 p-4 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
    <!-- Status Filter -->
    <div class="flex items-center gap-2">
        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">
            <i class="fas fa-filter mr-1.5 text-gray-400"></i>Status:
        </label>
        <select class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
            <option>All</option>
            <option>Active</option>
            <option>Inactive</option>
            <option>Pending</option>
        </select>
    </div>
    
    <!-- Date Range -->
    <div class="flex items-center gap-2">
        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">
            <i class="fas fa-calendar mr-1.5 text-gray-400"></i>Date:
        </label>
        <input type="date" class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" />
        <span class="text-gray-500 dark:text-gray-400 text-sm">to</span>
        <input type="date" class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" />
    </div>
    
    <!-- Search Filter -->
    <div class="flex items-center gap-2 flex-1 min-w-[150px]">
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" 
                   placeholder="Search..." 
                   class="w-full pl-9 pr-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" />
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex items-center gap-2 ml-auto">
        <button class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all hover:shadow-md">
            <i class="fas fa-check mr-1.5"></i>Apply
        </button>
        <button class="px-4 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
            <i class="fas fa-undo mr-1.5"></i>Clear
        </button>
        <button class="px-4 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
            <i class="fas fa-sliders-h mr-1.5"></i>More
        </button>
    </div>
</div>