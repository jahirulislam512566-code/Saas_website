
<?php if (isset($component)) { $__componentOriginalb6994dc4681b1fe7a8d5f23dd9bcd957 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb6994dc4681b1fe7a8d5f23dd9bcd957 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sections.technologies','data' => ['title' => 'Technologies We Use','subtitle' => 'Our Tech Stack','description' => 'We leverage modern technologies to build cutting-edge solutions for our clients.','layout' => 'grid','technologies' => [
        ['icon' => '⚛️', 'name' => 'React', 'description' => 'Frontend Framework'],
        ['icon' => '🟢', 'name' => 'Node.js', 'description' => 'Backend Runtime'],
        ['icon' => '🐘', 'name' => 'PHP', 'description' => 'Backend Language'],
        ['icon' => '🎨', 'name' => 'Tailwind CSS', 'description' => 'CSS Framework'],
        ['icon' => '📱', 'name' => 'React Native', 'description' => 'Mobile Framework'],
        ['icon' => '☁️', 'name' => 'AWS', 'description' => 'Cloud Platform'],
        ['icon' => '🐍', 'name' => 'Python', 'description' => 'Programming Language'],
        ['icon' => '🗄️', 'name' => 'MySQL', 'description' => 'Database'],
        ['icon' => '🔄', 'name' => 'Laravel', 'description' => 'PHP Framework'],
        ['icon' => '🎯', 'name' => 'Vue.js', 'description' => 'Frontend Framework'],
        ['icon' => '📊', 'name' => 'MongoDB', 'description' => 'NoSQL Database'],
        ['icon' => '🔷', 'name' => 'TypeScript', 'description' => 'Programming Language'],
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sections.technologies'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Technologies We Use','subtitle' => 'Our Tech Stack','description' => 'We leverage modern technologies to build cutting-edge solutions for our clients.','layout' => 'grid','technologies' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['icon' => '⚛️', 'name' => 'React', 'description' => 'Frontend Framework'],
        ['icon' => '🟢', 'name' => 'Node.js', 'description' => 'Backend Runtime'],
        ['icon' => '🐘', 'name' => 'PHP', 'description' => 'Backend Language'],
        ['icon' => '🎨', 'name' => 'Tailwind CSS', 'description' => 'CSS Framework'],
        ['icon' => '📱', 'name' => 'React Native', 'description' => 'Mobile Framework'],
        ['icon' => '☁️', 'name' => 'AWS', 'description' => 'Cloud Platform'],
        ['icon' => '🐍', 'name' => 'Python', 'description' => 'Programming Language'],
        ['icon' => '🗄️', 'name' => 'MySQL', 'description' => 'Database'],
        ['icon' => '🔄', 'name' => 'Laravel', 'description' => 'PHP Framework'],
        ['icon' => '🎯', 'name' => 'Vue.js', 'description' => 'Frontend Framework'],
        ['icon' => '📊', 'name' => 'MongoDB', 'description' => 'NoSQL Database'],
        ['icon' => '🔷', 'name' => 'TypeScript', 'description' => 'Programming Language'],
    ])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb6994dc4681b1fe7a8d5f23dd9bcd957)): ?>
<?php $attributes = $__attributesOriginalb6994dc4681b1fe7a8d5f23dd9bcd957; ?>
<?php unset($__attributesOriginalb6994dc4681b1fe7a8d5f23dd9bcd957); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb6994dc4681b1fe7a8d5f23dd9bcd957)): ?>
<?php $component = $__componentOriginalb6994dc4681b1fe7a8d5f23dd9bcd957; ?>
<?php unset($__componentOriginalb6994dc4681b1fe7a8d5f23dd9bcd957); ?>
<?php endif; ?><?php /**PATH E:\Saas website\webcraft\resources\views/frontend/home/technologies.blade.php ENDPATH**/ ?>