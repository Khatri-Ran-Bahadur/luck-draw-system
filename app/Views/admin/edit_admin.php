<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-8">
    <!-- Enhanced Page Header -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-700 rounded-2xl shadow-lg p-8 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-user-edit text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Edit Administrator</h1>
                    <p class="text-purple-100 text-lg mt-1">Update administrator account information and privileges</p>
                </div>
            </div>
            <a href="<?= base_url('admin/admins') ?>" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 text-white rounded-xl hover:bg-opacity-30 transition-all duration-200 backdrop-blur-sm border border-white border-opacity-30">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Admins
            </a>
        </div>
    </div>

    <!-- Enhanced Edit Form -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Administrator Information</h2>
            <p class="text-gray-600 mt-1">Update administrator account details and settings</p>
        </div>

        <form action="<?= base_url('admin/admins/edit/' . $admin['id']) ?>" method="post" enctype="multipart/form-data" class="p-8 space-y-8">
            <?= csrf_field() ?>

            <!-- Profile Image Section -->
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-6 border border-purple-200">
                <h3 class="text-lg font-semibold text-purple-900 mb-4 flex items-center">
                    <i class="fas fa-camera mr-3 text-purple-600"></i>
                    Profile Image
                </h3>



                <div class="flex items-center space-x-8">
                    <div class="w-28 h-28 bg-white rounded-2xl flex items-center justify-center overflow-hidden border-2 border-purple-200 shadow-lg">
                        <?php if ($admin['profile_image']): ?>
                            <?php
                            // Handle both full path and filename cases
                            $imagePath = $admin['profile_image'];
                            if (strpos($imagePath, 'uploads/') === 0) {
                                // Already has uploads/ prefix
                                $imageSrc = base_url($imagePath);
                            } else {
                                // Just filename, add uploads/profiles/ prefix
                                $imageSrc = base_url('uploads/profiles/' . $imagePath);
                            }
                            ?>
                            <img id="image-preview"
                                src="<?= $imageSrc ?>"
                                alt="<?= esc($admin['full_name']) ?>"
                                class="w-full h-full object-cover"
                                onerror="console.log('Image failed to load:', this.src); this.style.display='none'; document.getElementById('default-icon').style.display='flex';"
                                onload="console.log('Image loaded successfully:', this.src); document.getElementById('default-icon').style.display='none';">
                            <i class="fas fa-user-shield text-4xl text-purple-400" id="default-icon" style="display: none;"></i>
                        <?php else: ?>
                            <img id="image-preview" src="" alt="" class="w-full h-full object-cover hidden">
                            <i class="fas fa-user-shield text-4xl text-purple-400" id="default-icon"></i>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1 space-y-3">
                        <div class="relative">
                            <input type="file"
                                name="profile_image"
                                id="profile_image"
                                accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200 transition-all duration-200 cursor-pointer">
                        </div>
                        <p class="text-sm text-purple-600">PNG, JPG, GIF up to 5MB. Leave empty to keep current image.</p>
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
                                value="<?= esc($admin['username']) ?>"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50"
                                placeholder="Enter username">
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Username for login access</p>
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
                                value="<?= esc($admin['email']) ?>"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50"
                                placeholder="Enter email address">
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Used for notifications and password reset</p>
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
                                value="<?= esc($admin['full_name']) ?>"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50"
                                placeholder="Enter full name">
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Administrator's full name</p>
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
                                value="<?= esc($admin['phone'] ?? '') ?>"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50"
                                placeholder="Enter phone number (optional)">
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Optional contact number</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="status" class="block text-sm font-semibold text-gray-700">
                        Account Status
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-toggle-on text-gray-400"></i>
                        </div>
                        <select name="status" id="status" class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white cursor-pointer">
                            <option value="active" <?= $admin['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $admin['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Password Section -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-lock mr-3 text-green-600"></i>
                    Security Credentials (Optional)
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="new_password" class="block text-sm font-semibold text-gray-700">
                            New Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-key text-gray-400"></i>
                            </div>
                            <input type="password"
                                name="new_password"
                                id="new_password"
                                minlength="8"
                                class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50"
                                placeholder="Enter new password">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400 cursor-pointer hover:text-gray-600" onclick="togglePassword('new_password')"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Minimum 8 characters. Leave empty to keep current password.</p>
                    </div>

                    <div class="space-y-2">
                        <label for="confirm_new_password" class="block text-sm font-semibold text-gray-700">
                            Confirm New Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-check-circle text-gray-400"></i>
                            </div>
                            <input type="password"
                                name="confirm_new_password"
                                id="confirm_new_password"
                                minlength="8"
                                class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50"
                                placeholder="Confirm new password">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400 cursor-pointer hover:text-gray-600" onclick="togglePassword('confirm_new_password')"></i>
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
                        <h3 class="text-lg font-semibold text-purple-900 mb-3">Account Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-id-badge text-purple-500"></i>
                                <span class="text-sm text-purple-800">Admin ID: #<?= $admin['id'] ?></span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-calendar-plus text-purple-500"></i>
                                <span class="text-sm text-purple-800">Created: <?= date('M d, Y', strtotime($admin['created_at'])) ?></span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-calendar-check text-purple-500"></i>
                                <span class="text-sm text-purple-800">Last Updated: <?= $admin['updated_at'] ? date('M d, Y', strtotime($admin['updated_at'])) : 'Never' ?></span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-shield-alt text-purple-500"></i>
                                <span class="text-sm text-purple-800">Role: Administrator</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-4">
                <button type="submit" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-700 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-indigo-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-save mr-2"></i>
                    Update Administrator
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('profile_image');
        const imagePreview = document.getElementById('image-preview');
        const defaultIcon = document.getElementById('default-icon');

        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) { // 5MB limit
                    alert('File size must be less than 5MB');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    if (defaultIcon) defaultIcon.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // Password toggle functionality
        window.togglePassword = function(fieldId) {
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
        };

        // Password confirmation validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmNewPassword = document.getElementById('confirm_new_password').value;

            if (newPassword || confirmNewPassword) {
                if (newPassword !== confirmNewPassword) {
                    e.preventDefault();
                    alert('New passwords do not match');
                    return false;
                }

                if (newPassword.length > 0 && newPassword.length < 8) {
                    e.preventDefault();
                    alert('New password must be at least 8 characters long');
                    return false;
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>