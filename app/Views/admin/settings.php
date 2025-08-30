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

                <!-- Additional Contact Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Contact Address -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Business Address</label>
                        <textarea name="contact_address"
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter business address"><?= esc($settings['contact_address'] ?? '') ?></textarea>
                    </div>

                    <!-- Working Hours -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Working Hours</label>
                        <input type="text"
                            name="contact_working_hours"
                            value="<?= esc($settings['contact_working_hours'] ?? '') ?>"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="e.g., Monday to Friday, 9am to 6pm">
                    </div>
                </div>

                <!-- Social Media Links -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Social Media Links</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Facebook URL -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fab fa-facebook text-blue-600 mr-2"></i>Facebook URL
                            </label>
                            <input type="text"
                                name="facebook_url"
                                value="<?= esc($settings['facebook_url'] ?? '') ?>"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="https://facebook.com/yourpage">
                        </div>

                        <!-- Twitter URL -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fab fa-twitter text-blue-400 mr-2"></i>Twitter URL
                            </label>
                            <input type="text"
                                name="twitter_url"
                                value="<?= esc($settings['twitter_url'] ?? '') ?>"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="https://twitter.com/yourhandle">
                        </div>

                        <!-- Instagram URL -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fab fa-instagram text-pink-500 mr-2"></i>Instagram URL
                            </label>
                            <input type="text"
                                name="instagram_url"
                                value="<?= esc($settings['instagram_url'] ?? '') ?>"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="https://instagram.com/yourhandle">
                        </div>

                        <!-- LinkedIn URL -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fab fa-linkedin text-blue-700 mr-2"></i>LinkedIn URL
                            </label>
                            <input type="text"
                                name="linkedin_url"
                                value="<?= esc($settings['linkedin_url'] ?? '') ?>"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="https://linkedin.com/company/yourcompany">
                        </div>

                        <!-- YouTube URL -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fab fa-youtube text-red-600 mr-2"></i>YouTube URL
                            </label>
                            <input type="text"
                                name="youtube_url"
                                value="<?= esc($settings['youtube_url'] ?? '') ?>"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="https://youtube.com/channel/yourchannel">
                        </div>
                    </div>
                </div>

                <!-- Footer Settings -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Footer Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Footer Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Footer Description</label>
                            <textarea name="footer_description"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Brief description about your business..."><?= esc($settings['footer_description'] ?? '') ?></textarea>
                        </div>

                        <!-- Footer Copyright -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Copyright Text</label>
                            <input type="text"
                                name="footer_copyright"
                                value="<?= esc($settings['footer_copyright'] ?? '') ?>"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="© 2024 Your Company. All rights reserved.">
                        </div>


                    </div>
                </div>

                <!-- Logo and Favicon Upload -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Website Logo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Website Logo</label>
                        <div class="flex items-center space-x-4">
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

                    <!-- Favicon -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                        <div class="flex items-center space-x-4">
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
                                    accept="image/x-icon,image/png"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="text-xs text-gray-500 mt-1">Recommended: 32x32px, ICO/PNG format</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Referral System Settings -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Referral System Settings</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Referral Bonus (Fixed Amount) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Referral Bonus Amount (PKR)</label>
                        <input type="number"
                            name="referral_bonus_amount"
                            value="<?= esc($settings['referral_bonus_amount'] ?? '100') ?>"
                            min="0"
                            step="0.01"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter fixed bonus amount">
                        <p class="text-xs text-gray-500 mt-1">Fixed cash amount given as referral bonus</p>
                    </div>

                    <!-- Special User Commission Percentage -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Special User Commission (%)</label>
                        <input type="number"
                            name="special_user_commission"
                            value="<?= esc($settings['special_user_commission'] ?? '5') ?>"
                            min="0"
                            max="100"
                            step="0.01"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter commission percentage">
                        <p class="text-xs text-gray-500 mt-1">Commission percentage for special users on topup approvals</p>
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