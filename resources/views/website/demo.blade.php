@extends('layouts.website')

@section('title', 'Live Demo - Buildora')
@section('description', 'See the website builder in action')

@section('content')
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Live Demo</h1>
            <p class="text-xl text-gray-600">See how easy it is to build a website with our platform</p>
        </div>

        <div class="max-w-4xl mx-auto">
            {{-- Demo Video or Interactive Content --}}
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="bg-gray-900 p-4 flex items-center gap-2">
                    <div class="flex items-center gap-1.5">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    </div>
                    <div class="flex-1 text-center text-sm text-gray-400">Demo Preview</div>
                </div>
                
                <div class="aspect-video bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center p-8">
                    <div class="text-center text-white">
                        <svg class="w-24 h-24 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-2xl font-bold mb-2">Interactive Demo</h3>
                        <p class="text-white/70">Click the play button to see the builder in action</p>
                        <button class="mt-6 px-8 py-3 bg-white text-indigo-600 font-bold rounded-xl hover:bg-gray-100 transition-colors">
                            Start Demo
                        </button>
                    </div>
                </div>
            </div>

            {{-- Features Highlight --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                <div class="bg-white rounded-xl p-6 shadow-lg text-center">
                    <div class="text-3xl mb-3">🎨</div>
                    <h3 class="font-bold text-gray-900 mb-1">Drag & Drop</h3>
                    <p class="text-sm text-gray-500">Build pages visually</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-lg text-center">
                    <div class="text-3xl mb-3">📱</div>
                    <h3 class="font-bold text-gray-900 mb-1">Responsive</h3>
                    <p class="text-sm text-gray-500">Works on all devices</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-lg text-center">
                    <div class="text-3xl mb-3">⚡</div>
                    <h3 class="font-bold text-gray-900 mb-1">Fast Loading</h3>
                    <p class="text-sm text-gray-500">Optimized performance</p>
                </div>
            </div>

            {{-- CTA --}}
            <div class="text-center mt-12">
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg">
                    Start Free Trial
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection