<?php $__env->startSection('title', 'Sign In - AgencyPro'); ?>

<?php $__env->startSection('content'); ?>
    <div class="text-center mb-8">
        <div class="text-4xl mb-3">👋</div>
        <h2 class="text-2xl font-bold text-[#0a0a0a]">Welcome Back</h2>
        <p class="text-sm text-[#4a4a4a] mt-1">Sign in to your account to continue.</p>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl p-4">
            <p class="text-sm"><?php echo e(session('status')); ?></p>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-4">
        <?php echo csrf_field(); ?>

        
        <div>
            <label for="email" class="block text-sm font-medium text-[#0a0a0a] mb-1.5">
                <i class="fas fa-envelope mr-2 text-[#6c5ce7]"></i> Email Address
            </label>
            <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus autocomplete="username"
                   class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#6c5ce7] focus:ring-2 focus:ring-[#6c5ce7]/20 transition-all outline-none">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-1.5 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i> <?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div>
            <label for="password" class="block text-sm font-medium text-[#0a0a0a] mb-1.5">
                <i class="fas fa-lock mr-2 text-[#6c5ce7]"></i> Password
            </label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#6c5ce7] focus:ring-2 focus:ring-[#6c5ce7]/20 transition-all outline-none">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-1.5 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i> <?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-2 border-gray-300 text-[#6c5ce7] focus:ring-[#6c5ce7]/20">
                <span class="text-sm text-[#4a4a4a]">Remember me</span>
            </label>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('password.request')): ?>
                <a href="<?php echo e(route('password.request')); ?>" class="text-sm text-[#6c5ce7] hover:underline no-underline">
                    Forgot password?
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <button type="submit" class="w-full px-6 py-3.5 bg-[#6c5ce7] text-white font-medium rounded-xl hover:bg-[#5a4bd1] transition-all duration-300 shadow-lg shadow-[#6c5ce7]/30 flex items-center justify-center gap-2">
            <i class="fas fa-sign-in-alt"></i>
            Sign In
        </button>
    </form>

    <div class="mt-6 pt-6 border-t border-gray-100 text-center">
        <p class="text-sm text-[#4a4a4a]">
            Don't have an account?
            <a href="<?php echo e(route('register')); ?>" class="font-medium text-[#6c5ce7] hover:underline no-underline">
                Sign up
            </a>
        </p>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Saas website\webcraft\resources\views/auth/login.blade.php ENDPATH**/ ?>