{{-- resources/views/admin/customers/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Customers')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Customers</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Customers</h1>
            <p class="text-sm text-gray-500 mt-1">Manage all customers</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.customers.export') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-download mr-2"></i> Export
            </a>
            <a href="{{ route('admin.customers.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Add Customer
            </a>
        </div>
    </div>

    <!-- ===== STATS CARDS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Customers</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Active</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['active'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Orders</p>
                    <p class="text-xl font-bold text-blue-600">{{ $stats['total_orders'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Spent</p>
                    <p class="text-xl font-bold text-purple-600">${{ number_format($stats['total_spent'] ?? 0, 2) }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== FILTERS ===== -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" placeholder="Name or email..." value="{{ request('search') }}" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                <select name="sort" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date Registered</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                    <option value="orders_count" {{ request('sort') == 'orders_count' ? 'selected' : '' }}>Orders</option>
                    <option value="total_spent" {{ request('sort') == 'total_spent' ? 'selected' : '' }}>Total Spent</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.customers.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <!-- ===== CUSTOMERS TABLE ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Orders</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Spent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <x-admin.avatar :src="$customer->avatar" :name="$customer->name" size="sm" />
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $customer->name }}</p>
                                        <p class="text-xs text-gray-500">ID: #{{ $customer->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $customer->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $customer->orders_count ?? 0 }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                ${{ number_format($customer->total_spent ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $customer->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $customer->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <div>{{ $customer->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $customer->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    <a href="{{ route('admin.customers.show', $customer) }}" 
                                       class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                       title="View">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.customers.edit', $customer) }}" 
                                       class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" 
                                       title="Edit">
                                        <i class="fas fa-pen text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.customers.orders', $customer) }}" 
                                       class="p-2 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" 
                                       title="Orders">
                                        <i class="fas fa-shopping-cart text-sm"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-users text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-lg font-medium">No customers found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- ===== PAGINATION ===== -->
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            @if($customers instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="text-sm text-gray-500">
                    Showing {{ $customers->firstItem() ?? 0 }} to {{ $customers->lastItem() ?? 0 }} of {{ $customers->total() }} results
                </div>
                <div>
                    {{ $customers->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-sm text-gray-500">
                    Showing {{ $customers->count() }} results
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-hover {
        transition: all 0.2s ease;
    }
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    }
</style>
@endpush
@endsection