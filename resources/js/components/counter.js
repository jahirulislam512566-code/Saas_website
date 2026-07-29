/**
 * Counter Component - For statistics/numbers animation
 */

document.addEventListener('DOMContentLoaded', () => {
    class Counter {
        constructor(element) {
            this.element = element;
            this.target = parseInt(element.dataset.target);
            this.duration = parseInt(element.dataset.duration) || 2000;
            this.current = 0;
            this.isAnimated = false;
            this.prefix = element.dataset.prefix || '';
            this.suffix = element.dataset.suffix || '';
            
            this.init();
        }
        
        init() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !this.isAnimated) {
                        this.start();
                        this.isAnimated = true;
                    }
                });
            }, { threshold: 0.1 });
            
            observer.observe(this.element);
        }
        
        start() {
            const step = Math.ceil(this.target / (this.duration / 16));
            const interval = setInterval(() => {
                this.current += step;
                if (this.current >= this.target) {
                    this.current = this.target;
                    clearInterval(interval);
                }
                this.element.textContent = `${this.prefix}${this.current.toLocaleString()}${this.suffix}`;
            }, 16);
        }
    }
    
    // Initialize counters
    document.querySelectorAll('[data-counter]').forEach((element) => {
        new Counter(element);
    });
});