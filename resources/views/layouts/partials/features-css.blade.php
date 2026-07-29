@push('styles')
<style>
    /* Feature Card Hover Effects */
    .feature-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .feature-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    }
    
    /* Icon Pulse Animation */
    .feature-icon-pulse {
        animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    /* Gradient Border */
    .feature-border {
        position: relative;
        background: white;
        border-radius: 1rem;
    }
    
    .feature-border::before {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: 1rem;
        padding: 2px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .feature-border:hover::before {
        opacity: 1;
    }
    
    /* Shine Effect */
    .feature-shine {
        position: relative;
        overflow: hidden;
    }
    
    .feature-shine::after {
        content: '';
        position: absolute;
        inset: -100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transform: rotate(45deg) translateX(-100%);
        transition: transform 0.6s ease;
    }
    
    .feature-shine:hover::after {
        transform: rotate(45deg) translateX(100%);
    }
    
    /* Glass Effect */
    .feature-glass {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
</style>
@endpush