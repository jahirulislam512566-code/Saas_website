{{-- resources/views/admin/customers/partials/filters.blade.php --}}

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.customers.index') }}" method="GET" id="filter-form">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3 mb-md-0">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="search" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Name, Email, Company...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3 mb-md-0">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">All Status</option>
                                    @foreach(\App\Enums\CustomerStatus::options() as $value => $label)
                                        <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3 mb-md-0">
                                <label for="industry" class="form-label">Industry</label>
                                <select class="form-select" id="industry" name="industry">
                                    <option value="">All Industries</option>
                                    @foreach($industries ?? [] as $industry)
                                        <option value="{{ $industry }}" {{ request('industry') === $industry ? 'selected' : '' }}>
                                            {{ ucfirst($industry) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3 mb-md-0">
                                <label for="sort" class="form-label">Sort By</label>
                                <select class="form-select" id="sort" name="sort">
                                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Created Date</option>
                                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name</option>
                                    <option value="email" {{ request('sort') === 'email' ? 'selected' : '' }}>Email</option>
                                    <option value="company" {{ request('sort') === 'company' ? 'selected' : '' }}>Company</option>
                                    <option value="updated_at" {{ request('sort') === 'updated_at' ? 'selected' : '' }}>Last Updated</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3 mb-md-0">
                                <label for="order" class="form-label">Order</label>
                                <select class="form-select" id="order" name="order">
                                    <option value="desc" {{ request('order') === 'desc' ? 'selected' : '' }}>Newest First</option>
                                    <option value="asc" {{ request('order') === 'asc' ? 'selected' : '' }}>Oldest First</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="mb-3 mb-md-0">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @if(request()->has('search') || request()->has('status') || request()->has('industry'))
                                        <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-times"></i> Clear Filters
                                        </a>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-muted">
                                        <i class="fas fa-filter"></i> 
                                        {{ $customers->total() }} results found
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>