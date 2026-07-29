/**
 * Alpine.js - Clipboard Component
 */

document.addEventListener('alpine:init', () => {
    Alpine.data('clipboard', () => ({
        copied: false,
        text: '',
        
        copy(text) {
            this.text = text;
            navigator.clipboard.writeText(text).then(() => {
                this.copied = true;
                this.$dispatch('copied', { text });
                
                setTimeout(() => {
                    this.copied = false;
                }, 2000);
            }).catch(() => {
                // Fallback method
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                
                this.copied = true;
                setTimeout(() => {
                    this.copied = false;
                }, 2000);
            });
        }
    }));
});