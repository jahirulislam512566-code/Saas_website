/**
 * Alpine.js - Dropdown Component
 */

document.addEventListener('alpine:init', () => {
    Alpine.data('dropdown', (options = {}) => ({
        isOpen: false,
        selected: options.selected || null,
        items: options.items || [],
        
        init() {
            // Close dropdown on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.isOpen = false;
                }
            });
        },
        
        toggle() {
            this.isOpen = !this.isOpen;
        },
        
        close() {
            this.isOpen = false;
        },
        
        select(value) {
            this.selected = value;
            this.isOpen = false;
            this.$dispatch('dropdown-select', { value });
        },
        
        isSelected(value) {
            return this.selected === value;
        }
    }));
});