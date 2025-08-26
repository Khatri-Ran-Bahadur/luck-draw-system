<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Application Settings</h1>
            <p class="text-gray-600 mt-2">Configure website settings, referral rewards, and payment methods</p>
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

        <form action="<?= base_url('admin/save-application-settings') ?>" method="POST" enctype="multipart/form-data">
            <!-- Website Settings -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Website Settings</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Website Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Website Name</label>
                        <input type="text" 
                               name="website_name" 
                               value="<?= esc($settings['website_name'] ?? 'Lucky Draw System') ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter website name"
                               required>
                    </div>

                    <!-- Contact Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                        <input type="email" 
                               name="contact_email" 
                               value="<?= esc($settings['contact_email'] ?? '') ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter contact email">
                    </div>

                    <!-- Contact Phone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                        <input type="tel" 
                               name="contact_phone" 
                               value="<?= esc($settings['contact_phone'] ?? '') ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter contact phone">
                    </div>
                </div>

                <!-- Logo Upload -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Website Logo</label>
                    <div class="flex items-center space-x-6">
                        <?php if (!empty($settings['website_logo'])): ?>
                            <div class="w-32 h-20 bg-gray-100 rounded-lg border-2 border-gray-200 flex items-center justify-center overflow-hidden">
                                <img src="<?= base_url('uploads/settings/' . $settings['website_logo']) ?>" 
                                     alt="Website Logo" class="max-w-full max-h-full object-contain">
                            </div>
                        <?php else: ?>
                            <div class="w-32 h-20 bg-gray-100 rounded-lg border-2 border-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                            </div>
                        <?php endif; ?>
                        <div class="flex-1">
                            <input type="file" 
                                   name="website_logo" 
                                   accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-1">Recommended: 300x100px, PNG/JPG format</p>
                        </div>
                    </div>
                </div>

                <!-- Favicon Upload -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                    <div class="flex items-center space-x-6">
                        <?php if (!empty($settings['favicon'])): ?>
                            <div class="w-16 h-16 bg-gray-100 rounded-lg border-2 border-gray-200 flex items-center justify-center overflow-hidden">
                                <img src="<?= base_url('uploads/settings/' . $settings['favicon']) ?>" 
                                     alt="Favicon" class="max-w-full max-h-full object-contain">
                            </div>
                        <?php else: ?>
                            <div class="w-16 h-16 bg-gray-100 rounded-lg border-2 border-gray-200 flex items-center justify-center">
                                <i class="fas fa-star text-gray-400 text-xl"></i>
                            </div>
                        <?php endif; ?>
                        <div class="flex-1">
                            <input type="file" 
                                   name="favicon" 
                                   accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-1">Recommended: 32x32px, ICO/PNG format</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Referral System Settings -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Referral System Settings</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Referral Bonus Percentage -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Referral Bonus Percentage (%)</label>
                        <input type="number" 
                               name="referral_bonus_percentage" 
                               value="<?= esc($settings['referral_bonus_percentage'] ?? '5') ?>"
                               min="0"
                               max="100"
                               step="0.01"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter bonus percentage">
                        <p class="text-xs text-gray-500 mt-1">Percentage of topup amount given as referral bonus</p>
                    </div>
                </div>
            </div>

            <!-- User Transfer Settings -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">User Transfer Settings</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Transfer System -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="user_transfer_enabled" 
                                   value="1" 
                                   <?= ($settings['user_transfer_enabled'] ?? '1') == '1' ? 'checked' : '' ?>
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Enable User-to-User Transfers</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1">Allow special users to transfer money to other users</p>
                    </div>

                    <!-- Transfer Fee -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Transfer Fee Percentage (%)</label>
                        <input type="number" 
                               name="transfer_fee_percentage" 
                               value="<?= esc($settings['transfer_fee_percentage'] ?? '1') ?>"
                               min="0"
                               max="100"
                               step="0.01"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter transfer fee">
                        <p class="text-xs text-gray-500 mt-1">Fee charged on user-to-user transfers</p>
                    </div>

                    <!-- Minimum Transfer -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Transfer Amount (Rs.)</label>
                        <input type="number" 
                               name="min_transfer_amount" 
                               value="<?= esc($settings['min_transfer_amount'] ?? '100') ?>"
                               min="0"
                               step="0.01"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter minimum amount">
                    </div>

                    <!-- Maximum Transfer -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Transfer Amount (Rs.)</label>
                        <input type="number" 
                               name="max_transfer_amount" 
                               value="<?= esc($settings['max_transfer_amount'] ?? '10000') ?>"
                               min="0"
                               step="0.01"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter maximum amount">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i>Save All Settings
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>