<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Admin Profile</h1>
            <p class="text-gray-600 mt-2">Manage your admin account settings and preferences</p>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Account Information</h2>
                    
                    <form action="<?= base_url('admin/update-profile') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <!-- Profile Image -->
                        <div class="flex items-center space-x-6">
                            <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-100 border-2 border-gray-200">
                                <?php if (!empty($admin['profile_image'])): ?>
                                    <img src="<?= base_url('uploads/profiles/' . $admin['profile_image']) ?>" 
                                         alt="Admin Profile" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                        <i class="fas fa-user text-white text-3xl"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1">
                                <input type="file" 
                                       name="profile_image" 
                                       accept="image/*"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="text-xs text-gray-500 mt-1">JPG, PNG up to 2MB. Leave empty to keep current image.</p>
                            </div>
                        </div>

                        <!-- Full Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" 
                                   name="full_name" 
                                   value="<?= esc($admin['full_name'] ?? '') ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter your full name"
                                   required>
                        </div>

                        <!-- Username -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                            <input type="text" 
                                   name="username" 
                                   value="<?= esc($admin['username']) ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50"
                                   placeholder="Enter username"
                                   readonly>
                            <p class="text-xs text-gray-500 mt-1">Username cannot be changed</p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" 
                                   name="email" 
                                   value="<?= esc($admin['email']) ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter your email address"
                                   required>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" 
                                   name="phone" 
                                   value="<?= esc($admin['phone'] ?? '') ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter your phone number">
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-save mr-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Change Password</h2>
                    
                    <form action="<?= base_url('admin/change-password') ?>" method="POST" class="space-y-6">
                        <!-- Current Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                            <input type="password" 
                                   name="current_password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter current password"
                                   required>
                        </div>

                        <!-- New Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <input type="password" 
                                   name="new_password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter new password"
                                   required>
                        </div>

                        <!-- Confirm New Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                            <input type="password" 
                                   name="confirm_password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Confirm new password"
                                   required>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-key mr-2"></i>Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-6">
                <!-- Profile Summary -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Summary</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-100 border-2 border-gray-200">
                                <?php if (!empty($admin['profile_image'])): ?>
                                    <img src="<?= base_url('uploads/profiles/' . $admin['profile_image']) ?>" 
                                         alt="Admin Profile" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                        <i class="fas fa-user text-white text-2xl"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900"><?= esc($admin['full_name'] ?? $admin['username']) ?></h4>
                                <p class="text-sm text-gray-600">Administrator</p>
                            </div>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-200">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <div class="text-gray-500">Username</div>
                                    <div class="font-medium text-gray-900"><?= esc($admin['username']) ?></div>
                                </div>
                                <div>
                                    <div class="text-gray-500">Email</div>
                                    <div class="font-medium text-gray-900"><?= esc($admin['email']) ?></div>
                                </div>
                                <div>
                                    <div class="text-gray-500">Phone</div>
                                    <div class="font-medium text-gray-900"><?= esc($admin['phone'] ?? 'Not set') ?></div>
                                </div>
                                <div>
                                    <div class="text-gray-500">Member Since</div>
                                    <div class="font-medium text-gray-900"><?= date('M Y', strtotime($admin['created_at'])) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-3">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="<?= base_url('admin/settings') ?>" class="flex items-center text-blue-700 hover:text-blue-800 text-sm">
                            <i class="fas fa-cog mr-2"></i>
                            Application Settings
                        </a>
                        <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center text-blue-700 hover:text-blue-800 text-sm">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Dashboard
                        </a>
                        <a href="<?= base_url('admin/users') ?>" class="flex items-center text-blue-700 hover:text-blue-800 text-sm">
                            <i class="fas fa-users mr-2"></i>
                            Manage Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
