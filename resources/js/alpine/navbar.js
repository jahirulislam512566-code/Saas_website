/**
 * Alpine.js - Navbar Component
 */

document.addEventListener('alpine:init', () => {
    Alpine.data('navbar', () => ({
        mobileOpen: false,
        dropdownOpen: null,
        isScrolled: false,
        lastScroll: 0,
        
        init() {
            // Handle scroll events
            window.addEventListener('scroll', () => {
                const currentScroll = window.pageYOffset;
                this.isScrolled = currentScroll > 50;
                this.lastScroll = currentScroll;
            }, { passive: true });
            
            // Close mobile menu on resize
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    this.mobileOpen = false;
                }
            });
        },
        
        toggleMobile() {
            this.mobileOpen = !this.mobileOpen;
            document.body.style.overflow = this.mobileOpen ? 'hidden' : '';
        },
        
        toggleDropdown(name) {
            this.dropdownOpen = this.dropdownOpen === name ? null : name;
        },
        
        closeDropdown() {
            this.dropdownOpen = null;
        }
    }));
});