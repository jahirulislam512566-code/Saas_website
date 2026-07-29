{{-- resources/views/admin/customers/partials/actions.blade.php --}}

<div class="btn-group btn-group-sm">
    @can('view', $customer)
        <a href="{{ route('admin.customers.show', $customer) }}" 
           class="btn btn-info" 
           title="View">
            <i class="fas fa-eye"></i>
        </a>
    @endcan
    
    @can('update', $customer)
        <a href="{{ route('admin.customers.edit', $customer) }}" 
           class="btn btn-warning" 
           title="Edit">
            <i class="fas fa-edit"></i>
        </a>
    @endcan
    
    @can('delete', $customer)
        <button type="button" 
                class="btn btn-danger" 
                data-bs-toggle="modal" 
                data-bs-target="#deleteModal{{ $customer->id }}"
                title="Delete">
            <i class="fas fa-trash"></i>
        </button>
    @endcan
    
    @can('suspend', $customer)
        @if($customer->status === 'active')
            <button type="button" 
                    class="btn btn-warning" 
                    data-bs-toggle="modal" 
                    data-bs-target="#suspendModal{{ $customer->id }}"
                    title="Suspend">
                <i class="fas fa-pause"></i>
            </button>
        @elseif($customer->status === 'suspended')
            <button type="button" 
                    class="btn btn-success" 
                    data-bs-toggle="modal" 
                    data-bs-target="#activateModal{{ $customer->id }}"
                    title="Activate">
                <i class="fas fa-play"></i>
            </button>
        @endif
    @endcan
</div>

@if(isset($customer))
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal{{ $customer->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete customer <strong>{{ $customer->name }}</strong>?</p>
                    @if($customer->orders()->count() > 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            This customer has {{ $customer->orders()->count() }} order(s). 
                            They will also be deleted.
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Suspend Modal -->
    <div class="modal fade" id="suspendModal{{ $customer->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Suspension</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to suspend customer <strong>{{ $customer->name }}</strong>?</p>
                    <div class="mb-3">
                        <label for="reason{{ $customer->id }}" class="form-label">Reason</label>
                        <textarea class="form-control" id="reason{{ $customer->id }}" rows="2" placeholder="Reason for suspension..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.customers.suspend', $customer) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="reason" id="suspend-reason{{ $customer->id }}">
                        <button type="submit" class="btn btn-warning" onclick="document.getElementById('suspend-reason{{ $customer->id }}').value = document.getElementById('reason{{ $customer->id }}').value">
                            <i class="fas fa-pause"></i> Suspend
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Activate Modal -->
    <div class="modal fade" id="activateModal{{ $customer->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Activation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to activate customer <strong>{{ $customer->name }}</strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.customers.activate', $customer) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-play"></i> Activate
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif