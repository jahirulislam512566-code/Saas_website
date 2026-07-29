@extends('layouts.website')

@section('title', 'Our Menu - ' . config('app.name'))

@push('styles')
<style>
    .menu-item:hover {
        background-color: #f8fafc;
        transform: translateX(4px);
    }
    .category-tab.active {
        border-bottom: 3px solid #0284c7;
        color: #0284c7;
    }
</style>
@endpush

@section('content')
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-primary-600 font-semibold text-sm uppercase tracking-wider">Delicious Selection</span>
            <h1 class="text-4xl md:text-5xl font-bold mt-2 text-gray-900">Our Menu</h1>
            <div class="w-24 h-1 bg-primary-600 mx-auto mt-4"></div>
            <p class="text-gray-600 mt-4 max-w-2xl mx-auto">Crafted with passion, served with love</p>
        </div>
        
        <!-- Category Tabs -->
        <div class="flex flex-wrap justify-center gap-2 mb-12">
            @foreach($categories ?? $defaultCategories as $category)
                <button class="category-tab px-6 py-3 text-sm font-medium text-gray-600 hover:text-primary-600 transition border-b-2 border-transparent {{ $loop->first ? 'active' : '' }}" data-category="{{ $loop->index }}">
                    {{ $category['name'] ?? 'Category' }}
                </button>
            @endforeach
        </div>
        
        <!-- Menu Items -->
        @foreach($categories ?? $defaultCategories as $categoryIndex => $category)
            <div class="menu-category mb-16" data-category="{{ $categoryIndex }}" style="{{ $loop->first ? '' : 'display: none;' }}">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 pb-4 border-b border-gray-200">{{ $category['name'] ?? 'Category' }}</h2>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($category['items'] ?? [] as $item)
                        <div class="menu-item flex items-start space-x-4 p-4 rounded-lg transition-all duration-200">
                            <img src="{{ $item['image'] ?? '/assets/images/placeholder.jpg' }}" alt="{{ $item['name'] ?? 'Item' }}" class="w-24 h-24 object-cover rounded-lg shadow-sm flex-shrink-0">
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <h3 class="font-semibold text-lg text-gray-900">{{ $item['name'] ?? 'Item Name' }}</h3>
                                    <span class="text-primary-600 font-bold">{{ $item['price'] ?? '$0' }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $item['description'] ?? 'Description' }}</p>
                                @if(!empty($item['dietary'] ?? []))
                                    <div class="flex gap-2 mt-2">
                                        @foreach($item['dietary'] ?? [] as $diet)
                                            <x-badge variant="info" size="sm">{{ $diet }}</x-badge>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.category-tab');
        const categories = document.querySelectorAll('.menu-category');
        
        tabs.forEach((tab, index) => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Hide all categories and show the selected one
                categories.forEach(c => c.style.display = 'none');
                categories[index].style.display = 'block';
            });
        });
    });
</script>
@endpush
@endsection

@php
$defaultCategories = [
    ['name' => 'Appetizers', 'items' => [
        ['name' => 'Bruschetta', 'price' => '$12', 'description' => 'Toasted bread with tomatoes, garlic, and basil', 'image' => '/assets/images/bruschetta.jpg', 'dietary' => ['Vegetarian']],
        ['name' => 'Calamari', 'price' => '$14', 'description' => 'Crispy fried calamari with marinara sauce', 'image' => '/assets/images/calamari.jpg', 'dietary' => ['Gluten-Free']]
    ]],
    ['name' => 'Main Course', 'items' => [
        ['name' => 'Grilled Salmon', 'price' => '$28', 'description' => 'Fresh Atlantic salmon with vegetables', 'image' => '/assets/images/salmon.jpg', 'dietary' => ['Gluten-Free']],
        ['name' => 'Beef Tenderloin', 'price' => '$34', 'description' => 'Premium beef with truffle mash', 'image' => '/assets/images/steak.jpg', 'dietary' => []]
    ]],
    ['name' => 'Desserts', 'items' => [
        ['name' => 'Tiramisu', 'price' => '$10', 'description' => 'Classic Italian dessert with coffee flavor', 'image' => '/assets/images/tiramisu.jpg', 'dietary' => ['Vegetarian']],
        ['name' => 'Cheesecake', 'price' => '$9', 'description' => 'Creamy New York style cheesecake', 'image' => '/assets/images/cheesecake.jpg', 'dietary' => ['Vegetarian']]
    ]]
];
@endphp