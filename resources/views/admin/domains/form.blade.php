{{-- resources/views/admin/domains/form.blade.php --}}
@extends('admin.layouts.admin')

@section('title', isset($domain) ? 'Edit Domain' : 'Add Domain')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.domains.index') }}" class="text-gray-500 hover:text-gray-700">Domains</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">{{ isset($domain) ? 'Edit' : 'Add' }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">{{ isset($domain) ? 'Edit Domain' : 'Add New Domain' }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ isset($domain) ? 'Update domain settings' : 'Add a new domain' }}</p>
        </div>
        
        <form action="{{ isset($domain) ? route('admin.domains.update', $domain) : route('admin.domains.store') }}" 
              method="POST" class="p-6">
            @csrf
            @if(isset($domain))
                @method('PUT')
            @endif
            
            <div class="space-y-6">
                <!-- Domain Details -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Domain Details</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="domain" class="block text-sm font-medium text-gray-700 mb-1">
                                Domain Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="domain" id="domain" 
                                   value="{{ old('domain', $domain->domain ?? '') }}" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('domain') border-red-500 @enderror"
                                   placeholder="example.com">
                            @error('domain')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="website_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Website <span class="text-red-500">*</span>
                            </label>
                            <select name="website_id" id="website_id" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('website_id') border-red-500 @enderror">
                                <option value="">Select Website</option>
                                @foreach($websites as $website)
                                    <option value="{{ $website->id }}" {{ old('website_id', $domain->website_id ?? '') == $website->id ? 'selected' : '' }}>
                                        {{ $website->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('website_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SSL Settings -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">SSL Settings</h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="ssl_enabled" value="1" 
                                       {{ old('ssl_enabled', $domain->ssl_enabled ?? false) ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Enable SSL Certificate</span>
                            </label>
                        </div>
                        
                        <div>
                            <label for="ssl_expires_at" class="block text-sm font-medium text-gray-700 mb-1">
                                SSL Expiry Date
                            </label>
                            <input type="date" name="ssl_expires_at" id="ssl_expires_at" 
                                   value="{{ old('ssl_expires_at', isset($domain) && $domain->ssl_expires_at ? $domain->ssl_expires_at->format('Y-m-d') : '') }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('ssl_expires_at') border-red-500 @enderror">
                            @error('ssl_expires_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Status</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('status') border-red-500 @enderror">
                                <option value="pending" {{ old('status', $domain->status ?? 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="verified" {{ old('status', $domain->status ?? '') == 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="failed" {{ old('status', $domain->status ?? '') == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="flex items-center pt-6">
                                <input type="checkbox" name="is_primary" value="1" 
                                       {{ old('is_primary', $domain->is_primary ?? false) ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Set as primary domain</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.domains.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="fas fa-save mr-2"></i> {{ isset($domain) ? 'Update Domain' : 'Add Domain' }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    @if(isset($domain))
        <div class="mt-6 bg-white rounded-xl shadow-sm overflow-hidden border-2 border-red-200">
            <div class="px-6 py-4 border-b border-red-200 bg-red-50">
                <h3 class="text-sm font-bold text-red-700 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Danger Zone
                </h3>
            </div>
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Delete this domain</p>
                        <p class="text-xs text-gray-500">This action cannot be undone. All domain data will be permanently removed.</p>
                    </div>
                    <form action="{{ route('admin.domains.destroy', $domain) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this domain? This action cannot be undone!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                            <i class="fas fa-trash mr-2"></i> Delete Domain
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection