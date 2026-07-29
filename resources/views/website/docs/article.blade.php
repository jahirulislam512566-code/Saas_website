{{-- resources/views/website/docs/article.blade.php --}}
@extends('layouts.website')

@section('title', $article['title'] . ' - Documentation')
@section('meta_description', $article['description'] ?? 'Read this documentation article for detailed guidance.')

@section('content')
    {{-- Article Header --}}
    <section class="bg-gray-50 border-b border-gray-200 py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl">
                <nav class="flex items-center text-sm text-gray-500 mb-4">
                    <a href="{{ route('docs.index') }}" class="hover:text-gray-700">Documentation</a>
                    <svg class="mx-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <a href="{{ route('docs.category', $article['category_slug'] ?? '') }}" class="hover:text-gray-700">
                        {{ $article['category'] ?? 'Category' }}
                    </a>
                    <svg class="mx-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-gray-700 font-medium">{{ $article['title'] }}</span>
                </nav>
                
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">{{ $article['title'] }}</h1>
                        @if(isset($article['description']))
                            <p class="mt-2 text-xl text-gray-600">{{ $article['description'] }}</p>
                        @endif
                        <div class="mt-4 flex flex-wrap items-center gap-4 text-sm">
                            <span class="inline-flex items-center text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                Updated {{ $article['updated_at'] ?? 'Recently' }}
                            </span>
                            <span class="inline-flex items-center text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                </svg>
                                {{ $article['read_time'] ?? '5 min read' }}
                            </span>
                            @if(isset($article['difficulty']))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($article['difficulty'] === 'Beginner') bg-green-100 text-green-800
                                    @elseif($article['difficulty'] === 'Intermediate') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $article['difficulty'] }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Print/Share --}}
                    <div class="flex items-center gap-2">
                        <button onclick="window.print()" class="p-2 text-gray-400 hover:text-gray-600 transition-colors" title="Print">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                        </button>
                        <button onclick="shareArticle()" class="p-2 text-gray-400 hover:text-gray-600 transition-colors" title="Share">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Article Content --}}
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                {{-- Table of Contents --}}
                <aside class="lg:col-span-1 order-last lg:order-first">
                    <div class="sticky top-24">
                        <x-website.docs-toc :items="$article['toc'] ?? []" />
                    </div>
                </aside>

                {{-- Article Body --}}
                <main class="lg:col-span-2">
                    <div class="prose prose-gray max-w-none">
                        {!! $article['content'] ?? 'Content coming soon...' !!}
                    </div>

                    {{-- Feedback --}}
                    <div class="mt-12 pt-8 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Was this article helpful?</p>
                                <div class="mt-2 flex gap-2">
                                    <button onclick="feedback('yes')" class="px-4 py-2 bg-green-50 text-green-600 hover:bg-green-100 rounded-lg text-sm font-medium transition-colors">
                                        Yes
                                    </button>
                                    <button onclick="feedback('no')" class="px-4 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-medium transition-colors">
                                        No
                                    </button>
                                </div>
                            </div>
                            {{-- <a href="{{ route('docs.edit', $article['slug'] ?? '') }}" class="text-sm text-gray-400 hover:text-gray-600">
                                Edit this page on GitHub →
                            </a> --}}
                        </div>
                    </div>
                </main>

                {{-- Right Sidebar (Related) --}}
                <aside class="lg:col-span-1">
                    <div class="sticky top-24 space-y-6">
                        {{-- On this page --}}
                        <x-website.docs-on-this-page :items="$article['toc'] ?? []" />

                        {{-- Related Articles --}}
                        @if(isset($article['related_articles']) && count($article['related_articles']) > 0)
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">Related Articles</h4>
                                <ul class="mt-2 space-y-2">
                                    @foreach($article['related_articles'] as $related)
                                        <li>
                                            <a href="{{ route('docs.article', $related['slug']) }}" class="text-sm text-gray-600 hover:text-primary-600 transition-colors">
                                                {{ $related['title'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </section>

    {{-- Navigation --}}
    <section class="py-8 bg-gray-50 border-t border-gray-200">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto flex flex-wrap items-center justify-between gap-4">
                @if(isset($article['previous']))
                    <a href="{{ route('docs.article', $article['previous']['slug']) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-primary-600 transition-colors group">
                        <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        {{ $article['previous']['title'] }}
                    </a>
                @else
                    <div></div>
                @endif
                
                <a href="{{ route('docs.index') }}" class="text-sm text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </a>
                
                @if(isset($article['next']))
                    <a href="{{ route('docs.article', $article['next']['slug']) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-primary-600 transition-colors group">
                        {{ $article['next']['title'] }}
                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @else
                    <div></div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    function shareArticle() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $article['title'] }}',
                text: 'Check out this documentation article: {{ $article['title'] }}',
                url: window.location.href
            });
        } else {
            // Fallback
            navigator.clipboard.writeText(window.location.href);
            alert('Link copied to clipboard!');
        }
    }

    function feedback(type) {
        // Send feedback to your analytics
        fetch('{{ route('docs.feedback') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                article: '{{ $article['slug'] ?? '' }}',
                helpful: type === 'yes'
            })
        });
        
        // Show thank you message
        const button = document.querySelector(`button[onclick="feedback('${type}')"]`);
        button.textContent = 'Thank you!';
        button.classList.remove('bg-green-50', 'bg-red-50', 'hover:bg-green-100', 'hover:bg-red-100');
        button.classList.add('bg-gray-100', 'text-gray-500');
        button.disabled = true;
    }
</script>
@endpush