<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-8">
    <!-- Enhanced Page Header -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-700 rounded-2xl shadow-lg p-8 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-user-plus text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Create New Administrator</h1>
                    <p class="text-purple-100 text-lg mt-1">Add a new administrator to the system</p>
                </div>
            </div>
            <a href="<?= base_url('admin/admins') ?>" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 text-white rounded-xl hover:bg-opacity-30 transition-all duration-200 backdrop-blur-sm border border-white border-opacity-30">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Admins
            </a>
        </div>
    </div>

    <!-- Enhanced Create Form -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Administrator Information</h2>
            <p class="text-gray-600 mt-1">Fill in the details to create a new administrator account</p>
        </div>

        <form action="<?= base_url('admin/admins/create') ?>" method="post" enctype="multipart/form-data" class="p-8 space-y-8">
            <?= csrf_field() ?>

            <!-- Profile Image Section -->
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-6 border border-purple-200">
                <h3 class="text-lg font-semibold text-purple-900 mb-4 flex items-center">
                    <i class="fas fa-camera mr-3 text-purple-600"></i>
                    Profile Image
                </h3>
                <div class="flex items-center space-x-8">
                    <div class="w-28 h-28 bg-white rounded-2xl flex items-center justify-center overflow-hidden border-2 border-purple-200 shadow-lg">
                        <img id="image-preview" src="" alt="" class="w-full h-full object-cover hidden">
                        <i class="fas fa-user-plus text-4xl text-purple-400" id="default-icon"></i>
                    </div>
                    <div class="flex-1 space-y-3">
                        <div class="relative">
                            <input type="file"
                                name="profile_image"
                                id="profile_image"
                                accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200 transition-all duration-200 cursor-pointer">
                        </div>
                        <p class="text-sm text-purple-600">PNG, JPG, GIF up to 5MB. Optional but recommended for better identification.</p>
                    </div>
                </div>
            </div>

            <!-- Basic Information Section -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user mr-3 text-blue-600"></i>
                    Basic Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="username" class="block text-sm font-semibold text-gray-700">
                            Username
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text"
                                name="username"
                                id="username"
                                required
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gray-50"
                                placeholder="Enter unique username">
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Choose a unique username for login</p>
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700">
                            Email Address
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email"
                                name="email"
                                id="email"
                                required
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gray-50"
                                placeholder="Enter email address">
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Will be used for notifications and password reset</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="full_name" class="block text-sm font-semibold text-gray-700">
                            Full Name
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-gray-400"></i>
                            </div>
                            <input type="text"
                                name="full_name"
                                id="full_name"
                                required
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gray-50"
                                placeholder="Enter full name">
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Enter the administrator's full name</p>
                    </div>

                    <div class="space-y-2">
                        <label for="phone" class="block text-sm font-semibold text-gray-700">
                            Phone Number
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-phone text-gray-400"></i>
                            </div>
                            <input type="tel"
                                name="phone"
                                id="phone"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gray-50"
                                placeholder="Enter phone number (optional)">
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Optional contact number</p>
                    </div>
                </div>
            </div>

            <!-- Password Section -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-lock mr-3 text-green-600"></i>
                    Security Credentials
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">
                            Password
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-key text-gray-400"></i>
                            </div>
                            <input type="password"
                                name="password"
                                id="password"
                                required
                                minlength="8"
                                class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50"
                                placeholder="Enter secure password">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400 cursor-pointer hover:text-gray-600" onclick="togglePassword('password')"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Minimum 8 characters required</p>
                    </div>

                    <div class="space-y-2">
                        <label for="confirm_password" class="block text-sm font-semibold text-gray-700">
                            Confirm Password
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-check-circle text-gray-400"></i>
                            </div>
                            <input type="password"
                                name="confirm_password"
                                id="confirm_password"
                                required
                                minlength="8"
                                class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50"
                                placeholder="Confirm password">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400 cursor-pointer hover:text-gray-600" onclick="togglePassword('confirm_password')"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Must match the password above</p>
                    </div>
                </div>
            </div>

            <!-- Admin Privileges Info -->
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-xl p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-info text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-purple-900 mb-3">Administrator Privileges</h3>
                        <p class="text-purple-800 mb-3">This user will have full administrative access to:</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check-circle text-green-500"></i>
                                <span class="text-sm text-purple-800">Manage lucky draws and entries</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check-circle text-green-500"></i>
                                <span class="text-sm text-purple-800">View and manage users</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check-circle text-green-500"></i>
                                <span class="text-sm text-purple-800">Access transaction history</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check-circle text-green-500"></i>
                                <span class="text-sm text-purple-800">System configuration</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-4">
                <button type="submit" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-700 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-indigo-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-user-plus mr-2"></i>
                    Create Administrator
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Image preview functionality
    document.getElementById('profile_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('image-preview');
        const defaultIcon = document.getElementById('default-icon');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                defaultIcon.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('hidden');
            defaultIcon.classList.remove('hidden');
        }
    });

    // Password toggle functionality
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

    // Password confirmation validation
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const confirmPassword = document.getElementById('confirm_password');

        if (confirmPassword.value && password !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Passwords do not match');
        } else {
            confirmPassword.setCustomValidity('');
        }
    });

    document.getElementById('confirm_password').addEventListener('input', function() {
        const password = document.getElementById('password').value;

        if (this.value !== password) {
            this.setCustomValidity('Passwords do not match');
        } else {
            this.setCustomValidity('');
        }
    });

    // Form validation enhancement
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match. Please check and try again.');
            return false;
        }

        if (password.length < 8) {
            e.preventDefault();
            alert('Password must be at least 8 characters long.');
            return false;
        }
    });
</script>
<?= $this->endSection() ?>