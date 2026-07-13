{{-- resources/views/components/website/contact-form.blade.php --}}
<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-sm border border-gray-100 p-6']) }}>
    <h3 class="text-lg font-bold text-gray-900 mb-4">Send us a Message</h3>
    
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            {{ session('success') }}
        </div>
    @endif
    
    <form method="POST" action="{{ route('website.contact.store') }}">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Your Name</label>
                <input type="text" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-red-500 @enderror" value="{{ old('name') }}" required>
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('email') border-red-500 @enderror" value="{{ old('email') }}" required>
                @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
            <input type="text" name="subject" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('subject') border-red-500 @enderror" value="{{ old('subject') }}" required>
            @error('subject')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
            <textarea name="message" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('message') border-red-500 @enderror" required>{{ old('message') }}</textarea>
            @error('message')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mt-6">
            <button type="submit" class="w-full px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm hover:shadow">
                Send Message
                <i class="fas fa-paper-plane ml-2"></i>
            </button>
        </div>
    </form>
</div>