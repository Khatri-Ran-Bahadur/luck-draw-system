<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-8">
    <!-- Enhanced Page Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-lg p-8 text-white">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="fas fa-user-cog text-3xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold">Account Settings</h1>
                <p class="text-blue-100 text-lg mt-1">Manage your account security and password</p>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <p class="text-green-700 font-medium"><?= session()->getFlashdata('success') ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                <p class="text-red-700 font-medium"><?= session()->getFlashdata('error') ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Enhanced Settings Form -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Account Settings</h2>
            <p class="text-gray-600 mt-1">Update your password and account information</p>
        </div>

        <form action="<?= base_url('admin/settings') ?>" method="post" class="p-8 space-y-8">
            <?= csrf_field() ?>

            <!-- Current Admin Info -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
                <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                    <i class="fas fa-user-circle mr-3 text-blue-600"></i>
                    Current Account Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-blue-800">Username</label>
                        <div class="bg-white px-4 py-3 rounded-lg border border-blue-200 text-blue-900 font-medium">
                            <?= esc(session()->get('username')) ?>
                        </div>
                        <p class="text-xs text-blue-600">Username cannot be changed</p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-blue-800">Email Address</label>
                        <div class="bg-white px-4 py-3 rounded-lg border border-blue-200 text-blue-900 font-medium">
                            <?= esc(session()->get('email')) ?>
                        </div>
                        <p class="text-xs text-blue-600">Email cannot be changed</p>
                    </div>
                </div>
            </div>

            <!-- Password Change Section -->
            <div class="space-y-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-lock text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Change Password</h3>
                        <p class="text-gray-600 text-sm">Update your password to keep your account secure</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Current Password -->
                    <div class="space-y-2">
                        <label for="current_password" class="block text-sm font-semibold text-gray-700">
                            Current Password
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="password"
                                name="current_password"
                                id="current_password"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gray-50"
                                placeholder="Enter your current password">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400 cursor-pointer hover:text-gray-600" onclick="togglePassword('current_password')"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Enter your current password to verify your identity</p>
                    </div>

                    <!-- New Password Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="new_password" class="block text-sm font-semibold text-gray-700">
                                New Password
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <input type="password"
                                    name="new_password"
                                    id="new_password"
                                    required
                                    minlength="8"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50"
                                    placeholder="Enter new password">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-eye text-gray-400 cursor-pointer hover:text-gray-600" onclick="togglePassword('new_password')"></i>
                                </div>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Minimum 8 characters required</p>
                        </div>

                        <div class="space-y-2">
                            <label for="confirm_password" class="block text-sm font-semibold text-gray-700">
                                Confirm New Password
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <input type="password"
                                    name="confirm_password"
                                    id="confirm_password"
                                    required
                                    minlength="8"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50"
                                    placeholder="Confirm new password">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-eye text-gray-400 cursor-pointer hover:text-gray-600" onclick="togglePassword('confirm_password')"></i>
                                </div>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Must match your new password</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Password Requirements -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-info text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-blue-900 mb-3">Password Requirements</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check-circle text-green-500"></i>
                                <span class="text-sm text-blue-800">Minimum 8 characters long</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check-circle text-green-500"></i>
                                <span class="text-sm text-blue-800">Include uppercase and lowercase letters</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check-circle text-green-500"></i>
                                <span class="text-sm text-blue-800">Include numbers and special characters</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check-circle text-green-500"></i>
                                <span class="text-sm text-blue-800">Different from current password</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-4">
                <button type="submit" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-save mr-2"></i>
                    Update Password
                </button>
            </div>
        </form>
    </div>

    <!-- Enhanced Security Tips -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-8 py-6 border-b border-amber-200">
            <h3 class="text-xl font-semibold text-amber-900 flex items-center">
                <i class="fas fa-shield-alt mr-3 text-amber-600"></i>
                Security Best Practices
            </h3>
        </div>
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-key text-amber-600 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Strong Passwords</h4>
                            <p class="text-sm text-gray-600 mt-1">Use unique, complex passwords for each account and change them regularly.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-mobile-alt text-amber-600 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Two-Factor Authentication</h4>
                            <p class="text-sm text-gray-600 mt-1">Enable 2FA on your account for an extra layer of security.</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-eye-slash text-amber-600 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Privacy Protection</h4>
                            <p class="text-sm text-gray-600 mt-1">Never share your password or login credentials with anyone.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-history text-amber-600 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Regular Updates</h4>
                            <p class="text-sm text-gray-600 mt-1">Keep your password updated and monitor your account activity.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = field.nextElementSibling.querySelector('i');

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Password strength validation
    document.getElementById('new_password').addEventListener('input', function() {
        const password = this.value;
        const confirmPassword = document.getElementById('confirm_password');

        if (confirmPassword.value && password !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Passwords do not match');
        } else {
            confirmPassword.setCustomValidity('');
        }
    });

    document.getElementById('confirm_password').addEventListener('input', function() {
        const password = document.getElementById('new_password').value;

        if (this.value !== password) {
            this.setCustomValidity('Passwords do not match');
        } else {
            this.setCustomValidity('');
        }
    });
</script>
<?= $this->endSection() ?>