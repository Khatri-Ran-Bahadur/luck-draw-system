<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-purple-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg w-full">
        <!-- Register Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto w-16 h-16 bg-gradient-to-r from-green-600 to-blue-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                    <i class="fas fa-user-plus text-2xl text-white"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Create Account</h2>
                <p class="text-gray-600">Join thousands of users and start winning amazing prizes instantly!</p>
            </div>

            <?php if (isset($errors['general'])): ?>
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <p class="text-red-700 font-medium"><?= $errors['general'] ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Register Form -->
            <form class="space-y-6" method="POST" action="/register">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input id="full_name" name="full_name" type="text" required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="Enter your full name"
                                value="<?= session()->getFlashdata('old_input')['full_name'] ?? '' ?>">
                        </div>
                        <?php if (isset($errors['full_name'])): ?>
                            <div class="mt-2 text-red-600 text-sm flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <?= $errors['full_name'] ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-at text-gray-400"></i>
                            </div>
                            <input id="username" name="username" type="text" required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="Choose a username"
                                value="<?= session()->getFlashdata('old_input')['username'] ?? '' ?>">
                        </div>
                        <?php if (isset($errors['username'])): ?>
                            <div class="mt-2 text-red-600 text-sm flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <?= $errors['username'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            placeholder="Enter your email address"
                            value="<?= session()->getFlashdata('old_input')['email'] ?? '' ?>">
                    </div>
                    <?php if (isset($errors['email'])): ?>
                        <div class="mt-2 text-red-600 text-sm flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <?= $errors['email'] ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number <span class="text-gray-500 text-xs">(Optional)</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-phone text-gray-400"></i>
                        </div>
                        <input id="phone" name="phone" type="tel"
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            placeholder="Enter your phone number"
                            value="<?= session()->getFlashdata('old_input')['phone'] ?? '' ?>">
                    </div>
                    <?php if (isset($errors['phone'])): ?>
                        <div class="mt-2 text-red-600 text-sm flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <?= $errors['phone'] ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="referral_code" class="block text-sm font-medium text-gray-700 mb-2">Referral Code <span class="text-gray-500 text-xs">(Optional)</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-gift text-gray-400"></i>
                        </div>
                        <input id="referral_code" name="referral_code" type="text"
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            placeholder="Enter referral code to get bonus"
                            value="<?= session()->getFlashdata('old_input')['referral_code'] ?? session()->get('referral_code') ?? '' ?>">
                    </div>
                    <p class="mt-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Enter a friend's referral code to earn bonus rewards when you register!
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="new-password" required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="Create a strong password">
                        </div>
                        <?php if (isset($errors['password'])): ?>
                            <div class="mt-2 text-red-600 text-sm flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <?= $errors['password'] ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="confirm_password" name="confirm_password" type="password" autocomplete="new-password" required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="Confirm your password">
                        </div>
                        <div id="password-match" class="mt-2 text-sm hidden">
                            <span class="text-green-600 flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                Passwords match
                            </span>
                        </div>
                        <div id="password-mismatch" class="mt-2 text-sm hidden">
                            <span class="text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                Passwords don't match
                            </span>
                        </div>
                        <?php if (isset($errors['confirm_password'])): ?>
                            <div class="mt-2 text-red-600 text-sm flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <?= $errors['confirm_password'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="flex items-start">
                    <input id="terms" name="terms" type="checkbox" required
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1">
                    <label for="terms" class="ml-2 text-sm text-gray-700">
                        I agree to the
                        <a href="/terms" class="text-blue-600 hover:text-blue-500 font-medium">Terms & Conditions</a>
                        and
                        <a href="/privacy" class="text-blue-600 hover:text-blue-500 font-medium">Privacy Policy</a>
                    </label>
                </div>

                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-user-plus text-green-300 group-hover:text-green-200"></i>
                        </span>
                        Create Account & Start Playing
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="mt-8">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300" />
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">Or continue with</span>
                    </div>
                </div>

                <!-- Google Register Button -->
                <div class="mt-6">
                    <a href="/auth/google"
                        class="w-full flex justify-center py-3 px-4 border-2 border-gray-300 rounded-xl shadow-sm bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-red-500 transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                        </svg>
                        Continue with Google
                    </a>
                </div>

                <!-- Login Link -->
                <div class="mt-4">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300" />
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">Already have an account?</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="/login"
                            class="w-full flex justify-center py-3 px-4 border-2 border-gray-300 rounded-xl shadow-sm bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-blue-500 transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-sign-in-alt mr-2 text-blue-600"></i>
                            Sign In
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-600">
                By creating an account, you agree to our
                <a href="/terms" class="text-blue-600 hover:text-blue-500 font-medium">Terms of Service</a>
                and
                <a href="/privacy" class="text-blue-600 hover:text-blue-500 font-medium">Privacy Policy</a>
            </p>
        </div>
    </div>
</div>

<!-- Background decoration -->
<div class="fixed inset-0 -z-10 overflow-hidden">
    <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-green-200 to-blue-200 rounded-full opacity-20 blur-3xl"></div>
    <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-blue-200 to-purple-200 rounded-full opacity-20 blur-3xl"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        const passwordMatch = document.getElementById('password-match');
        const passwordMismatch = document.getElementById('password-mismatch');

        function validatePassword() {
            if (password.value === '' || confirmPassword.value === '') {
                passwordMatch.classList.add('hidden');
                passwordMismatch.classList.add('hidden');
                return;
            }

            if (password.value === confirmPassword.value) {
                passwordMatch.classList.remove('hidden');
                passwordMismatch.classList.add('hidden');
                confirmPassword.setCustomValidity('');
            } else {
                passwordMatch.classList.add('hidden');
                passwordMismatch.classList.remove('hidden');
                confirmPassword.setCustomValidity("Passwords don't match");
            }
        }

        password.addEventListener('input', validatePassword);
        confirmPassword.addEventListener('input', validatePassword);

        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            if (password.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Passwords do not match. Please try again.');
                return false;
            }
        });
    });
</script>

<?= $this->endSection() ?>