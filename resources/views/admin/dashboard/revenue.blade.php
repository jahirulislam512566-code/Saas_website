{{-- resources/views/admin/dashboard/revenue.blade.php --}}

<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Latest Payments</h6>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-primary">
                    View All
                </a>
            </div>
            <div class="card-body">
                @if(isset($latestPayments) && $latestPayments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestPayments as $payment)
                                    <tr>
                                        <td>{{ $payment->transaction_id ?? $payment->id }}</td>
                                        <td>{{ $payment->order->customer->name ?? 'N/A' }}</td>
                                        <td>{{ money($payment->amount ?? 0) }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $payment->status->color() ?? '#6c757d' }}">
                                                {{ $payment->status->label() ?? $payment->status ?? 'Pending' }}
                                            </span>
                                        </td>
                                        <td>{{ $payment->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Payments Yet</h5>
                        <p class="text-muted">There are no recent payments to display.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>