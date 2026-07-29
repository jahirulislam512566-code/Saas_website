{{-- resources/views/auth/forgot-password.blade.php --}}
@extends('layouts.auth')

@section('title', 'Reset Password - AgencyPro')

@section('content')
    <div class="text-center mb-8">
        <div class="text-4xl mb-3">🔐</div>
        <h2 class="text-2xl font-bold text-[#0a0a0a]">Forgot Password</h2>
        <p class="text-sm text-[#4a4a4a] mt-1">
            No problem. Just let us know your email address and we'll email you a password reset link.
        </p>
    </div>

    @if(session('status'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl p-4">
            <p class="text-sm">{{ session('status') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        {{-- Email Address --}}
        <div>
            <label for="email" class="block text-sm font-medium text-[#0a0a0a] mb-1.5">
                <i class="fas fa-envelope mr-2 text-[#6c5ce7]"></i> Email Address
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#6c5ce7] focus:ring-2 focus:ring-[#6c5ce7]/20 transition-all outline-none">
            @error('email')
                <p class="mt-1.5 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full px-6 py-3.5 bg-[#6c5ce7] text-white font-medium rounded-xl hover:bg-[#5a4bd1] transition-all duration-300 shadow-lg shadow-[#6c5ce7]/30 flex items-center justify-center gap-2">
            <i class="fas fa-paper-plane"></i>
            Send Reset Link
        </button>
    </form>

    <div class="mt-6 pt-6 border-t border-gray-100 text-center">
        <p class="text-sm text-[#4a4a4a]">
            Remember your password?
            <a href="{{ route('login') }}" class="font-medium text-[#6c5ce7] hover:underline no-underline">
                Back to Sign in
            </a>
        </p>
    </div>
@endsection