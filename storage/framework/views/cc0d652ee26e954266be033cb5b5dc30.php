
<section class="py-20 bg-white" id="team">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-sm font-semibold text-[#6c5ce7] uppercase tracking-wider">Our Team</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-[#0a0a0a]">Meet the Minds Behind the Magic</h2>
            <p class="mt-4 text-lg text-[#4a4a4a]">We're a diverse group of creative thinkers, problem solvers, and technology enthusiasts dedicated to making a difference.</p>
        </div>

        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            
            <?php if (isset($component)) { $__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cards.team-card','data' => ['image' => ''.e(asset('images/team/ceo.jpg')).'','name' => 'Alex Rivera','role' => 'CEO & Co-Founder','bio' => '15+ years of experience in digital strategy and business development. Passionate about helping businesses grow.','email' => 'alex@agencypro.com','phone' => '+1 (555) 123-4567','socials' => [
                    'twitter' => 'https://twitter.com/alexrivera',
                    'linkedin' => 'https://linkedin.com/in/alexrivera',
                    'github' => 'https://github.com/alexrivera',
                ],'skills' => ['Leadership', 'Strategy', 'Business Development']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cards.team-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => ''.e(asset('images/team/ceo.jpg')).'','name' => 'Alex Rivera','role' => 'CEO & Co-Founder','bio' => '15+ years of experience in digital strategy and business development. Passionate about helping businesses grow.','email' => 'alex@agencypro.com','phone' => '+1 (555) 123-4567','socials' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                    'twitter' => 'https://twitter.com/alexrivera',
                    'linkedin' => 'https://linkedin.com/in/alexrivera',
                    'github' => 'https://github.com/alexrivera',
                ]),'skills' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Leadership', 'Strategy', 'Business Development'])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1)): ?>
<?php $attributes = $__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1; ?>
<?php unset($__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1)): ?>
<?php $component = $__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1; ?>
<?php unset($__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1); ?>
<?php endif; ?>

            
            <?php if (isset($component)) { $__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cards.team-card','data' => ['image' => ''.e(asset('images/team/cto.jpg')).'','name' => 'Maria Santos','role' => 'CTO & Co-Founder','bio' => 'Expert in full-stack development and cloud architecture. Leads our technical innovation and engineering team.','email' => 'maria@agencypro.com','phone' => '+1 (555) 123-4568','socials' => [
                    'twitter' => 'https://twitter.com/mariasantos',
                    'linkedin' => 'https://linkedin.com/in/mariasantos',
                    'github' => 'https://github.com/mariasantos',
                    'dribbble' => 'https://dribbble.com/mariasantos',
                ],'skills' => ['Full-Stack', 'Cloud', 'AI/ML']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cards.team-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => ''.e(asset('images/team/cto.jpg')).'','name' => 'Maria Santos','role' => 'CTO & Co-Founder','bio' => 'Expert in full-stack development and cloud architecture. Leads our technical innovation and engineering team.','email' => 'maria@agencypro.com','phone' => '+1 (555) 123-4568','socials' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                    'twitter' => 'https://twitter.com/mariasantos',
                    'linkedin' => 'https://linkedin.com/in/mariasantos',
                    'github' => 'https://github.com/mariasantos',
                    'dribbble' => 'https://dribbble.com/mariasantos',
                ]),'skills' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Full-Stack', 'Cloud', 'AI/ML'])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1)): ?>
<?php $attributes = $__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1; ?>
<?php unset($__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1)): ?>
<?php $component = $__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1; ?>
<?php unset($__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1); ?>
<?php endif; ?>

            
            <?php if (isset($component)) { $__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cards.team-card','data' => ['image' => ''.e(asset('images/team/design.jpg')).'','name' => 'James Park','role' => 'Design Director','bio' => 'Award-winning designer with expertise in UI/UX, brand identity, and design systems. Creates beautiful, functional experiences.','email' => 'james@agencypro.com','phone' => '+1 (555) 123-4569','socials' => [
                    'twitter' => 'https://twitter.com/jamespark',
                    'linkedin' => 'https://linkedin.com/in/jamespark',
                    'dribbble' => 'https://dribbble.com/jamespark',
                ],'skills' => ['UI/UX', 'Branding', 'Design Systems']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cards.team-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => ''.e(asset('images/team/design.jpg')).'','name' => 'James Park','role' => 'Design Director','bio' => 'Award-winning designer with expertise in UI/UX, brand identity, and design systems. Creates beautiful, functional experiences.','email' => 'james@agencypro.com','phone' => '+1 (555) 123-4569','socials' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                    'twitter' => 'https://twitter.com/jamespark',
                    'linkedin' => 'https://linkedin.com/in/jamespark',
                    'dribbble' => 'https://dribbble.com/jamespark',
                ]),'skills' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['UI/UX', 'Branding', 'Design Systems'])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1)): ?>
<?php $attributes = $__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1; ?>
<?php unset($__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1)): ?>
<?php $component = $__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1; ?>
<?php unset($__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1); ?>
<?php endif; ?>

            
            <?php if (isset($component)) { $__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cards.team-card','data' => ['image' => ''.e(asset('images/team/marketing.jpg')).'','name' => 'Sarah Chen','role' => 'Marketing Lead','bio' => 'Digital marketing strategist with expertise in SEO, content marketing, and growth strategies. Drives client success through data-driven campaigns.','email' => 'sarah@agencypro.com','phone' => '+1 (555) 123-4570','socials' => [
                    'twitter' => 'https://twitter.com/sarahchen',
                    'linkedin' => 'https://linkedin.com/in/sarahchen',
                    'instagram' => 'https://instagram.com/sarahchen',
                ],'skills' => ['SEO', 'Content', 'Analytics']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cards.team-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => ''.e(asset('images/team/marketing.jpg')).'','name' => 'Sarah Chen','role' => 'Marketing Lead','bio' => 'Digital marketing strategist with expertise in SEO, content marketing, and growth strategies. Drives client success through data-driven campaigns.','email' => 'sarah@agencypro.com','phone' => '+1 (555) 123-4570','socials' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                    'twitter' => 'https://twitter.com/sarahchen',
                    'linkedin' => 'https://linkedin.com/in/sarahchen',
                    'instagram' => 'https://instagram.com/sarahchen',
                ]),'skills' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['SEO', 'Content', 'Analytics'])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1)): ?>
<?php $attributes = $__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1; ?>
<?php unset($__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1)): ?>
<?php $component = $__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1; ?>
<?php unset($__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1); ?>
<?php endif; ?>

            
            <?php if (isset($component)) { $__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cards.team-card','data' => ['image' => ''.e(asset('images/team/developer.jpg')).'','name' => 'Michael Okonkwo','role' => 'Lead Developer','bio' => 'Senior full-stack developer with 12+ years of experience. Specializes in React, Laravel, and scalable architecture.','email' => 'michael@agencypro.com','phone' => '+1 (555) 123-4571','socials' => [
                    'twitter' => 'https://twitter.com/michaelokonkwo',
                    'linkedin' => 'https://linkedin.com/in/michaelokonkwo',
                    'github' => 'https://github.com/michaelokonkwo',
                ],'skills' => ['React', 'Laravel', 'AWS']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cards.team-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => ''.e(asset('images/team/developer.jpg')).'','name' => 'Michael Okonkwo','role' => 'Lead Developer','bio' => 'Senior full-stack developer with 12+ years of experience. Specializes in React, Laravel, and scalable architecture.','email' => 'michael@agencypro.com','phone' => '+1 (555) 123-4571','socials' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                    'twitter' => 'https://twitter.com/michaelokonkwo',
                    'linkedin' => 'https://linkedin.com/in/michaelokonkwo',
                    'github' => 'https://github.com/michaelokonkwo',
                ]),'skills' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['React', 'Laravel', 'AWS'])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1)): ?>
<?php $attributes = $__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1; ?>
<?php unset($__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1)): ?>
<?php $component = $__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1; ?>
<?php unset($__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1); ?>
<?php endif; ?>

            
            <?php if (isset($component)) { $__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cards.team-card','data' => ['image' => ''.e(asset('images/team/pm.jpg')).'','name' => 'Emily Watson','role' => 'Project Manager','bio' => 'Certified PMP with expertise in agile methodologies. Ensures projects are delivered on time, on budget, and to the highest quality.','email' => 'emily@agencypro.com','phone' => '+1 (555) 123-4572','socials' => [
                    'linkedin' => 'https://linkedin.com/in/emilywatson',
                    'twitter' => 'https://twitter.com/emilywatson',
                ],'skills' => ['Agile', 'Scrum', 'Project Management']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cards.team-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => ''.e(asset('images/team/pm.jpg')).'','name' => 'Emily Watson','role' => 'Project Manager','bio' => 'Certified PMP with expertise in agile methodologies. Ensures projects are delivered on time, on budget, and to the highest quality.','email' => 'emily@agencypro.com','phone' => '+1 (555) 123-4572','socials' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                    'linkedin' => 'https://linkedin.com/in/emilywatson',
                    'twitter' => 'https://twitter.com/emilywatson',
                ]),'skills' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Agile', 'Scrum', 'Project Management'])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1)): ?>
<?php $attributes = $__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1; ?>
<?php unset($__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1)): ?>
<?php $component = $__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1; ?>
<?php unset($__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1); ?>
<?php endif; ?>

            
            <?php if (isset($component)) { $__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cards.team-card','data' => ['image' => ''.e(asset('images/team/ux.jpg')).'','name' => 'David Kim','role' => 'UX Researcher','bio' => 'Specializes in user research, usability testing, and data-driven design decisions. Advocates for user-centered design.','email' => 'david@agencypro.com','phone' => '+1 (555) 123-4573','socials' => [
                    'linkedin' => 'https://linkedin.com/in/davidkim',
                    'dribbble' => 'https://dribbble.com/davidkim',
                ],'skills' => ['User Research', 'Usability', 'Data Analysis']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cards.team-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => ''.e(asset('images/team/ux.jpg')).'','name' => 'David Kim','role' => 'UX Researcher','bio' => 'Specializes in user research, usability testing, and data-driven design decisions. Advocates for user-centered design.','email' => 'david@agencypro.com','phone' => '+1 (555) 123-4573','socials' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                    'linkedin' => 'https://linkedin.com/in/davidkim',
                    'dribbble' => 'https://dribbble.com/davidkim',
                ]),'skills' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['User Research', 'Usability', 'Data Analysis'])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1)): ?>
<?php $attributes = $__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1; ?>
<?php unset($__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1)): ?>
<?php $component = $__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1; ?>
<?php unset($__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1); ?>
<?php endif; ?>

            
            <?php if (isset($component)) { $__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cards.team-card','data' => ['image' => ''.e(asset('images/team/qa.jpg')).'','name' => 'Lisa Thompson','role' => 'QA Engineer','bio' => 'Quality assurance expert with a passion for automation testing and ensuring exceptional user experiences.','email' => 'lisa@agencypro.com','phone' => '+1 (555) 123-4574','socials' => [
                    'linkedin' => 'https://linkedin.com/in/lisathompson',
                    'github' => 'https://github.com/lisathompson',
                ],'skills' => ['Automation', 'Testing', 'CI/CD']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cards.team-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => ''.e(asset('images/team/qa.jpg')).'','name' => 'Lisa Thompson','role' => 'QA Engineer','bio' => 'Quality assurance expert with a passion for automation testing and ensuring exceptional user experiences.','email' => 'lisa@agencypro.com','phone' => '+1 (555) 123-4574','socials' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                    'linkedin' => 'https://linkedin.com/in/lisathompson',
                    'github' => 'https://github.com/lisathompson',
                ]),'skills' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Automation', 'Testing', 'CI/CD'])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1)): ?>
<?php $attributes = $__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1; ?>
<?php unset($__attributesOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1)): ?>
<?php $component = $__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1; ?>
<?php unset($__componentOriginal80b0a83fae48cf45b0ed6a9dff8dfaa1); ?>
<?php endif; ?>
        </div>

        
        <div class="mt-16 text-center bg-gradient-to-r from-[#6c5ce7]/5 to-[#a78bfa]/5 rounded-2xl p-12 border border-[#6c5ce7]/10">
            <h3 class="text-2xl font-bold text-[#0a0a0a]">Join Our Growing Team</h3>
            <p class="text-[#4a4a4a] mt-2 max-w-2xl mx-auto">We're always looking for talented individuals who share our passion for innovation and excellence.</p>
            <a href="#careers" class="inline-flex items-center gap-2 mt-4 text-[#6c5ce7] font-semibold hover:gap-3 transition-all">
                View Open Positions
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section><?php /**PATH E:\Saas website\webcraft\resources\views/frontend/about/team.blade.php ENDPATH**/ ?>