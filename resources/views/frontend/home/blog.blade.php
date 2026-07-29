{{-- resources/views/frontend/home/blog.blade.php --}}
<section class="py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">Latest Blog</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">Insights & Updates</h2>
            <p class="mt-4 text-lg text-[#4a4a4a]">Stay updated with the latest trends, tips, and insights from our industry experts.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $blogPosts = \App\Models\BlogPost::where('is_published', true)->limit(3)->get() ?? collect();
            @endphp

            @forelse($blogPosts as $post)
                <x-cards.blog-card 
                    image="{{ $post->featured_image ?? asset('images/blog/default.jpg') }}"
                    title="{{ $post->title }}"
                    excerpt="{{ $post->excerpt ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.' }}"
                    author="{{ $post->author_name ?? 'AgencyPro Team' }}"
                    date="{{ $post->published_at ? $post->published_at->format('M d, Y') : date('M d, Y') }}"
                    category="{{ $post->category->name ?? 'General' }}"
                    readingTime="{{ $post->reading_time ?? '5 min read' }}"
                    :tags="$post->tags->pluck('name')->toArray() ?? []"
                    link="{{ route('blog.show', $post->slug ?? '#') }}"
                    :featured="$post->is_featured ?? false"
                />
            @empty
                <x-cards.blog-card 
                    title="10 Web Design Trends That Will Dominate 2024"
                    excerpt="Discover the latest design trends that are shaping the future of the web."
                    author="Jane Smith"
                    date="Dec 18, 2024"
                    category="Design"
                    readingTime="10 min read"
                    :tags="['Web Design', 'Trends', 'UI/UX']"
                    link="#"
                    featured="true"
                />
                <x-cards.blog-card 
                    title="The Future of AI in Web Development"
                    excerpt="How artificial intelligence is revolutionizing the way we build and maintain web applications."
                    author="John Doe"
                    date="Dec 15, 2024"
                    category="Technology"
                    readingTime="8 min read"
                    :tags="['AI', 'Web Development', 'Technology']"
                    link="#"
                />
                <x-cards.blog-card 
                    title="SEO Strategies That Actually Work in 2024"
                    excerpt="Learn proven SEO strategies that can help you rank higher and attract more organic traffic."
                    author="Sarah Johnson"
                    date="Dec 12, 2024"
                    category="Marketing"
                    readingTime="6 min read"
                    :tags="['SEO', 'Marketing', 'Strategy']"
                    link="#"
                />
            @endforelse
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-[#6c5ce7] font-medium hover:gap-3 transition-all">
                View All Posts
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>