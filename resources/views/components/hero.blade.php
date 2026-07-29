@props([
    'title' => 'Build Beautiful Websites Without Coding',
    'subtitle' => 'Create professional business websites in minutes. Hosting included. SEO ready. Fast. Secure.',
    'primaryButtonText' => 'Start Free Trial',
    'primaryButtonUrl' => '#',
    'secondaryButtonText' => 'Live Demo',
    'secondaryButtonUrl' => '#',
    'image' => null,
    'imageAlt' => 'Website Builder Dashboard',
    'badge' => null,
    'badgeUrl' => null,
    'stats' => [],
    'trustedBy' => [],
    'animation' => 'fade-up',
    'alignment' => 'left', // left, center, right
    'background' => 'gradient', // gradient, solid, image, video
    'backgroundImage' => null,
    'overlay' => true,
    'overlayOpacity' => 50,
    'height' => 'full', // full, half, auto
    'className' => '',
    'showWave' => true,
])

<!-- Hero Section -->
<section 
    class="relative overflow-hidden {{ $height === 'full' ? 'min-h-screen' : ($height === 'half' ? 'min-h-[60vh]' : 'py-20') }} flex items-center {{ $className }}"
    @style([
        'background' => $background === 'gradient' ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' : ($background === 'solid' ? '#667eea' : null),
        'background-image' => ($background === 'image' && $backgroundImage) ? "url('{$backgroundImage}')" : null,
        'background-size' => $background === 'image' ? 'cover' : null,
        'background-position' => $background === 'image' ? 'center' : null,
    ])
    x-data="heroSection()"
    x-init="init()"
>
    {{-- Overlay --}}
    @if($overlay)
        <div 
            class="absolute inset-0 bg-black/{{ $overlayOpacity }}"
            @style([
                'display: none;' => $background !== 'image' && $background !== 'video',
            ])
        ></div>
    @endif

    {{-- Background Video --}}
    @if($background === 'video' && isset($video))
        <video 
            class="absolute inset-0 w-full h-full object-cover"
            autoplay
            muted
            loop
            playsinline
        >
            <source src="{{ $video }}" type="video/mp4">
        </video>
    @endif

    {{-- Wave Divider --}}
    @if($showWave && $background !== 'image' && $background !== 'video')
        <div class="absolute bottom-0 left-0 right-0">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" class="w-full">
                <path fill="#ffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    @endif

    {{-- Badge --}}
    @if($badge)
        <div class="absolute top-8 left-1/2 -translate-x-1/2 z-20">
            <a 
                href="{{ $badgeUrl ?? '#' }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-full text-white text-sm font-medium hover:bg-white/20 transition-colors"
            >
                <span class="inline-block w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                {{ $badge }}
            </a>
        </div>
    @endif

    <div class="container relative z-10 mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            {{-- Content --}}
            <div 
                class="{{ $alignment === 'center' ? 'text-center lg:text-center' : ($alignment === 'right' ? 'text-center lg:text-right' : 'text-center lg:text-left') }}"
                x-data="{ visible: false }"
                x-init="setTimeout(() => visible = true, 100)"
                :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
                class="transition-all duration-1000"
            >
                {{-- Tagline --}}
                @if($tagline ?? false)
                    <div class="inline-block px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-white text-sm font-semibold mb-6">
                        {{ $tagline }}
                    </div>
                @endif

                {{-- Title --}}
                <h1 
                    class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6"
                    x-data="{ title: '{{ $title }}' }"
                >
                    <span 
                        class="bg-gradient-to-r from-white to-white/80 bg-clip-text text-transparent"
                        x-html="title"
                    ></span>
                </h1>

                {{-- Subtitle --}}
                <p class="text-lg sm:text-xl text-white/90 leading-relaxed mb-8 max-w-2xl {{ $alignment === 'center' ? 'mx-auto' : '' }} {{ $alignment === 'right' ? 'ml-auto' : '' }}">
                    {{ $subtitle }}
                </p>

                {{-- Buttons --}}
                <div class="flex flex-wrap gap-4 justify-{{ $alignment === 'center' ? 'center' : ($alignment === 'right' ? 'end' : 'start') }}">
                    @if($primaryButtonText)
                        <a 
                            href="{{ $primaryButtonUrl }}"
                            class="inline-flex items-center px-8 py-4 bg-white text-indigo-600 font-bold rounded-lg hover:bg-indigo-50 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl group"
                        >
                            {{ $primaryButtonText }}
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    @endif

                    @if($secondaryButtonText)
                        <a 
                            href="{{ $secondaryButtonUrl }}"
                            class="inline-flex items-center px-8 py-4 border-2 border-white/30 text-white font-bold rounded-lg hover:bg-white/10 transform hover:scale-105 transition-all duration-300"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $secondaryButtonText }}
                        </a>
                    @endif
                </div>

                {{-- Trust Indicators --}}
                @if(!empty($trustedBy))
                    <div class="mt-8">
                        <p class="text-sm text-white/70 uppercase tracking-wider mb-4">
                            Trusted by 10,000+ businesses worldwide
                        </p>
                        <div class="flex flex-wrap gap-6 justify-{{ $alignment === 'center' ? 'center' : ($alignment === 'right' ? 'end' : 'start') }} items-center">
                            @foreach($trustedBy as $logo)
                                <img 
                                    src="{{ $logo['src'] }}" 
                                    alt="{{ $logo['alt'] }}"
                                    class="h-8 w-auto grayscale brightness-200 opacity-70"
                                >
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Stats --}}
                @if(!empty($stats))
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8">
                        @foreach($stats as $stat)
                            <div class="text-center">
                                <div class="text-2xl font-bold text-white">{{ $stat['value'] }}</div>
                                <div class="text-sm text-white/70">{{ $stat['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Image/Media --}}
          @if($image || isset($media) || !empty(trim($slot)))
                <div 
                    class="relative"
                    x-data="{ visible: false }"
                    x-init="setTimeout(() => visible = true, 200)"
                    :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
                    class="transition-all duration-1000 delay-200"
                >
                    {{-- Image Container --}}
                    <div class="relative rounded-2xl shadow-2xl overflow-hidden">
                        @if($image)
                            <img 
                                src="{{ $image }}" 
                                alt="{{ $imageAlt }}"
                                class="w-full h-auto object-cover"
                                loading="lazy"
                            >
                        @else
                            {{ $slot }}
                        @endif

                        {{-- Floating Elements --}}
                        <div class="absolute -top-4 -right-4 bg-white rounded-xl shadow-xl p-4 animate-bounce-slow hidden sm:block">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-bold">100%</div>
                                    <div class="text-xs text-gray-500">Satisfaction</div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -bottom-4 -left-4 bg-white rounded-xl shadow-xl p-4 animate-bounce-slow animation-delay-500 hidden sm:block">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-bold">24/7</div>
                                    <div class="text-xs text-gray-500">Support</div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute top-1/2 -left-3 -translate-y-1/2 bg-white rounded-xl shadow-xl p-3 animate-bounce-slow animation-delay-1000 hidden lg:block">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-bold">4.9★</div>
                                    <div class="text-xs text-gray-500">Rating</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Floating Shapes Animation --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full animate-float"></div>
        <div class="absolute bottom-20 right-10 w-32 h-32 bg-white/5 rounded-full animate-float animation-delay-1000"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-white/5 rounded-full animate-pulse-slow"></div>
    </div>
</section>

@push('styles')
<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    @keyframes pulse-slow {
        0%, 100% { transform: scale(1); opacity: 0.1; }
        50% { transform: scale(1.1); opacity: 0.2; }
    }
    
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    
    .animate-bounce-slow {
        animation: bounce-slow 3s ease-in-out infinite;
    }
    
    .animate-pulse-slow {
        animation: pulse-slow 4s ease-in-out infinite;
    }
    
    .animation-delay-500 {
        animation-delay: 500ms;
    }
    
    .animation-delay-1000 {
        animation-delay: 1000ms;
    }
</style>
@endpush

@push('scripts')
<script>
    function heroSection() {
        return {
            init() {
                // Optional: Add parallax effect
                window.addEventListener('scroll', this.handleScroll.bind(this));
            },
            handleScroll() {
                const scrolled = window.pageYOffset;
                const hero = document.querySelector('section');
                if (hero) {
                    hero.style.backgroundPositionY = -(scrolled * 0.5) + 'px';
                }
            }
        }
    }
</script>
@endpush