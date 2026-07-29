{{-- resources/views/admin/customers/partials/form.blade.php --}}

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="name" class="form-label required">Name</label>
            <input type="text" 
                   class="form-control @error('name') is-invalid @enderror" 
                   id="name" 
                   name="name" 
                   value="{{ old('name', $customer->name ?? '') }}" 
                   required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="mb-3">
            <label for="email" class="form-label required">Email</label>
            <input type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   id="email" 
                   name="email" 
                   value="{{ old('email', $customer->email ?? '') }}" 
                   required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" 
                   class="form-control @error('phone') is-invalid @enderror" 
                   id="phone" 
                   name="phone" 
                   value="{{ old('phone', $customer->phone ?? '') }}">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="mb-3">
            <label for="company" class="form-label">Company</label>
            <input type="text" 
                   class="form-control @error('company') is-invalid @enderror" 
                   id="company" 
                   name="company" 
                   value="{{ old('company', $customer->company ?? '') }}">
            @error('company')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="status" class="form-label required">Status</label>
            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                @foreach($statuses ?? \App\Enums\CustomerStatus::options() as $value => $label)
                    <option value="{{ $value }}" 
                            {{ old('status', $customer->status ?? 'active') === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="mb-3">
            <label for="avatar" class="form-label">Avatar</label>
            <input type="file" 
                   class="form-control @error('avatar') is-invalid @enderror" 
                   id="avatar" 
                   name="avatar" 
                   accept="image/*">
            @if(isset($customer) && $customer->avatar)
                <div class="mt-2">
                    <img src="{{ asset($customer->avatar) }}" alt="Avatar" width="50" height="50" class="rounded-circle">
                </div>
            @endif
            @error('avatar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control @error('address') is-invalid @enderror" 
                      id="address" 
                      name="address" 
                      rows="2">{{ old('address', $customer->address ?? '') }}</textarea>
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label for="city" class="form-label">City</label>
            <input type="text" 
                   class="form-control @error('city') is-invalid @enderror" 
                   id="city" 
                   name="city" 
                   value="{{ old('city', $customer->city ?? '') }}">
            @error('city')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="mb-3">
            <label for="state" class="form-label">State</label>
            <input type="text" 
                   class="form-control @error('state') is-invalid @enderror" 
                   id="state" 
                   name="state" 
                   value="{{ old('state', $customer->state ?? '') }}">
            @error('state')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="mb-3">
            <label for="postal_code" class="form-label">Postal Code</label>
            <input type="text" 
                   class="form-control @error('postal_code') is-invalid @enderror" 
                   id="postal_code" 
                   name="postal_code" 
                   value="{{ old('postal_code', $customer->postal_code ?? '') }}">
            @error('postal_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="country" class="form-label">Country</label>
            <select class="form-select @error('country') is-invalid @enderror" id="country" name="country">
                <option value="">Select Country</option>
                @foreach($countries ?? [] as $code => $name)
                    <option value="{{ $code }}" 
                            {{ old('country', $customer->country ?? 'US') === $code ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
            @error('country')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="mb-3">
            <label for="website" class="form-label">Website</label>
            <input type="url" 
                   class="form-control @error('website') is-invalid @enderror" 
                   id="website" 
                   name="website" 
                   value="{{ old('website', $customer->website ?? '') }}" 
                   placeholder="https://example.com">
            @error('website')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control @error('notes') is-invalid @enderror" 
                      id="notes" 
                      name="notes" 
                      rows="3">{{ old('notes', $customer->notes ?? '') }}</textarea>
            @error('notes')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="tax_id" class="form-label">Tax ID</label>
            <input type="text" 
                   class="form-control @error('tax_id') is-invalid @enderror" 
                   id="tax_id" 
                   name="tax_id" 
                   value="{{ old('tax_id', $customer->tax_id ?? '') }}">
            @error('tax_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="mb-3">
            <label for="industry" class="form-label">Industry</label>
            <input type="text" 
                   class="form-control @error('industry') is-invalid @enderror" 
                   id="industry" 
                   name="industry" 
                   value="{{ old('industry', $customer->industry ?? '') }}">
            @error('industry')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>