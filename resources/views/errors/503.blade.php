{{-- resources/views/errors/503.blade.php --}}
@extends('errors.layout')

@section('title', 'Service Unavailable - AgencyPro')

@section('content')
    <div class="text-center max-w-lg mx-auto">
        <div class="text-8xl mb-6 text-amber-500">🔧</div>
        <h1 class="text-4xl font-bold text-[#0a0a0a] mb-4">503</h1>
        <h2 class="text-2xl font-semibold text-[#0a0a0a] mb-4">Service Unavailable</h2>
        <p class="text-lg text-[#4a4a4a] mb-8">
            {{ $exception->getMessage() ?: 'We are currently performing maintenance. We should be back online shortly.' }}
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <button onclick="location.reload()" class="inline-flex items-center px-6 py-3 bg-[#6c5ce7] text-white font-medium rounded-xl hover:bg-[#5a4bd1] transition-all duration-300 shadow-lg shadow-[#6c5ce7]/30">
                <i class="fas fa-sync-alt mr-2"></i>
                Check Again
            </button>
            <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-white border-2 border-gray-200 text-[#0a0a0a] font-medium rounded-xl hover:border-[#6c5ce7] hover:text-[#6c5ce7] transition-all duration-300">
                <i class="fas fa-home mr-2"></i>
                Go Home
            </a>
        </div>

        {{-- Countdown Timer --}}
        <div class="mt-8 p-4 bg-gray-50 rounded-xl">
            <p class="text-sm text-[#4a4a4a]">Estimated return time</p>
            <div class="flex justify-center gap-4 mt-2 text-2xl font-bold text-[#0a0a0a]" x-data="countdown()" x-init="start()">
                <div>
                    <span x-text="minutes" class="text-3xl text-[#6c5ce7]"></span>
                    <span class="text-sm text-[#4a4a4a] block">Minutes</span>
                </div>
                <div>
                    <span x-text="seconds" class="text-3xl text-[#6c5ce7]"></span>
                    <span class="text-sm text-[#4a4a4a] block">Seconds</span>
                </div>
            </div>
        </div>

        <div class="mt-8 text-sm text-[#4a4a4a]">
            <p>We apologize for the inconvenience. Our team is working hard to restore service.</p>
            <p class="mt-1">For updates, <a href="https://twitter.com/AgencyPro" target="_blank" class="text-[#6c5ce7] hover:underline">follow us on Twitter</a>.</p>
        </div>
    </div>

    <script>
        function countdown() {
            return {
                minutes: 2,
                seconds: 0,
                start() {
                    setInterval(() => {
                        if (this.seconds === 0) {
                            if (this.minutes === 0) {
                                // Reload the page when countdown reaches 0
                                location.reload();
                                return;
                            }
                            this.minutes--;
                            this.seconds = 59;
                        } else {
                            this.seconds--;
                        }
                    }, 1000);
                }
            }
        }
    </script>
@endsection