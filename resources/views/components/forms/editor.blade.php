{{-- resources/views/components/forms/editor.blade.php --}}
@props([
    'name' => '',
    'id' => null,
    'label' => null,
    'value' => '',
    'placeholder' => 'Write something...',
    'rows' => 6,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'error' => null,
    'help' => null,
    'toolbar' => true,
    'minHeight' => '200px',
])

@php
    $id = $id ?? $name;
    $errorClass = $error ? 'border-red-500' : 'border-gray-200';
    $disabledClass = $disabled || $readonly ? 'bg-gray-50 cursor-not-allowed' : '';
    $toolbar = [
        ['bold', 'italic', 'underline', 'strike'],
        ['h1', 'h2', 'h3'],
        ['ul', 'ol'],
        ['link', 'image', 'code'],
        ['clean']
    ];
@endphp

<div class="w-full" x-data="{
    content: @json(old($name, $value)),
    isFocused: false,
    execCommand(command, value = null) {
        document.execCommand(command, false, value);
        this.$refs.editor.focus();
        this.updateContent();
    },
    updateContent() {
        this.content = this.$refs.editor.innerHTML;
        this.$refs.hiddenInput.value = this.content;
    },
    init() {
        const editor = this.$refs.editor;
        editor.innerHTML = this.content;
        editor.addEventListener('input', () => this.updateContent());
        
        // Handle paste to clean formatting
        editor.addEventListener('paste', (e) => {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text/plain');
            document.execCommand('insertHTML', false, text);
        });
    }
}">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-[#0a0a0a] mb-1.5">
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-0.5">*</span>
            @endif
        </label>
    @endif

    <div class="border-2 {{ $errorClass }} rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-[#6c5ce7]/20 focus-within:border-[#6c5ce7] transition-all duration-200 {{ $disabledClass }}">
        {{-- Toolbar --}}
        @if($toolbar)
            <div class="flex flex-wrap gap-1 p-2 border-b border-gray-200 bg-gray-50/50">
                @foreach($toolbar as $group)
                    <div class="flex gap-1 items-center">
                        @foreach($group as $command)
                            @php
                                $buttonMap = [
                                    'bold' => ['icon' => '𝐁', 'title' => 'Bold', 'cmd' => 'bold'],
                                    'italic' => ['icon' => '𝐼', 'title' => 'Italic', 'cmd' => 'italic'],
                                    'underline' => ['icon' => '𝑈', 'title' => 'Underline', 'cmd' => 'underline'],
                                    'strike' => ['icon' => '𝑆', 'title' => 'Strikethrough', 'cmd' => 'strikeThrough'],
                                    'h1' => ['icon' => 'H1', 'title' => 'Heading 1', 'cmd' => 'formatBlock', 'arg' => 'h1'],
                                    'h2' => ['icon' => 'H2', 'title' => 'Heading 2', 'cmd' => 'formatBlock', 'arg' => 'h2'],
                                    'h3' => ['icon' => 'H3', 'title' => 'Heading 3', 'cmd' => 'formatBlock', 'arg' => 'h3'],
                                    'ul' => ['icon' => '•', 'title' => 'Bullet List', 'cmd' => 'insertUnorderedList'],
                                    'ol' => ['icon' => '1.', 'title' => 'Numbered List', 'cmd' => 'insertOrderedList'],
                                    'link' => ['icon' => '🔗', 'title' => 'Insert Link', 'cmd' => 'createLink', 'arg' => 'https://'],
                                    'image' => ['icon' => '🖼️', 'title' => 'Insert Image', 'cmd' => 'insertImage', 'arg' => 'https://'],
                                    'code' => ['icon' => '</>', 'title' => 'Code', 'cmd' => 'formatBlock', 'arg' => 'pre'],
                                    'clean' => ['icon' => '🧹', 'title' => 'Remove Formatting', 'cmd' => 'removeFormat'],
                                ];
                                $btn = $buttonMap[$command] ?? null;
                            @endphp
                            @if($btn)
                                <button type="button"
                                        class="p-1.5 rounded hover:bg-gray-200 transition-colors text-sm font-medium w-8 h-8 flex items-center justify-center"
                                        title="{{ $btn['title'] }}"
                                        x-on:click="execCommand('{{ $btn['cmd'] }}', '{{ $btn['arg'] ?? '' }}')">
                                    {{ $btn['icon'] }}
                                </button>
                            @endif
                        @endforeach
                        @if(!$loop->last)
                            <span class="w-px h-6 bg-gray-300 mx-1"></span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Editor Content --}}
        <div 
            x-ref="editor"
            contenteditable="{{ $disabled || $readonly ? 'false' : 'true' }}"
            class="p-4 outline-none min-h-[{{ $minHeight }}] text-[#0a0a0a] prose prose-sm max-w-none"
            x-bind:class="{ 'opacity-60 cursor-not-allowed': {{ $disabled || $readonly ? 'true' : 'false' }} }"
            x-on:focus="isFocused = true"
            x-on:blur="isFocused = false; updateContent()"
            placeholder="{{ $placeholder }}"
            data-placeholder="{{ $placeholder }}"
            x-init="init()"
            style="caret-color: #6c5ce7;"
        ></div>

        {{-- Hidden Input for Form Submission --}}
        <textarea name="{{ $name }}" id="{{ $id }}" x-ref="hiddenInput" class="hidden"></textarea>
    </div>

    @if($error)
        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
            <span>⚠️</span> {{ $error }}
        </p>
    @endif

    @if($help && !$error)
        <p class="mt-1.5 text-sm text-[#4a4a4a]">{{ $help }}</p>
    @endif

    {{-- Editor Styles --}}
    <style>
        [contenteditable]:empty::before {
            content: attr(data-placeholder);
            color: #9ca3af;
            pointer-events: none;
        }
        [contenteditable] h1 { font-size: 2rem; font-weight: 700; margin: 0.5rem 0; }
        [contenteditable] h2 { font-size: 1.5rem; font-weight: 600; margin: 0.5rem 0; }
        [contenteditable] h3 { font-size: 1.25rem; font-weight: 600; margin: 0.5rem 0; }
        [contenteditable] ul, [contenteditable] ol { padding-left: 1.5rem; margin: 0.5rem 0; }
        [contenteditable] pre { background: #f3f4f6; padding: 0.75rem; border-radius: 0.5rem; overflow-x: auto; }
        [contenteditable] a { color: #6c5ce7; text-decoration: underline; }
        [contenteditable] img { max-width: 100%; border-radius: 0.5rem; margin: 0.5rem 0; }
    </style>
</div>