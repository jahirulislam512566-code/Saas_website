{{-- resources/views/admin/subscriptions/coupons.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Coupons')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.subscriptions.index') }}" class="text-gray-500 hover:text-gray-700">Subscriptions</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Coupons</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Coupons</h1>
            <p class="text-sm text-gray-500 mt-1">Manage discount coupons and promotions</p>
        </div>
        <button onclick="showAddCoupon()" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Add Coupon
        </button>
    </div>

    <!-- ===== STATS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Coupons</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Active</p>
            <p class="text-xl font-bold text-green-600">{{ $stats['active'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Used</p>
            <p class="text-xl font-bold text-blue-600">{{ $stats['used'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Discount</p>
            <p class="text-xl font-bold text-purple-600">${{ number_format($stats['total_discount'] ?? 0, 2) }}</p>
        </div>
    </div>

    <!-- ===== COUPONS TABLE ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Discount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Uses</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expires</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($coupons as $coupon)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg bg-primary-50 text-primary-700 font-mono text-sm font-medium">
                                    {{ $coupon->code }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                @if($coupon->discount_type === 'percentage')
                                    {{ $coupon->discount_value }}%
                                @else
                                    ${{ number_format($coupon->discount_value, 2) }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ ucfirst($coupon->discount_type) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $coupon->used_count ?? 0 }} / {{ $coupon->max_uses ?? '∞' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @if($coupon->expires_at)
                                    {{ $coupon->expires_at->format('M d, Y') }}
                                    @if($coupon->expires_at->isPast())
                                        <span class="text-xs text-red-600 block">(Expired)</span>
                                    @endif
                                @else
                                    <span class="text-gray-400">Never</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $coupon->is_active && (!$coupon->expires_at || !$coupon->expires_at->isPast()) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $coupon->is_active && (!$coupon->expires_at || !$coupon->expires_at->isPast()) ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    <button onclick="editCoupon('{{ $coupon->id }}')" 
                                            class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" 
                                            title="Edit">
                                        <i class="fas fa-pen text-sm"></i>
                                    </button>
                                    <button onclick="toggleCoupon('{{ $coupon->id }}')" 
                                            class="p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors" 
                                            title="{{ $coupon->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas {{ $coupon->is_active ? 'fa-pause' : 'fa-play' }} text-sm"></i>
                                    </button>
                                    <button onclick="deleteCoupon('{{ $coupon->id }}', '{{ $coupon->code }}')" 
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                            title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-ticket-alt text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-lg font-medium">No coupons found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $coupons->links() }}
        </div>
    </div>
</div>

<!-- Add/Edit Coupon Modal -->
<div x-data="{ show: false, editing: false, couponId: null }" 
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="show = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="coupon-form" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="coupon-modal-title">Add Coupon</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Coupon Code <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex space-x-2">
                                        <input type="text" name="code" id="coupon-code" required
                                               class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                               placeholder="SUMMER2024">
                                        <button type="button" onclick="generateCouponCode()" 
                                                class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                            <i class="fas fa-random"></i>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Discount Type
                                    </label>
                                    <select name="discount_type" id="discount-type" 
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="percentage">Percentage</option>
                                        <option value="fixed">Fixed Amount</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Discount Value <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="discount_value" id="discount-value" required
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                           placeholder="10" step="0.01" min="0">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Max Uses
                                    </label>
                                    <input type="number" name="max_uses" id="max-uses"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                           placeholder="Unlimited">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Expires At
                                    </label>
                                    <input type="datetime-local" name="expires_at" id="expires-at"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="is_active" value="1" checked
                                               class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">Active</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Coupon
                    </button>
                    <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== FORMS ===== -->
<form id="toggle-coupon-form" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<form id="delete-coupon-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    function generateCouponCode() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let code = '';
        for (let i = 0; i < 8; i++) {
            code += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('coupon-code').value = code;
    }

    function showAddCoupon() {
        const modal = document.querySelector('[x-data]').__x.$data;
        modal.show = true;
        modal.editing = false;
        modal.couponId = null;
        document.getElementById('coupon-modal-title').textContent = 'Add Coupon';
        document.getElementById('coupon-form').action = '{{ route("admin.subscriptions.coupons.store") }}';
        document.getElementById('coupon-form').querySelector('input[name="_method"]')?.remove();
        document.getElementById('coupon-code').value = '';
        document.getElementById('discount-type').value = 'percentage';
        document.getElementById('discount-value').value = '';
        document.getElementById('max-uses').value = '';
        document.getElementById('expires-at').value = '';
        document.querySelector('#coupon-form input[name="is_active"]').checked = true;
    }

    function editCoupon(couponId) {
        fetch(`/admin/subscriptions/coupons/${couponId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = document.querySelector('[x-data]').__x.$data;
                    modal.show = true;
                    modal.editing = true;
                    modal.couponId = couponId;
                    document.getElementById('coupon-modal-title').textContent = 'Edit Coupon';
                    document.getElementById('coupon-form').action = `/admin/subscriptions/coupons/${couponId}`;
                    
                    let methodInput = document.getElementById('coupon-form').querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        document.getElementById('coupon-form').appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';
                    
                    document.getElementById('coupon-code').value = data.data.code;
                    document.getElementById('discount-type').value = data.data.discount_type;
                    document.getElementById('discount-value').value = data.data.discount_value;
                    document.getElementById('max-uses').value = data.data.max_uses || '';
                    document.getElementById('expires-at').value = data.data.expires_at ? data.data.expires_at.replace(' ', 'T') : '';
                    document.querySelector('#coupon-form input[name="is_active"]').checked = data.data.is_active;
                }
            });
    }

    function toggleCoupon(couponId) {
        const form = document.getElementById('toggle-coupon-form');
        form.action = `/admin/subscriptions/coupons/${couponId}/toggle`;
        form.submit();
    }

    function deleteCoupon(couponId, code) {
        if (confirm(`Delete coupon "${code}"?`)) {
            const form = document.getElementById('delete-coupon-form');
            form.action = `/admin/subscriptions/coupons/${couponId}`;
            form.submit();
        }
    }
</script>
@endpush
@endsection