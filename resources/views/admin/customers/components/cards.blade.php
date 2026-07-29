{{-- resources/views/admin/customers/components/cards.blade.php --}}

<div class="row">
    @forelse($customers as $customer)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <img src="{{ $customer->avatar ?? asset('images/default-avatar.png') }}" 
                         alt="{{ $customer->name }}" 
                         class="rounded-circle mb-3" 
                         width="80" 
                         height="80">
                    <h5 class="card-title">{{ $customer->name }}</h5>
                    <p class="text-muted">{{ $customer->company ?? 'Individual' }}</p>
                    <p class="text-muted small">
                        <i class="fas fa-envelope"></i> {{ $customer->email }}
                    </p>
                    @if($customer->phone)
                        <p class="text-muted small">
                            <i class="fas fa-phone"></i> {{ $customer->phone }}
                        </p>
                    @endif
                    <span class="badge" style="background-color: {{ $customer->status->color() }}">
                        {{ $customer->status->label() }}
                    </span>
                    
                    <hr>
                    
                    <div class="row text-center">
                        <div class="col-4">
                            <h6>{{ $customer->orders_count ?? 0 }}</h6>
                            <small class="text-muted">Orders</small>
                        </div>
                        <div class="col-4">
                            <h6>{{ money($customer->total_spent ?? 0) }}</h6>
                            <small class="text-muted">Spent</small>
                        </div>
                        <div class="col-4">
                            <h6>{{ $customer->projects_count ?? 0 }}</h6>
                            <small class="text-muted">Projects</small>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i> View Profile
                        </a>
                        @can('update', $customer)
                            <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No customers found</h5>
                <p class="text-muted">Start by creating your first customer.</p>
            </div>
        </div>
    @endforelse
</div>