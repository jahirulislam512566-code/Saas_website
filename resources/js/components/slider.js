/**
 * Slider Component - For testimonials, portfolio, etc.
 */

document.addEventListener('DOMContentLoaded', () => {
    class Slider {
        constructor(element, options = {}) {
            this.element = element;
            this.slides = element.querySelectorAll('.slide');
            this.currentSlide = 0;
            this.totalSlides = this.slides.length;
            this.autoplay = options.autoplay || false;
            this.interval = options.interval || 5000;
            this.autoSlideInterval = null;
            
            this.init();
        }
        
        init() {
            this.showSlide(0);
            
            // Navigation buttons
            const prevBtn = this.element.querySelector('[data-slide-prev]');
            const nextBtn = this.element.querySelector('[data-slide-next]');
            
            if (prevBtn) prevBtn.addEventListener('click', () => this.prev());
            if (nextBtn) nextBtn.addEventListener('click', () => this.next());
            
            // Dot indicators
            const dots = this.element.querySelectorAll('[data-slide-dot]');
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => this.goTo(index));
            });
            
            // Start autoplay
            if (this.autoplay) {
                this.startAutoplay();
            }
        }
        
        showSlide(index) {
            this.slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === index);
            });
            
            // Update dots
            const dots = this.element.querySelectorAll('[data-slide-dot]');
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
            });
            
            this.currentSlide = index;
        }
        
        next() {
            const next = (this.currentSlide + 1) % this.totalSlides;
            this.showSlide(next);
        }
        
        prev() {
            const prev = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
            this.showSlide(prev);
        }
        
        goTo(index) {
            this.showSlide(index);
            if (this.autoplay) {
                this.resetAutoplay();
            }
        }
        
        startAutoplay() {
            this.autoSlideInterval = setInterval(() => {
                this.next();
            }, this.interval);
        }
        
        stopAutoplay() {
            if (this.autoSlideInterval) {
                clearInterval(this.autoSlideInterval);
                this.autoSlideInterval = null;
            }
        }
        
        resetAutoplay() {
            this.stopAutoplay();
            this.startAutoplay();
        }
    }
    
    // Initialize sliders
    document.querySelectorAll('[data-slider]').forEach((element) => {
        const options = {
            autoplay: element.dataset.autoplay === 'true',
            interval: parseInt(element.dataset.interval) || 5000
        };
        new Slider(element, options);
    });
});