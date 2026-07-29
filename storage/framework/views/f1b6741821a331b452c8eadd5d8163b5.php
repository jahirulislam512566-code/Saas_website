
<?php if (isset($component)) { $__componentOriginal3059bb6f234c7f6929934634de0e93cd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3059bb6f234c7f6929934634de0e93cd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sections.cta','data' => ['title' => 'Ready to Transform Your Business?','subtitle' => 'Let\'s Work Together','description' => 'Contact us today and let\'s create something amazing together.','primaryText' => 'Start Your Project','primaryLink' => '#contact','secondaryText' => 'View Portfolio','secondaryLink' => ''.e(route('portfolio.index')).'','variant' => 'default']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sections.cta'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Ready to Transform Your Business?','subtitle' => 'Let\'s Work Together','description' => 'Contact us today and let\'s create something amazing together.','primary-text' => 'Start Your Project','primary-link' => '#contact','secondary-text' => 'View Portfolio','secondary-link' => ''.e(route('portfolio.index')).'','variant' => 'default']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3059bb6f234c7f6929934634de0e93cd)): ?>
<?php $attributes = $__attributesOriginal3059bb6f234c7f6929934634de0e93cd; ?>
<?php unset($__attributesOriginal3059bb6f234c7f6929934634de0e93cd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3059bb6f234c7f6929934634de0e93cd)): ?>
<?php $component = $__componentOriginal3059bb6f234c7f6929934634de0e93cd; ?>
<?php unset($__componentOriginal3059bb6f234c7f6929934634de0e93cd); ?>
<?php endif; ?><?php /**PATH E:\Saas website\webcraft\resources\views/frontend/home/cta.blade.php ENDPATH**/ ?>