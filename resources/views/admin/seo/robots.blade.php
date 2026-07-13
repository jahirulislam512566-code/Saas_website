{{-- resources/views/admin/seo/robots.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Robots.txt Management')

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
            <span class="text-gray-500">Robots.txt</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Robots.txt Management</h1>
            <p class="text-sm text-gray-500 mt-1">Control search engine crawler access</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ url('/robots.txt') }}" target="_blank" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-external-link-alt mr-2"></i> View Robots.txt
            </a>
            <button onclick="resetRobots()" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                <i class="fas fa-undo mr-2"></i> Reset to Default
            </button>
        </div>
    </div>

    <!-- ===== EDITOR ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-900">Robots.txt Editor</h3>
            <p class="text-xs text-gray-500 mt-1">Edit the robots.txt file to control crawler access</p>
        </div>
        
        <form action="{{ route('admin.seo.robots.update') }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">User-agent</label>
                    <input type="text" name="user_agent" value="{{ $robots['user_agent'] ?? '*' }}" 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Disallow</label>
                    <input type="text" name="disallow" value="{{ $robots['disallow'] ?? '' }}" 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                           placeholder="e.g., /admin/, /private/">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Allow</label>
                    <input type="text" name="allow" value="{{ $robots['allow'] ?? '' }}" 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                           placeholder="e.g., /public/, /images/">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sitemap</label>
                    <input type="url" name="sitemap" value="{{ $robots['sitemap'] ?? url('/sitemap.xml') }}" 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Crawl Delay</label>
                    <input type="number" name="crawl_delay" value="{{ $robots['crawl_delay'] ?? 5 }}" 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                           placeholder="5">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Additional Directives</label>
                    <textarea name="additional" rows="5" 
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 font-mono text-sm"
                              placeholder="Add custom directives here...">{{ $robots['additional'] ?? '' }}</textarea>
                </div>
                
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                        Changes will be applied immediately to robots.txt
                    </div>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="fas fa-save mr-2"></i> Update Robots.txt
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- ===== PREVIEW ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-900">Preview</h3>
        </div>
        <div class="p-6 bg-gray-50">
            <pre class="text-sm font-mono text-gray-700 whitespace-pre-wrap" id="robots-preview">
User-agent: {{ $robots['user_agent'] ?? '*' }}
@if(!empty($robots['disallow']))
Disallow: {{ $robots['disallow'] }}
@endif
@if(!empty($robots['allow']))
Allow: {{ $robots['allow'] }}
@endif
@if(!empty($robots['crawl_delay']))
Crawl-delay: {{ $robots['crawl_delay'] }}
@endif
Sitemap: {{ $robots['sitemap'] ?? url('/sitemap.xml') }}
@if(!empty($robots['additional']))
{{ $robots['additional'] }}
@endif
            </pre>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function resetRobots() {
        if (confirm('Reset to default robots.txt?')) {
            fetch('{{ route("admin.seo.robots.reset") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(() => window.location.reload());
        }
    }

    // Auto-update preview on input change
    document.querySelectorAll('#robots-form input, #robots-form textarea').forEach(el => {
        el.addEventListener('input', updatePreview);
    });

    function updatePreview() {
        const userAgent = document.querySelector('input[name="user_agent"]').value || '*';
        const disallow = document.querySelector('input[name="disallow"]').value;
        const allow = document.querySelector('input[name="allow"]').value;
        const crawlDelay = document.querySelector('input[name="crawl_delay"]').value;
        const sitemap = document.querySelector('input[name="sitemap"]').value || '{{ url('/sitemap.xml') }}';
        const additional = document.querySelector('textarea[name="additional"]').value;

        let preview = `User-agent: ${userAgent}\n`;
        if (disallow) preview += `Disallow: ${disallow}\n`;
        if (allow) preview += `Allow: ${allow}\n`;
        if (crawlDelay) preview += `Crawl-delay: ${crawlDelay}\n`;
        preview += `Sitemap: ${sitemap}\n`;
        if (additional) preview += additional;

        document.getElementById('robots-preview').textContent = preview;
    }
</script>
@endpush
@endsection