{{-- resources/views/admin/websites/publish.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Publish Website')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.websites.index') }}" class="text-gray-500 hover:text-gray-700">Websites</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-700">{{ $website->name }}</span>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Publish</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Publish Website</h2>
            <p class="text-sm text-gray-500 mt-1">Review and publish your website to make it live</p>
        </div>
        
        <div class="p-6">
            <!-- Website Preview -->
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-900 mb-3">Website Preview</h4>
                <div class="bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                    <div class="h-48 bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center">
                        @if($website->screenshot)
                            <img src="{{ Storage::disk('public')->url($website->screenshot) }}" 
                                 alt="{{ $website->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="text-center">
                                <i class="fas fa-globe text-4xl text-primary-400 mb-2 block"></i>
                                <p class="text-gray-600 font-medium">{{ $website->name }}</p>
                                <p class="text-sm text-gray-400">{{ $website->domain ?? 'No domain' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Publishing Checklist -->
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-900 mb-3">Publishing Checklist</h4>
                <div class="space-y-2">
                    <div class="flex items-center p-3 bg-green-50 rounded-lg border border-green-200">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-green-800">Website Name</p>
                            <p class="text-xs text-green-600">{{ $website->name }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-3 {{ $website->domain ? 'bg-green-50 border-green-200' : 'bg-yellow-50 border-yellow-200' }} rounded-lg border">
                        <i class="fas {{ $website->domain ? 'fa-check-circle text-green-500' : 'fa-exclamation-triangle text-yellow-500' }} mr-3"></i>
                        <div>
                            <p class="text-sm font-medium {{ $website->domain ? 'text-green-800' : 'text-yellow-800' }}">Domain</p>
                            <p class="text-xs {{ $website->domain ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ $website->domain ?? 'No domain configured' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-3 {{ $website->template_id ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }} rounded-lg border">
                        <i class="fas {{ $website->template_id ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500' }} mr-3"></i>
                        <div>
                            <p class="text-sm font-medium {{ $website->template_id ? 'text-green-800' : 'text-red-800' }}">Template</p>
                            <p class="text-xs {{ $website->template_id ? 'text-green-600' : 'text-red-600' }}">
                                {{ $website->template->name ?? 'No template selected' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-3 {{ $website->pages()->count() > 0 ? 'bg-green-50 border-green-200' : 'bg-yellow-50 border-yellow-200' }} rounded-lg border">
                        <i class="fas {{ $website->pages()->count() > 0 ? 'fa-check-circle text-green-500' : 'fa-exclamation-triangle text-yellow-500' }} mr-3"></i>
                        <div>
                            <p class="text-sm font-medium {{ $website->pages()->count() > 0 ? 'text-green-800' : 'text-yellow-800' }}">Pages</p>
                            <p class="text-xs {{ $website->pages()->count() > 0 ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ $website->pages()->count() }} pages created
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Publish Confirmation -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                    <div>
                        <p class="text-sm text-blue-800 font-medium">Ready to publish?</p>
                        <p class="text-xs text-blue-600 mt-1">
                            Publishing will make your website live at 
                            <strong>{{ $website->domain ?? 'its custom domain' }}</strong>. 
                            All changes will be visible to the public.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.websites.show', $website) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <form action="{{ route('admin.websites.publish.store', $website) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-rocket mr-2"></i> Publish Website
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection