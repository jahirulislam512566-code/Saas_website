<footer class="bg-white border-t border-gray-200 py-4">
    <div class="px-6 flex items-center justify-between">
        <p class="text-sm text-gray-500">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>
        <div class="flex items-center space-x-4 text-sm text-gray-500">
            <a href="#" class="hover:text-gray-700">Privacy Policy</a>
            <a href="#" class="hover:text-gray-700">Terms of Service</a>
            <span>v{{ config('app.version', '1.0.0') }}</span>
        </div>
    </div>
</footer>