<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-8">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-700 rounded-2xl shadow-lg p-8 text-white">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="fas fa-credit-card text-3xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold">Payment Settings</h1>
                <p class="text-purple-100 text-lg mt-1">Configure payment methods and wallet system</p>
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

    <!-- Payment Settings Form -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Payment Method Configuration</h2>
            <p class="text-gray-600 mt-1">Enable or disable payment methods and configure settings</p>
        </div>

        <form action="<?= base_url('admin/payment-settings') ?>" method="post" class="p-8 space-y-8">
            <!-- Payment Method Settings -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-credit-card text-blue-600 mr-2"></i>
                    Payment Method Control
                </h3>
                <p class="text-sm text-gray-600 mb-4">
                    These settings are controlled by environment variables. Changes here will be overridden on server restart.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Manual Top-up -->
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900">Manual Top-up (Slip/Proof)</h4>
                            <p class="text-sm text-gray-500">Primary top-up method</p>
                        </div>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-3">
                                Always Enabled
                            </span>
                            <span class="text-sm text-gray-500">ENV: ENABLE_MANUAL_TOPUP</span>
                        </div>
                    </div>

                    <!-- PayPal -->
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900">PayPal</h4>
                            <p class="text-sm text-gray-500">International payments</p>
                        </div>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $settingModel->getPayPalEnabled() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?> mr-3">
                                <?= $settingModel->getPayPalEnabled() ? 'Enabled' : 'Disabled' ?>
                            </span>
                            <span class="text-sm text-gray-500">ENV: ENABLE_PAYPAL</span>
                        </div>
                    </div>

                    <!-- Easypaisa -->
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900">Easypaisa</h4>
                            <p class="text-sm text-gray-500">Mobile payments</p>
                        </div>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $settingModel->getEasypaisaEnabled() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?> mr-3">
                                <?= $settingModel->getEasypaisaEnabled() ? 'Enabled' : 'Disabled' ?>
                            </span>
                            <span class="text-sm text-gray-500">ENV: ENABLE_EASYPAISA</span>
                        </div>
                    </div>

                    <!-- Jazz Cash -->
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900">Jazz Cash</h4>
                            <p class="text-sm text-gray-500">Mobile payments</p>
                        </div>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $settingModel->getJazzCashEnabled() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?> mr-3">
                                <?= $settingModel->getJazzCashEnabled() ? 'Enabled' : 'Disabled' ?>
                            </span>
                            <span class="text-sm text-gray-500">ENV: ENABLE_JAZZ_CASH</span>
                        </div>
                    </div>

                    <!-- Bank Transfer -->
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900">Bank Transfer</h4>
                            <p class="text-sm text-gray-500">Direct bank payments</p>
                        </div>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $settingModel->getBankTransferEnabled() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?> mr-3">
                                <?= $settingModel->getBankTransferEnabled() ? 'Enabled' : 'Disabled' ?>
                            </span>
                            <span class="text-sm text-gray-500">ENV: ENABLE_BANK_TRANSFER</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-1 mr-2"></i>
                        <div class="text-sm text-blue-800">
                            <p class="font-medium mb-1">Environment Variable Control:</p>
                            <p>To change these settings, update the <code class="bg-blue-100 px-1 rounded">env</code> file and restart the server. Manual top-up is always enabled as the primary method.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top-up Settings Section -->
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Top-up Settings</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Top-up Amount (PKR)</label>
                        <input type="number" name="min_topup_amount" value="<?= $settings['min_topup_amount'] ?>" step="0.01" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Top-up Amount (PKR)</label>
                        <input type="number" name="max_topup_amount" value="<?= $settings['max_topup_amount'] ?>" step="0.01" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                    <div>
                        <h4 class="font-medium text-gray-900">Require Admin Approval</h4>
                        <p class="text-sm text-gray-500">All top-up requests must be approved by admin</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="topup_approval_required" value="1" class="sr-only peer" <?= $settings['topup_approval_required'] ? 'checked' : '' ?>>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                    <div>
                        <h4 class="font-medium text-gray-900">Manual Top-up Enabled</h4>
                        <p class="text-sm text-gray-500">Allow users to submit manual top-up requests</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="manual_topup_enabled" value="1" class="sr-only peer" <?= $settings['manual_topup_enabled'] ? 'checked' : '' ?>>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>

            <!-- User Transfer Settings Section -->
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">User Transfer Settings</h3>

                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                    <div>
                        <h4 class="font-medium text-gray-900">Enable User Transfers</h4>
                        <p class="text-sm text-gray-500">Allow users to transfer money to other users</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="user_transfer_enabled" value="1" class="sr-only peer" <?= $settings['user_transfer_enabled'] ? 'checked' : '' ?>>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transfer Fee Percentage (%)</label>
                    <input type="number" name="transfer_fee_percentage" value="<?= $settings['transfer_fee_percentage'] ?>" step="0.01" min="0" max="100" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Fee deducted from transfer amount (0 = no fee)</p>
                </div>
            </div>

            <!-- Wallet Display Settings Section -->
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Wallet Display Settings</h3>

                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                    <div>
                        <h4 class="font-medium text-gray-900">Random Wallet Display</h4>
                        <p class="text-sm text-gray-500">Show random wallet details for top-ups (like 1x bet app)</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="random_wallet_display" value="1" class="sr-only peer" <?= $settings['random_wallet_display'] ? 'checked' : '' ?>>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of Wallets to Display</label>
                    <input type="number" name="wallet_display_count" value="<?= $settings['wallet_display_count'] ?>" min="1" max="10" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">How many random wallets to show users</p>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>