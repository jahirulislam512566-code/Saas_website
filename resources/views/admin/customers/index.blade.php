{{-- resources/views/admin/customers/index.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Customers')
@section('page-title', 'Customer Management')

@section('content')
<div class="container-fluid">
    <!-- Stats Cards -->
    @include('admin.customers.components.stats')
    
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h4 class="mb-0">Customers List</h4>
        </div>
        <div class="col-md-6 text-end">
            @can('create', App\Models\Customer::class)
                <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Customer
                </a>
            @endcan
            @can('viewTrashed', App\Models\Customer::class)
                <a href="{{ route('admin.customers.trash') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-trash"></i> Trash
                    <span class="badge bg-danger">{{ $trashCount ?? 0 }}</span>
                </a>
            @endcan
        </div>
    </div>

    <!-- Filters -->
    @include('admin.customers.partials.filters')

    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('admin.customers.partials.table')
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Showing {{ $customers->firstItem() ?? 0 }} to {{ $customers->lastItem() ?? 0 }} 
                            of {{ $customers->total() }} results
                        </div>
                        <div>
                            {{ $customers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @include('admin.customers.partials.modals')
</div>
@endsection

@push('scripts')
<script>
    // Bulk action handling
    document.addEventListener('DOMContentLoaded', function() {
        // Select all checkbox
        document.getElementById('select-all')?.addEventListener('change', function() {
            document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = this.checked);
        });

        // Bulk delete
        document.getElementById('bulk-delete')?.addEventListener('click', function() {
            const selected = document.querySelectorAll('.row-checkbox:checked');
            if (selected.length === 0) {
                alert('Please select at least one item.');
                return;
            }
            if (confirm(`Are you sure you want to delete ${selected.length} item(s)?`)) {
                document.getElementById('bulk-action-form').submit();
            }
        });
    });
</script>
@endpush