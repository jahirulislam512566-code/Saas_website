


<?php $__env->startSection('title', 'Pricing - SaaS Platform'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Pricing Header -->
    <?php if (isset($component)) { $__componentOriginal185b3dd56349bc4bc66c853cff0e617a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal185b3dd56349bc4bc66c853cff0e617a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.website.hero-section','data' => ['title' => 'Simple, Transparent Pricing','subtitle' => 'Choose the plan that\'s right for your business. All plans include a 14-day free trial.','ctaText' => 'Start Free Trial','ctaLink' => ''.e(route('register')).'','secondaryText' => 'View Features','secondaryLink' => ''.e(route('features.index')).'','size' => 'small']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('website.hero-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Simple, Transparent Pricing','subtitle' => 'Choose the plan that\'s right for your business. All plans include a 14-day free trial.','cta_text' => 'Start Free Trial','cta_link' => ''.e(route('register')).'','secondary_text' => 'View Features','secondary_link' => ''.e(route('features.index')).'','size' => 'small']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal185b3dd56349bc4bc66c853cff0e617a)): ?>
<?php $attributes = $__attributesOriginal185b3dd56349bc4bc66c853cff0e617a; ?>
<?php unset($__attributesOriginal185b3dd56349bc4bc66c853cff0e617a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal185b3dd56349bc4bc66c853cff0e617a)): ?>
<?php $component = $__componentOriginal185b3dd56349bc4bc66c853cff0e617a; ?>
<?php unset($__componentOriginal185b3dd56349bc4bc66c853cff0e617a); ?>
<?php endif; ?>
    
    <!-- Pricing Plans -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Starter Plan -->
                <?php if (isset($component)) { $__componentOriginalf06940fa4424e8b8b42bc3ebbcdba42a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf06940fa4424e8b8b42bc3ebbcdba42a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.website.pricing-card','data' => ['name' => 'Starter','price' => '$29','period' => '/month','description' => 'Perfect for small teams getting started.','features' => [
                        'Up to 100 users',
                        '10GB storage',
                        'Basic analytics',
                        'Email support'
                    ],'ctaText' => 'Start Free Trial','ctaLink' => ''.e(route('register')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('website.pricing-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'Starter','price' => '$29','period' => '/month','description' => 'Perfect for small teams getting started.','features' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                        'Up to 100 users',
                        '10GB storage',
                        'Basic analytics',
                        'Email support'
                    ]),'cta_text' => 'Start Free Trial','cta_link' => ''.e(route('register')).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf06940fa4424e8b8b42bc3ebbcdba42a)): ?>
<?php $attributes = $__attributesOriginalf06940fa4424e8b8b42bc3ebbcdba42a; ?>
<?php unset($__attributesOriginalf06940fa4424e8b8b42bc3ebbcdba42a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf06940fa4424e8b8b42bc3ebbcdba42a)): ?>
<?php $component = $__componentOriginalf06940fa4424e8b8b42bc3ebbcdba42a; ?>
<?php unset($__componentOriginalf06940fa4424e8b8b42bc3ebbcdba42a); ?>
<?php endif; ?>
                
                <!-- Pro Plan -->
                <?php if (isset($component)) { $__componentOriginalf06940fa4424e8b8b42bc3ebbcdba42a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf06940fa4424e8b8b42bc3ebbcdba42a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.website.pricing-card','data' => ['name' => 'Pro','price' => '$79','period' => '/month','description' => 'Most popular for growing businesses.','features' => [
                        'Unlimited users',
                        '100GB storage',
                        'Advanced analytics',
                        'Priority support',
                        'Custom integrations'
                    ],'ctaText' => 'Start Free Trial','ctaLink' => ''.e(route('register')).'','popular' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('website.pricing-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'Pro','price' => '$79','period' => '/month','description' => 'Most popular for growing businesses.','features' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                        'Unlimited users',
                        '100GB storage',
                        'Advanced analytics',
                        'Priority support',
                        'Custom integrations'
                    ]),'cta_text' => 'Start Free Trial','cta_link' => ''.e(route('register')).'','popular' => 'true']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf06940fa4424e8b8b42bc3ebbcdba42a)): ?>
<?php $attributes = $__attributesOriginalf06940fa4424e8b8b42bc3ebbcdba42a; ?>
<?php unset($__attributesOriginalf06940fa4424e8b8b42bc3ebbcdba42a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf06940fa4424e8b8b42bc3ebbcdba42a)): ?>
<?php $component = $__componentOriginalf06940fa4424e8b8b42bc3ebbcdba42a; ?>
<?php unset($__componentOriginalf06940fa4424e8b8b42bc3ebbcdba42a); ?>
<?php endif; ?>
                
                <!-- Enterprise Plan -->
                <?php if (isset($component)) { $__componentOriginalf06940fa4424e8b8b42bc3ebbcdba42a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf06940fa4424e8b8b42bc3ebbcdba42a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.website.pricing-card','data' => ['name' => 'Enterprise','price' => '$199','period' => '/month','description' => 'For large organizations with complex needs.','features' => [
                        'Unlimited everything',
                        '1TB storage',
                        'Custom analytics',
                        '24/7 phone support',
                        'Dedicated account manager'
                    ],'ctaText' => 'Contact Sales','ctaLink' => ''.e(route('website.contact')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('website.pricing-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'Enterprise','price' => '$199','period' => '/month','description' => 'For large organizations with complex needs.','features' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                        'Unlimited everything',
                        '1TB storage',
                        'Custom analytics',
                        '24/7 phone support',
                        'Dedicated account manager'
                    ]),'cta_text' => 'Contact Sales','cta_link' => ''.e(route('website.contact')).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf06940fa4424e8b8b42bc3ebbcdba42a)): ?>
<?php $attributes = $__attributesOriginalf06940fa4424e8b8b42bc3ebbcdba42a; ?>
<?php unset($__attributesOriginalf06940fa4424e8b8b42bc3ebbcdba42a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf06940fa4424e8b8b42bc3ebbcdba42a)): ?>
<?php $component = $__componentOriginalf06940fa4424e8b8b42bc3ebbcdba42a; ?>
<?php unset($__componentOriginalf06940fa4424e8b8b42bc3ebbcdba42a); ?>
<?php endif; ?>
            </div>
        </div>
    </section>
    
    <!-- FAQ Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php if (isset($component)) { $__componentOriginal19801a6e13237f45258d8cba866163f2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal19801a6e13237f45258d8cba866163f2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.website.section-header','data' => ['title' => 'Frequently Asked Questions','subtitle' => 'Everything you need to know about our pricing and plans.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('website.section-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Frequently Asked Questions','subtitle' => 'Everything you need to know about our pricing and plans.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal19801a6e13237f45258d8cba866163f2)): ?>
<?php $attributes = $__attributesOriginal19801a6e13237f45258d8cba866163f2; ?>
<?php unset($__attributesOriginal19801a6e13237f45258d8cba866163f2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal19801a6e13237f45258d8cba866163f2)): ?>
<?php $component = $__componentOriginal19801a6e13237f45258d8cba866163f2; ?>
<?php unset($__componentOriginal19801a6e13237f45258d8cba866163f2); ?>
<?php endif; ?>
            
            <div class="space-y-4 mt-8">
                <?php if (isset($component)) { $__componentOriginal1dbb70fc2d463e2f28300339ad600766 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1dbb70fc2d463e2f28300339ad600766 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.website.faq-item','data' => ['question' => 'Can I change plans later?','answer' => 'Yes, you can upgrade or downgrade your plan at any time. Changes will be prorated.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('website.faq-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['question' => 'Can I change plans later?','answer' => 'Yes, you can upgrade or downgrade your plan at any time. Changes will be prorated.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1dbb70fc2d463e2f28300339ad600766)): ?>
<?php $attributes = $__attributesOriginal1dbb70fc2d463e2f28300339ad600766; ?>
<?php unset($__attributesOriginal1dbb70fc2d463e2f28300339ad600766); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1dbb70fc2d463e2f28300339ad600766)): ?>
<?php $component = $__componentOriginal1dbb70fc2d463e2f28300339ad600766; ?>
<?php unset($__componentOriginal1dbb70fc2d463e2f28300339ad600766); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal1dbb70fc2d463e2f28300339ad600766 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1dbb70fc2d463e2f28300339ad600766 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.website.faq-item','data' => ['question' => 'What payment methods do you accept?','answer' => 'We accept all major credit cards, PayPal, and bank transfers for enterprise plans.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('website.faq-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['question' => 'What payment methods do you accept?','answer' => 'We accept all major credit cards, PayPal, and bank transfers for enterprise plans.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1dbb70fc2d463e2f28300339ad600766)): ?>
<?php $attributes = $__attributesOriginal1dbb70fc2d463e2f28300339ad600766; ?>
<?php unset($__attributesOriginal1dbb70fc2d463e2f28300339ad600766); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1dbb70fc2d463e2f28300339ad600766)): ?>
<?php $component = $__componentOriginal1dbb70fc2d463e2f28300339ad600766; ?>
<?php unset($__componentOriginal1dbb70fc2d463e2f28300339ad600766); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal1dbb70fc2d463e2f28300339ad600766 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1dbb70fc2d463e2f28300339ad600766 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.website.faq-item','data' => ['question' => 'Is there a free trial?','answer' => 'Yes, all plans come with a 14-day free trial. No credit card required.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('website.faq-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['question' => 'Is there a free trial?','answer' => 'Yes, all plans come with a 14-day free trial. No credit card required.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1dbb70fc2d463e2f28300339ad600766)): ?>
<?php $attributes = $__attributesOriginal1dbb70fc2d463e2f28300339ad600766; ?>
<?php unset($__attributesOriginal1dbb70fc2d463e2f28300339ad600766); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1dbb70fc2d463e2f28300339ad600766)): ?>
<?php $component = $__componentOriginal1dbb70fc2d463e2f28300339ad600766; ?>
<?php unset($__componentOriginal1dbb70fc2d463e2f28300339ad600766); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal1dbb70fc2d463e2f28300339ad600766 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1dbb70fc2d463e2f28300339ad600766 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.website.faq-item','data' => ['question' => 'Can I cancel my subscription anytime?','answer' => 'Absolutely. You can cancel your subscription at any time with no hidden fees.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('website.faq-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['question' => 'Can I cancel my subscription anytime?','answer' => 'Absolutely. You can cancel your subscription at any time with no hidden fees.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1dbb70fc2d463e2f28300339ad600766)): ?>
<?php $attributes = $__attributesOriginal1dbb70fc2d463e2f28300339ad600766; ?>
<?php unset($__attributesOriginal1dbb70fc2d463e2f28300339ad600766); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1dbb70fc2d463e2f28300339ad600766)): ?>
<?php $component = $__componentOriginal1dbb70fc2d463e2f28300339ad600766; ?>
<?php unset($__componentOriginal1dbb70fc2d463e2f28300339ad600766); ?>
<?php endif; ?>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <?php if (isset($component)) { $__componentOriginaldb8a495881136add5ebf020bfbfce2bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldb8a495881136add5ebf020bfbfce2bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.website.cta-section','data' => ['title' => 'Ready to Get Started?','subtitle' => 'Join thousands of businesses already using our platform.','ctaText' => 'Start Your Free Trial','ctaLink' => ''.e(route('register')).'','variant' => 'dark']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('website.cta-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Ready to Get Started?','subtitle' => 'Join thousands of businesses already using our platform.','cta_text' => 'Start Your Free Trial','cta_link' => ''.e(route('register')).'','variant' => 'dark']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldb8a495881136add5ebf020bfbfce2bb)): ?>
<?php $attributes = $__attributesOriginaldb8a495881136add5ebf020bfbfce2bb; ?>
<?php unset($__attributesOriginaldb8a495881136add5ebf020bfbfce2bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldb8a495881136add5ebf020bfbfce2bb)): ?>
<?php $component = $__componentOriginaldb8a495881136add5ebf020bfbfce2bb; ?>
<?php unset($__componentOriginaldb8a495881136add5ebf020bfbfce2bb); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.website', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Saas website\webcraft\resources\views/website/pricing.blade.php ENDPATH**/ ?>