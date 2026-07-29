/**
 * Animation Component - Scroll animations
 */

document.addEventListener('DOMContentLoaded', () => {
    class Animation {
        constructor() {
            this.init();
        }
        
        init() {
            if (!('IntersectionObserver' in window)) return;
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const element = entry.target;
                        const animation = element.dataset.animation || 'fadeIn';
                        const delay = parseInt(element.dataset.delay) || 0;
                        
                        setTimeout(() => {
                            element.classList.add('animated', animation);
                            element.style.opacity = '1';
                        }, delay);
                        
                        if (!element.dataset.repeat) {
                            observer.unobserve(element);
                        }
                    } else if (element.dataset.repeat) {
                        element.classList.remove('animated', animation);
                        element.style.opacity = '0';
                    }
                });
            }, { threshold: 0.1 });
            
            document.querySelectorAll('[data-animation]').forEach((element) => {
                element.style.opacity = '0';
                observer.observe(element);
            });
        }
    }
    
    new Animation();
});