/**
 * Lazy Load Component - Lazy load images and iframes
 */

document.addEventListener('DOMContentLoaded', () => {
    class LazyLoad {
        constructor() {
            this.init();
        }
        
        init() {
            if (!('IntersectionObserver' in window)) {
                this.loadAll();
                return;
            }
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const element = entry.target;
                        this.loadElement(element);
                        observer.unobserve(element);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });
            
            document.querySelectorAll('[data-src], [data-background]').forEach((element) => {
                observer.observe(element);
            });
        }
        
        loadElement(element) {
            if (element.dataset.src) {
                element.src = element.dataset.src;
                element.removeAttribute('data-src');
                element.addEventListener('load', () => {
                    element.classList.add('loaded');
                });
            }
            
            if (element.dataset.srcset) {
                element.srcset = element.dataset.srcset;
                element.removeAttribute('data-srcset');
            }
            
            if (element.dataset.background) {
                element.style.backgroundImage = `url(${element.dataset.background})`;
                element.removeAttribute('data-background');
                element.classList.add('loaded');
            }
        }
        
        loadAll() {
            document.querySelectorAll('[data-src]').forEach((element) => {
                this.loadElement(element);
            });
        }
    }
    
    new LazyLoad();
});