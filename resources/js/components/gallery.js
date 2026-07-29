/**
 * Gallery Component - Image gallery with lightbox
 */

document.addEventListener('DOMContentLoaded', () => {
    class Gallery {
        constructor(element) {
            this.element = element;
            this.images = element.querySelectorAll('[data-gallery-image]');
            this.currentIndex = 0;
            this.lightbox = null;
            
            this.init();
        }
        
        init() {
            this.createLightbox();
            
            this.images.forEach((img, index) => {
                img.addEventListener('click', () => {
                    this.open(index);
                });
            });
        }
        
        createLightbox() {
            this.lightbox = document.createElement('div');
            this.lightbox.className = 'fixed inset-0 bg-black/90 z-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300';
            this.lightbox.innerHTML = `
                <button class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300 transition-colors z-10" data-gallery-close>&times;</button>
                <button class="absolute left-4 text-white text-4xl hover:text-gray-300 transition-colors" data-gallery-prev>&#10094;</button>
                <button class="absolute right-4 text-white text-4xl hover:text-gray-300 transition-colors" data-gallery-next>&#10095;</button>
                <div class="relative max-w-4xl max-h-[80vh] mx-4">
                    <img class="w-full h-full object-contain" data-gallery-image-main src="" alt="Gallery image">
                </div>
                <div class="absolute bottom-8 text-white text-sm" data-gallery-counter></div>
            `;
            
            document.body.appendChild(this.lightbox);
            
            // Event listeners
            this.lightbox.querySelector('[data-gallery-close]').addEventListener('click', () => this.close());
            this.lightbox.querySelector('[data-gallery-prev]').addEventListener('click', () => this.prev());
            this.lightbox.querySelector('[data-gallery-next]').addEventListener('click', () => this.next());
            
            // Close on overlay click
            this.lightbox.addEventListener('click', (e) => {
                if (e.target === this.lightbox) this.close();
            });
            
            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (!this.lightbox.classList.contains('pointer-events-auto')) return;
                if (e.key === 'Escape') this.close();
                if (e.key === 'ArrowLeft') this.prev();
                if (e.key === 'ArrowRight') this.next();
            });
        }
        
        open(index) {
            this.currentIndex = index;
            this.updateImage();
            this.lightbox.classList.remove('opacity-0', 'pointer-events-none');
            this.lightbox.classList.add('opacity-100', 'pointer-events-auto');
            document.body.style.overflow = 'hidden';
        }
        
        close() {
            this.lightbox.classList.remove('opacity-100', 'pointer-events-auto');
            this.lightbox.classList.add('opacity-0', 'pointer-events-none');
            document.body.style.overflow = '';
        }
        
        prev() {
            this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
            this.updateImage();
        }
        
        next() {
            this.currentIndex = (this.currentIndex + 1) % this.images.length;
            this.updateImage();
        }
        
        updateImage() {
            const img = this.images[this.currentIndex];
            const mainImg = this.lightbox.querySelector('[data-gallery-image-main]');
            const counter = this.lightbox.querySelector('[data-gallery-counter]');
            
            mainImg.src = img.dataset.full || img.src;
            mainImg.alt = img.alt || '';
            counter.textContent = `${this.currentIndex + 1} / ${this.images.length}`;
        }
    }
    
    // Initialize galleries
    document.querySelectorAll('[data-gallery]').forEach((element) => {
        new Gallery(element);
    });
});