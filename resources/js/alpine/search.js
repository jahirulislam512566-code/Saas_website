/**
 * Alpine.js - Search Component
 */

document.addEventListener('alpine:init', () => {
    Alpine.data('search', () => ({
        isOpen: false,
        query: '',
        results: [],
        isLoading: false,
        
        init() {
            // Keyboard shortcut
            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    this.open();
                }
                if (e.key === 'Escape') {
                    this.close();
                }
            });
        },
        
        open() {
            this.isOpen = true;
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                this.$refs.input?.focus();
            }, 100);
        },
        
        close() {
            this.isOpen = false;
            document.body.style.overflow = '';
            this.query = '';
            this.results = [];
        },
        
        async search() {
            if (this.query.length < 2) {
                this.results = [];
                return;
            }
            
            this.isLoading = true;
            try {
                const response = await fetch(`/api/search?q=${encodeURIComponent(this.query)}`);
                this.results = await response.json();
            } catch (error) {
                console.error('Search error:', error);
            } finally {
                this.isLoading = false;
            }
        }
    }));
});