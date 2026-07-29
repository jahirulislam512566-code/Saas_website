/**
 * Home Page Specific JavaScript
 */

document.addEventListener('DOMContentLoaded', () => {
    // Initialize homepage specific features
    const home = {
        init() {
            this.initHeroAnimation();
            this.initStatsCounter();
            this.initTestimonialSlider();
        },
        
        initHeroAnimation() {
            const hero = document.querySelector('.hero-section');
            if (!hero) return;
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        hero.classList.add('visible');
                    }
                });
            }, { threshold: 0.1 });
            
            observer.observe(hero);
        },
        
        initStatsCounter() {
            document.querySelectorAll('.stat-number').forEach((stat) => {
                const target = parseInt(stat.dataset.count);
                const duration = 2000;
                const step = Math.ceil(target / (duration / 16));
                let current = 0;
                
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const interval = setInterval(() => {
                                current += step;
                                if (current >= target) {
                                    current = target;
                                    clearInterval(interval);
                                }
                                stat.textContent = current.toLocaleString();
                            }, 16);
                            observer.unobserve(stat);
                        }
                    });
                }, { threshold: 0.5 });
                
                observer.observe(stat);
            });
        },
        
        initTestimonialSlider() {
            const slider = document.querySelector('.testimonial-slider');
            if (!slider) return;
            
            // Auto-slide testimonials
            let currentSlide = 0;
            const slides = slider.querySelectorAll('.testimonial-item');
            const totalSlides = slides.length;
            
            setInterval(() => {
                slides.forEach((slide, index) => {
                    slide.classList.toggle('active', index === currentSlide);
                });
                currentSlide = (currentSlide + 1) % totalSlides;
            }, 5000);
        }
    };
    
    home.init();
});