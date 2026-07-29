{{-- resources/views/errors/401.blade.php --}}
@extends('errors.layout')

@section('title', 'Unauthorized - AgencyPro')

@section('content')
    <div class="text-center max-w-lg mx-auto">
        <div class="text-8xl mb-6 text-[#6c5ce7]">🔒</div>
        <h1 class="text-4xl font-bold text-[#0a0a0a] mb-4">401</h1>
        <h2 class="text-2xl font-semibold text-[#0a0a0a] mb-4">Unauthorized Access</h2>
        <p class="text-lg text-[#4a4a4a] mb-8">
            {{ $exception->getMessage() ?: 'You are not authorized to access this page. Please log in with the correct credentials.' }}
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-[#6c5ce7] text-white font-medium rounded-xl hover:bg-[#5a4bd1] transition-all duration-300 shadow-lg shadow-[#6c5ce7]/30">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Sign In
            </a>
            <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-white border-2 border-gray-200 text-[#0a0a0a] font-medium rounded-xl hover:border-[#6c5ce7] hover:text-[#6c5ce7] transition-all duration-300">
                <i class="fas fa-home mr-2"></i>
                Go Home
            </a>
        </div>
        <div class="mt-8">
            <a href="{{ route('contact.index') }}" class="text-sm text-[#6c5ce7] hover:underline">
                Need help? Contact Support
            </a>
        </div>
    </div>
@endsection