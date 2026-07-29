{{-- resources/views/components/forms/upload.blade.php --}}
@props([
    'name' => '',
    'id' => null,
    'label' => null,
    'accept' => 'image/*',
    'multiple' => false,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'maxSize' => null,
    'preview' => false,
    'existing' => null,
])

@php
    $id = $id ?? $name;
    $multipleAttr = $multiple ? 'multiple' : '';
    $acceptAttr = $accept ? 'accept="' . $accept . '"' : '';
    $disabledClass = $disabled ? 'opacity-60 cursor-not-allowed' : '';
    $errorClass = $error ? 'border-red-500' : 'border-gray-300 hover:border-[#6c5ce7]';
    $existingFiles = is_array($existing) ? $existing : [$existing];
@endphp

<div class="w-full" x-data="{ 
    files: [], 
    dragOver: false,
    previews: @json($existing ? array_filter($existingFiles) : []),
    removeFile(index) {
        this.previews.splice(index, 1);
        this.files.splice(index, 1);
        const input = document.getElementById('{{ $id }}');
        const dt = new DataTransfer();
        this.files.forEach(f => dt.items.add(f));
        input.files = dt.files;
        input.dispatchEvent(new Event('change'));
    }
}">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-[#0a0a0a] mb-1.5">
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-0.5">*</span>
            @endif
            @if($maxSize)
                <span class="text-xs text-[#4a4a4a] font-normal">(Max {{ $maxSize }}MB)</span>
            @endif
        </label>
    @endif

    {{-- Preview existing files --}}
    @if($existing && $preview)
        <div class="flex flex-wrap gap-3 mb-3">
            <template x-for="(preview, index) in previews" :key="index">
                <div class="relative group">
                    <img :src="preview" alt="Preview" class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                    <button type="button" 
                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600 transition-colors"
                            x-on:click="removeFile(index)">
                        ✕
                    </button>
                </div>
            </template>
        </div>
    @endif

    {{-- Upload Area --}}
    <div class="relative">
        <div class="border-2 border-dashed {{ $errorClass }} rounded-xl p-8 text-center transition-all duration-200 {{ $disabledClass }}"
             x-bind:class="{ 'border-[#6c5ce7] bg-[#6c5ce7]/5': dragOver }"
             x-on:dragover.prevent="dragOver = true"
             x-on:dragleave.prevent="dragOver = false"
             x-on:drop.prevent="dragOver = false; $refs.input.files = $event.dataTransfer.files; $el.dispatchEvent(new Event('change')); files = $event.dataTransfer.files">
            
            <input type="file"
                   name="{{ $name }}{{ $multiple ? '[]' : '' }}"
                   id="{{ $id }}"
                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer {{ $disabled ? 'cursor-not-allowed' : '' }}"
                   {{ $required ? 'required' : '' }}
                   {{ $disabled ? 'disabled' : '' }}
                   {{ $multipleAttr }}
                   {!! $acceptAttr !!}
                   x-ref="input"
                   x-on:change="files = $event.target.files; previews = []; for(let file of files) { const reader = new FileReader(); reader.onload = (e) => { previews.push(e.target.result); }; reader.readAsDataURL(file); }"
                   {{ $attributes->except(['class', 'name', 'id', 'accept', 'multiple', 'required', 'disabled']) }}
            >

            <div class="flex flex-col items-center gap-2">
                <span class="text-4xl">📁</span>
                <div>
                    <span class="text-[#6c5ce7] font-semibold">Click to upload</span>
                    <span class="text-[#4a4a4a]"> or drag and drop</span>
                </div>
                <p class="text-sm text-[#4a4a4a]">
                    @if($accept)
                        {{ str_replace('*', '', $accept) }}
                    @else
                        Any file type
                    @endif
                    @if($maxSize)
                        • Max {{ $maxSize }}MB
                    @endif
                </p>
                <p class="text-xs text-[#4a4a4a]" x-show="files.length > 0">
                    <span x-text="files.length + ' file(s) selected'"></span>
                </p>
            </div>
        </div>
    </div>

    @if($error)
        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
            <span>⚠️</span> {{ $error }}
        </p>
    @endif

    @if($help && !$error)
        <p class="mt-1.5 text-sm text-[#4a4a4a]">{{ $help }}</p>
    @endif
</div>