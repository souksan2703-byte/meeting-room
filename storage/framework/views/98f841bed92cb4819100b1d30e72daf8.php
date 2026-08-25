<?php $__env->startSection('title', 'Login - RoomReserve'); ?>

<?php $__env->startSection('content'); ?>
<h2 class="text-xl font-bold text-gray-800 mb-1">Welcome back</h2>
<p class="text-sm text-gray-500 mb-6">ເຂົ້າສູ່ລະບົບເພື່ອຈອງຫ້ອງປະຊຸມ</p>

<?php if(session('status')): ?>
    <div class="bg-green-50 text-green-700 text-sm rounded-lg p-3 mb-4">
        <?php echo e(session('status')); ?>

    </div>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div class="bg-red-50 text-red-600 text-sm rounded-lg p-3 mb-4">
        <ul class="list-disc list-inside">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-4">
    <?php echo csrf_field(); ?>

    <div>
        <label class="text-xs font-medium text-gray-600">Email</label>
        <input type="email" name="email" value="<?php echo e(old('email')); ?>"
               class="w-full border rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-red-200 focus:border-red-500 outline-none"
               required autofocus autocomplete="username">
    </div>

    <div>
        <label class="text-xs font-medium text-gray-600">Password</label>
        <input type="password" name="password"
               class="w-full border rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-red-200 focus:border-red-500 outline-none"
               required autocomplete="current-password">
    </div>

    <div class="flex items-center justify-between text-sm">
        <label class="flex items-center gap-2 text-gray-600">
            <input type="checkbox" name="remember" class="rounded border-gray-300 text-red-700 focus:ring-red-500">
            Remember me
        </label>

        <?php if(Route::has('password.request')): ?>
            <a href="<?php echo e(route('password.request')); ?>" class="text-red-700 hover:underline">
                Forgot password?
            </a>
        <?php endif; ?>
    </div>

    <button type="submit"
            class="w-full bg-red-700 text-white rounded-lg py-2.5 text-sm font-semibold hover:bg-red-600 transition-colors duration-150 active:scale-[0.98]">
        Log in
    </button>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\meeting-room\resources\views/auth/login.blade.php ENDPATH**/ ?>