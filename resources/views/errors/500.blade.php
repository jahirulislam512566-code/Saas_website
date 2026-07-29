{{-- resources/views/errors/500.blade.php --}}
@extends('errors.layout')

@section('title', 'Server Error - AgencyPro')

@section('content')
    <div class="text-center max-w-lg mx-auto">
        <div class="text-8xl mb-6 text-red-500">💥</div>
        <h1 class="text-4xl font-bold text-[#0a0a0a] mb-4">500</h1>
        <h2 class="text-2xl font-semibold text-[#0a0a0a] mb-4">Server Error</h2>
        <p class="text-lg text-[#4a4a4a] mb-8">
            {{ $exception->getMessage() ?: 'Something went wrong on our end. Our team has been notified and is working to fix the issue.' }}
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
            <p>If the problem persists, please <a href="{{ route('contact.index') }}" class="text-[#6c5ce7] hover:underline">contact us</a>.</p>
            <p class="mt-1">Error reference: #{{ Str::random(8) }}</p>
        </div>
    </div>
@endsection