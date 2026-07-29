/**
 * Alpine.js - Tabs Component
 */

document.addEventListener('alpine:init', () => {
    Alpine.data('tabs', (options = {}) => ({
        activeTab: options.active || 0,
        
        setActive(index) {
            this.activeTab = index;
            this.$dispatch('tab-change', { index });
        },
        
        isActive(index) {
            return this.activeTab === index;
        }
    }));
});