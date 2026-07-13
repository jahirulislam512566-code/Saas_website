{{-- resources/views/admin/websites/domains.blade.php --}}
@extends('admin.layouts.admin')

@section('title', $website->name . ' - Domains')

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
            <a href="{{ route('admin.websites.show', $website) }}" class="text-gray-500 hover:text-gray-700">{{ $website->name }}</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Domains</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Domains - {{ $website->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">Manage domains for this website</p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="showAddDomain()" 
                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Add Domain
            </button>
            <a href="{{ route('admin.websites.show', $website) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <!-- Domains List -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-medium text-gray-900">Domains ({{ $domains->count() }})</h3>
            <span class="text-xs text-gray-500">Primary domain is highlighted</span>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($domains as $domain)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full {{ $domain->is_primary ? 'bg-primary-100 text-primary-600' : 'bg-gray-100 text-gray-600' }} flex items-center justify-center">
                            <i class="fas fa-globe"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 {{ $domain->is_primary ? 'text-primary-700' : '' }}">
                                {{ $domain->domain }}
                                @if($domain->is_primary)
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary-100 text-primary-800">
                                        Primary
                                    </span>
                                @endif
                            </p>
                            <div class="flex items-center space-x-2 text-xs">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                    {{ $domain->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $domain->is_verified ? 'Verified' : 'Pending Verification' }}
                                </span>
                                <span class="text-gray-400">Added {{ $domain->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if(!$domain->is_verified)
                            <button onclick="verifyDomain('{{ $domain->id }}')" 
                                    class="px-3 py-1 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Verify
                            </button>
                        @endif
                        @if(!$domain->is_primary && $domain->is_verified)
                            <form action="{{ route('admin.websites.domains.primary', [$website, $domain]) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 text-sm bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                    Set Primary
                                </button>
                            </form>
                        @endif
                        @if(!$domain->is_primary)
                            <form action="{{ route('admin.websites.domains.destroy', [$website, $domain]) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Remove this domain?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-globe text-3xl text-gray-300 mb-3 block"></i>
                    <p>No domains added yet</p>
                    <p class="text-xs mt-1">Add a domain to connect your website</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- DNS Settings -->
    @if($domains->isNotEmpty())
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">DNS Settings</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-2">To connect your domain, add the following DNS records:</p>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <p class="text-xs text-gray-500">Type</p>
                        <p class="text-sm font-medium text-gray-900">A Record</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <p class="text-xs text-gray-500">Host</p>
                        <p class="text-sm font-medium text-gray-900">@</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <p class="text-xs text-gray-500">Value</p>
                        <p class="text-sm font-medium text-gray-900">{{ $ipAddress ?? '192.168.1.1' }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <p class="text-xs text-gray-500">TTL</p>
                        <p class="text-sm font-medium text-gray-900">3600</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Add Domain Modal -->
<div x-data="{ show: false }" 
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="show = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('admin.websites.domains.store', $website) }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Add Domain</h3>
                            <div class="mt-4">
                                <label for="domain" class="block text-sm font-medium text-gray-700 mb-1">
                                    Domain Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="domain" id="domain" required
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                       placeholder="example.com">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Add Domain
                    </button>
                    <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showAddDomain() {
        document.querySelector('[x-data]').__x.$data.show = true;
    }

    function verifyDomain(domainId) {
        if (confirm('Verify this domain?')) {
            fetch(`/admin/websites/domains/${domainId}/verify`, {
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