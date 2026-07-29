/**
 * Blog Page Specific JavaScript
 */

document.addEventListener('DOMContentLoaded', () => {
    const blog = {
        init() {
            this.initSearch();
            this.initLoadMore();
        },
        
        initSearch() {
            const searchInput = document.querySelector('[data-blog-search]');
            if (!searchInput) return;
            
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const query = this.value;
                    if (query.length >= 2) {
                        blog.searchPosts(query);
                    }
                }, 500);
            });
        },
        
        async searchPosts(query) {
            const resultsContainer = document.querySelector('[data-search-results]');
            if (!resultsContainer) return;
            
            try {
                const response = await fetch(`/blog/search?q=${encodeURIComponent(query)}`);
                const data = await response.json();
                
                if (data.length) {
                    resultsContainer.innerHTML = data.map(post => `
                        <div class="search-result">
                            <h3><a href="/blog/${post.slug}">${post.title}</a></h3>
                            <p>${post.excerpt}</p>
                        </div>
                    `).join('');
                } else {
                    resultsContainer.innerHTML = '<p class="text-center text-gray-500">No results found</p>';
                }
            } catch (error) {
                console.error('Search error:', error);
            }
        },
        
        initLoadMore() {
            const loadMoreBtn = document.querySelector('[data-load-more]');
            if (!loadMoreBtn) return;
            
            let page = 1;
            loadMoreBtn.addEventListener('click', async function() {
                page++;
                const container = document.querySelector('[data-blog-posts]');
                
                try {
                    const response = await fetch(`/blog?page=${page}`);
                    const html = await response.text();
                    
                    // Append new posts
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;
                    const newPosts = tempDiv.querySelector('[data-blog-posts]');
                    
                    if (newPosts) {
                        container.innerHTML += newPosts.innerHTML;
                    }
                    
                    // Check if last page
                    const hasMore = tempDiv.querySelector('[data-load-more]');
                    if (!hasMore) {
                        this.style.display = 'none';
                    }
                } catch (error) {
                    console.error('Load more error:', error);
                }
            });
        }
    };
    
    blog.init();
});