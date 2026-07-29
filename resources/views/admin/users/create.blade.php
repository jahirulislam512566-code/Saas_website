<!-- admin/users/create.blade.php -->
@extends('layout.app')

@section('title', 'Create User')
@section('page-title', 'Create New User')
@section('page-subtitle', 'Add a new user to the platform')

@section('content')
<div x-data="userForm()" x-init="init()">
    <form @submit.prevent="submitForm()" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Basic Information</h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name *</label>
                                <input type="text" x-model="form.name" required
                                       class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address *</label>
                                <input type="email" x-model="form.email" required
                                       class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password *</label>
                                    <input type="password" x-model="form.password" required minlength="8"
                                           class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Minimum 8 characters</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password *</label>
                                    <input type="password" x-model="form.password_confirmation" required
                                           class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
                                <input type="tel" x-model="form.phone"
                                       class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Additional Information</h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bio</label>
                                <textarea x-model="form.bio" rows="3"
                                          class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                          placeholder="Brief description about the user..."></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Department</label>
                                <select x-model="form.department"
                                        class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <option value="">Select department...</option>
                                    <option value="engineering">Engineering</option>
                                    <option value="product">Product</option>
                                    <option value="sales">Sales</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="support">Support</option>
                                    <option value="hr">HR</option>
                                    <option value="finance">Finance</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Role & Status -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Role & Status</h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role *</label>
                                <select x-model="form.role" required
                                        class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <option value="admin">Admin</option>
                                    <option value="manager">Manager</option>
                                    <option value="support">Support</option>
                                    <option value="developer">Developer</option>
                                    <option value="editor">Editor</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                <select x-model="form.status"
                                        class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                    <option value="active">Active</option>
                                    <option value="pending">Pending</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Avatar -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Profile Photo</h4>
                        
                        <div class="flex flex-col items-center">
                            <div class="relative">
                                <img id="avatarPreview" 
                                     src="https://ui-avatars.com/api/?name=New+User&background=4f46e5&color=fff&size=128" 
                                     alt="Avatar Preview" 
                                     class="w-24 h-24 rounded-full border-2 border-gray-300 dark:border-gray-700">
                                <label for="avatar" 
                                       class="absolute bottom-0 right-0 p-1.5 bg-indigo-600 hover:bg-indigo-700 rounded-full cursor-pointer transition-colors">
                                    <i class="fas fa-camera text-white text-xs"></i>
                                    <input type="file" id="avatar" x-model="form.avatar" accept="image/*" 
                                           class="hidden" @change="previewAvatar($event)">
                                </label>
                            </div>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Upload a profile photo</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG up to 2MB</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="space-y-2">
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all">
                                <i class="fas fa-save mr-1.5"></i>Create User
                            </button>
                            <a href="{{ route('admin.users.index') }}" 
                               class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                <i class="fas fa-arrow-left mr-1.5"></i>Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function userForm() {
    return {
        form: {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            phone: '',
            bio: '',
            department: '',
            role: 'editor',
            status: 'active',
            avatar: null
        },
        
        previewAvatar(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        
        submitForm() {
            if (this.form.password !== this.form.password_confirmation) {
                alert('Passwords do not match!');
                return;
            }
            
            const formData = new FormData();
            Object.entries(this.form).forEach(([key, value]) => {
                if (value !== null && value !== '') {
                    formData.append(key, value);
                }
            });
            
            fetch('{{ route("admin.users.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '{{ route("admin.users.index") }}';
                } else {
                    alert(data.error || 'Error creating user');
                }
            })
            .catch(error => console.error('Error creating user:', error));
        }
    }
}
</script>
@endpush