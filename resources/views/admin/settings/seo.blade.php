@extends('admin.layouts.admin')

@section('title', 'SEO Settings')

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
            <span class="text-gray-500">SEO</span>
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
            <a href="{{ route('admin.settings.seo') }}" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium">
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

    <!-- SEO Score Overview -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">SEO Score</p>
                    <p class="text-2xl font-bold text-gray-900" id="seoScore">85%</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
            </div>
            <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                <div class="bg-green-500 h-2 rounded-full" style="width: 85%"></div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Meta Tags</p>
                    <p class="text-2xl font-bold text-gray-900">3/3</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-tags text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Open Graph</p>
                    <p class="text-2xl font-bold text-gray-900">2/3</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-share-alt text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Keywords</p>
                    <p class="text-2xl font-bold text-gray-900">{{ setting('meta_keywords') ? count(explode(',', setting('meta_keywords'))) : 0 }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="fas fa-key text-xl"></i>
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
                        <i class="fas fa-search text-primary-600 mr-2"></i>
                        SEO Settings
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Optimize your website for search engines</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="analyzeSEO()" class="px-3 py-1 bg-primary-100 text-primary-700 rounded-lg text-sm hover:bg-primary-200 transition-colors">
                        <i class="fas fa-sync mr-1"></i> Analyze
                    </button>
                    <span class="text-xs text-gray-400">
                        <i class="fas fa-clock mr-1"></i>
                        Last updated: {{ setting('seo_updated_at') ? \Carbon\Carbon::parse(setting('seo_updated_at'))->diffForHumans() : 'Never' }}
                    </span>
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.settings.update.seo') }}" method="POST" enctype="multipart/form-data" class="p-6" id="seoForm">
            @csrf
            @method('PUT')
            
            <div class="space-y-8">
                <!-- Meta Tags -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-tags text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Meta Tags</h4>
                            <p class="text-xs text-gray-500">Configure default meta tags for your pages</p>
                        </div>
                        <div class="ml-auto">
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full" id="metaStatus">
                                <i class="fas fa-check-circle mr-1"></i> Complete
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">
                            Default Meta Title <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-heading text-gray-400"></i>
                            </div>
                            <input type="text" name="meta_title" id="meta_title" 
                                   value="{{ old('meta_title', setting('meta_title', config('app.name'))) }}"
                                   class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('meta_title') border-red-500 @enderror"
                                   placeholder="Default page title"
                                   oninput="validateMetaTitle(this)">
                        </div>
                        <div class="mt-1 flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <span class="text-xs text-gray-500">Recommended: 50-60 characters</span>
                                <span class="text-xs text-gray-400" id="meta-title-count">{{ strlen(setting('meta_title', config('app.name'))) }}</span>
                            </div>
                            <div id="metaTitleStatus" class="text-xs"></div>
                        </div>
                        @error('meta_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mt-4">
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">
                            Default Meta Description <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                <i class="fas fa-align-left text-gray-400"></i>
                            </div>
                            <textarea name="meta_description" id="meta_description" rows="3"
                                      class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('meta_description') border-red-500 @enderror"
                                      placeholder="Default page description"
                                      oninput="validateMetaDescription(this)">{{ old('meta_description', setting('meta_description')) }}</textarea>
                        </div>
                        <div class="mt-1 flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <span class="text-xs text-gray-500">Recommended: 150-160 characters</span>
                                <span class="text-xs text-gray-400" id="meta-description-count">{{ strlen(setting('meta_description', '')) }}</span>
                            </div>
                            <div id="metaDescriptionStatus" class="text-xs"></div>
                        </div>
                        @error('meta_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mt-4">
                        <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">
                            Meta Keywords
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-key text-gray-400"></i>
                            </div>
                            <input type="text" name="meta_keywords" id="meta_keywords" 
                                   value="{{ old('meta_keywords', setting('meta_keywords')) }}"
                                   class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('meta_keywords') border-red-500 @enderror"
                                   placeholder="keyword1, keyword2, keyword3"
                                   oninput="validateKeywords(this)">
                        </div>
                        <div class="mt-1 flex items-center justify-between">
                            <span class="text-xs text-gray-500">Comma separated keywords (recommended: 5-10)</span>
                            <span class="text-xs text-gray-400" id="keywordCount">{{ setting('meta_keywords') ? count(explode(',', setting('meta_keywords'))) : 0 }}</span>
                        </div>
                        @error('meta_keywords')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Open Graph -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                <i class="fas fa-share-alt text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Open Graph (Social Sharing)</h4>
                            <p class="text-xs text-gray-500">Customize how your content appears on social media</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="og_image" class="block text-sm font-medium text-gray-700 mb-1">
                                Open Graph Image
                            </label>
                            @if(setting('og_image'))
                                <div class="mb-3 p-3 bg-white rounded-lg border border-gray-200">
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ asset('storage/' . setting('og_image')) }}" 
                                             alt="OG Image" 
                                             class="max-h-20 rounded-lg border border-gray-200">
                                        <div>
                                            <p class="text-xs text-gray-500">Current image</p>
                                            <button type="button" class="text-xs text-red-600 hover:text-red-800" onclick="removeOgImage()">
                                                <i class="fas fa-times-circle mr-1"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                                <input type="file" name="og_image" id="og_image" accept="image/*"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('og_image') border-red-500 @enderror">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Recommended: 1200x630px (Max: 2MB)
                            </p>
                            @error('og_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="og_title" class="block text-sm font-medium text-gray-700 mb-1">
                                Open Graph Title
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-heading text-gray-400"></i>
                                </div>
                                <input type="text" name="og_title" id="og_title" 
                                       value="{{ old('og_title', setting('og_title', config('app.name'))) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('og_title') border-red-500 @enderror"
                                       placeholder="Social sharing title"
                                       oninput="validateOgTitle(this)">
                            </div>
                            <div class="mt-1 flex items-center justify-between">
                                <span class="text-xs text-gray-500">Recommended: 60-70 characters</span>
                                <span class="text-xs text-gray-400" id="og-title-count">{{ strlen(setting('og_title', config('app.name'))) }}</span>
                            </div>
                            @error('og_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="og_description" class="block text-sm font-medium text-gray-700 mb-1">
                            Open Graph Description
                        </label>
                        <div class="relative">
                            <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                <i class="fas fa-align-left text-gray-400"></i>
                            </div>
                            <textarea name="og_description" id="og_description" rows="2"
                                      class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('og_description') border-red-500 @enderror"
                                      placeholder="Social sharing description"
                                      oninput="validateOgDescription(this)">{{ old('og_description', setting('og_description')) }}</textarea>
                        </div>
                        <div class="mt-1 flex items-center justify-between">
                            <span class="text-xs text-gray-500">Recommended: 200-250 characters</span>
                            <span class="text-xs text-gray-400" id="og-description-count">{{ strlen(setting('og_description', '')) }}</span>
                        </div>
                        @error('og_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Twitter Cards -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-blue-400 flex items-center justify-center">
                                <i class="fab fa-twitter text-white"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Twitter Cards</h4>
                            <p class="text-xs text-gray-500">Configure Twitter card sharing settings</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="twitter_card_type" class="block text-sm font-medium text-gray-700 mb-1">
                                Card Type
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-card text-gray-400"></i>
                                </div>
                                <select name="twitter_card_type" id="twitter_card_type"
                                        class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('twitter_card_type') border-red-500 @enderror">
                                    <option value="summary" {{ old('twitter_card_type', setting('twitter_card_type', 'summary')) == 'summary' ? 'selected' : '' }}>
                                        Summary Card
                                    </option>
                                    <option value="summary_large_image" {{ old('twitter_card_type', setting('twitter_card_type', 'summary')) == 'summary_large_image' ? 'selected' : '' }}>
                                        Summary Card with Large Image
                                    </option>
                                    <option value="app" {{ old('twitter_card_type', setting('twitter_card_type', 'summary')) == 'app' ? 'selected' : '' }}>
                                        App Card
                                    </option>
                                </select>
                            </div>
                            @error('twitter_card_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="twitter_site" class="block text-sm font-medium text-gray-700 mb-1">
                                Twitter Site (@username)
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fab fa-twitter text-gray-400"></i>
                                </div>
                                <input type="text" name="twitter_site" id="twitter_site" 
                                       value="{{ old('twitter_site', setting('twitter_site')) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('twitter_site') border-red-500 @enderror"
                                       placeholder="@yourhandle">
                            </div>
                            @error('twitter_site')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Advanced SEO -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                <i class="fas fa-cog text-red-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Advanced SEO</h4>
                            <p class="text-xs text-gray-500">Technical SEO settings and configurations</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="google_analytics_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Google Analytics ID
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fab fa-google text-gray-400"></i>
                                    </div>
                                    <input type="text" name="google_analytics_id" id="google_analytics_id" 
                                           value="{{ old('google_analytics_id', setting('google_analytics_id')) }}"
                                           class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('google_analytics_id') border-red-500 @enderror"
                                           placeholder="UA-XXXXXXXXX-X">
                                </div>
                                @error('google_analytics_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="google_tag_manager_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Google Tag Manager ID
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-tags text-gray-400"></i>
                                    </div>
                                    <input type="text" name="google_tag_manager_id" id="google_tag_manager_id" 
                                           value="{{ old('google_tag_manager_id', setting('google_tag_manager_id')) }}"
                                           class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('google_tag_manager_id') border-red-500 @enderror"
                                           placeholder="GTM-XXXXXXX">
                                </div>
                                @error('google_tag_manager_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="verification_google" class="block text-sm font-medium text-gray-700 mb-1">
                                    Google Verification Code
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fab fa-google text-gray-400"></i>
                                    </div>
                                    <input type="text" name="verification_google" id="verification_google" 
                                           value="{{ old('verification_google', setting('verification_google')) }}"
                                           class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('verification_google') border-red-500 @enderror"
                                           placeholder="Google verification code">
                                </div>
                                @error('verification_google')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="verification_bing" class="block text-sm font-medium text-gray-700 mb-1">
                                    Bing Verification Code
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fab fa-microsoft text-gray-400"></i>
                                    </div>
                                    <input type="text" name="verification_bing" id="verification_bing" 
                                           value="{{ old('verification_bing', setting('verification_bing')) }}"
                                           class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('verification_bing') border-red-500 @enderror"
                                           placeholder="Bing verification code">
                                </div>
                                @error('verification_bing')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="robots_txt" class="block text-sm font-medium text-gray-700 mb-1">
                            Robots.txt Content
                        </label>
                        <div class="relative">
                            <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                <i class="fas fa-robot text-gray-400"></i>
                            </div>
                            <textarea name="robots_txt" id="robots_txt" rows="6"
                                      class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('robots_txt') border-red-500 @enderror font-mono text-sm"
                                      placeholder="User-agent: *&#10;Allow: /">{{ old('robots_txt', setting('robots_txt', "User-agent: *\nAllow: /\n\nSitemap: " . config('app.url') . "/sitemap.xml")) }}</textarea>
                        </div>
                        @error('robots_txt')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="mt-2 p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="text-xs text-blue-700">
                                <i class="fas fa-info-circle mr-1"></i>
                                <strong>Current Sitemap URL:</strong> 
                                <code class="bg-blue-100 px-2 py-1 rounded">{{ config('app.url') }}/sitemap.xml</code>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Schema Markup -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <i class="fas fa-code text-indigo-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Schema Markup</h4>
                            <p class="text-xs text-gray-500">Structured data for rich search results</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="schema_organization" class="block text-sm font-medium text-gray-700 mb-1">
                                Organization Schema
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-building text-gray-400"></i>
                                </div>
                                <select name="schema_organization" id="schema_organization"
                                        class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <option value="none" {{ old('schema_organization', setting('schema_organization', 'none')) == 'none' ? 'selected' : '' }}>
                                        None
                                    </option>
                                    <option value="Organization" {{ old('schema_organization', setting('schema_organization', 'none')) == 'Organization' ? 'selected' : '' }}>
                                        Organization
                                    </option>
                                    <option value="Corporation" {{ old('schema_organization', setting('schema_organization', 'none')) == 'Corporation' ? 'selected' : '' }}>
                                        Corporation
                                    </option>
                                    <option value="LocalBusiness" {{ old('schema_organization', setting('schema_organization', 'none')) == 'LocalBusiness' ? 'selected' : '' }}>
                                        Local Business
                                    </option>
                                    <option value="OnlineBusiness" {{ old('schema_organization', setting('schema_organization', 'none')) == 'OnlineBusiness' ? 'selected' : '' }}>
                                        Online Business
                                    </option>
                                </select>
                            </div>
                            @error('schema_organization')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="schema_logo" class="block text-sm font-medium text-gray-700 mb-1">
                                Schema Logo URL
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                                <input type="url" name="schema_logo" id="schema_logo" 
                                       value="{{ old('schema_logo', setting('schema_logo')) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('schema_logo') border-red-500 @enderror"
                                       placeholder="https://example.com/logo.png">
                            </div>
                            @error('schema_logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
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
                        <button type="button" onclick="previewSEO()" class="px-4 py-2 text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors">
                            <i class="fas fa-eye mr-2"></i> Preview
                        </button>
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
    // Initialize all validations
    validateMetaTitle(document.getElementById('meta_title'));
    validateMetaDescription(document.getElementById('meta_description'));
    validateKeywords(document.getElementById('meta_keywords'));
    validateOgTitle(document.getElementById('og_title'));
    validateOgDescription(document.getElementById('og_description'));
});

// Meta Title Validation
function validateMetaTitle(input) {
    const count = document.getElementById('meta-title-count');
    const status = document.getElementById('metaTitleStatus');
    const length = input.value.length;
    count.textContent = length;
    
    if (length < 30) {
        status.innerHTML = '<span class="text-yellow-600">⚠️ Too short (min: 30)</span>';
    } else if (length > 60) {
        status.innerHTML = '<span class="text-yellow-600">⚠️ Too long (max: 60)</span>';
    } else if (length >= 40 && length <= 55) {
        status.innerHTML = '<span class="text-green-600">✅ Perfect length</span>';
    } else {
        status.innerHTML = '<span class="text-blue-600">ℹ️ Good length</span>';
    }
}

// Meta Description Validation
function validateMetaDescription(input) {
    const count = document.getElementById('meta-description-count');
    const status = document.getElementById('metaDescriptionStatus');
    const length = input.value.length;
    count.textContent = length;
    
    if (length < 100) {
        status.innerHTML = '<span class="text-yellow-600">⚠️ Too short (min: 100)</span>';
    } else if (length > 160) {
        status.innerHTML = '<span class="text-yellow-600">⚠️ Too long (max: 160)</span>';
    } else if (length >= 140 && length <= 155) {
        status.innerHTML = '<span class="text-green-600">✅ Perfect length</span>';
    } else {
        status.innerHTML = '<span class="text-blue-600">ℹ️ Good length</span>';
    }
}

// Keywords Validation
function validateKeywords(input) {
    const count = document.getElementById('keywordCount');
    const keywords = input.value.split(',').filter(k => k.trim());
    count.textContent = keywords.length;
}

// OG Title Validation
function validateOgTitle(input) {
    const count = document.getElementById('og-title-count');
    const length = input.value.length;
    count.textContent = length;
}

// OG Description Validation
function validateOgDescription(input) {
    const count = document.getElementById('og-description-count');
    const length = input.value.length;
    count.textContent = length;
}

// Analyze SEO
function analyzeSEO() {
    const button = document.querySelector('[onclick="analyzeSEO()"]');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Analyzing...';
    button.disabled = true;
    
    setTimeout(() => {
        const score = Math.floor(Math.random() * 30) + 70; // Random score between 70-100
        document.getElementById('seoScore').textContent = score + '%';
        const bar = document.querySelector('.bg-green-500');
        bar.style.width = score + '%';
        button.innerHTML = originalText;
        button.disabled = false;
    }, 1500);
}

// Preview SEO
function previewSEO() {
    const metaTitle = document.getElementById('meta_title').value || 'No Title';
    const metaDesc = document.getElementById('meta_description').value || 'No Description';
    const siteName = '{{ config('app.name') }}';
    
    // Create preview modal
    const previewHTML = `
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" onclick="this.remove()">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-lg bg-white">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">SEO Preview</h3>
                    <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <p class="text-blue-600 text-sm hover:underline cursor-pointer">${metaTitle}</p>
                    <p class="text-green-700 text-xs">${siteName}</p>
                    <p class="text-gray-600 text-sm mt-2">${metaDesc}</p>
                </div>
                <div class="mt-4 flex justify-end">
                    <button onclick="this.closest('.fixed').remove()" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Close
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', previewHTML);
}

// Remove OG Image
function removeOgImage() {
    if (confirm('Are you sure you want to remove the Open Graph image?')) {
        fetch('{{ route("admin.settings.remove.og-image") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

// Reset form
function resetForm() {
    if (confirm('Are you sure you want to reset all fields to their current saved values?')) {
        location.reload();
    }
}
</script>
@endpush
@endsection