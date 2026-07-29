
<?php if (isset($component)) { $__componentOriginal7d77bb759cf09fb7609ab7d50dcb0764 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7d77bb759cf09fb7609ab7d50dcb0764 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sections.hero','data' => ['title' => 'We Build <span class=\'text-[#6c5ce7]\'>Digital Experiences</span> That Drive Growth','subtitle' => 'Strategy, design, and development for forward-thinking brands. We combine creativity with technology to deliver exceptional results.','image' => ''.e(asset('images/hero-illustration.svg')).'','primaryText' => 'Start Your Project','primaryLink' => '#contact','secondaryText' => 'Explore Our Work','secondaryLink' => '#portfolio','badge' => '🚀 Trusted by 500+ Companies Worldwide','alignment' => 'left','stats' => [
        ['value' => '500+', 'label' => 'Projects Delivered'],
        ['value' => '98%', 'label' => 'Client Satisfaction'],
        ['value' => '4.9/5', 'label' => 'Average Rating'],
        ['value' => '50+', 'label' => 'Team Members'],
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sections.hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'We Build <span class=\'text-[#6c5ce7]\'>Digital Experiences</span> That Drive Growth','subtitle' => 'Strategy, design, and development for forward-thinking brands. We combine creativity with technology to deliver exceptional results.','image' => ''.e(asset('images/hero-illustration.svg')).'','primary-text' => 'Start Your Project','primary-link' => '#contact','secondary-text' => 'Explore Our Work','secondary-link' => '#portfolio','badge' => '🚀 Trusted by 500+ Companies Worldwide','alignment' => 'left','stats' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['value' => '500+', 'label' => 'Projects Delivered'],
        ['value' => '98%', 'label' => 'Client Satisfaction'],
        ['value' => '4.9/5', 'label' => 'Average Rating'],
        ['value' => '50+', 'label' => 'Team Members'],
    ])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7d77bb759cf09fb7609ab7d50dcb0764)): ?>
<?php $attributes = $__attributesOriginal7d77bb759cf09fb7609ab7d50dcb0764; ?>
<?php unset($__attributesOriginal7d77bb759cf09fb7609ab7d50dcb0764); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7d77bb759cf09fb7609ab7d50dcb0764)): ?>
<?php $component = $__componentOriginal7d77bb759cf09fb7609ab7d50dcb0764; ?>
<?php unset($__componentOriginal7d77bb759cf09fb7609ab7d50dcb0764); ?>
<?php endif; ?><?php /**PATH E:\Saas website\webcraft\resources\views/frontend/home/hero.blade.php ENDPATH**/ ?>