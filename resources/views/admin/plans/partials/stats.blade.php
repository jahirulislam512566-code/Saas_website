<!-- Plan Stats Partial -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Plans</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                <i class="fas fa-crown"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Active Plans</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['active'] ?? 0 }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Subscribers</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['subscribers'] ?? 0 }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Monthly Revenue</p>
                <p class="text-xl font-bold text-gray-900">${{ number_format($stats['monthly_revenue'] ?? 0, 2) }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
    </div>
</div>