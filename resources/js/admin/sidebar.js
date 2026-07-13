// resources/js/admin/sidebar.js
export function sidebarComponent() {
    return {
        // State
        isOpen: window.innerWidth >= 1024,
        isCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' || false,
        isHovering: false,
        userMenuOpen: false,
        isDesktop: window.innerWidth >= 1024,
        isMobileOpen: false, // Add this
        windowWidth: window.innerWidth, // Add this
        resizeTimer: null,

        // Init method
        init() {
            // Register with Alpine store for child components
            if (window.Alpine) {
                window.Alpine.store('sidebar', {
                    isCollapsed: this.isCollapsed,
                    isHovering: this.isHovering,
                    isDesktop: this.isDesktop,
                    isOpen: this.isOpen,
                    isMobileOpen: this.isMobileOpen,
                    closeSidebar: () => this.closeSidebar(),
                    toggleCollapse: () => this.toggleCollapse(),
                    toggleSidebar: () => this.toggleSidebar(),
                    closeMobileSidebar: () => this.closeMobileSidebar(),
                });
            }

            // Watch for changes and update store
            this.$watch('isCollapsed', (value) => {
                localStorage.setItem('sidebarCollapsed', JSON.stringify(value));
                if (window.Alpine) {
                    window.Alpine.store('sidebar').isCollapsed = value;
                }
            });

            this.$watch('isHovering', (value) => {
                if (window.Alpine) {
                    window.Alpine.store('sidebar').isHovering = value;
                }
            });

            this.$watch('isDesktop', (value) => {
                if (window.Alpine) {
                    window.Alpine.store('sidebar').isDesktop = value;
                }
            });

            this.$watch('isMobileOpen', (value) => {
                if (window.Alpine) {
                    window.Alpine.store('sidebar').isMobileOpen = value;
                }
            });

            // Handle resize with debounce
            window.addEventListener('resize', () => {
                clearTimeout(this.resizeTimer);
                this.resizeTimer = setTimeout(() => {
                    this.windowWidth = window.innerWidth;
                    this.isDesktop = window.innerWidth >= 1024;
                    
                    if (this.isDesktop) {
                        this.isOpen = true;
                        this.isMobileOpen = false;
                    } else {
                        this.isOpen = false;
                    }
                    
                    if (window.Alpine) {
                        window.Alpine.store('sidebar').isDesktop = this.isDesktop;
                        window.Alpine.store('sidebar').isOpen = this.isOpen;
                        window.Alpine.store('sidebar').isMobileOpen = this.isMobileOpen;
                    }
                }, 150);
            });

            // Close sidebar on route change (for mobile)
            document.addEventListener('livewire:navigated', () => {
                if (!this.isDesktop) {
                    this.closeMobileSidebar();
                }
            });

            // Restore state from localStorage
            const savedState = localStorage.getItem('sidebarOpen');
            if (savedState !== null && this.isDesktop) {
                this.isOpen = JSON.parse(savedState);
            }

            console.log('✅ Sidebar initialized', {
                isOpen: this.isOpen,
                isCollapsed: this.isCollapsed,
                isDesktop: this.isDesktop,
                isMobileOpen: this.isMobileOpen,
                windowWidth: this.windowWidth
            });
        },

        // Toggle sidebar (mobile)
        toggleSidebar() {
            this.isMobileOpen = !this.isMobileOpen;
            if (this.isMobileOpen) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
            if (window.Alpine) {
                window.Alpine.store('sidebar').isMobileOpen = this.isMobileOpen;
            }
        },

        // Open sidebar (mobile)
        openSidebar() {
            this.isMobileOpen = true;
            document.body.style.overflow = 'hidden';
            if (window.Alpine) {
                window.Alpine.store('sidebar').isMobileOpen = this.isMobileOpen;
            }
        },

        // Close sidebar (mobile)
        closeMobileSidebar() {
            this.isMobileOpen = false;
            document.body.style.overflow = '';
            if (window.Alpine) {
                window.Alpine.store('sidebar').isMobileOpen = this.isMobileOpen;
            }
        },

        // Close sidebar (desktop)
        closeSidebar() {
            this.isOpen = false;
            if (window.Alpine) {
                window.Alpine.store('sidebar').isOpen = this.isOpen;
            }
        },

        // Toggle collapse (desktop)
        toggleCollapse() {
            this.isCollapsed = !this.isCollapsed;
            if (window.Alpine) {
                window.Alpine.store('sidebar').isCollapsed = this.isCollapsed;
            }
        },

        // Toggle user menu
        toggleUserMenu() {
            this.userMenuOpen = !this.userMenuOpen;
        },

        // Perform search
        performSearch(query) {
            if (query.trim().length > 0) {
                window.location.href = `/admin/search?q=${encodeURIComponent(query)}`;
            }
        }
    };
}