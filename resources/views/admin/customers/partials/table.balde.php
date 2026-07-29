{{-- resources/views/admin/customers/partials/table.blade.php --}}

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th width="50">
                    <input type="checkbox" id="select-all">
                </th>
                <th>ID</th>
                <th>Customer</th>
                <th>Company</th>
                <th>Email</th>
                <th>Status</th>
                <th>Created</th>
                <th width="150">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
                <tr>
                    <td>
                        <input type="checkbox" class="row-checkbox" value="{{ $customer->id }}">
                    </td>
                    <td>#{{ $customer->id }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ $customer->avatar ?? asset('images/default-avatar.png') }}" 
                                 alt="{{ $customer->name }}" 
                                 class="rounded-circle me-2" 
                                 width="32" 
                                 height="32">
                            <div>
                                <div>{{ $customer->name }}</div>
                                <small class="text-muted">{{ $customer->customer_number }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $customer->company ?? 'N/A' }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>
                        <span class="badge" style="background-color: {{ $customer->status->color() }}">
                            {{ $customer->status->label() }}
                        </span>
                    </td>
                    <td>{{ $customer->created_at->format('M d, Y') }}</td>
                    <td>
                        @include('admin.customers.partials.actions', ['customer' => $customer])
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div class="text-muted">
                            <i class="fas fa-users fa-2x mb-2 d-block"></i>
                            <p>No customers found.</p>
                            @can('create', App\Models\Customer::class)
                                <a href="{{ route('admin.customers.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Create First Customer
                                </a>
                            @endcan
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Bulk Actions -->
<div class="row mt-3">
    <div class="col-12">
        <form action="{{ route('admin.customers.bulk-action') }}" method="POST" id="bulk-action-form">
            @csrf
            <input type="hidden" name="ids" id="bulk-ids">
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-danger" id="bulk-delete">
                    <i class="fas fa-trash"></i> Delete Selected
                </button>
                <button type="button" class="btn btn-sm btn-warning" id="bulk-suspend">
                    <i class="fas fa-pause"></i> Suspend Selected
                </button>
                <button type="button" class="btn btn-sm btn-success" id="bulk-activate">
                    <i class="fas fa-play"></i> Activate Selected
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bulkForm = document.getElementById('bulk-action-form');
        const bulkIds = document.getElementById('bulk-ids');

        document.getElementById('bulk-delete')?.addEventListener('click', function() {
            const selected = getSelectedIds();
            if (selected.length === 0) {
                alert('Please select at least one item.');
                return;
            }
            if (confirm(`Are you sure you want to delete ${selected.length} item(s)?`)) {
                bulkIds.value = JSON.stringify(selected);
                bulkForm.action = '{{ route("admin.customers.bulk-delete") }}';
                bulkForm.submit();
            }
        });

        document.getElementById('bulk-suspend')?.addEventListener('click', function() {
            const selected = getSelectedIds();
            if (selected.length === 0) {
                alert('Please select at least one item.');
                return;
            }
            if (confirm(`Are you sure you want to suspend ${selected.length} customer(s)?`)) {
                bulkIds.value = JSON.stringify(selected);
                bulkForm.action = '{{ route("admin.customers.bulk-suspend") }}';
                bulkForm.submit();
            }
        });

        document.getElementById('bulk-activate')?.addEventListener('click', function() {
            const selected = getSelectedIds();
            if (selected.length === 0) {
                alert('Please select at least one item.');
                return;
            }
            if (confirm(`Are you sure you want to activate ${selected.length} customer(s)?`)) {
                bulkIds.value = JSON.stringify(selected);
                bulkForm.action = '{{ route("admin.customers.bulk-activate") }}';
                bulkForm.submit();
            }
        });

        function getSelectedIds() {
            return Array.from(document.querySelectorAll('.row-checkbox:checked'))
                .map(cb => cb.value);
        }
    });
</script>
@endpush