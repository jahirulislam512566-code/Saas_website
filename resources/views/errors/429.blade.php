{{-- resources/views/errors/429.blade.php --}}
@extends('errors.layout')

@section('title', 'Too Many Requests - AgencyPro')

@section('content')
    <div class="text-center max-w-lg mx-auto">
        <div class="text-8xl mb-6 text-amber-500">🚦</div>
        <h1 class="text-4xl font-bold text-[#0a0a0a] mb-4">429</h1>
        <h2 class="text-2xl font-semibold text-[#0a0a0a] mb-4">Too Many Requests</h2>
        <p class="text-lg text-[#4a4a4a] mb-8">
            {{ $exception->getMessage() ?: 'You have made too many requests. Please wait a moment and try again.' }}
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <button onclick="location.reload()" class="inline-flex items-center px-6 py-3 bg-[#6c5ce7] text-white font-medium rounded-xl hover:bg-[#5a4bd1] transition-all duration-300 shadow-lg shadow-[#6c5ce7]/30">
                <i class="fas fa-sync-alt mr-2"></i>
                Try Again
            </button>
            <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-white border-2 border-gray-200 text-[#0a0a0a] font-medium rounded-xl hover:border-[#6c5ce7] hover:text-[#6c5ce7] transition-all duration-300">
                <i class="fas fa-home mr-2"></i>
                Go Home
            </a>
        </div>
        <div class="mt-8 text-sm text-[#4a4a4a]">
            <p>Please wait a few minutes before trying again.</p>
            <p class="mt-1">If you need immediate assistance, <a href="{{ route('contact.index') }}" class="text-[#6c5ce7] hover:underline">contact support</a>.</p>
        </div>
    </div>
@endsection