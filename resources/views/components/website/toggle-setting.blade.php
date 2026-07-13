{{-- resources/views/components/website/toggle-setting.blade.php --}}
@props([
    'label' => 'Setting Label',
    'description' => 'Setting description goes here.',
    'checked' => false,
    'name' => null
])

<div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
    <div>
        <label class="text-sm font-medium text-gray-900">{{ $label }}</label>
        <p class="text-sm text-gray-500">{{ $description }}</p>
    </div>
    <div class="flex-shrink-0 ml-4">
        <button type="button" 
                role="switch" 
                aria-checked="{{ $checked ? 'true' : 'false' }}"
                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $checked ? 'bg-indigo-600' : 'bg-gray-200' }}"
                onclick="this.setAttribute('aria-checked', this.getAttribute('aria-checked') === 'true' ? 'false' : 'true'); this.classList.toggle('bg-indigo-600'); this.classList.toggle('bg-gray-200');">
            <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $checked ? 'translate-x-5' : 'translate-x-0' }}"></span>
        </button>
    </div>
</div>