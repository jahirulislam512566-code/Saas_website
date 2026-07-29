
<?php if (isset($component)) { $__componentOriginal67958b40bb9729ac877d83f1bb8ebdcf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal67958b40bb9729ac877d83f1bb8ebdcf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sections.pricing','data' => ['title' => 'Choose Your Plan','subtitle' => 'Pricing That Scales','description' => 'Select the perfect plan for your business needs. All plans include a 14-day free trial.','billing' => 'true','defaultBilling' => 'monthly','plans' => [
        [
            'name' => 'Starter',
            'price' => '29',
            'currency' => '$',
            'period' => 'month',
            'description' => 'Perfect for small businesses and startups.',
            'features' => [
                '1 Project',
                'Basic Support',
                '5GB Storage',
                'Analytics Dashboard',
                'Email Support',
            ],
            'ctaText' => 'Start Free Trial',
            'ctaLink' => '#',
            'highlighted' => false,
        ],
        [
            'name' => 'Professional',
            'price' => '99',
            'currency' => '$',
            'period' => 'month',
            'description' => 'Ideal for growing teams and businesses.',
            'features' => [
                '5 Projects',
                'Priority Support',
                '50GB Storage',
                'Advanced Analytics',
                'API Access',
                'Team Collaboration',
                '24/7 Support',
            ],
            'ctaText' => 'Start Free Trial',
            'ctaLink' => '#',
            'highlighted' => true,
            'badge' => 'Most Popular',
        ],
        [
            'name' => 'Enterprise',
            'price' => '299',
            'currency' => '$',
            'period' => 'month',
            'description' => 'For large organizations with complex needs.',
            'features' => [
                'Unlimited Projects',
                'Dedicated Support',
                '500GB Storage',
                'Custom Analytics',
                'Advanced Security',
                'Custom Development',
                'SLA Guarantee',
                'Training & Onboarding',
            ],
            'ctaText' => 'Contact Sales',
            'ctaLink' => '#',
            'highlighted' => false,
        ],
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sections.pricing'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Choose Your Plan','subtitle' => 'Pricing That Scales','description' => 'Select the perfect plan for your business needs. All plans include a 14-day free trial.','billing' => 'true','default-billing' => 'monthly','plans' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        [
            'name' => 'Starter',
            'price' => '29',
            'currency' => '$',
            'period' => 'month',
            'description' => 'Perfect for small businesses and startups.',
            'features' => [
                '1 Project',
                'Basic Support',
                '5GB Storage',
                'Analytics Dashboard',
                'Email Support',
            ],
            'ctaText' => 'Start Free Trial',
            'ctaLink' => '#',
            'highlighted' => false,
        ],
        [
            'name' => 'Professional',
            'price' => '99',
            'currency' => '$',
            'period' => 'month',
            'description' => 'Ideal for growing teams and businesses.',
            'features' => [
                '5 Projects',
                'Priority Support',
                '50GB Storage',
                'Advanced Analytics',
                'API Access',
                'Team Collaboration',
                '24/7 Support',
            ],
            'ctaText' => 'Start Free Trial',
            'ctaLink' => '#',
            'highlighted' => true,
            'badge' => 'Most Popular',
        ],
        [
            'name' => 'Enterprise',
            'price' => '299',
            'currency' => '$',
            'period' => 'month',
            'description' => 'For large organizations with complex needs.',
            'features' => [
                'Unlimited Projects',
                'Dedicated Support',
                '500GB Storage',
                'Custom Analytics',
                'Advanced Security',
                'Custom Development',
                'SLA Guarantee',
                'Training & Onboarding',
            ],
            'ctaText' => 'Contact Sales',
            'ctaLink' => '#',
            'highlighted' => false,
        ],
    ])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal67958b40bb9729ac877d83f1bb8ebdcf)): ?>
<?php $attributes = $__attributesOriginal67958b40bb9729ac877d83f1bb8ebdcf; ?>
<?php unset($__attributesOriginal67958b40bb9729ac877d83f1bb8ebdcf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal67958b40bb9729ac877d83f1bb8ebdcf)): ?>
<?php $component = $__componentOriginal67958b40bb9729ac877d83f1bb8ebdcf; ?>
<?php unset($__componentOriginal67958b40bb9729ac877d83f1bb8ebdcf); ?>
<?php endif; ?><?php /**PATH E:\Saas website\webcraft\resources\views/frontend/home/pricing.blade.php ENDPATH**/ ?>