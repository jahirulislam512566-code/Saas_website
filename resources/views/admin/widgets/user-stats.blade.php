<div>
    <h3 class="text-lg font-semibold text-gray-900 mb-4">User Statistics</h3>
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-blue-50 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-blue-600">{{ $totalUsers ?? 0 }}</p>
            <p class="text-sm text-gray-500">Total Users</p>
        </div>
        <div class="bg-green-50 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-green-600">{{ $newUsers ?? 0 }}</p>
            <p class="text-sm text-gray-500">New This Month</p>
        </div>
    </div>
</div>