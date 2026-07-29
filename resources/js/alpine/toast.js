/**
 * Alpine.js - Toast Component
 */

document.addEventListener('alpine:init', () => {
    Alpine.data('toast', () => ({
        toasts: [],
        position: 'bottom-right',
        
        init() {
            // Listen for toast events
            window.addEventListener('show-toast', (e) => {
                this.show(e.detail.message, e.detail.type || 'success');
            });
        },
        
        show(message, type = 'success') {
            const id = Date.now();
            this.toasts.push({ id, message, type });
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                this.remove(id);
            }, 5000);
        },
        
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        },
        
        getTypeClass(type) {
            const classes = {
                success: 'bg-green-500 text-white',
                error: 'bg-red-500 text-white',
                warning: 'bg-yellow-500 text-white',
                info: 'bg-blue-500 text-white',
            };
            return classes[type] || classes.info;
        }
    }));
});