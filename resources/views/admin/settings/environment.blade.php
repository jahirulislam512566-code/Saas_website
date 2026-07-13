@extends('admin.layouts.admin')

@section('title', 'Environment Settings')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.settings.index') }}" class="text-gray-500 hover:text-gray-700">Settings</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Environment</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Settings Navigation -->
    <div class="mb-6">
        <div class="flex flex-wrap gap-2 bg-white rounded-xl shadow-sm p-3">
            <a href="{{ route('admin.settings.general') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-cog mr-2"></i> General
            </a>
            <a href="{{ route('admin.settings.payment') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-credit-card mr-2"></i> Payment
            </a>
            <a href="{{ route('admin.settings.smtp') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-envelope mr-2"></i> SMTP
            </a>
            <a href="{{ route('admin.settings.seo') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-search mr-2"></i> SEO
            </a>
            <a href="{{ route('admin.settings.social') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-share-alt mr-2"></i> Social
            </a>
            <a href="{{ route('admin.settings.system') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-server mr-2"></i> System
            </a>
            <a href="{{ route('admin.settings.security') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-shield-alt mr-2"></i> Security
            </a>
            <a href="{{ route('admin.settings.integrations') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-plug mr-2"></i> Integrations
            </a>
            <a href="{{ route('admin.settings.environment') }}" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium">
                <i class="fas fa-code mr-2"></i> Environment
            </a>
        </div>
    </div>

    <!-- Environment Overview -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 {{ config('app.env') == 'production' ? 'border-green-500' : 'border-yellow-500' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Environment</p>
                    <p class="text-2xl font-bold text-gray-900">{{ ucfirst(config('app.env')) }}</p>
                </div>
                <div class="w-12 h-12 rounded-full {{ config('app.env') == 'production' ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600' }} flex items-center justify-center">
                    <i class="fas fa-server text-xl"></i>
                </div>
            </div>
            <span class="text-xs {{ config('app.env') == 'production' ? 'text-green-600' : 'text-yellow-600' }}">
                <i class="fas fa-{{ config('app.env') == 'production' ? 'check-circle' : 'exclamation-triangle' }} mr-1"></i>
                {{ config('app.env') == 'production' ? 'Live' : 'Development' }}
            </span>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Debug Mode</p>
                    <p class="text-2xl font-bold text-gray-900">{{ config('app.debug') ? 'ON' : 'OFF' }}</p>
                </div>
                <div class="w-12 h-12 rounded-full {{ config('app.debug') ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }} flex items-center justify-center">
                    <i class="fas fa-bug text-xl"></i>
                </div>
            </div>
            <span class="text-xs {{ config('app.debug') ? 'text-red-600' : 'text-green-600' }}">
                <i class="fas fa-{{ config('app.debug') ? 'exclamation-circle' : 'check-circle' }} mr-1"></i>
                {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
            </span>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">PHP Version</p>
                    <p class="text-2xl font-bold text-gray-900">{{ PHP_VERSION }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fab fa-php text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Laravel</p>
                    <p class="text-2xl font-bold text-gray-900">{{ app()->version() }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <i class="fab fa-laravel text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-code text-primary-600 mr-2"></i>
                        Environment Settings
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">View and manage your application environment configuration</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs text-gray-400">
                        <i class="fas fa-clock mr-1"></i>
                        {{ now()->format('l, F j, Y g:i A') }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Environment Variables -->
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-cogs text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Application Settings</h4>
                            <p class="text-xs text-gray-500">Core application environment variables</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500">APP_NAME</p>
                            <p class="text-sm font-medium text-gray-900">{{ env('APP_NAME', 'Not Set') }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500">APP_ENV</p>
                            <p class="text-sm font-medium text-gray-900">{{ env('APP_ENV', 'Not Set') }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500">APP_DEBUG</p>
                            <p class="text-sm font-medium text-gray-900">{{ env('APP_DEBUG', 'Not Set') }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500">APP_URL</p>
                            <p class="text-sm font-medium text-gray-900">{{ env('APP_URL', 'Not Set') }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500">APP_TIMEZONE</p>
                            <p class="text-sm font-medium text-gray-900">{{ env('APP_TIMEZONE', 'Not Set') }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500">APP_LOCALE</p>
                            <p class="text-sm font-medium text-gray-900">{{ env('APP_LOCALE', 'Not Set') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Database Settings -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-database text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Database Settings</h4>
                            <p class="text-xs text-gray-500">Database connection configuration</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500">DB_CONNECTION</p>
                            <p class="text-sm font-medium text-gray-900">{{ env('DB_CONNECTION', 'Not Set') }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500">DB_HOST</p>
                            <p class="text-sm font-medium text-gray-900">{{ env('DB_HOST', 'Not Set') }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500">DB_PORT</p>
                            <p class="text-sm font-medium text-gray-900">{{ env('DB_PORT', 'Not Set') }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500">DB_DATABASE</p>
                            <p class="text-sm font-medium text-gray-900">{{ env('DB_DATABASE', 'Not Set') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Cache & Session Settings -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                <i class="fas fa-bolt text-yellow-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Cache & Session</h4>
                            <p class="text-xs text-gray-500">Performance and session configuration</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500">CACHE_DRIVER</p>
                            <p class="text-sm font-medium text-gray-900">{{ env('CACHE_DRIVER', 'Not Set') }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500">SESSION_DRIVER</p>
                            <p class="text-sm font-medium text-gray-900">{{ env('SESSION_DRIVER', 'Not Set') }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500">QUEUE_CONNECTION</p>
                            <p class="text-sm font-medium text-gray-900">{{ env('QUEUE_CONNECTION', 'Not Set') }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500">SESSION_LIFETIME</p>
                            <p class="text-sm font-medium text-gray-900">{{ env('SESSION_LIFETIME', 'Not Set') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- System Information -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                <i class="fas fa-info-circle text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">System Information</h4>
                            <p class="text-xs text-gray-500">Server and system details</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500">Operating System</p>
                            <p class="text-sm font-medium text-gray-900">{{ php_uname('s') }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500">Server Software</p>
                            <p class="text-sm font-medium text-gray-900">{{ $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500">PHP Memory Limit</p>
                            <p class="text-sm font-medium text-gray-900">{{ ini_get('memory_limit') }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500">Max Upload Size</p>
                            <p class="text-sm font-medium text-gray-900">{{ ini_get('upload_max_filesize') }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500">Max Execution Time</p>
                            <p class="text-sm font-medium text-gray-900">{{ ini_get('max_execution_time') }} seconds</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500">Post Max Size</p>
                            <p class="text-sm font-medium text-gray-900">{{ ini_get('post_max_size') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <div>
                        <a href="{{ route('admin.settings.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Settings
                        </a>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button onclick="refreshEnvironment()" class="px-4 py-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <i class="fas fa-sync mr-2"></i> Refresh
                        </button>
                        <button onclick="exportEnv()" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fas fa-download mr-2"></i> Export
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function refreshEnvironment() {
    showLoading('Refreshing environment information...');
    location.reload();
}

function exportEnv() {
    window.location.href = '{{ route("admin.settings.export.env") }}';
}

function showLoading(message) {
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
                <p class="text-center text-gray-700 mt-3" id="loadingMessage">${message}</p>
            </div>
        `;
        document.body.appendChild(overlay);
    } else {
        document.getElementById('loadingMessage').textContent = message;
        overlay.classList.remove('hidden');
    }
}
</script>
@endpush
@endsection