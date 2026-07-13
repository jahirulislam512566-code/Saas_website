{{-- resources/views/admin/partials/search.blade.php --}}
<div x-data="searchComponent()" 
     x-init="init()"
     class="relative"
     @keydown.escape="closeSearch()">
    
    <!-- Search Input -->
    <div class="relative">
        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input type="text" 
               x-model="query"
               @input="performSearch()"
               @focus="openSearch()"
               placeholder="Search for users, posts, pages..."
               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-gray-50 focus:bg-white transition-all">
        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded border border-gray-200">
            ⌘K
        </span>
    </div>
    
    <!-- Search Results -->
    <div x-show="isOpen && results.length > 0" 
         @click.away="closeSearch()"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="absolute top-full left-0 right-0 mt-2 bg-white rounded-lg shadow-xl border border-gray-200 z-50 max-h-96 overflow-y-auto">
        
        <!-- Results Header -->
        <div class="px-4 py-2 border-b border-gray-100 flex items-center justify-between">
            <span class="text-xs font-medium text-gray-500">Search Results</span>
            <span class="text-xs text-gray-400" x-text="results.length + ' found'"></span>
        </div>
        
        <!-- Result Items -->
        <div class="divide-y divide-gray-100">
            <template x-for="result in results" :key="result.id">
                <a :href="result.url" 
                   class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors group">
                    <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center flex-shrink-0">
                        <i :class="result.icon"></i>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 group-hover:text-primary-600 transition-colors" x-text="result.title"></p>
                        <p class="text-xs text-gray-500 truncate" x-text="result.description"></p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-300 group-hover:text-primary-600 transition-colors"></i>
                </a>
            </template>
        </div>
        
        <!-- View All -->
        <div class="px-4 py-2 border-t border-gray-100 bg-gray-50 rounded-b-lg">
            <a :href="'/admin/search?q=' + query" 
               class="text-xs text-primary-600 hover:text-primary-700 font-medium">
                View all results →
            </a>
        </div>
    </div>
    
    <!-- Loading State -->
    <div x-show="isLoading" 
         class="absolute top-full left-0 right-0 mt-2 bg-white rounded-lg shadow-xl border border-gray-200 z-50 p-4">
        <div class="flex items-center justify-center space-x-3">
            <div class="animate-spin rounded-full h-5 w-5 border-2 border-primary-600 border-t-transparent"></div>
            <span class="text-sm text-gray-500">Searching...</span>
        </div>
    </div>
    
    <!-- No Results -->
    <div x-show="isOpen && query.length > 0 && results.length === 0 && !isLoading" 
         @click.away="closeSearch()"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="absolute top-full left-0 right-0 mt-2 bg-white rounded-lg shadow-xl border border-gray-200 z-50 p-6 text-center">
        <i class="fas fa-search text-gray-300 text-3xl mb-3 block"></i>
        <p class="text-gray-500 text-sm">No results found for "<span x-text="query" class="font-medium"></span>"</p>
        <p class="text-xs text-gray-400 mt-1">Try adjusting your search terms</p>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('searchComponent', () => ({
        query: '',
        results: [],
        isOpen: false,
        isLoading: false,
        searchTimeout: null,
        
        init() {
            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    this.openSearch();
                }
            });
        },
        
        openSearch() {
            this.isOpen = true;
        },
        
        closeSearch() {
            this.isOpen = false;
            this.results = [];
        },
        
        performSearch() {
            clearTimeout(this.searchTimeout);
            
            if (this.query.length < 2) {
                this.results = [];
                return;
            }
            
            this.isLoading = true;
            
            this.searchTimeout = setTimeout(() => {
                fetch(`/admin/api/search?q=${encodeURIComponent(this.query)}`)
                    .then(response => response.json())
                    .then(data => {
                        this.results = data.data || [];
                        this.isLoading = false;
                        this.isOpen = true;
                    })
                    .catch(() => {
                        this.isLoading = false;
                        this.results = [];
                    });
            }, 300);
        }
    }));
});
</script>