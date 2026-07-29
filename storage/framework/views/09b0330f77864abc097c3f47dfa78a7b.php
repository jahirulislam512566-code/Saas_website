
<?php if (isset($component)) { $__componentOriginal871f819925592cac5093a9ea5abc64c2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal871f819925592cac5093a9ea5abc64c2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sections.faq','data' => ['title' => 'Frequently Asked Questions','subtitle' => 'FAQ','description' => 'Find answers to common questions about our services, process, and policies.','columns' => '2','items' => [
        [
            'question' => 'What services do you offer?',
            'answer' => 'We offer a comprehensive range of digital services including UI/UX design, web development, mobile app development, digital strategy, SEO and marketing, and cybersecurity solutions. Our team of experts can help you at any stage of your digital journey.'
        ],
        [
            'question' => 'How long does a typical project take?',
            'answer' => 'Project timelines vary depending on the scope and complexity. A typical website redesign might take 6-12 weeks, while a custom application development project could take 3-6 months. We provide detailed timelines during our initial consultation.'
        ],
        [
            'question' => 'What is your pricing model?',
            'answer' => 'We offer flexible pricing models including fixed-price projects, hourly rates, and retainer arrangements. We work with you to find the best option that fits your budget and project requirements.'
        ],
        [
            'question' => 'Do you offer ongoing support?',
            'answer' => 'Yes, we offer various support and maintenance packages to ensure your digital assets remain secure, up-to-date, and performing optimally. Our support plans include regular updates, security patches, and priority assistance.'
        ],
        [
            'question' => 'Can you help with a complete digital transformation?',
            'answer' => 'Absolutely. We specialize in end-to-end digital transformation services. From strategy development and planning to execution and optimization, we can help guide your organization through every step of the process.'
        ],
        [
            'question' => 'What industries do you work with?',
            'answer' => 'We work with clients across various industries including technology, healthcare, finance, e-commerce, education, non-profits, and more. Our diverse experience allows us to bring best practices from different sectors to your project.'
        ],
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sections.faq'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Frequently Asked Questions','subtitle' => 'FAQ','description' => 'Find answers to common questions about our services, process, and policies.','columns' => '2','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        [
            'question' => 'What services do you offer?',
            'answer' => 'We offer a comprehensive range of digital services including UI/UX design, web development, mobile app development, digital strategy, SEO and marketing, and cybersecurity solutions. Our team of experts can help you at any stage of your digital journey.'
        ],
        [
            'question' => 'How long does a typical project take?',
            'answer' => 'Project timelines vary depending on the scope and complexity. A typical website redesign might take 6-12 weeks, while a custom application development project could take 3-6 months. We provide detailed timelines during our initial consultation.'
        ],
        [
            'question' => 'What is your pricing model?',
            'answer' => 'We offer flexible pricing models including fixed-price projects, hourly rates, and retainer arrangements. We work with you to find the best option that fits your budget and project requirements.'
        ],
        [
            'question' => 'Do you offer ongoing support?',
            'answer' => 'Yes, we offer various support and maintenance packages to ensure your digital assets remain secure, up-to-date, and performing optimally. Our support plans include regular updates, security patches, and priority assistance.'
        ],
        [
            'question' => 'Can you help with a complete digital transformation?',
            'answer' => 'Absolutely. We specialize in end-to-end digital transformation services. From strategy development and planning to execution and optimization, we can help guide your organization through every step of the process.'
        ],
        [
            'question' => 'What industries do you work with?',
            'answer' => 'We work with clients across various industries including technology, healthcare, finance, e-commerce, education, non-profits, and more. Our diverse experience allows us to bring best practices from different sectors to your project.'
        ],
    ])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal871f819925592cac5093a9ea5abc64c2)): ?>
<?php $attributes = $__attributesOriginal871f819925592cac5093a9ea5abc64c2; ?>
<?php unset($__attributesOriginal871f819925592cac5093a9ea5abc64c2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal871f819925592cac5093a9ea5abc64c2)): ?>
<?php $component = $__componentOriginal871f819925592cac5093a9ea5abc64c2; ?>
<?php unset($__componentOriginal871f819925592cac5093a9ea5abc64c2); ?>
<?php endif; ?><?php /**PATH E:\Saas website\webcraft\resources\views/frontend/home/faq.blade.php ENDPATH**/ ?>