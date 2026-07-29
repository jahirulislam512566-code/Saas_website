{{-- resources/views/components/ui/modal.blade.php --}}
@props([
    'id' => 'modal',
    'title' => null,
    'size' => 'md',
    'show' => false,
])

@php
    $sizes = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        'full' => 'max-w-7xl',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

@if($show)
    <div class="fixed inset-0 z-[2000] overflow-y-auto modal-overlay" id="{{ $id }}-overlay">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeModal('{{ $id }}')"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full {{ $sizeClass }} transform transition-all modal-content" id="{{ $id }}-content">
                @if($title || isset($header))
                    <div class="flex items-center justify-between p-6 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-[#0a0a0a]">{{ $title ?? '' }}</h3>
                        <button class="text-2xl text-[#4a4a4a] hover:text-[#6c5ce7] transition-colors bg-transparent border-none cursor-pointer" onclick="closeModal('{{ $id }}')">✕</button>
                    </div>
                @endif
                <div class="p-6">
                    {{ $slot }}
                </div>
                @if(isset($footer))
                    <div class="p-6 pt-0 border-t border-gray-100">
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function closeModal(id) {
            document.getElementById(id + '-overlay')?.remove();
            document.getElementById(id + '-content')?.remove();
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const overlay = document.querySelector('.modal-overlay');
                if (overlay) {
                    const id = overlay.id.replace('-overlay', '');
                    closeModal(id);
                }
            }
        });
    </script>
@endif