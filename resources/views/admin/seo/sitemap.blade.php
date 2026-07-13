{{-- resources/views/admin/seo/sitemap.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Sitemap Management')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.seo.dashboard') }}" class="text-gray-500 hover:text-gray-700">SEO</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Sitemap</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Sitemap Management</h1>
            <p class="text-sm text-gray-500 mt-1">Generate and manage your XML sitemap</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('sitemap.xml') }}" target="_blank" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-external-link-alt mr-2"></i> View Sitemap
            </a>
            <button onclick="generateSitemap()" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-sync mr-2"></i> Generate
            </button>
        </div>
    </div>

    <!-- ===== STATS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total URLs</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Pages</p>
            <p class="text-xl font-bold text-blue-600">{{ $stats['pages'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Posts</p>
            <p class="text-xl font-bold text-green-600">{{ $stats['posts'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Last Generated</p>
            <p class="text-xl font-bold text-purple-600">{{ $stats['last_generated'] ?? 'Never' }}</p>
        </div>
    </div>

    <!-- ===== SITEMAP GENERATION ===== -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-medium text-gray-900 mb-4">Sitemap Settings</h3>
        
        <form action="{{ route('admin.seo.sitemap.generate') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Include Pages
                    </label>
                    <select name="include_pages" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        <option value="all">All Pages</option>
                        <option value="published">Published Only</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Include Posts
                    </label>
                    <select name="include_posts" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        <option value="all">All Posts</option>
                        <option value="published">Published Only</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-sync mr-2"></i> Generate Sitemap
            </button>
        </form>
    </div>

    <!-- ===== SITEMAP PREVIEW ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-900">Sitemap Preview</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">URL</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Change Frequency</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Modified</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sitemapUrls ?? [] as $url)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $url->url }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $url->priority ?? '0.5' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $url->changefreq ?? 'weekly' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $url->lastmod ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-sitemap text-3xl text-gray-300 mb-3 block"></i>
                                <p>No sitemap generated yet</p>
                                <p class="text-xs mt-1">Generate a sitemap to see the preview</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function generateSitemap() {
        if (confirm('Generate a new sitemap?')) {
            fetch('{{ route("admin.seo.sitemap.generate") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(() => window.location.reload());
        }
    }
</script>
@endpush
@endsection