{{-- resources/views/frontend/partials/sidebar.blade.php --}}
@props([
    'title' => 'Sidebar',
    'items' => [],
    'sticky' => false,
])

<aside class="w-full {{ $sticky ? 'lg:sticky lg:top-24' : '' }} space-y-6">
    {{-- Sidebar Content --}}
    @if(isset($header))
        <div class="bg-white rounded-2xl p-6 shadow-md">
            {{ $header }}
        </div>
    @endif

    {{-- Navigation --}}
    @if(!empty($items))
        <div class="bg-white rounded-2xl p-6 shadow-md">
            @if($title)
                <h3 class="text-lg font-bold text-[#0a0a0a] mb-4">{{ $title }}</h3>
            @endif
            <nav>
                <ul class="space-y-2">
                    @foreach($items as $item)
                        <li>
                            <a href="{{ $item['url'] ?? '#' }}" 
                               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-[#4a4a4a] hover:bg-[#6c5ce7]/5 hover:text-[#6c5ce7] transition-all duration-200 {{ isset($item['active']) && $item['active'] ? 'bg-[#6c5ce7]/10 text-[#6c5ce7] font-medium' : '' }}">
                                @if(isset($item['icon']))
                                    <span class="text-lg">{{ $item['icon'] }}</span>
                                @endif
                                {{ $item['label'] }}
                                @if(isset($item['badge']))
                                    <span class="ml-auto text-xs px-2 py-0.5 bg-[#6c5ce7]/10 text-[#6c5ce7] rounded-full">{{ $item['badge'] }}</span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
    @endif

    {{-- Additional Content --}}
    {{ $slot }}

    {{-- Footer --}}
    @if(isset($footer))
        <div class="bg-white rounded-2xl p-6 shadow-md">
            {{ $footer }}
        </div>
    @endif

    {{-- Recent Posts --}}
    @if(isset($recentPosts) && !empty($recentPosts))
        <div class="bg-white rounded-2xl p-6 shadow-md">
            <h3 class="text-lg font-bold text-[#0a0a0a] mb-4">Recent Posts</h3>
            <ul class="space-y-3">
                @foreach($recentPosts as $post)
                    <li>
                        <a href="{{ $post['url'] ?? '#' }}" class="group block">
                            <span class="text-sm font-medium text-[#0a0a0a] group-hover:text-[#6c5ce7] transition-colors">{{ $post['title'] }}</span>
                            <span class="text-xs text-[#4a4a4a] block">{{ $post['date'] ?? '' }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tags --}}
    @if(isset($tags) && !empty($tags))
        <div class="bg-white rounded-2xl p-6 shadow-md">
            <h3 class="text-lg font-bold text-[#0a0a0a] mb-4">Tags</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($tags as $tag)
                    <a href="{{ $tag['url'] ?? '#' }}" class="px-3 py-1.5 bg-gray-100 text-gray-700 text-sm rounded-full hover:bg-[#6c5ce7] hover:text-white transition-colors no-underline">
                        #{{ $tag['name'] }}
                        @if(isset($tag['count']))
                            <span class="text-xs opacity-60">({{ $tag['count'] }})</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Newsletter --}}
    @if(isset($newsletter) && $newsletter)
        <div class="bg-gradient-to-br from-[#6c5ce7] to-[#a78bfa] rounded-2xl p-6 text-white">
            <h3 class="text-lg font-bold">Subscribe</h3>
            <p class="text-sm text-white/80 mt-1">Get updates and insights</p>
            <form class="mt-3">
                <input type="email" placeholder="Your email" 
                       class="w-full px-4 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white placeholder:text-white/70 focus:outline-none focus:ring-2 focus:ring-white/30 text-sm">
                <button type="submit" class="w-full mt-2 px-4 py-2.5 bg-white text-[#6c5ce7] rounded-xl font-medium hover:bg-gray-100 transition-colors text-sm">
                    Subscribe
                </button>
            </form>
        </div>
    @endif
</aside>