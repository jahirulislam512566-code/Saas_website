{{-- resources/views/admin/subscriptions/discounts.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Discounts')

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
            <span class="text-gray-500">Discounts</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Discounts</h1>
            <p class="text-sm text-gray-500 mt-1">Manage subscription discounts and promotions</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.subscriptions.discounts.export') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-download mr-2"></i> Export
            </a>
            <button onclick="showAddDiscount()" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Add Discount
            </button>
        </div>
    </div>

    <!-- ===== STATS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Discounts</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Active</p>
            <p class="text-xl font-bold text-green-600">{{ $stats['active'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Total Savings</p>
            <p class="text-xl font-bold text-blue-600">${{ number_format($stats['total_savings'] ?? 0, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-sm text-gray-500">Applied</p>
            <p class="text-xl font-bold text-purple-600">{{ $stats['applied'] ?? 0 }}</p>
        </div>
    </div>

    <!-- ===== DISCOUNTS TABLE ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Discount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Applied</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($discounts as $discount)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $discount->name }}</p>
                                <p class="text-xs text-gray-500">ID: #{{ $discount->id }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                @if($discount->type === 'percentage')
                                    {{ $discount->value }}% Off
                                @else
                                    ${{ number_format($discount->value, 2) }} Off
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $discount->plan->name ?? 'All Plans' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $discount->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $discount->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $discount->applied_count ?? 0 }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $discount->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    <button onclick="editDiscount('{{ $discount->id }}')" 
                                            class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" 
                                            title="Edit">
                                        <i class="fas fa-pen text-sm"></i>
                                    </button>
                                    <button onclick="toggleDiscount('{{ $discount->id }}')" 
                                            class="p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors" 
                                            title="{{ $discount->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas {{ $discount->is_active ? 'fa-pause' : 'fa-play' }} text-sm"></i>
                                    </button>
                                    <button onclick="deleteDiscount('{{ $discount->id }}', '{{ $discount->name }}')" 
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
                                <i class="fas fa-percent text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-lg font-medium">No discounts found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $discounts->links() }}
        </div>
    </div>
</div>

<!-- Add/Edit Discount Modal -->
<div x-data="{ show: false, editing: false, discountId: null }" 
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="show = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="discount-form" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="discount-modal-title">Add Discount</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Discount Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="discount-name" required
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                           placeholder="e.g., Summer Sale">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Discount Type
                                    </label>
                                    <select name="type" id="discount-type" 
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="percentage">Percentage</option>
                                        <option value="fixed">Fixed Amount</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Discount Value <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="value" id="discount-value" required
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                           placeholder="10" step="0.01" min="0">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Apply to Plan
                                    </label>
                                    <select name="plan_id" id="discount-plan" 
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="">All Plans</option>
                                        @foreach($plans as $plan)
                                            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                        @endforeach
                                    </select>
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
                        Save Discount
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
<form id="toggle-discount-form" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<form id="delete-discount-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    function showAddDiscount() {
        const modal = document.querySelector('[x-data]').__x.$data;
        modal.show = true;
        modal.editing = false;
        modal.discountId = null;
        document.getElementById('discount-modal-title').textContent = 'Add Discount';
        document.getElementById('discount-form').action = '{{ route("admin.subscriptions.discounts.store") }}';
        document.getElementById('discount-form').querySelector('input[name="_method"]')?.remove();
        document.getElementById('discount-name').value = '';
        document.getElementById('discount-type').value = 'percentage';
        document.getElementById('discount-value').value = '';
        document.getElementById('discount-plan').value = '';
        document.querySelector('#discount-form input[name="is_active"]').checked = true;
    }

    function editDiscount(discountId) {
        fetch(`/admin/subscriptions/discounts/${discountId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = document.querySelector('[x-data]').__x.$data;
                    modal.show = true;
                    modal.editing = true;
                    modal.discountId = discountId;
                    document.getElementById('discount-modal-title').textContent = 'Edit Discount';
                    document.getElementById('discount-form').action = `/admin/subscriptions/discounts/${discountId}`;
                    
                    let methodInput = document.getElementById('discount-form').querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        document.getElementById('discount-form').appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';
                    
                    document.getElementById('discount-name').value = data.data.name;
                    document.getElementById('discount-type').value = data.data.type;
                    document.getElementById('discount-value').value = data.data.value;
                    document.getElementById('discount-plan').value = data.data.plan_id || '';
                    document.querySelector('#discount-form input[name="is_active"]').checked = data.data.is_active;
                }
            });
    }

    function toggleDiscount(discountId) {
        const form = document.getElementById('toggle-discount-form');
        form.action = `/admin/subscriptions/discounts/${discountId}/toggle`;
        form.submit();
    }

    function deleteDiscount(discountId, name) {
        if (confirm(`Delete discount "${name}"?`)) {
            const form = document.getElementById('delete-discount-form');
            form.action = `/admin/subscriptions/discounts/${discountId}`;
            form.submit();
        }
    }
</script>
@endpush
@endsection