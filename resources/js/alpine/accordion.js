/**
 * Alpine.js - Accordion Component
 */

document.addEventListener('alpine:init', () => {
    Alpine.data('accordion', (options = {}) => ({
        openItems: options.openItems || [],
        multiple: options.multiple || false,
        
        toggle(index) {
            if (this.multiple) {
                const idx = this.openItems.indexOf(index);
                if (idx > -1) {
                    this.openItems.splice(idx, 1);
                } else {
                    this.openItems.push(index);
                }
            } else {
                if (this.openItems[0] === index) {
                    this.openItems = [];
                } else {
                    this.openItems = [index];
                }
            }
        },
        
        isOpen(index) {
            return this.openItems.includes(index);
        }
    }));
});