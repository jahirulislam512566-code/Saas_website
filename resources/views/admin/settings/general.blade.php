@extends('admin.layouts.admin')

@section('title', 'General Settings')

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
            <span class="text-gray-500">General</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Settings Navigation -->
    <div class="mb-6">
        <div class="flex flex-wrap gap-2 bg-white rounded-xl shadow-sm p-3">
            <a href="{{ route('admin.settings.general') }}" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium">
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
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
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
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
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

    <!-- Main Form -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-sliders-h text-primary-600 mr-2"></i>
                        General Settings
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Configure your application's core settings and preferences</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs text-gray-400">
                        <i class="fas fa-clock mr-1"></i>
                        Last updated: {{ setting('updated_at') ? \Carbon\Carbon::parse(setting('updated_at'))->diffForHumans() : 'Never' }}
                    </span>
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.settings.update.general') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-8">
                <!-- Application Information -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                                <i class="fas fa-info-circle text-primary-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Application Information</h4>
                            <p class="text-xs text-gray-500">Basic information about your application</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="app_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Application Name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                                <input type="text" name="app_name" id="app_name" 
                                       value="{{ old('app_name', setting('app_name', config('app.name'))) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('app_name') border-red-500 @enderror"
                                       placeholder="Enter application name"
                                       required>
                            </div>
                            @error('app_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">This name will appear in the browser tab and email notifications</p>
                        </div>
                        
                        <div>
                            <label for="app_url" class="block text-sm font-medium text-gray-700 mb-1">
                                Application URL <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-link text-gray-400"></i>
                                </div>
                                <input type="url" name="app_url" id="app_url" 
                                       value="{{ old('app_url', setting('app_url', config('app.url'))) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('app_url') border-red-500 @enderror"
                                       placeholder="https://example.com"
                                       required>
                            </div>
                            @error('app_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">The base URL of your application</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="app_description" class="block text-sm font-medium text-gray-700 mb-1">
                            Application Description
                        </label>
                        <div class="relative">
                            <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                <i class="fas fa-align-left text-gray-400"></i>
                            </div>
                            <textarea name="app_description" id="app_description" rows="3"
                                      class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('app_description') border-red-500 @enderror"
                                      placeholder="Describe your application briefly">{{ old('app_description', setting('app_description')) }}</textarea>
                        </div>
                        @error('app_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="flex justify-between mt-1">
                            <span class="text-xs text-gray-500">Brief description of your application</span>
                            <span class="text-xs text-gray-400" id="charCount">0/500 characters</span>
                        </div>
                    </div>
                </div>
                
                <!-- Branding -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                <i class="fas fa-palette text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Branding</h4>
                            <p class="text-xs text-gray-500">Customize your application's visual identity</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">
                                Logo
                            </label>
                            @if(setting('logo'))
                                <div class="mb-3 p-4 bg-white rounded-lg border border-gray-200">
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ asset('storage/' . setting('logo')) }}" 
                                             alt="Logo" 
                                             class="h-12 w-auto object-contain">
                                        <div>
                                            <p class="text-xs text-gray-500">Current logo</p>
                                            <button type="button" class="text-xs text-red-600 hover:text-red-800" onclick="removeLogo()">
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
                                <input type="file" name="logo" id="logo" accept="image/*"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('logo') border-red-500 @enderror">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Recommended: 200x50px PNG with transparent background (Max: 2MB)
                            </p>
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="favicon" class="block text-sm font-medium text-gray-700 mb-1">
                                Favicon
                            </label>
                            @if(setting('favicon'))
                                <div class="mb-3 p-4 bg-white rounded-lg border border-gray-200">
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ asset('storage/' . setting('favicon')) }}" 
                                             alt="Favicon" 
                                             class="w-8 h-8">
                                        <div>
                                            <p class="text-xs text-gray-500">Current favicon</p>
                                            <button type="button" class="text-xs text-red-600 hover:text-red-800" onclick="removeFavicon()">
                                                <i class="fas fa-times-circle mr-1"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-shield-alt text-gray-400"></i>
                                </div>
                                <input type="file" name="favicon" id="favicon" accept="image/*"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('favicon') border-red-500 @enderror">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Recommended: 32x32px ICO or PNG (Max: 500KB)
                            </p>
                            @error('favicon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Localization -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-globe text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Localization</h4>
                            <p class="text-xs text-gray-500">Language and regional settings</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="timezone" class="block text-sm font-medium text-gray-700 mb-1">
                                Timezone <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-clock text-gray-400"></i>
                                </div>
                                <select name="timezone" id="timezone" required
                                        class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('timezone') border-red-500 @enderror">
                                    @foreach($timezones ?? [] as $timezone)
                                        <option value="{{ $timezone }}" 
                                            {{ old('timezone', setting('timezone', 'UTC')) == $timezone ? 'selected' : '' }}>
                                            {{ $timezone }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('timezone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="locale" class="block text-sm font-medium text-gray-700 mb-1">
                                Default Language <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-language text-gray-400"></i>
                                </div>
                                <select name="locale" id="locale" required
                                        class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('locale') border-red-500 @enderror">
                                    <option value="en" {{ old('locale', setting('locale', 'en')) == 'en' ? 'selected' : '' }}>🇺🇸 English</option>
                                    <option value="fr" {{ old('locale', setting('locale', 'en')) == 'fr' ? 'selected' : '' }}>🇫🇷 French</option>
                                    <option value="es" {{ old('locale', setting('locale', 'en')) == 'es' ? 'selected' : '' }}>🇪🇸 Spanish</option>
                                    <option value="de" {{ old('locale', setting('locale', 'en')) == 'de' ? 'selected' : '' }}>🇩🇪 German</option>
                                    <option value="it" {{ old('locale', setting('locale', 'en')) == 'it' ? 'selected' : '' }}>🇮🇹 Italian</option>
                                    <option value="pt" {{ old('locale', setting('locale', 'en')) == 'pt' ? 'selected' : '' }}>🇵🇹 Portuguese</option>
                                    <option value="ja" {{ old('locale', setting('locale', 'en')) == 'ja' ? 'selected' : '' }}>🇯🇵 Japanese</option>
                                    <option value="zh" {{ old('locale', setting('locale', 'en')) == 'zh' ? 'selected' : '' }}>🇨🇳 Chinese</option>
                                    <option value="ar" {{ old('locale', setting('locale', 'en')) == 'ar' ? 'selected' : '' }}>🇸🇦 Arabic</option>
                                    <option value="ru" {{ old('locale', setting('locale', 'en')) == 'ru' ? 'selected' : '' }}>🇷🇺 Russian</option>
                                    <option value="hi" {{ old('locale', setting('locale', 'en')) == 'hi' ? 'selected' : '' }}>🇮🇳 Hindi</option>
                                </select>
                            </div>
                            @error('locale')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label for="date_format" class="block text-sm font-medium text-gray-700 mb-1">
                                Date Format <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                                <select name="date_format" id="date_format" required
                                        class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('date_format') border-red-500 @enderror">
                                    <option value="M d, Y" {{ old('date_format', setting('date_format', 'M d, Y')) == 'M d, Y' ? 'selected' : '' }}>Jan 15, 2024</option>
                                    <option value="d/m/Y" {{ old('date_format', setting('date_format', 'M d, Y')) == 'd/m/Y' ? 'selected' : '' }}>15/01/2024</option>
                                    <option value="m/d/Y" {{ old('date_format', setting('date_format', 'M d, Y')) == 'm/d/Y' ? 'selected' : '' }}>01/15/2024</option>
                                    <option value="Y-m-d" {{ old('date_format', setting('date_format', 'M d, Y')) == 'Y-m-d' ? 'selected' : '' }}>2024-01-15</option>
                                    <option value="d M Y" {{ old('date_format', setting('date_format', 'M d, Y')) == 'd M Y' ? 'selected' : '' }}>15 Jan 2024</option>
                                </select>
                            </div>
                            @error('date_format')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="time_format" class="block text-sm font-medium text-gray-700 mb-1">
                                Time Format <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-clock text-gray-400"></i>
                                </div>
                                <select name="time_format" id="time_format" required
                                        class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('time_format') border-red-500 @enderror">
                                    <option value="g:i A" {{ old('time_format', setting('time_format', 'g:i A')) == 'g:i A' ? 'selected' : '' }}>3:30 PM (12-hour)</option>
                                    <option value="H:i" {{ old('time_format', setting('time_format', 'g:i A')) == 'H:i' ? 'selected' : '' }}>15:30 (24-hour)</option>
                                </select>
                            </div>
                            @error('time_format')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Currency Settings -->
                <div class="bg-gray-50 rounded-lg p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                <i class="fas fa-money-bill-wave text-yellow-600"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Currency Settings</h4>
                            <p class="text-xs text-gray-500">Default currency and formatting</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">
                                Currency <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-dollar-sign text-gray-400"></i>
                                </div>
                                <select name="currency" id="currency" required
                                        class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('currency') border-red-500 @enderror">
                                    <option value="USD" {{ old('currency', setting('currency', 'USD')) == 'USD' ? 'selected' : '' }}>$ USD - US Dollar</option>
                                    <option value="EUR" {{ old('currency', setting('currency', 'USD')) == 'EUR' ? 'selected' : '' }}>€ EUR - Euro</option>
                                    <option value="GBP" {{ old('currency', setting('currency', 'USD')) == 'GBP' ? 'selected' : '' }}>£ GBP - British Pound</option>
                                    <option value="CAD" {{ old('currency', setting('currency', 'USD')) == 'CAD' ? 'selected' : '' }}>C$ CAD - Canadian Dollar</option>
                                    <option value="AUD" {{ old('currency', setting('currency', 'USD')) == 'AUD' ? 'selected' : '' }}>A$ AUD - Australian Dollar</option>
                                    <option value="INR" {{ old('currency', setting('currency', 'USD')) == 'INR' ? 'selected' : '' }}>₹ INR - Indian Rupee</option>
                                    <option value="JPY" {{ old('currency', setting('currency', 'USD')) == 'JPY' ? 'selected' : '' }}>¥ JPY - Japanese Yen</option>
                                    <option value="CNY" {{ old('currency', setting('currency', 'USD')) == 'CNY' ? 'selected' : '' }}>¥ CNY - Chinese Yuan</option>
                                    <option value="BRL" {{ old('currency', setting('currency', 'USD')) == 'BRL' ? 'selected' : '' }}>R$ BRL - Brazilian Real</option>
                                </select>
                            </div>
                            @error('currency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="currency_symbol" class="block text-sm font-medium text-gray-700 mb-1">
                                Currency Symbol <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                                <input type="text" name="currency_symbol" id="currency_symbol" 
                                       value="{{ old('currency_symbol', setting('currency_symbol', '$')) }}"
                                       class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('currency_symbol') border-red-500 @enderror"
                                       placeholder="$"
                                       required>
                            </div>
                            @error('currency_symbol')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Symbol to display before amounts</p>
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
    // Character counter for description
    const description = document.getElementById('app_description');
    const charCount = document.getElementById('charCount');
    
    if (description && charCount) {
        description.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = `${length}/500 characters`;
            if (length > 500) {
                charCount.classList.add('text-red-600');
            } else {
                charCount.classList.remove('text-red-600');
            }
        });
        
        // Trigger initial count
        description.dispatchEvent(new Event('input'));
    }
    
    // Preview logo upload
    const logoInput = document.getElementById('logo');
    if (logoInput) {
        logoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Show preview if needed
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
});

function removeLogo() {
    if (confirm('Are you sure you want to remove the logo?')) {
        // Implement logo removal via AJAX or form submission
        fetch('{{ route("admin.settings.remove.logo") }}', {
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

function removeFavicon() {
    if (confirm('Are you sure you want to remove the favicon?')) {
        fetch('{{ route("admin.settings.remove.favicon") }}', {
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

function resetForm() {
    if (confirm('Are you sure you want to reset all fields to their current saved values?')) {
        location.reload();
    }
}
</script>
@endpush
@endsection