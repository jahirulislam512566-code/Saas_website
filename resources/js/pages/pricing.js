/**
 * Pricing Page Specific JavaScript
 */

document.addEventListener('DOMContentLoaded', () => {
    const pricing = {
        init() {
            this.initBillingToggle();
            this.initPricingCalculator();
        },
        
        initBillingToggle() {
            const toggle = document.querySelector('[data-billing-toggle]');
            if (!toggle) return;
            
            const monthly = document.querySelector('[data-billing-monthly]');
            const yearly = document.querySelector('[data-billing-yearly]');
            const savings = document.querySelector('[data-billing-savings]');
            
            toggle.addEventListener('change', function() {
                const isYearly = this.checked;
                
                if (monthly) monthly.style.display = isYearly ? 'none' : 'block';
                if (yearly) yearly.style.display = isYearly ? 'block' : 'none';
                if (savings) savings.style.display = isYearly ? 'inline' : 'none';
                
                // Update prices
                document.querySelectorAll('[data-price]').forEach((el) => {
                    const monthlyPrice = el.dataset.monthly;
                    const yearlyPrice = el.dataset.yearly;
                    el.textContent = isYearly ? yearlyPrice : monthlyPrice;
                });
            });
        },
        
        initPricingCalculator() {
            const calculator = document.querySelector('.pricing-calculator');
            if (!calculator) return;
            
            const inputs = calculator.querySelectorAll('input[type="range"]');
            const totalEl = calculator.querySelector('[data-total]');
            
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    const value = this.value;
                    const display = this.parentElement.querySelector('.value-display');
                    if (display) display.textContent = value;
                    
                    this.updateTotal();
                });
            });
            
            // Update total function
            calculator.updateTotal = function() {
                let total = 0;
                inputs.forEach(input => {
                    total += parseInt(input.value);
                });
                if (totalEl) totalEl.textContent = '$' + total.toLocaleString();
            };
        }
    };
    
    pricing.init();
});