// Template Functions
window.TemplateManager = {
    // Filter templates by category
    filterByCategory(category) {
        const cards = document.querySelectorAll('.template-card');
        cards.forEach(card => {
            if (category === 'all' || card.dataset.category === category) {
                card.style.display = 'block';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        });
    },

    // Preview template
    previewTemplate(id) {
        fetch(`/api/templates/${id}`)
            .then(response => response.json())
            .then(data => {
                // Open modal with template data
                const modal = document.getElementById('templatePreviewModal');
                if (modal) {
                    modal.querySelector('.template-name').textContent = data.name;
                    modal.querySelector('.template-description').textContent = data.description;
                    modal.querySelector('.template-image').src = data.image;
                    modal.querySelector('.template-pages').textContent = `${data.pages} pages`;
                    modal.querySelector('.template-features').innerHTML = data.features
                        .map(f => `<span class="px-3 py-1.5 bg-indigo-50 text-indigo-600 text-sm font-medium rounded-lg">${f}</span>`)
                        .join('');
                    modal.classList.add('active');
                }
            });
    },

    // Use template
    useTemplate(id) {
        // Check if user is logged in
        const isLoggedIn = document.querySelector('meta[name="user-id"]');
        
        if (isLoggedIn) {
            window.location.href = `/builder?template=${id}`;
        } else {
            window.location.href = `/register?template=${id}`;
        }
    },

    // Initialize template showcase
    init() {
        // Add click listeners to filter buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const category = this.dataset.category;
                window.TemplateManager.filterByCategory(category);
                
                // Update active state
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Add click listeners to preview buttons
        document.querySelectorAll('.preview-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.templateId;
                window.TemplateManager.previewTemplate(id);
            });
        });

        // Add click listeners to use template buttons
        document.querySelectorAll('.use-template-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.templateId;
                window.TemplateManager.useTemplate(id);
            });
        });
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.TemplateManager.init();
});