{{-- resources/views/admin/domains/show.blade.php --}}
@extends('admin.layouts.admin')

@section('title', $domain->domain)

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
            <span class="text-gray-700">{{ $domain->domain }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-primary-100 text-primary-600 flex items-center justify-center">
                <i class="fas fa-globe text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $domain->domain }}</h1>
                <div class="flex items-center space-x-3 mt-1">
                    <span class="text-sm text-gray-500">Website: {{ $domain->website->name ?? 'N/A' }}</span>
                    <span class="text-gray-300">|</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $domain->status == 'verified' ? 'bg-green-100 text-green-800' : 
                           ($domain->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($domain->status) }}
                    </span>
                    @if($domain->is_primary)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            <i class="fas fa-star mr-1"></i> Primary
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            @if($domain->status !== 'verified')
                <button onclick="verifyDomain('{{ $domain->id }}')" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-check mr-2"></i> Verify Domain
                </button>
            @endif
            <a href="{{ route('admin.domains.edit', $domain) }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-pen mr-2"></i> Edit
            </a>
            <a href="{{ route('admin.domains.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <!-- ===== DOMAIN DETAILS ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- DNS Records -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">DNS Records</h3>
                <div class="space-y-3">
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div>
                                <p class="text-xs text-gray-500">Type</p>
                                <p class="font-medium text-gray-900">A Record</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Host</p>
                                <p class="font-medium text-gray-900">@</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Value</p>
                                <p class="font-medium text-gray-900">{{ $ipAddress ?? '192.168.1.1' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div>
                                <p class="text-xs text-gray-500">Type</p>
                                <p class="font-medium text-gray-900">CNAME</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Host</p>
                                <p class="font-medium text-gray-900">www</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Value</p>
                                <p class="font-medium text-gray-900">{{ $domain->domain }}</p>
                            </div>
                        </div>
                    </div>
                    @if($domain->ssl_enabled)
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <div class="grid grid-cols-3 gap-4 text-sm">
                                <div>
                                    <p class="text-xs text-gray-500">Type</p>
                                    <p class="font-medium text-gray-900">TXT</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Host</p>
                                    <p class="font-medium text-gray-900">_acme-challenge</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Value</p>
                                    <p class="font-medium text-gray-900">SSL verification value</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- SSL Certificate -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">SSL Certificate</h3>
                @if($domain->ssl_enabled)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-lock text-green-500 mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-green-800">SSL Certificate Active</p>
                                <p class="text-xs text-green-600">Valid until: {{ $domain->ssl_expires_at ? $domain->ssl_expires_at->format('M d, Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    @if($domain->ssl_expires_at && $domain->ssl_expires_at->diffInDays(now()) < 30)
                        <div class="mt-3 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                            <p class="text-sm text-yellow-800">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                SSL certificate will expire in {{ $domain->ssl_expires_at->diffInDays(now()) }} days
                            </p>
                        </div>
                    @endif
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-unlock text-gray-400 mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-700">SSL Certificate Disabled</p>
                                <p class="text-xs text-gray-500">Enable SSL for secure connections</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Domain Information -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Domain Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs text-gray-500">Domain</dt>
                        <dd class="text-sm text-gray-900">{{ $domain->domain }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Website</dt>
                        <dd class="text-sm text-gray-900">{{ $domain->website->name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Status</dt>
                        <dd class="text-sm text-gray-900">{{ ucfirst($domain->status) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Primary</dt>
                        <dd class="text-sm text-gray-900">{{ $domain->is_primary ? 'Yes' : 'No' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">SSL</dt>
                        <dd class="text-sm text-gray-900">{{ $domain->ssl_enabled ? 'Enabled' : 'Disabled' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Created</dt>
                        <dd class="text-sm text-gray-900">{{ $domain->created_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Last Updated</dt>
                        <dd class="text-sm text-gray-900">{{ $domain->updated_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    @if($domain->status !== 'verified')
                        <button onclick="verifyDomain('{{ $domain->id }}')" 
                                class="block w-full text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-check mr-2"></i> Verify Domain
                        </button>
                    @endif
                    @if(!$domain->is_primary)
                        <button onclick="setPrimary('{{ $domain->id }}')" 
                                class="block w-full text-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fas fa-star mr-2"></i> Set as Primary
                        </button>
                    @endif
                    <a href="{{ route('admin.domains.edit', $domain) }}" 
                       class="block w-full text-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="fas fa-pen mr-2"></i> Edit Domain
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="verify-domain-form" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<form id="set-primary-form" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

@push('scripts')
<script>
    function verifyDomain(domainId) {
        if (confirm('Verify this domain?')) {
            const form = document.getElementById('verify-domain-form');
            form.action = `/admin/domains/${domainId}/verify`;
            form.submit();
        }
    }

    function setPrimary(domainId) {
        if (confirm('Set this as primary domain?')) {
            const form = document.getElementById('set-primary-form');
            form.action = `/admin/domains/${domainId}/primary`;
            form.submit();
        }
    }
</script>
@endpush
@endsection