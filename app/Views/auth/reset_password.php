<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">Reset Password</h2>
            <p class="mt-2 text-sm text-gray-600">
                Enter your new password below.
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

            <form class="space-y-6" action="<?= base_url('reset-password/' . $token) ?>" method="POST">
                <?= csrf_field() ?>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        New Password
                    </label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" required
                            class="appearance-none block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            placeholder="Enter your new password" minlength="6">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" id="togglePassword" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Password Strength Indicator -->
                    <div class="mt-2">
                        <div class="flex items-center space-x-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div id="strengthBar" class="h-2 rounded-full transition-all duration-300" style="width: 0%;"></div>
                            </div>
                            <span id="strengthText" class="text-xs font-medium text-gray-500"></span>
                        </div>
                        <div class="mt-1 text-xs text-gray-500">
                            Password must be at least 6 characters long
                        </div>
                    </div>

                    <?php if (isset($validation) && $validation->hasError('password')): ?>
                        <p class="mt-2 text-sm text-red-600">
                            <?= $validation->getError('password') ?>
                        </p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">
                        Confirm New Password
                    </label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="confirm_password" name="confirm_password" type="password" required
                            class="appearance-none block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            placeholder="Confirm your new password">
                    </div>
                    <?php if (isset($validation) && $validation->hasError('confirm_password')): ?>
                        <p class="mt-2 text-sm text-red-600">
                            <?= $validation->getError('confirm_password') ?>
                        </p>
                    <?php endif; ?>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-key mr-2"></i>
                        Reset Password
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const togglePassword = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');

        // Toggle password visibility
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            if (type === 'text') {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });

        // Password strength checker
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let strengthLabel = '';
            let color = '';

            if (password.length >= 6) strength += 1;
            if (password.match(/[a-z]/)) strength += 1;
            if (password.match(/[A-Z]/)) strength += 1;
            if (password.match(/[0-9]/)) strength += 1;
            if (password.match(/[^a-zA-Z0-9]/)) strength += 1;

            switch (strength) {
                case 0:
                case 1:
                    strengthLabel = 'Very Weak';
                    color = '#ef4444';
                    break;
                case 2:
                    strengthLabel = 'Weak';
                    color = '#f97316';
                    break;
                case 3:
                    strengthLabel = 'Fair';
                    color = '#eab308';
                    break;
                case 4:
                    strengthLabel = 'Good';
                    color = '#22c55e';
                    break;
                case 5:
                    strengthLabel = 'Strong';
                    color = '#16a34a';
                    break;
            }

            const percentage = (strength / 5) * 100;
            strengthBar.style.width = percentage + '%';
            strengthBar.style.backgroundColor = color;
            strengthText.textContent = strengthLabel;
            strengthText.style.color = color;
        });

        // Password match validation
        function validatePasswordMatch() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            if (confirmPassword && password !== confirmPassword) {
                confirmPasswordInput.setCustomValidity('Passwords do not match');
                confirmPasswordInput.classList.add('border-red-500');
                confirmPasswordInput.classList.remove('border-gray-300');
            } else {
                confirmPasswordInput.setCustomValidity('');
                confirmPasswordInput.classList.remove('border-red-500');
                confirmPasswordInput.classList.add('border-gray-300');
            }
        }

        passwordInput.addEventListener('input', validatePasswordMatch);
        confirmPasswordInput.addEventListener('input', validatePasswordMatch);

        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match. Please try again.');
                return false;
            }

            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long.');
                return false;
            }
        });
    });
</script>

<?= $this->endSection() ?>