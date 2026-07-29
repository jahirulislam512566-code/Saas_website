/**
 * Portfolio Page Specific JavaScript
 */

document.addEventListener('DOMContentLoaded', () => {
    const portfolio = {
        init() {
            this.initFilter();
            this.initLightbox();
            this.initIsotope();
        },
        
        initFilter() {
            const filterButtons = document.querySelectorAll('[data-filter]');
            const items = document.querySelectorAll('.portfolio-item');
            
            if (!filterButtons.length) return;
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const filter = this.dataset.filter;
                    
                    // Update active state
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Filter items
                    items.forEach(item => {
                        if (filter === 'all' || item.dataset.category === filter) {
                            item.style.display = 'block';
                            setTimeout(() => {
                                item.classList.add('visible');
                            }, 50);
                        } else {
                            item.classList.remove('visible');
                            setTimeout(() => {
                                item.style.display = 'none';
                            }, 300);
                        }
                    });
                });
            });
        },
        
        initLightbox() {
            // Lightbox is handled by gallery component
        },
        
        initIsotope() {
            // If using Isotope library for grid layout
            if (typeof Isotope !== 'undefined') {
                const grid = document.querySelector('.portfolio-grid');
                if (!grid) return;
                
                const iso = new Isotope(grid, {
                    itemSelector: '.portfolio-item',
                    layoutMode: 'masonry'
                });
                
                // Refresh layout after images load
                imagesLoaded(grid, () => {
                    iso.layout();
                });
                
                // Re-layout on resize
                window.addEventListener('resize', () => {
                    iso.layout();
                });
            }
        }
    };
    
    portfolio.init();
});