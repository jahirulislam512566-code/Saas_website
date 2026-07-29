/**
 * Alpine.js - Main App Component
 */

document.addEventListener('alpine:init', () => {
    Alpine.data('app', () => ({
        // Global state
        isLoading: false,
        isDarkMode: false,
        currentRoute: window.location.pathname,
        
        init() {
            // Check for dark mode preference
            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                this.isDarkMode = true;
                document.documentElement.classList.add('dark');
            }
            
            // Listen for route changes
            window.addEventListener('popstate', () => {
                this.currentRoute = window.location.pathname;
            });
        },
        
        toggleTheme() {
            this.isDarkMode = !this.isDarkMode;
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', this.isDarkMode);
        },
        
        navigate(url) {
            this.isLoading = true;
            window.location.href = url;
        }
    }));
});