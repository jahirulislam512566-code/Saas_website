
<?php if (isset($component)) { $__componentOriginal18a0ec3e0580bff97cbbe3c339f815c9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18a0ec3e0580bff97cbbe3c339f815c9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sections.newsletter','data' => ['title' => 'Subscribe to Our Newsletter','subtitle' => 'Stay Updated','description' => 'Join 10,000+ subscribers and get weekly insights, tips, and updates delivered to your inbox.','formAction' => ''.e(route('api.newsletter.subscribe')).'','formMethod' => 'POST','buttonText' => 'Subscribe Now','placeholder' => 'Enter your email address','privacyText' => 'We respect your privacy. No spam, unsubscribe anytime.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sections.newsletter'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Subscribe to Our Newsletter','subtitle' => 'Stay Updated','description' => 'Join 10,000+ subscribers and get weekly insights, tips, and updates delivered to your inbox.','form-action' => ''.e(route('api.newsletter.subscribe')).'','form-method' => 'POST','button-text' => 'Subscribe Now','placeholder' => 'Enter your email address','privacy-text' => 'We respect your privacy. No spam, unsubscribe anytime.']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18a0ec3e0580bff97cbbe3c339f815c9)): ?>
<?php $attributes = $__attributesOriginal18a0ec3e0580bff97cbbe3c339f815c9; ?>
<?php unset($__attributesOriginal18a0ec3e0580bff97cbbe3c339f815c9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18a0ec3e0580bff97cbbe3c339f815c9)): ?>
<?php $component = $__componentOriginal18a0ec3e0580bff97cbbe3c339f815c9; ?>
<?php unset($__componentOriginal18a0ec3e0580bff97cbbe3c339f815c9); ?>
<?php endif; ?><?php /**PATH E:\Saas website\webcraft\resources\views/frontend/home/newsletter.blade.php ENDPATH**/ ?>