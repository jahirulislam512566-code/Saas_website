{{-- resources/views/frontend/search/results.blade.php --}}
@extends('layouts.app')

@section('title', 'Search Results - AgencyPro')
@section('description', 'Search results for "' . ($query ?? '') . '" on AgencyPro')

@section('content')
    <x-layout.page-header 
        title="Search Results"
        subtitle="Search"
        :breadcrumb="[['label' => 'Search']]"
    />

    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                {{-- Search Form --}}
                <div class="mb-8">
                    <form action="{{ route('search') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                        <input 
                            type="text" 
                            name="q" 
                            value="{{ $query }}"
                            placeholder="Search for services, articles, projects..."
                            class="flex-1 px-5 py-3.5 text-lg border-2 border-gray-200 rounded-xl focus:outline-none focus:border-[#6c5ce7] focus:ring-2 focus:ring-[#6c5ce7]/20 transition-all duration-200"
                            autofocus
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
                </div>

                {{-- Results Info --}}
                @if(!empty($query))
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                        <h2 class="text-xl font-bold text-[#0a0a0a]">
                            @if($totalResults > 0)
                                {{ $totalResults }} results for "{{ $query }}"
                            @else
                                No results found for "{{ $query }}"
                            @endif
                        </h2>
                        @if($searchTime)
                            <span class="text-sm text-[#4a4a4a]">Found in {{ $searchTime }}ms</span>
                        @endif
                    </div>
                @endif

                {{-- Results --}}
                @if($results->count() > 0)
                    <div class="space-y-6">
                        @foreach($results as $result)
                            <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 hover:border-[#6c5ce7]/20">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-12 h-12 bg-[#6c5ce7]/10 rounded-xl flex items-center justify-center text-2xl">
                                        <i class="fas {{ $result->icon ?? 'fa-file' }} text-[#6c5ce7]"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <h3 class="text-xl font-bold text-[#0a0a0a] hover:text-[#6c5ce7] transition-colors">
                                                <a href="{{ $result->url ?? '#' }}">{{ $result->title ?? $result->name ?? 'Untitled' }}</a>
                                            </h3>
                                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-[#6c5ce7]/10 text-[#6c5ce7]">
                                                {{ $result->type_label ?? ucfirst($result->type ?? '') }}
                                            </span>
                                        </div>
                                        
                                        @if(isset($result->description) || isset($result->excerpt))
                                            <p class="text-[#4a4a4a] mt-1">
                                                {{ Str::limit($result->description ?? $result->excerpt ?? '', 200) }}
                                            </p>
                                        @endif

                                        <div class="flex flex-wrap items-center gap-3 mt-3 text-sm text-[#4a4a4a]">
                                            @if(isset($result->client))
                                                <span class="flex items-center gap-1">
                                                    <i class="fas fa-user text-xs"></i>
                                                    {{ $result->client }}
                                                </span>
                                            @endif
                                            @if(isset($result->category))
                                                <span class="flex items-center gap-1">
                                                    <i class="fas fa-folder text-xs"></i>
                                                    {{ is_object($result->category) ? $result->category->name : $result->category }}
                                                </span>
                                            @endif
                                            @if(isset($result->published_at) || isset($result->created_at))
                                                <span class="flex items-center gap-1">
                                                    <i class="fas fa-calendar text-xs"></i>
                                                    {{ ($result->published_at ?? $result->created_at)->format('M d, Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-12">
                        {{ $results->appends(['q' => $query])->links() }}
                    </div>
                @elseif(!empty($query))
                    {{-- No Results --}}
                    <div class="text-center py-16">
                        <div class="text-6xl mb-4">🔍</div>
                        <h3 class="text-2xl font-bold text-[#0a0a0a]">No results found</h3>
                        <p class="text-[#4a4a4a] mt-2 max-w-md mx-auto">
                            We couldn't find any results for "{{ $query }}". Try adjusting your search terms or browse our categories.
                        </p>
                        <div class="flex flex-wrap justify-center gap-4 mt-6">
                            <a href="{{ route('services.index') }}" class="inline-flex items-center gap-2 text-[#6c5ce7] font-medium hover:gap-3 transition-all">
                                Browse Services
                            </a>
                            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-[#6c5ce7] font-medium hover:gap-3 transition-all">
                                Read Blog
                            </a>
                            <a href="{{ route('portfolio.index') }}" class="inline-flex items-center gap-2 text-[#6c5ce7] font-medium hover:gap-3 transition-all">
                                View Portfolio
                            </a>
                        </div>
                    </div>
                @else
                    {{-- Empty Search --}}
                    <div class="text-center py-16">
                        <div class="text-6xl mb-4">🔎</div>
                        <h3 class="text-2xl font-bold text-[#0a0a0a]">Start Searching</h3>
                        <p class="text-[#4a4a4a] mt-2">Enter a search term above to find what you're looking for.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection