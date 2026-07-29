@push('styles')
<style>
    /* Template Card Animations */
    .template-card {
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .template-card:hover {
        transform: translateY(-8px);
    }
    
    /* Shimmer Effect on Hover */
    .template-shimmer {
        position: relative;
        overflow: hidden;
    }
    
    .template-shimmer::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            45deg,
            transparent 40%,
            rgba(255, 255, 255, 0.1) 50%,
            transparent 60%
        );
        transform: rotate(45deg) translateX(-100%);
        transition: transform 0.6s ease;
    }
    
    .template-shimmer:hover::after {
        transform: rotate(45deg) translateX(100%);
    }
    
    /* Category Filter Active State */
    .filter-active {
        @apply bg-indigo-600 text-white shadow-lg shadow-indigo-200;
    }
    
    /* Modal Animation */
    .modal-enter {
        animation: modalIn 0.3s ease-out;
    }
    
    @keyframes modalIn {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    
    /* Price Tag Glow */
    .price-glow {
        animation: priceGlow 2s ease-in-out infinite;
    }
    
    @keyframes priceGlow {
        0%, 100% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.3); }
        50% { box-shadow: 0 0 40px rgba(99, 102, 241, 0.6); }
    }
</style>
@endpush