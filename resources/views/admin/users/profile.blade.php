<!-- admin/users/profile.blade.php -->
@extends('layout.app')

@section('title', 'My Profile')
@section('page-title', 'Profile Settings')
@section('page-subtitle', 'Manage your personal information and preferences')

@section('content')
<div x-data="profileSettings()" x-init="init()">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Personal Information</h4>
                    
                    <form @submit.prevent="updateProfile()" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
                            <input type="text" x-model="profile.name" required
                                   class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                            <input type="email" x-model="profile.email" required
                                   class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
                            <input type="tel" x-model="profile.phone"
                                   class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bio</label>
                            <textarea x-model="profile.bio" rows="3"
                                      class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>

                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all">
                            <i class="fas fa-save mr-1.5"></i>Update Profile
                        </button>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Change Password</h4>
                    
                    <form @submit.prevent="updatePassword()" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Password</label>
                            <input type="password" x-model="password.current" required
                                   class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Password</label>
                            <input type="password" x-model="password.new" required minlength="8"
                                   class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm New Password</label>
                            <input type="password" x-model="password.confirmation" required
                                   class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all">
                            <i class="fas fa-key mr-1.5"></i>Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Avatar -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Profile Photo</h4>
                    
                    <form @submit.prevent="updateAvatar()" class="flex flex-col items-center">
                        <div class="relative">
                            <img id="avatarPreview" 
                                 src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=4f46e5&color=fff&size=128' }}" 
                                 alt="Avatar" 
                                 class="w-24 h-24 rounded-full border-2 border-gray-300 dark:border-gray-700">
                            <label for="avatar" 
                                   class="absolute bottom-0 right-0 p-1.5 bg-indigo-600 hover:bg-indigo-700 rounded-full cursor-pointer transition-colors">
                                <i class="fas fa-camera text-white text-xs"></i>
                                <input type="file" id="avatar" accept="image/*" 
                                       class="hidden" @change="previewAvatar($event)">
                            </label>
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">PNG, JPG up to 2MB</p>
                        <button type="submit" class="mt-3 px-4 py-1.5 text-sm bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                            Upload Photo
                        </button>
                    </form>
                </div>
            </div>

            <!-- Preferences -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Preferences</h4>
                    
                    <form @submit.prevent="updatePreferences()" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Language</label>
                            <select x-model="preferences.language"
                                    class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="en">English</option>
                                <option value="es">Spanish</option>
                                <option value="fr">French</option>
                                <option value="de">German</option>
                                <option value="zh">Chinese</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Timezone</label>
                            <select x-model="preferences.timezone"
                                    class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="UTC">UTC</option>
                                <option value="America/New_York">Eastern Time</option>
                                <option value="America/Chicago">Central Time</option>
                                <option value="America/Denver">Mountain Time</option>
                                <option value="America/Los_Angeles">Pacific Time</option>
                                <option value="Europe/London">London</option>
                                <option value="Europe/Paris">Paris</option>
                                <option value="Asia/Dubai">Dubai</option>
                                <option value="Asia/Singapore">Singapore</option>
                                <option value="Australia/Sydney">Sydney</option>
                            </select>
                        </div>

                        <button type="submit" 
                                class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all">
                            <i class="fas fa-save mr-1.5"></i>Save Preferences
                        </button>
                    </form>
                </div>
            </div>

            <!-- Account Stats -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Account Stats</h4>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Member Since</span>
                            <span class="text-gray-900 dark:text-white">{{ auth()->user()->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Last Login</span>
                            <span class="text-gray-900 dark:text-white">{{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('M d, Y h:i A') : 'Never' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Role</span>
                            <span class="text-gray-900 dark:text-white capitalize">{{ auth()->user()->role }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function profileSettings() {
    return {
        profile: @json(auth()->user()->only(['name', 'email', 'phone', 'bio'])),
        password: {
            current: '',
            new: '',
            confirmation: ''
        },
        preferences: {
            language: 'en',
            timezone: 'UTC'
        },
        avatarFile: null,
        
        updateProfile() {
            fetch('{{ route("admin.users.profile.update") }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(this.profile)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Profile updated successfully!');
                }
            })
            .catch(error => console.error('Error updating profile:', error));
        },
        
        updatePassword() {
            if (this.password.new !== this.password.confirmation) {
                alert('New passwords do not match!');
                return;
            }
            
            fetch('{{ route("admin.users.profile.password") }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(this.password)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Password updated successfully!');
                    this.password = { current: '', new: '', confirmation: '' };
                } else {
                    alert(data.error || 'Error updating password');
                }
            })
            .catch(error => console.error('Error updating password:', error));
        },
        
        previewAvatar(event) {
            const file = event.target.files[0];
            if (file) {
                this.avatarFile = file;
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        
        updateAvatar() {
            if (!this.avatarFile) return;
            
            const formData = new FormData();
            formData.append('avatar', this.avatarFile);
            
            fetch('{{ route("admin.users.profile.avatar") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error updating avatar:', error));
        },
        
        updatePreferences() {
            fetch('{{ route("admin.users.profile.preferences") }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(this.preferences)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Preferences updated successfully!');
                }
            })
            .catch(error => console.error('Error updating preferences:', error));
        }
    }
}
</script>
@endpush