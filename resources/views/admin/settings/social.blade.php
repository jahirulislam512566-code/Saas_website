@extends('admin.layouts.admin')

@section('title', 'Social Settings')

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
            <span class="text-gray-500">Social</span>
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
            <a href="{{ route('admin.settings.social') }}" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium">
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
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm animate-slide-down">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
                <button type="button" class="ml-auto text-green-500 hover:text-green-700" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm animate-slide-down">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
                <button type="button" class="ml-auto text-red-500 hover:text-red-700" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Social Overview -->
    <div class="mb-6 grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Connected</p>
                    <p class="text-lg font-bold text-gray-900">
                        {{ collect(['facebook_enabled', 'google_enabled', 'twitter_enabled', 'github_enabled'])->filter(fn($key) => setting($key, false))->count() }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-link text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Social Links</p>
                    <p class="text-lg font-bold text-gray-900">
                        {{ collect(['social_facebook', 'social_twitter', 'social_instagram', 'social_linkedin', 'social_youtube', 'social_github'])->filter(fn($key) => setting($key))->count() }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-hashtag text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Share Buttons</p>
                    <p class="text-lg font-bold text-gray-900">
                        {{ collect(['share_facebook', 'share_twitter', 'share_linkedin', 'share_whatsapp'])->filter(fn($key) => setting($key, true))->count() }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-share-alt text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Status</p>
                    <p class="text-lg font-bold {{ setting('social_facebook') || setting('social_twitter') ? 'text-green-600' : 'text-gray-400' }}">
                        {{ setting('social_facebook') || setting('social_twitter') ? 'Active' : 'Inactive' }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="fas fa-signal text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-share-alt text-primary-600 mr-2"></i>
                        Social Settings
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Configure social media integration and sharing options</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs text-gray-400">
                        <i class="fas fa-clock mr-1"></i>
                        Last updated: {{ setting('social_updated_at') ? \Carbon\Carbon::parse(setting('social_updated_at'))->diffForHumans() : 'Never' }}
                    </span>
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.settings.update.social') }}" method="POST" class="p-6" id="socialForm">
            @csrf
            @method('PUT')
            
            <div class="space-y-8">
                <!-- Social Login -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-sign-in-alt text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Social Login</h4>
                            <p class="text-xs text-gray-500">Enable social authentication providers</p>
                        </div>
                        <div class="ml-auto">
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                <i class="fas fa-check-circle mr-1"></i> OAuth
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Facebook -->
                        <div class="border rounded-lg p-4 bg-white hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <i class="fab fa-facebook text-blue-600 text-2xl mr-3"></i>
                                    <span class="font-medium text-gray-900">Facebook</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="facebook_enabled" value="1" 
                                           {{ old('facebook_enabled', setting('facebook_enabled', false)) ? 'checked' : '' }}
                                           class="sr-only peer"
                                           onchange="toggleSocialLogin('facebook', this.checked)">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                            <div class="space-y-2">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-id-card text-gray-400"></i>
                                    </div>
                                    <input type="text" name="facebook_client_id" 
                                           value="{{ old('facebook_client_id', setting('facebook_client_id')) }}"
                                           class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"
                                           placeholder="Client ID">
                                </div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-key text-gray-400"></i>
                                    </div>
                                    <input type="password" name="facebook_client_secret" 
                                           value="{{ old('facebook_client_secret', setting('facebook_client_secret')) }}"
                                           class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"
                                           placeholder="Client Secret">
                                    <button type="button" onclick="togglePasswordVisibility('facebook_client_secret')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Redirect URI: <code class="bg-gray-100 px-1 rounded">{{ url('/auth/facebook/callback') }}</code>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Google -->
                        <div class="border rounded-lg p-4 bg-white hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <i class="fab fa-google text-red-500 text-2xl mr-3"></i>
                                    <span class="font-medium text-gray-900">Google</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="google_enabled" value="1" 
                                           {{ old('google_enabled', setting('google_enabled', false)) ? 'checked' : '' }}
                                           class="sr-only peer"
                                           onchange="toggleSocialLogin('google', this.checked)">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                                </label>
                            </div>
                            <div class="space-y-2">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-id-card text-gray-400"></i>
                                    </div>
                                    <input type="text" name="google_client_id" 
                                           value="{{ old('google_client_id', setting('google_client_id')) }}"
                                           class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"
                                           placeholder="Client ID">
                                </div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-key text-gray-400"></i>
                                    </div>
                                    <input type="password" name="google_client_secret" 
                                           value="{{ old('google_client_secret', setting('google_client_secret')) }}"
                                           class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"
                                           placeholder="Client Secret">
                                    <button type="button" onclick="togglePasswordVisibility('google_client_secret')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Redirect URI: <code class="bg-gray-100 px-1 rounded">{{ url('/auth/google/callback') }}</code>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Twitter / X -->
                        <div class="border rounded-lg p-4 bg-white hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <i class="fab fa-x-twitter text-black text-2xl mr-3"></i>
                                    <span class="font-medium text-gray-900">Twitter / X</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="twitter_enabled" value="1" 
                                           {{ old('twitter_enabled', setting('twitter_enabled', false)) ? 'checked' : '' }}
                                           class="sr-only peer"
                                           onchange="toggleSocialLogin('twitter', this.checked)">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-black"></div>
                                </label>
                            </div>
                            <div class="space-y-2">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-id-card text-gray-400"></i>
                                    </div>
                                    <input type="text" name="twitter_client_id" 
                                           value="{{ old('twitter_client_id', setting('twitter_client_id')) }}"
                                           class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"
                                           placeholder="API Key">
                                </div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-key text-gray-400"></i>
                                    </div>
                                    <input type="password" name="twitter_client_secret" 
                                           value="{{ old('twitter_client_secret', setting('twitter_client_secret')) }}"
                                           class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"
                                           placeholder="API Secret">
                                    <button type="button" onclick="togglePasswordVisibility('twitter_client_secret')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Redirect URI: <code class="bg-gray-100 px-1 rounded">{{ url('/auth/twitter/callback') }}</code>
                                </p>
                            </div>
                        </div>
                        
                        <!-- GitHub -->
                        <div class="border rounded-lg p-4 bg-white hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <i class="fab fa-github text-gray-700 text-2xl mr-3"></i>
                                    <span class="font-medium text-gray-900">GitHub</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="github_enabled" value="1" 
                                           {{ old('github_enabled', setting('github_enabled', false)) ? 'checked' : '' }}
                                           class="sr-only peer"
                                           onchange="toggleSocialLogin('github', this.checked)">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-gray-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gray-700"></div>
                                </label>
                            </div>
                            <div class="space-y-2">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-id-card text-gray-400"></i>
                                    </div>
                                    <input type="text" name="github_client_id" 
                                           value="{{ old('github_client_id', setting('github_client_id')) }}"
                                           class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"
                                           placeholder="Client ID">
                                </div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-key text-gray-400"></i>
                                    </div>
                                    <input type="password" name="github_client_secret" 
                                           value="{{ old('github_client_secret', setting('github_client_secret')) }}"
                                           class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"
                                           placeholder="Client Secret">
                                    <button type="button" onclick="togglePasswordVisibility('github_client_secret')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Redirect URI: <code class="bg-gray-100 px-1 rounded">{{ url('/auth/github/callback') }}</code>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Social Login Providers -->
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="border rounded-lg p-4 bg-white hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <i class="fab fa-linkedin text-blue-700 text-xl mr-2"></i>
                                    <span class="font-medium text-gray-900">LinkedIn</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="linkedin_enabled" value="1" 
                                           {{ old('linkedin_enabled', setting('linkedin_enabled', false)) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-700"></div>
                                </label>
                            </div>
                            <input type="text" name="linkedin_client_id" 
                                   value="{{ old('linkedin_client_id', setting('linkedin_client_id')) }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"
                                   placeholder="Client ID">
                        </div>
                        
                        <div class="border rounded-lg p-4 bg-white hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <i class="fab fa-apple text-gray-700 text-xl mr-2"></i>
                                    <span class="font-medium text-gray-900">Apple</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="apple_enabled" value="1" 
                                           {{ old('apple_enabled', setting('apple_enabled', false)) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-gray-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gray-700"></div>
                                </label>
                            </div>
                            <input type="text" name="apple_client_id" 
                                   value="{{ old('apple_client_id', setting('apple_client_id')) }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"
                                   placeholder="Client ID">
                        </div>
                        
                        <div class="border rounded-lg p-4 bg-white hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <i class="fab fa-microsoft text-blue-600 text-xl mr-2"></i>
                                    <span class="font-medium text-gray-900">Microsoft</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="microsoft_enabled" value="1" 
                                           {{ old('microsoft_enabled', setting('microsoft_enabled', false)) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                            <input type="text" name="microsoft_client_id" 
                                   value="{{ old('microsoft_client_id', setting('microsoft_client_id')) }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"
                                   placeholder="Client ID">
                        </div>
                    </div>
                </div>
                
                <!-- Social Links -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                <i class="fas fa-link text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Social Links</h4>
                            <p class="text-xs text-gray-500">Add your social media profile URLs</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="social_facebook" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fab fa-facebook text-blue-600 mr-1"></i> Facebook
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-link text-gray-400"></i>
                                </div>
                                <input type="url" name="social_facebook" id="social_facebook" 
                                       value="{{ old('social_facebook', setting('social_facebook')) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('social_facebook') border-red-500 @enderror"
                                       placeholder="https://facebook.com/yourpage">
                            </div>
                            @error('social_facebook')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="social_twitter" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fab fa-x-twitter text-black mr-1"></i> Twitter / X
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-link text-gray-400"></i>
                                </div>
                                <input type="url" name="social_twitter" id="social_twitter" 
                                       value="{{ old('social_twitter', setting('social_twitter')) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('social_twitter') border-red-500 @enderror"
                                       placeholder="https://twitter.com/yourprofile">
                            </div>
                            @error('social_twitter')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="social_instagram" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fab fa-instagram text-pink-600 mr-1"></i> Instagram
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-link text-gray-400"></i>
                                </div>
                                <input type="url" name="social_instagram" id="social_instagram" 
                                       value="{{ old('social_instagram', setting('social_instagram')) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('social_instagram') border-red-500 @enderror"
                                       placeholder="https://instagram.com/yourprofile">
                            </div>
                            @error('social_instagram')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="social_linkedin" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fab fa-linkedin text-blue-700 mr-1"></i> LinkedIn
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-link text-gray-400"></i>
                                </div>
                                <input type="url" name="social_linkedin" id="social_linkedin" 
                                       value="{{ old('social_linkedin', setting('social_linkedin')) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('social_linkedin') border-red-500 @enderror"
                                       placeholder="https://linkedin.com/company/yourcompany">
                            </div>
                            @error('social_linkedin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="social_youtube" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fab fa-youtube text-red-600 mr-1"></i> YouTube
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-link text-gray-400"></i>
                                </div>
                                <input type="url" name="social_youtube" id="social_youtube" 
                                       value="{{ old('social_youtube', setting('social_youtube')) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('social_youtube') border-red-500 @enderror"
                                       placeholder="https://youtube.com/channel/yourchannel">
                            </div>
                            @error('social_youtube')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="social_github" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fab fa-github text-gray-700 mr-1"></i> GitHub
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-link text-gray-400"></i>
                                </div>
                                <input type="url" name="social_github" id="social_github" 
                                       value="{{ old('social_github', setting('social_github')) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('social_github') border-red-500 @enderror"
                                       placeholder="https://github.com/yourusername">
                            </div>
                            @error('social_github')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Additional Social Links -->
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="social_tiktok" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fab fa-tiktok text-black mr-1"></i> TikTok
                            </label>
                            <input type="url" name="social_tiktok" id="social_tiktok" 
                                   value="{{ old('social_tiktok', setting('social_tiktok')) }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   placeholder="https://tiktok.com/@yourprofile">
                        </div>
                        
                        <div>
                            <label for="social_pinterest" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fab fa-pinterest text-red-600 mr-1"></i> Pinterest
                            </label>
                            <input type="url" name="social_pinterest" id="social_pinterest" 
                                   value="{{ old('social_pinterest', setting('social_pinterest')) }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   placeholder="https://pinterest.com/yourprofile">
                        </div>
                        
                        <div>
                            <label for="social_whatsapp" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fab fa-whatsapp text-green-500 mr-1"></i> WhatsApp
                            </label>
                            <input type="url" name="social_whatsapp" id="social_whatsapp" 
                                   value="{{ old('social_whatsapp', setting('social_whatsapp')) }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   placeholder="https://wa.me/yournumber">
                        </div>
                    </div>
                </div>
                
                <!-- Social Share Buttons -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-share-alt text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Social Share Buttons</h4>
                            <p class="text-xs text-gray-500">Choose which share buttons to display</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <label class="flex items-center p-3 bg-white rounded-lg border hover:border-primary-300 transition-colors cursor-pointer">
                            <input type="checkbox" name="share_facebook" value="1" 
                                   {{ old('share_facebook', setting('share_facebook', true)) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">
                                <i class="fab fa-facebook text-blue-600 mr-1"></i> Facebook
                            </span>
                        </label>
                        
                        <label class="flex items-center p-3 bg-white rounded-lg border hover:border-primary-300 transition-colors cursor-pointer">
                            <input type="checkbox" name="share_twitter" value="1" 
                                   {{ old('share_twitter', setting('share_twitter', true)) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">
                                <i class="fab fa-x-twitter text-black mr-1"></i> Twitter / X
                            </span>
                        </label>
                        
                        <label class="flex items-center p-3 bg-white rounded-lg border hover:border-primary-300 transition-colors cursor-pointer">
                            <input type="checkbox" name="share_linkedin" value="1" 
                                   {{ old('share_linkedin', setting('share_linkedin', true)) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">
                                <i class="fab fa-linkedin text-blue-700 mr-1"></i> LinkedIn
                            </span>
                        </label>
                        
                        <label class="flex items-center p-3 bg-white rounded-lg border hover:border-primary-300 transition-colors cursor-pointer">
                            <input type="checkbox" name="share_whatsapp" value="1" 
                                   {{ old('share_whatsapp', setting('share_whatsapp', true)) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">
                                <i class="fab fa-whatsapp text-green-500 mr-1"></i> WhatsApp
                            </span>
                        </label>
                        
                        <label class="flex items-center p-3 bg-white rounded-lg border hover:border-primary-300 transition-colors cursor-pointer">
                            <input type="checkbox" name="share_telegram" value="1" 
                                   {{ old('share_telegram', setting('share_telegram', true)) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">
                                <i class="fab fa-telegram text-blue-400 mr-1"></i> Telegram
                            </span>
                        </label>
                        
                        <label class="flex items-center p-3 bg-white rounded-lg border hover:border-primary-300 transition-colors cursor-pointer">
                            <input type="checkbox" name="share_pinterest" value="1" 
                                   {{ old('share_pinterest', setting('share_pinterest', true)) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">
                                <i class="fab fa-pinterest text-red-600 mr-1"></i> Pinterest
                            </span>
                        </label>
                        
                        <label class="flex items-center p-3 bg-white rounded-lg border hover:border-primary-300 transition-colors cursor-pointer">
                            <input type="checkbox" name="share_email" value="1" 
                                   {{ old('share_email', setting('share_email', true)) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">
                                <i class="fas fa-envelope text-gray-600 mr-1"></i> Email
                            </span>
                        </label>
                        
                        <label class="flex items-center p-3 bg-white rounded-lg border hover:border-primary-300 transition-colors cursor-pointer">
                            <input type="checkbox" name="share_print" value="1" 
                                   {{ old('share_print', setting('share_print', true)) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">
                                <i class="fas fa-print text-gray-600 mr-1"></i> Print
                            </span>
                        </label>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <div>
                        <a href="{{ route('admin.settings.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Settings
                        </a>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button type="button" onclick="resetForm()" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fas fa-undo mr-2"></i> Reset
                        </button>
                        <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                            <i class="fas fa-save mr-2"></i> Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize toggle states
    @foreach(['facebook', 'google', 'twitter', 'github', 'linkedin', 'apple', 'microsoft'] as $provider)
        toggleSocialLogin('{{ $provider }}', document.querySelector('[name="{{ $provider }}_enabled"]')?.checked || false);
    @endforeach
});

function toggleSocialLogin(provider, enabled) {
    const fields = document.querySelectorAll(`[name^="${provider}_"]`);
    fields.forEach(field => {
        if (field.type === 'text' || field.type === 'password') {
            field.disabled = !enabled;
            field.classList.toggle('opacity-50', !enabled);
        }
    });
}

function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    if (!field) return;
    
    const button = field.parentElement.querySelector('button');
    const icon = button?.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        if (icon) {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    } else {
        field.type = 'password';
        if (icon) {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
}

function resetForm() {
    if (confirm('Are you sure you want to reset all fields to their current saved values?')) {
        location.reload();
    }
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.animate-slide-down');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
</script>
@endpush
@endsection