/**
 * Alpine.js - Theme Component
 */

document.addEventListener('alpine:init', () => {
    Alpine.data('theme', () => ({
        dark: false,
        
        init() {
            // Check local storage
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                this.dark = true;
                document.documentElement.classList.add('dark');
            } else if (savedTheme === 'light') {
                this.dark = false;
                document.documentElement.classList.remove('dark');
            } else {
                // Check system preference
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    this.dark = true;
                    document.documentElement.classList.add('dark');
                }
            }
        },
        
        toggle() {
            this.dark = !this.dark;
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
            this.$dispatch('theme-change', { dark: this.dark });
        }
    }));
});