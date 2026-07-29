{{-- resources/views/admin/customers/show.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Customer Details')
@section('page-title', 'Customer: ' . $customer->name)

@section('content')
<div class="container-fluid">
    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="btn-group">
                @can('update', $customer)
                    <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                @endcan
                @can('delete', $customer)
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                @endcan
                @can('suspend', $customer)
                    @if($customer->status === 'active')
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#suspendModal">
                            <i class="fas fa-pause"></i> Suspend
                        </button>
                    @else
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#activateModal">
                            <i class="fas fa-play"></i> Activate
                        </button>
                    @endif
                @endcan
                <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Information -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="{{ $customer->avatar ?? asset('images/default-avatar.png') }}" 
                             alt="{{ $customer->name }}" 
                             class="rounded-circle" 
                             width="100" 
                             height="100">
                    </div>
                    <table class="table table-borderless">
                        <tr>
                            <th>Customer Number:</th>
                            <td>{{ $customer->customer_number }}</td>
                        </tr>
                        <tr>
                            <th>Name:</th>
                            <td>{{ $customer->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $customer->email }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $customer->phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Company:</th>
                            <td>{{ $customer->company ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge" style="background-color: {{ $customer->status->color() }}">
                                    {{ $customer->status->label() }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td>{{ $customer->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $customer->updated_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Orders</h5>
                            <h2 class="mb-0">{{ $customer->orders_count ?? 0 }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Spent</h5>
                            <h2 class="mb-0">{{ money($customer->total_spent ?? 0) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Active Projects</h5>
                            <h2 class="mb-0">{{ $customer->active_projects_count ?? 0 }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">Open Tickets</h5>
                            <h2 class="mb-0">{{ $customer->open_tickets_count ?? 0 }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Orders</h5>
                </div>
                <div class="card-body">
                    @if($customer->orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->orders->take(5) as $order)
                                        <tr>
                                            <td>{{ $order->order_number }}</td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            <td>{{ money($order->total) }}</td>
                                            <td>
                                                <span class="badge" style="background-color: {{ $order->status->color() }}">
                                                    {{ $order->status->label() }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($customer->orders->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.orders.index', ['customer' => $customer->id]) }}" class="btn btn-sm btn-primary">
                                    View All Orders
                                </a>
                            </div>
                        @endif
                    @else
                        <p class="text-muted">No orders found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@include('admin.customers.partials.modals', ['customer' => $customer])
@endsection