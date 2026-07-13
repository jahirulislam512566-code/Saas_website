@extends('admin.layouts.admin')

@section('title', 'Settings Dashboard')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Settings</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Settings Dashboard</h1>
                <p class="text-sm text-gray-500 mt-1">Manage your application configuration and preferences</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-xs text-gray-400">
                    <i class="fas fa-clock mr-1"></i>
                    {{ now()->format('l, F j, Y g:i A') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Configured Settings</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $configuredSettings ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-sliders-h text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Active Services</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeServices ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Environment</p>
                    <p class="text-2xl font-bold text-gray-900">{{ ucfirst(config('app.env')) }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="fas fa-server text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Cache Status</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $cacheStatus ?? 'Active' }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-database text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden sticky top-6">
                <div class="px-4 py-3 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                    <h5 class="text-sm font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-list-ul text-primary-600 mr-2"></i>
                        Settings Menu
                    </h5>
                </div>
                <div class="p-2 space-y-1">
                    <a href="{{ route('admin.settings.general') }}" 
                       class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.settings.general') ? 'bg-primary-50 text-primary-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-cog w-5 h-5 {{ request()->routeIs('admin.settings.general') ? 'text-primary-500' : 'text-gray-400' }}"></i>
                        <span class="ml-3">General</span>
                        @if(setting('site_name'))
                            <span class="ml-auto text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full">Active</span>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.settings.payment') }}" 
                       class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.settings.payment') ? 'bg-primary-50 text-primary-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-credit-card w-5 h-5 {{ request()->routeIs('admin.settings.payment') ? 'text-primary-500' : 'text-gray-400' }}"></i>
                        <span class="ml-3">Payment</span>
                        @if(setting('payment_gateway'))
                            <span class="ml-auto text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full">{{ ucfirst(setting('payment_gateway')) }}</span>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.settings.smtp') }}" 
                       class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.settings.smtp') ? 'bg-primary-50 text-primary-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-envelope w-5 h-5 {{ request()->routeIs('admin.settings.smtp') ? 'text-primary-500' : 'text-gray-400' }}"></i>
                        <span class="ml-3">SMTP</span>
                        @if(setting('mail_host'))
                            <span class="ml-auto text-xs {{ setting('mail_host') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-2 py-0.5 rounded-full">
                                {{ setting('mail_host') ? 'Configured' : 'Missing' }}
                            </span>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.settings.seo') }}" 
                       class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.settings.seo') ? 'bg-primary-50 text-primary-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-search w-5 h-5 {{ request()->routeIs('admin.settings.seo') ? 'text-primary-500' : 'text-gray-400' }}"></i>
                        <span class="ml-3">SEO</span>
                        @if(setting('meta_title'))
                            <span class="ml-auto text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">Optimized</span>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.settings.social') }}" 
                       class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.settings.social') ? 'bg-primary-50 text-primary-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-share-alt w-5 h-5 {{ request()->routeIs('admin.settings.social') ? 'text-primary-500' : 'text-gray-400' }}"></i>
                        <span class="ml-3">Social</span>
                        @if(setting('social_facebook') || setting('social_twitter'))
                            <span class="ml-auto text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full">Connected</span>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.settings.system') }}" 
                       class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.settings.system') ? 'bg-primary-50 text-primary-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-server w-5 h-5 {{ request()->routeIs('admin.settings.system') ? 'text-primary-500' : 'text-gray-400' }}"></i>
                        <span class="ml-3">System</span>
                    </a>
                    
                    <a href="{{ route('admin.settings.security') }}" 
                       class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.settings.security') ? 'bg-primary-50 text-primary-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-shield-alt w-5 h-5 {{ request()->routeIs('admin.settings.security') ? 'text-primary-500' : 'text-gray-400' }}"></i>
                        <span class="ml-3">Security</span>
                    </a>
                    
                    <a href="{{ route('admin.settings.integrations') }}" 
                       class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.settings.integrations') ? 'bg-primary-50 text-primary-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-plug w-5 h-5 {{ request()->routeIs('admin.settings.integrations') ? 'text-primary-500' : 'text-gray-400' }}"></i>
                        <span class="ml-3">Integrations</span>
                    </a>
                    
                    <a href="{{ route('admin.settings.environment') }}" 
                       class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.settings.environment') ? 'bg-primary-50 text-primary-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-code w-5 h-5 {{ request()->routeIs('admin.settings.environment') ? 'text-primary-500' : 'text-gray-400' }}"></i>
                        <span class="ml-3">Environment</span>
                        <span class="ml-auto text-xs {{ config('app.env') == 'production' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} px-2 py-0.5 rounded-full">
                            {{ ucfirst(config('app.env')) }}
                        </span>
                    </a>
                </div>
                
                <!-- System Info -->
                <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                    <div class="text-xs text-gray-500 space-y-1">
                        <p><span class="font-medium">PHP:</span> {{ PHP_VERSION }}</p>
                        <p><span class="font-medium">Laravel:</span> {{ app()->version() }}</p>
                        <p><span class="font-medium">Memory:</span> {{ ini_get('memory_limit') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="lg:col-span-3">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <button onclick="clearCache()" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-xl hover:bg-red-50 hover:border-red-200 transition-all duration-200 group border border-transparent">
                            <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 group-hover:bg-red-200 flex items-center justify-center mb-2">
                                <i class="fas fa-trash-alt text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-red-700">Clear Cache</span>
                            <span class="text-xs text-gray-400">Clear all caches</span>
                        </button>
                        
                        <button onclick="optimizeApp()" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-xl hover:bg-blue-50 hover:border-blue-200 transition-all duration-200 group border border-transparent">
                            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 group-hover:bg-blue-200 flex items-center justify-center mb-2">
                                <i class="fas fa-rocket text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Optimize App</span>
                            <span class="text-xs text-gray-400">Optimize performance</span>
                        </button>
                        
                        <button onclick="viewLogs()" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-xl hover:bg-purple-50 hover:border-purple-200 transition-all duration-200 group border border-transparent">
                            <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 group-hover:bg-purple-200 flex items-center justify-center mb-2">
                                <i class="fas fa-file-alt text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-purple-700">View Logs</span>
                            <span class="text-xs text-gray-400">System logs</span>
                        </button>
                        
                        <button onclick="checkHealth()" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-xl hover:bg-green-50 hover:border-green-200 transition-all duration-200 group border border-transparent">
                            <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 group-hover:bg-green-200 flex items-center justify-center mb-2">
                                <i class="fas fa-heartbeat text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-green-700">Health Check</span>
                            <span class="text-xs text-gray-400">System status</span>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- System Status -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-heartbeat text-green-500 mr-2"></i>
                        System Health
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-database text-blue-500 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Database</p>
                                    <p class="text-xs text-gray-500">Connection status</p>
                                </div>
                            </div>
                            <span class="text-sm font-medium text-green-600">
                                <i class="fas fa-check-circle mr-1"></i> Connected
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-server text-purple-500 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Queue</p>
                                    <p class="text-xs text-gray-500">Worker status</p>
                                </div>
                            </div>
                            <span class="text-sm font-medium text-green-600">
                                <i class="fas fa-check-circle mr-1"></i> Running
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-yellow-500 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Mail</p>
                                    <p class="text-xs text-gray-500">SMTP status</p>
                                </div>
                            </div>
                            <span class="text-sm font-medium {{ setting('mail_host') ? 'text-green-600' : 'text-red-600' }}">
                                <i class="fas {{ setting('mail_host') ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                {{ setting('mail_host') ? 'Configured' : 'Not Configured' }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-shield-alt text-red-500 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Security</p>
                                    <p class="text-xs text-gray-500">SSL/HTTPS</p>
                                </div>
                            </div>
                            <span class="text-sm font-medium {{ request()->secure() ? 'text-green-600' : 'text-yellow-600' }}">
                                <i class="fas {{ request()->secure() ? 'fa-check-circle' : 'fa-exclamation-triangle' }} mr-1"></i>
                                {{ request()->secure() ? 'Secure' : 'Not Secure' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="mt-6 bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-clock text-gray-500 mr-2"></i>
                        Recent Activity
                    </h3>
                    <a href="{{ route('admin.activities.index') }}" class="text-sm text-primary-600 hover:text-primary-800">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="p-6">
                    @if(isset($recentActivities) && count($recentActivities) > 0)
                        <div class="space-y-4">
                            @foreach($recentActivities as $activity)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 rounded-full {{ $activity['color'] ?? 'bg-gray-100' }} flex items-center justify-center">
                                            <i class="fas {{ $activity['icon'] ?? 'fa-clock' }} {{ $activity['icon_color'] ?? 'text-gray-500' }}"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-900">{{ $activity['description'] ?? 'Activity logged' }}</p>
                                        <div class="flex items-center space-x-2 mt-1">
                                            <span class="text-xs text-gray-500">{{ $activity['time'] ?? 'Just now' }}</span>
                                            @if(isset($activity['user']))
                                                <span class="text-xs text-gray-400">by {{ $activity['user'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if(isset($activity['status']))
                                        <span class="text-xs {{ $activity['status'] === 'success' ? 'text-green-600' : 'text-gray-400' }}">
                                            <i class="fas {{ $activity['status'] === 'success' ? 'fa-check-circle' : 'fa-clock' }}"></i>
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                            <p class="text-gray-500">No recent activity</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Health Check Modal -->
<div id="healthModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50"></div>
    <div class="relative bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">
                <i class="fas fa-heartbeat text-green-500 mr-2"></i>
                System Health Check
            </h3>
            <button onclick="closeHealthModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="healthResults" class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <span class="text-sm font-medium text-gray-700">Checking system health...</span>
                <i class="fas fa-spinner fa-spin text-blue-500"></i>
            </div>
        </div>
        <div class="mt-4 text-right">
            <button onclick="closeHealthModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                Close
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function clearCache() {
    if (!confirm('Are you sure you want to clear all caches?')) return;
    
    showLoading('Clearing cache...');
    
    fetch('{{ route("admin.settings.clear.cache") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showToast('success', 'Cache cleared successfully!');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast('error', 'Failed to clear cache: ' + data.message);
        }
    })
    .catch(error => {
        hideLoading();
        showToast('error', 'Failed to clear cache: ' + error.message);
    });
}

function optimizeApp() {
    if (!confirm('Are you sure you want to optimize the application?')) return;
    
    showLoading('Optimizing application...');
    
    fetch('{{ route("admin.settings.optimize") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showToast('success', 'Application optimized successfully!');
        } else {
            showToast('error', 'Failed to optimize: ' + data.message);
        }
    })
    .catch(error => {
        hideLoading();
        showToast('error', 'Failed to optimize: ' + error.message);
    });
}

function viewLogs() {
    window.location.href = '{{ route("admin.system.logs") }}';
}

function checkHealth() {
    const modal = document.getElementById('healthModal');
    const results = document.getElementById('healthResults');
    modal.classList.remove('hidden');
    
    results.innerHTML = `
        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span class="text-sm font-medium text-gray-700">Checking system health...</span>
            <i class="fas fa-spinner fa-spin text-blue-500"></i>
        </div>
    `;
    
    fetch('{{ route("admin.dashboard.health") }}', {
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Health data received:', data); // Debug log
        
        // Check if the response has the expected structure
        if (data.status && data.checks) {
            let html = '';
            
            // Display overall status
            const overallStatus = data.status === 'healthy' ? '✅' : '⚠️';
            const overallColor = data.status === 'healthy' ? 'text-green-600' : 'text-yellow-600';
            html += `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-2">
                    <span class="text-sm font-medium text-gray-700">Overall Status</span>
                    <span class="text-sm font-medium ${overallColor}">${overallStatus} ${data.status}</span>
                </div>
            `;
            
            // Display individual checks
            for (const [key, value] of Object.entries(data.checks)) {
                const status = value.status === 'ok' ? '✅' : '❌';
                const color = value.status === 'ok' ? 'text-green-600' : 'text-red-600';
                const displayName = key.charAt(0).toUpperCase() + key.slice(1);
                
                html += `
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">${displayName}</span>
                        <span class="text-sm font-medium ${color}">${status} ${value.message}</span>
                    </div>
                `;
            }
            
            // Add environment info
            html += `
                <div class="mt-3 p-2 bg-gray-100 rounded-lg text-xs text-gray-500">
                    <span>Environment: ${data.environment || 'N/A'}</span>
                    <span class="mx-2">|</span>
                    <span>PHP: ${data.php_version || 'N/A'}</span>
                    <span class="mx-2">|</span>
                    <span>Laravel: ${data.laravel_version || 'N/A'}</span>
                    <br>
                    <span>Last check: ${new Date(data.timestamp).toLocaleString()}</span>
                </div>
            `;
            
            results.innerHTML = html;
            
            // Auto-close modal after 10 seconds (optional)
            // setTimeout(() => {
            //     modal.classList.add('hidden');
            // }, 10000);
            
        } else {
            // Handle unexpected response structure
            results.innerHTML = `
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <span class="text-sm font-medium text-yellow-700">Unexpected response format</span>
                    <span class="text-sm text-yellow-600">Please check console for details</span>
                </div>
            `;
            console.error('Unexpected data structure:', data);
        }
    })
    .catch(error => {
        console.error('Health check error:', error);
        results.innerHTML = `
            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                <span class="text-sm font-medium text-red-700">Error checking system health</span>
                <span class="text-sm text-red-600">${error.message || 'Unknown error'}</span>
            </div>
        `;
    });
}

function closeHealthModal() {
    document.getElementById('healthModal').classList.add('hidden');
}

function showLoading(message) {
    // Create loading overlay if not exists
    let overlay = document.getElementById('loadingOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'loadingOverlay';
        overlay.className = 'fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50';
        overlay.innerHTML = `
            <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4">
                <div class="flex items-center justify-center">
                    <i class="fas fa-spinner fa-spin text-primary-600 text-3xl"></i>
                </div>
                <p class="text-center text-gray-700 mt-3" id="loadingMessage">Processing...</p>
            </div>
        `;
        document.body.appendChild(overlay);
    }
    document.getElementById('loadingMessage').textContent = message;
    overlay.classList.remove('hidden');
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.classList.add('hidden');
    }
}

function showToast(type, message) {
    const toast = document.createElement('div');
    const colors = {
        success: 'bg-green-50 border-green-500 text-green-700',
        error: 'bg-red-50 border-red-500 text-red-700',
        warning: 'bg-yellow-50 border-yellow-500 text-yellow-700',
        info: 'bg-blue-50 border-blue-500 text-blue-700'
    };
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-times-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg border-l-4 shadow-lg max-w-sm ${colors[type] || colors.info}`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${icons[type] || icons.info} mr-3"></i>
            <span class="text-sm font-medium">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.5s ease';
        setTimeout(() => toast.remove(), 500);
    }, 5000);
}
</script>
@endpush
@endsection