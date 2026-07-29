/**
 * Alpine.js - Modal Component
 */

document.addEventListener('alpine:init', () => {
    Alpine.data('modal', (options = {}) => ({
        isOpen: false,
        size: options.size || 'md',
        title: options.title || '',
        closeOnOverlay: options.closeOnOverlay !== false,
        
        init() {
            // Close on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.isOpen) {
                    this.close();
                }
            });
            
            // Lock body scroll when modal opens
            this.$watch('isOpen', (value) => {
                document.body.style.overflow = value ? 'hidden' : '';
            });
        },
        
        open() {
            this.isOpen = true;
        },
        
        close() {
            this.isOpen = false;
            this.$dispatch('modal-close');
        },
        
        closeOnOverlayClick(e) {
            if (this.closeOnOverlay && e.target === e.currentTarget) {
                this.close();
            }
        }
    }));
});