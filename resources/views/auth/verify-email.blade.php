{{-- resources/views/auth/verify-email.blade.php --}}
<layouts.auth>
    <x-slot:authHeader>
        Verify Your Email
    </x-slot:authHeader>
    
    <x-slot:authSubheader>
        Thanks for signing up! Before getting started, please verify your email address.
    </x-slot:authSubheader>

    <div class="space-y-6">
        {{-- Info Message --}}
        <div class="bg-blue-50 border border-blue-200 text-blue-700 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <span class="text-lg">📧</span>
                <div>
                    <p class="text-sm font-medium">Check your email</p>
                    <p class="text-sm text-blue-600/80 mt-0.5">
                        We've sent a verification link to your email address. Please click the link to verify your account.
                    </p>
                </div>
            </div>
        </div>

        {{-- Success Message --}}
        @if (session('status') == 'verification-link-sent')
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl p-4 animate-pulse">
                <div class="flex items-start gap-3">
                    <span class="text-lg">✅</span>
                    <div>
                        <p class="text-sm font-medium">Verification email sent!</p>
                        <p class="text-sm text-emerald-600/80 mt-0.5">
                            A new verification link has been sent to the email address you provided during registration.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Didn't receive email? --}}
        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
            <p class="text-sm text-[#4a4a4a]">
                Didn't receive the email? Check your spam folder or click the button below to request a new one.
            </p>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
            {{-- Resend Form --}}
            <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                @csrf
                <x-ui.button variant="primary" size="md" type="submit" full-width="true" class="sm:w-auto">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    {{ __('Resend Verification Email') }}
                </x-ui.button>
            </form>

            {{-- Logout Form --}}
            <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                @csrf
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors bg-transparent border border-gray-200 rounded-xl hover:border-[#6c5ce7] hover:bg-[#6c5ce7]/5">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>

        {{-- Help Text --}}
        <p class="text-xs text-[#4a4a4a] text-center mt-2">
            Having trouble? <a href="{{ route('contact.index') }}" class="text-[#6c5ce7] hover:underline font-medium">Contact support</a>
        </p>
    </div>

    <x-slot:authFooter>
        <p class="text-sm text-[#4a4a4a]">
            <a href="{{ route('home') }}" class="font-medium text-[#6c5ce7] hover:underline no-underline">
                ← Back to Home
            </a>
        </p>
    </x-slot:authFooter>
</layouts.auth>