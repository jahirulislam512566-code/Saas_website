<div>
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Overview</h3>
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-purple-50 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-purple-600">${{ number_format($totalRevenue ?? 0, 2) }}</p>
            <p class="text-sm text-gray-500">Total Revenue</p>
        </div>
        <div class="bg-indigo-50 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-indigo-600">${{ number_format($monthlyRevenue ?? 0, 2) }}</p>
            <p class="text-sm text-gray-500">Monthly Revenue</p>
        </div>
    </div>
</div>