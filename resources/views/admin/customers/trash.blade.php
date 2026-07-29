{{-- resources/views/admin/customers/trash.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Trashed Customers')
@section('page-title', 'Trashed Customers')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Deleted Customers</h4>
                <div>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Back to Customers
                    </a>
                    @can('forceDelete', App\Models\Customer::class)
                        <button type="button" class="btn btn-danger" id="empty-trash">
                            <i class="fas fa-trash"></i> Empty Trash
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($customers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="select-all">
                                        </th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Company</th>
                                        <th>Deleted At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customers as $customer)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="row-checkbox" value="{{ $customer->id }}">
                                            </td>
                                            <td>{{ $customer->name }}</td>
                                            <td>{{ $customer->email }}</td>
                                            <td>{{ $customer->company ?? 'N/A' }}</td>
                                            <td>{{ $customer->deleted_at->diffForHumans() }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    @can('restore', $customer)
                                                        <button type="button" class="btn btn-sm btn-success restore-btn" data-id="{{ $customer->id }}">
                                                            <i class="fas fa-undo"></i> Restore
                                                        </button>
                                                    @endcan
                                                    @can('forceDelete', $customer)
                                                        <button type="button" class="btn btn-sm btn-danger force-delete-btn" data-id="{{ $customer->id }}">
                                                            <i class="fas fa-trash-alt"></i> Permanently Delete
                                                        </button>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                Showing {{ $customers->firstItem() ?? 0 }} to {{ $customers->lastItem() ?? 0 }} 
                                of {{ $customers->total() }} results
                            </div>
                            <div>
                                {{ $customers->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-trash-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Trash is empty</h5>
                            <p class="text-muted">No deleted customers found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select all checkbox
        document.getElementById('select-all')?.addEventListener('change', function() {
            document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = this.checked);
        });

        // Restore single item
        document.querySelectorAll('.restore-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                if (confirm('Are you sure you want to restore this item?')) {
                    window.location.href = `{{ route('admin.customers.restore', '') }}/${id}`;
                }
            });
        });

        // Force delete single item
        document.querySelectorAll('.force-delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                if (confirm('Are you sure you want to permanently delete this item? This action cannot be undone.')) {
                    window.location.href = `{{ route('admin.customers.force-delete', '') }}/${id}`;
                }
            });
        });

        // Empty trash
        document.getElementById('empty-trash')?.addEventListener('click', function() {
            if (confirm('Are you sure you want to permanently delete all trashed items? This action cannot be undone.')) {
                window.location.href = '{{ route('admin.customers.empty-trash') }}';
            }
        });
    });
</script>
@endpush
@endsection