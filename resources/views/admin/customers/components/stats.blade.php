{{-- resources/views/admin/customers/components/stats.blade.php --}}

<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Customers</h6>
                        <h2 class="mt-2 mb-0">{{ $stats['total'] ?? 0 }}</h2>
                    </div>
                    <div class="fs-1">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <small>
                        <i class="fas fa-arrow-up"></i> 
                        {{ $stats['growth'] ?? 0 }}% from last month
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Active Customers</h6>
                        <h2 class="mt-2 mb-0">{{ $stats['active'] ?? 0 }}</h2>
                    </div>
                    <div class="fs-1">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <small>
                        <i class="fas fa-percentage"></i> 
                        {{ $stats['active_percentage'] ?? 0 }}% of total
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">New This Month</h6>
                        <h2 class="mt-2 mb-0">{{ $stats['new_this_month'] ?? 0 }}</h2>
                    </div>
                    <div class="fs-1">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <small>
                        <i class="fas fa-calendar"></i> 
                        {{ $stats['new_this_month_percentage'] ?? 0 }}% of total
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Suspended</h6>
                        <h2 class="mt-2 mb-0">{{ $stats['suspended'] ?? 0 }}</h2>
                    </div>
                    <div class="fs-1">
                        <i class="fas fa-user-slash"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <small>
                        <i class="fas fa-percentage"></i> 
                        {{ $stats['suspended_percentage'] ?? 0 }}% of total
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>