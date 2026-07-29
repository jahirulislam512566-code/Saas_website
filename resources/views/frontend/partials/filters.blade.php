{{-- resources/views/frontend/partials/filters.blade.php --}}
@props([
    'filters' => [],
    'active' => [],
    'url' => '#',
])

@php
    $defaultFilters = [
        'categories' => ['All', 'Design', 'Development', 'Marketing', 'Strategy'],
        'sort' => ['Newest', 'Oldest', 'Popular', 'Trending'],
        'tags' => [],
        'price_range' => ['min' => 0, 'max' => 1000],
    ];
    $filters = array_merge($defaultFilters, $filters);
@endphp

<div class="bg-white rounded-2xl p-6 shadow-md" x-data="filterManager()">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-4">
            {{-- Categories --}}
            @if(!empty($filters['categories']))
                <div class="flex flex-wrap gap-2">
                    @foreach($filters['categories'] as $category)
                        <button @click="setFilter('category', '{{ $category }}')"
                                :class="{ 'bg-[#6c5ce7] text-white': activeCategory === '{{ $category }}', 'bg-gray-100 text-[#4a4a4a]': activeCategory !== '{{ $category }}' }"
                                class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 hover:bg-[#6c5ce7] hover:text-white">
                            {{ $category }}
                        </button>
                    @endforeach
                </div>
            @endif

            {{-- Sort --}}
            @if(!empty($filters['sort']))
                <div class="relative">
                    <select @change="setFilter('sort', $event.target.value)" 
                            class="px-4 py-2 rounded-xl border-2 border-gray-200 focus:border-[#6c5ce7] focus:outline-none text-sm appearance-none bg-white pr-8">
                        @foreach($filters['sort'] as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#4a4a4a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            @endif
        </div>

        {{-- Active Filters --}}
        <div class="flex flex-wrap items-center gap-2">
            <span x-show="hasActiveFilters()" class="text-sm text-[#4a4a4a]">Active filters:</span>
            <template x-for="(value, key) in activeFilters" :key="key">
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-[#6c5ce7]/10 text-[#6c5ce7] text-sm rounded-full">
                    <span x-text="key + ': ' + value"></span>
                    <button @click="removeFilter(key)" class="hover:text-[#5a4bd1]">✕</button>
                </span>
            </template>
            <button @click="clearAllFilters()" 
                    x-show="hasActiveFilters()"
                    class="text-sm text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors">
                Clear all
            </button>
        </div>
    </div>

    <script>
        function filterManager() {
            return {
                activeCategory: 'All',
                activeSort: 'Newest',
                activeFilters: {},
                setFilter(type, value) {
                    if (type === 'category') {
                        this.activeCategory = value;
                    } else if (type === 'sort') {
                        this.activeSort = value;
                    }
                    this.activeFilters[type] = value;
                    // Trigger filter change event
                    this.$dispatch('filter-change', { type, value, filters: this.activeFilters });
                },
                removeFilter(key) {
                    delete this.activeFilters[key];
                    if (key === 'category') this.activeCategory = 'All';
                    if (key === 'sort') this.activeSort = 'Newest';
                    this.$dispatch('filter-change', { filters: this.activeFilters });
                },
                hasActiveFilters() {
                    return Object.keys(this.activeFilters).length > 0;
                },
                clearAllFilters() {
                    this.activeFilters = {};
                    this.activeCategory = 'All';
                    this.activeSort = 'Newest';
                    this.$dispatch('filter-change', { filters: {} });
                }
            }
        }
    </script>
</div>