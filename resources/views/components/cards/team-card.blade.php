{{-- resources/views/components/cards/team-card.blade.php --}}
@props([
    'image' => null,
    'name' => 'Team Member',
    'role' => 'Role',
    'bio' => null,
    'email' => null,
    'phone' => null,
    'socials' => [],
    'skills' => [],
])

<div class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden text-center">
    {{-- Image --}}
    <div class="relative aspect-square bg-gradient-to-br from-[#6c5ce7]/10 to-[#a78bfa]/10 overflow-hidden">
        @if($image)
            <img src="{{ $image }}" alt="{{ $name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        @else
            <div class="w-full h-full flex items-center justify-center text-8xl text-[#6c5ce7]/30">
                👤
            </div>
        @endif

        {{-- Social Overlay --}}
        @if(count($socials) > 0)
            <div class="absolute inset-0 bg-[#0a0a0a]/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-3">
                @foreach($socials as $platform => $url)
                    <a href="{{ $url }}" class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-white hover:text-[#0a0a0a] transition-colors" aria-label="{{ $platform }}">
                        @switch($platform)
                            @case('twitter') 🐦 @break
                            @case('linkedin') 🔗 @break
                            @case('github') 🐙 @break
                            @case('dribbble') 🏀 @break
                            @case('instagram') 📸 @break
                            @default {{ substr($platform, 0, 2) }}
                        @endswitch
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Info --}}
    <div class="p-6">
        <h3 class="text-lg font-bold text-[#0a0a0a]">{{ $name }}</h3>
        <p class="text-sm text-[#6c5ce7] font-medium">{{ $role }}</p>
        
        @if($bio)
            <p class="text-sm text-[#4a4a4a] mt-2">{{ $bio }}</p>
        @endif

        {{-- Skills --}}
        @if(count($skills) > 0)
            <div class="flex flex-wrap justify-center gap-2 mt-3">
                @foreach($skills as $skill)
                    <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">{{ $skill }}</span>
                @endforeach
            </div>
        @endif

        {{-- Contact --}}
        <div class="mt-4 pt-4 border-t border-gray-100 space-y-1 text-sm">
            @if($email)
                <div class="flex items-center justify-center gap-2 text-[#4a4a4a]">
                    <span>✉️</span>
                    <a href="mailto:{{ $email }}" class="hover:text-[#6c5ce7] transition-colors">{{ $email }}</a>
                </div>
            @endif
            @if($phone)
                <div class="flex items-center justify-center gap-2 text-[#4a4a4a]">
                    <span>📞</span>
                    <a href="tel:{{ $phone }}" class="hover:text-[#6c5ce7] transition-colors">{{ $phone }}</a>
                </div>
            @endif
        </div>
    </div>
</div>