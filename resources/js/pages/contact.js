/**
 * Contact Page Specific JavaScript
 */

document.addEventListener('DOMContentLoaded', () => {
    const contact = {
        init() {
            this.initFormValidation();
            this.initMap();
        },
        
        initFormValidation() {
            const form = document.querySelector('[data-contact-form]');
            if (!form) return;
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate form
                const isValid = contact.validateForm(this);
                if (isValid) {
                    this.submit();
                }
            });
            
            // Real-time validation
            form.querySelectorAll('input, textarea').forEach(input => {
                input.addEventListener('blur', function() {
                    contact.validateField(this);
                });
                
                input.addEventListener('input', function() {
                    if (this.dataset.validated === 'true') {
                        contact.validateField(this);
                    }
                });
            });
        },
        
        validateForm(form) {
            let isValid = true;
            form.querySelectorAll('[data-validate]').forEach(input => {
                if (!contact.validateField(input)) {
                    isValid = false;
                }
            });
            return isValid;
        },
        
        validateField(input) {
            const type = input.dataset.validate;
            const value = input.value.trim();
            let isValid = true;
            let errorMessage = '';
            
            switch(type) {
                case 'required':
                    isValid = value.length > 0;
                    errorMessage = 'This field is required';
                    break;
                case 'email':
                    isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
                    errorMessage = 'Please enter a valid email address';
                    break;
                case 'phone':
                    isValid = /^[\d\s\-\(\)\+]{10,}$/.test(value.replace(/\s/g, ''));
                    errorMessage = 'Please enter a valid phone number';
                    break;
                case 'name':
                    isValid = value.length >= 2;
                    errorMessage = 'Name must be at least 2 characters';
                    break;
            }
            
            const errorEl = input.parentElement.querySelector('.error-message');
            if (!isValid) {
                input.classList.add('border-red-500');
                if (errorEl) {
                    errorEl.textContent = errorMessage;
                    errorEl.style.display = 'block';
                }
                input.dataset.validated = 'false';
            } else {
                input.classList.remove('border-red-500');
                if (errorEl) {
                    errorEl.style.display = 'none';
                }
                input.dataset.validated = 'true';
            }
            
            return isValid;
        },
        
        initMap() {
            const mapContainer = document.querySelector('[data-map]');
            if (!mapContainer) return;
            
            // Check if Google Maps is loaded
            if (typeof google !== 'undefined' && google.maps) {
                const lat = parseFloat(mapContainer.dataset.lat) || 40.7128;
                const lng = parseFloat(mapContainer.dataset.lng) || -74.0060;
                
                const map = new google.maps.Map(mapContainer, {
                    center: { lat, lng },
                    zoom: 14,
                    styles: [
                        {
                            featureType: 'all',
                            elementType: 'labels',
                            stylers: [{ visibility: 'off' }]
                        }
                    ]
                });
                
                new google.maps.Marker({
                    position: { lat, lng },
                    map: map,
                    title: 'AgencyPro'
                });
            }
        }
    };
    
    contact.init();
});