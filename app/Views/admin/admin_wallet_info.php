<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Admin Wallet Information</h1>
            <p class="text-gray-600 mt-2">Set your wallet information for special users to request topups</p>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Wallet Information Form -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-wallet text-blue-600 mr-3"></i>
                    Set Wallet Information
                </h2>

                <form action="<?= base_url('admin/admin-wallet-info') ?>" method="POST" class="space-y-6">
                    <!-- Wallet Name -->
                    <div>
                        <label for="wallet_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Wallet Name
                        </label>
                        <input type="text" 
                               id="wallet_name" 
                               name="wallet_name" 
                               value="<?= esc($admin['wallet_name'] ?? '') ?>"
                               class="block w-full py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="e.g., Admin Main Wallet"
                               required>
                        <p class="mt-1 text-sm text-gray-500">Name that will be displayed to special users</p>
                    </div>

                    <!-- Wallet Type -->
                    <div>
                        <label for="wallet_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Wallet Type
                        </label>
                        <select id="wallet_type" name="wallet_type" class="block w-full py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Select wallet type</option>
                            <option value="easypaisa" <?= ($admin['wallet_type'] ?? '') === 'easypaisa' ? 'selected' : '' ?>>Easypaisa</option>
                            <option value="jazz_cash" <?= ($admin['wallet_type'] ?? '') === 'jazz_cash' ? 'selected' : '' ?>>Jazz Cash</option>
                            <option value="bank" <?= ($admin['wallet_type'] ?? '') === 'bank' ? 'selected' : '' ?>>Bank Transfer</option>
                            <option value="manual" <?= ($admin['wallet_type'] ?? '') === 'manual' ? 'selected' : '' ?>>Manual/Cash</option>
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Type of wallet for receiving payments</p>
                    </div>

                    <!-- Wallet Number -->
                    <div>
                        <label for="wallet_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Wallet Number/Account
                        </label>
                        <input type="text" 
                               id="wallet_number" 
                               name="wallet_number" 
                               value="<?= esc($admin['wallet_number'] ?? '') ?>"
                               class="block w-full py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="e.g., 03001234567 or Account Number"
                               required>
                        <p class="mt-1 text-sm text-gray-500">Phone number, account number, or identifier</p>
                    </div>

                    <!-- Bank Name (if applicable) -->
                    <div>
                        <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Bank Name (Optional)
                        </label>
                        <input type="text" 
                               id="bank_name" 
                               name="bank_name" 
                               value="<?= esc($admin['bank_name'] ?? '') ?>"
                               class="block w-full py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="e.g., HBL, UBL, Meezan Bank">
                        <p class="mt-1 text-sm text-gray-500">Bank name if using bank transfer</p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        <i class="fas fa-save mr-2"></i>
                        Update Wallet Information
                    </button>
                </form>
            </div>

            <!-- Current Wallet Information Display -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-info-circle text-green-600 mr-3"></i>
                    Current Wallet Information
                </h2>

                <?php if (!empty($admin['wallet_name']) && !empty($admin['wallet_number'])): ?>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-600">Wallet Name:</span>
                                <span class="text-sm font-semibold text-gray-900"><?= esc($admin['wallet_name']) ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-600">Wallet Type:</span>
                                <span class="text-sm font-semibold text-gray-900"><?= ucfirst(str_replace('_', ' ', $admin['wallet_type'])) ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-600">Wallet Number:</span>
                                <span class="text-sm font-semibold text-gray-900"><?= esc($admin['wallet_number']) ?></span>
                            </div>
                            <?php if (!empty($admin['bank_name'])): ?>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-600">Bank Name:</span>
                                    <span class="text-sm font-semibold text-gray-900"><?= esc($admin['bank_name']) ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-600">Status:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Active
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h4 class="font-medium text-blue-900 mb-2">Information for Special Users</h4>
                        <p class="text-sm text-blue-800">
                            Special users will see this wallet information when they request topups. 
                            They will send money to these details and upload payment proof for your approval.
                        </p>
                    </div>
                <?php else: ?>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                            <div>
                                <h4 class="font-medium text-yellow-900">No Wallet Information Set</h4>
                                <p class="text-sm text-yellow-800 mt-1">
                                    Please set your wallet information above so special users can request topups.
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
