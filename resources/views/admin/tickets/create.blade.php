@extends('admin.layouts.admin')

@section('title', 'Create Ticket')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.tickets.index') }}" class="text-gray-500 hover:text-gray-700">Tickets</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Create</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <h2 class="text-xl font-bold text-gray-900">
                <i class="fas fa-plus-circle text-primary-600 mr-2"></i>
                Create New Ticket
            </h2>
            <p class="text-sm text-gray-500 mt-1">Create a new support ticket</p>
        </div>

        <form action="{{ route('admin.tickets.store') }}" method="POST" class="p-6">
            @csrf

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">
                            Subject <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="subject" id="subject" 
                               value="{{ old('subject') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('subject') border-red-500 @enderror"
                               placeholder="Enter ticket subject"
                               required>
                        @error('subject')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Department <span class="text-red-500">*</span>
                        </label>
                        <select name="department_id" id="department_id" 
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('department_id') border-red-500 @enderror"
                                required>
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <select name="priority" id="priority" 
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('priority') border-red-500 @enderror"
                                required>
                            <option value="">Select Priority</option>
                            @foreach($priorities as $priority)
                                <option value="{{ $priority }}" {{ old('priority') == $priority ? 'selected' : '' }}>
                                    {{ ucfirst($priority) }}
                                </option>
                            @endforeach
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1">
                            Assign To
                        </label>
                        <select name="assigned_to" id="assigned_to" 
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('assigned_to') border-red-500 @enderror">
                            <option value="">Unassigned</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">
                        Message <span class="text-red-500">*</span>
                    </label>
                    <textarea name="message" id="message" rows="6"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('message') border-red-500 @enderror"
                              placeholder="Describe your issue in detail..."
                              required>{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">
                        Category
                    </label>
                    <input type="text" name="category" id="category" 
                           value="{{ old('category') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 @error('category') border-red-500 @enderror"
                           placeholder="e.g., Bug, Feature Request, Support">
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.tickets.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="fas fa-save mr-2"></i> Create Ticket
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection