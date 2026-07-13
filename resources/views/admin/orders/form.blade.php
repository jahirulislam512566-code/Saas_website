{{-- resources/views/admin/orders/form.blade.php --}}
@extends('admin.layouts.admin')

@section('title', isset($order) ? 'Edit Order' : 'Create Order')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.orders.index') }}" class="text-gray-500 hover:text-gray-700">Orders</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">{{ isset($order) ? 'Edit' : 'Create' }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">{{ isset($order) ? 'Edit Order' : 'Create New Order' }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ isset($order) ? 'Update order details' : 'Create a new order' }}</p>
        </div>
        
        <form action="{{ isset($order) ? route('admin.orders.update', $order) : route('admin.orders.store') }}" 
              method="POST" class="p-6">
            @csrf
            @if(isset($order))
                @method('PUT')
            @endif
            
            <div class="space-y-6">
                <!-- Customer Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Customer Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Customer <span class="text-red-500">*</span>
                            </label>
                            <select name="customer_id" id="customer_id" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('customer_id') border-red-500 @enderror">
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id', $order->customer_id ?? '') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} ({{ $customer->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="order_number" class="block text-sm font-medium text-gray-700 mb-1">
                                Order Number
                            </label>
                            <input type="text" name="order_number" id="order_number" 
                                   value="{{ old('order_number', $order->order_number ?? '') }}" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('order_number') border-red-500 @enderror"
                                   placeholder="Auto-generated">
                            @error('order_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">
                            Shipping Address
                        </label>
                        <textarea name="shipping_address" id="shipping_address" rows="3"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('shipping_address') border-red-500 @enderror"
                                  placeholder="Enter shipping address">{{ old('shipping_address', $order->shipping_address ?? '') }}</textarea>
                        @error('shipping_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Order Items -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Order Items</h4>
                    
                    <div x-data="orderItems()" class="space-y-3">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="flex-1">
                                    <input type="text" 
                                           :name="'items[' + index + '][name]'" 
                                           x-model="item.name"
                                           placeholder="Item name"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div class="w-24">
                                    <input type="number" 
                                           :name="'items[' + index + '][quantity]'" 
                                           x-model="item.quantity"
                                           placeholder="Qty"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div class="w-32">
                                    <input type="number" 
                                           :name="'items[' + index + '][price]'" 
                                           x-model="item.price"
                                           placeholder="Price"
                                           step="0.01"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div class="w-32">
                                    <span class="text-sm font-medium text-gray-900" x-text="'$' + (item.quantity * item.price).toFixed(2)"></span>
                                </div>
                                <button type="button" @click="removeItem(index)" 
                                        class="p-2 text-red-500 hover:text-red-700 transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </template>
                        
                        <button type="button" @click="addItem()" 
                                class="inline-flex items-center text-sm text-primary-600 hover:text-primary-700 transition-colors">
                            <i class="fas fa-plus mr-1"></i> Add Item
                        </button>
                        
                        <div class="flex justify-end p-3 bg-gray-50 rounded-lg">
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Total</p>
                                <p class="text-xl font-bold text-gray-900" x-text="'$' + total.toFixed(2)"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Details -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Order Details</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('status') border-red-500 @enderror">
                                <option value="pending" {{ old('status', $order->status ?? 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ old('status', $order->status ?? '') == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ old('status', $order->status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="shipped" {{ old('status', $order->status ?? '') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ old('status', $order->status ?? '') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ old('status', $order->status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="refunded" {{ old('status', $order->status ?? '') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">
                                Payment Method
                            </label>
                            <select name="payment_method" id="payment_method"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('payment_method') border-red-500 @enderror">
                                <option value="credit_card" {{ old('payment_method', $order->payment_method ?? '') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                <option value="paypal" {{ old('payment_method', $order->payment_method ?? '') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                <option value="bank_transfer" {{ old('payment_method', $order->payment_method ?? '') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cash_on_delivery" {{ old('payment_method', $order->payment_method ?? '') == 'cash_on_delivery' ? 'selected' : '' }}>Cash on Delivery</option>
                            </select>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                            Order Notes
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('notes') border-red-500 @enderror"
                                  placeholder="Additional notes about the order">{{ old('notes', $order->notes ?? '') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="fas fa-save mr-2"></i> {{ isset($order) ? 'Update Order' : 'Create Order' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('orderItems', () => ({
            items: @json(old('items', isset($order) ? $order->items : [['name' => '', 'quantity' => 1, 'price' => 0]])),
            
            addItem() {
                this.items.push({ name: '', quantity: 1, price: 0 });
            },
            
            removeItem(index) {
                this.items.splice(index, 1);
            },
            
            get total() {
                return this.items.reduce((sum, item) => sum + (item.quantity * item.price), 0);
            }
        }));
    });
</script>
@endpush
@endsection