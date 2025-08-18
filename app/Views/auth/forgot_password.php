<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">Forgot Password</h2>
            <p class="mt-2 text-sm text-gray-600">
                Enter your email address and we'll send you a link to reset your password.
            </p>
        </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-lg sm:rounded-2xl sm:px-10 border border-gray-200">
            <?php if (session()->has('error')): ?>
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?= session()->get('error') ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (session()->has('success')): ?>
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?= session()->get('success') ?>
                    </div>
                </div>
            <?php endif; ?>

            <form class="space-y-6" action="<?= base_url('forgot-password') ?>" method="POST">
                <?= csrf_field() ?>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email address
                    </label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" required
                            class="appearance-none block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent sm:text-sm"
                            placeholder="Enter your email"
                            value="<?= old('email') ?>">
                    </div>
                    <?php if (isset($validation) && $validation->hasError('email')): ?>
                        <p class="mt-2 text-sm text-red-600">
                            <?= $validation->getError('email') ?>
                        </p>
                    <?php endif; ?>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Send Reset Link
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Remember your password?
                        <a href="<?= base_url('login') ?>" class="font-medium text-blue-600 hover:text-blue-500">
                            Sign in
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>