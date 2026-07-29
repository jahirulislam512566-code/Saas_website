<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center" x-data="{ count: 0 }" x-init="setTimeout(() => count = 10000, 200)">
                <div class="text-4xl md:text-5xl font-bold text-indigo-600 mb-2">
                    <span x-text="count.toLocaleString()">0</span>+
                </div>
                <p class="text-gray-600 font-medium">Websites Built</p>
            </div>
            
            <div class="text-center" x-data="{ count: 0 }" x-init="setTimeout(() => count = 99.9, 400)">
                <div class="text-4xl md:text-5xl font-bold text-indigo-600 mb-2">
                    <span x-text="count">0</span>%
                </div>
                <p class="text-gray-600 font-medium">Uptime Guarantee</p>
            </div>
            
            <div class="text-center" x-data="{ count: 0 }" x-init="setTimeout(() => count = 4.9, 600)">
                <div class="text-4xl md:text-5xl font-bold text-indigo-600 mb-2">
                    <span x-text="count">0</span>★
                </div>
                <p class="text-gray-600 font-medium">Average Rating</p>
            </div>
            
            <div class="text-center" x-data="{ count: 0 }" x-init="setTimeout(() => count = 24, 800)">
                <div class="text-4xl md:text-5xl font-bold text-indigo-600 mb-2">
                    <span x-text="count">0</span>/7
                </div>
                <p class="text-gray-600 font-medium">Customer Support</p>
            </div>
        </div>
    </div>
</section>