
<?php if (isset($component)) { $__componentOriginalbc7b4f0c2c9047f644458dd33f7c67ae = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbc7b4f0c2c9047f644458dd33f7c67ae = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sections.testimonials','data' => ['title' => 'What Our Clients Say','subtitle' => 'Testimonials','description' => 'Don\'t just take our word for it. Hear from our satisfied clients about their experience working with us.','layout' => 'grid','testimonials' => [
        [
            'quote' => 'Working with AgencyPro was a game-changer for our business. Their team understood our vision and delivered a product that exceeded our expectations.',
            'author' => 'Sarah Johnson',
            'role' => 'CEO, TechCorp Innovations',
            'avatar' => asset('images/avatars/sarah.jpg'),
            'rating' => 5,
            'company' => 'TechCorp',
            'featured' => true,
        ],
        [
            'quote' => 'The attention to detail and commitment to quality from the AgencyPro team is outstanding. They truly care about their clients\' success.',
            'author' => 'Michael Chen',
            'role' => 'Director of Product, InnovateTech',
            'avatar' => asset('images/avatars/michael.jpg'),
            'rating' => 5,
            'company' => 'InnovateTech',
        ],
        [
            'quote' => 'We\'ve worked with many agencies, but AgencyPro stands out for their strategic thinking and technical expertise. Highly recommended!',
            'author' => 'Emily Rodriguez',
            'role' => 'VP Marketing, GrowthLabs',
            'avatar' => asset('images/avatars/emily.jpg'),
            'rating' => 5,
            'company' => 'GrowthLabs',
        ],
        [
            'quote' => 'AgencyPro helped us redesign our entire digital presence. The results were immediate and impactful. Our conversion rates increased by 40%.',
            'author' => 'David Kim',
            'role' => 'Founder, StartUp Hub',
            'avatar' => asset('images/avatars/david.jpg'),
            'rating' => 5,
            'company' => 'StartUp Hub',
        ],
        [
            'quote' => 'Their expertise in both design and development is rare. They delivered a beautiful, functional product that our users love.',
            'author' => 'Lisa Thompson',
            'role' => 'Product Manager, CloudSolutions',
            'avatar' => asset('images/avatars/lisa.jpg'),
            'rating' => 4.5,
            'company' => 'CloudSolutions',
        ],
        [
            'quote' => 'The team at AgencyPro is professional, creative, and highly responsive. They made the entire process smooth and enjoyable.',
            'author' => 'James Wilson',
            'role' => 'CEO, Digital Ventures',
            'avatar' => asset('images/avatars/james.jpg'),
            'rating' => 5,
            'company' => 'Digital Ventures',
        ],
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sections.testimonials'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'What Our Clients Say','subtitle' => 'Testimonials','description' => 'Don\'t just take our word for it. Hear from our satisfied clients about their experience working with us.','layout' => 'grid','testimonials' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        [
            'quote' => 'Working with AgencyPro was a game-changer for our business. Their team understood our vision and delivered a product that exceeded our expectations.',
            'author' => 'Sarah Johnson',
            'role' => 'CEO, TechCorp Innovations',
            'avatar' => asset('images/avatars/sarah.jpg'),
            'rating' => 5,
            'company' => 'TechCorp',
            'featured' => true,
        ],
        [
            'quote' => 'The attention to detail and commitment to quality from the AgencyPro team is outstanding. They truly care about their clients\' success.',
            'author' => 'Michael Chen',
            'role' => 'Director of Product, InnovateTech',
            'avatar' => asset('images/avatars/michael.jpg'),
            'rating' => 5,
            'company' => 'InnovateTech',
        ],
        [
            'quote' => 'We\'ve worked with many agencies, but AgencyPro stands out for their strategic thinking and technical expertise. Highly recommended!',
            'author' => 'Emily Rodriguez',
            'role' => 'VP Marketing, GrowthLabs',
            'avatar' => asset('images/avatars/emily.jpg'),
            'rating' => 5,
            'company' => 'GrowthLabs',
        ],
        [
            'quote' => 'AgencyPro helped us redesign our entire digital presence. The results were immediate and impactful. Our conversion rates increased by 40%.',
            'author' => 'David Kim',
            'role' => 'Founder, StartUp Hub',
            'avatar' => asset('images/avatars/david.jpg'),
            'rating' => 5,
            'company' => 'StartUp Hub',
        ],
        [
            'quote' => 'Their expertise in both design and development is rare. They delivered a beautiful, functional product that our users love.',
            'author' => 'Lisa Thompson',
            'role' => 'Product Manager, CloudSolutions',
            'avatar' => asset('images/avatars/lisa.jpg'),
            'rating' => 4.5,
            'company' => 'CloudSolutions',
        ],
        [
            'quote' => 'The team at AgencyPro is professional, creative, and highly responsive. They made the entire process smooth and enjoyable.',
            'author' => 'James Wilson',
            'role' => 'CEO, Digital Ventures',
            'avatar' => asset('images/avatars/james.jpg'),
            'rating' => 5,
            'company' => 'Digital Ventures',
        ],
    ])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbc7b4f0c2c9047f644458dd33f7c67ae)): ?>
<?php $attributes = $__attributesOriginalbc7b4f0c2c9047f644458dd33f7c67ae; ?>
<?php unset($__attributesOriginalbc7b4f0c2c9047f644458dd33f7c67ae); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbc7b4f0c2c9047f644458dd33f7c67ae)): ?>
<?php $component = $__componentOriginalbc7b4f0c2c9047f644458dd33f7c67ae; ?>
<?php unset($__componentOriginalbc7b4f0c2c9047f644458dd33f7c67ae); ?>
<?php endif; ?><?php /**PATH E:\Saas website\webcraft\resources\views/frontend/home/testimonials.blade.php ENDPATH**/ ?>