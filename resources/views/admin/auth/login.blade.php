@extends('admin.layouts.guest')

@section('title', 'Admin Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900">Admin Login</h1>
            <p class="mt-2 text-gray-600">Sign in to access the admin panel</p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('admin.login.submit') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" id="email" name="email" 
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                           value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" 
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                           required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-600">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
                <a href="#" class="text-sm text-primary-600 hover:text-primary-700">Forgot password?</a>
            </div>

            <button type="submit" 
                    class="w-full py-3 px-4 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-2xl transition-all active:scale-95">
                Sign In
            </button>
        </form>
    </div>
</div>
@endsection