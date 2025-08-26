<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="<?= base_url('wallet') ?>" class="text-blue-600 hover:text-blue-700 mr-4">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Wallet Profile</h1>
            </div>
            <p class="text-gray-600">Manage your wallet details and personal information</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column: Forms -->
            <div class="space-y-6">
                <!-- User Profile Information Form -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-user text-blue-600 mr-3"></i>
                        Personal Information
                    </h2>

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

                    <form action="<?= base_url('wallet/update-profile') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <!-- Profile Image -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Profile Image</label>
                            <div class="flex items-center space-x-6">
                                <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-100 border-2 border-gray-200">
                                    <?php if (!empty($user['profile_image'])): ?>
                                        <img src="<?= base_url('uploads/profiles/' . $user['profile_image']) ?>"
                                            alt="Profile" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                            <i class="fas fa-user text-white text-2xl"></i>
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
                        </div>

                        <!-- Full Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text"
                                name="full_name"
                                value="<?= esc($user['full_name']) ?>"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Enter your full name"
                                required>
                            <p class="text-sm text-gray-500 mt-1">Your full name as it appears on official documents</p>
                        </div>

                        <!-- Username -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                            <input type="text"
                                name="username"
                                value="<?= esc($user['username']) ?>"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Enter your username"
                                required>
                            <p class="text-sm text-gray-500 mt-1">Unique username for your account (cannot be changed)</p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email"
                                name="email"
                                value="<?= esc($user['email']) ?>"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Enter your email address"
                                required>
                            <p class="text-sm text-gray-500 mt-1">Primary email for account notifications</p>
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel"
                                name="phone"
                                value="<?= esc($user['phone'] ?? '') ?>"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Enter your phone number">
                            <p class="text-sm text-gray-500 mt-1">Optional: For additional verification and support</p>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i>
                            Update Personal Information
                        </button>
                    </form>
                </div>

                <!-- Wallet Information Form -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-wallet text-green-600 mr-3"></i>
                        Wallet Information
                    </h2>

                    <form action="<?= base_url('wallet/update-wallet') ?>" method="POST" class="space-y-6">
                        <!-- Wallet Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Wallet Name</label>
                            <input type="text"
                                name="wallet_name"
                                value="<?= esc($wallet_details['wallet_name'] ?? '') ?>"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Enter your wallet name"
                                required>
                            <p class="text-sm text-gray-500 mt-1">This is the name that will be displayed to other users</p>
                        </div>

                        <!-- Wallet Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Wallet Number</label>
                            <input type="text"
                                name="wallet_number"
                                value="<?= esc($wallet_details['wallet_number'] ?? '') ?>"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Enter your wallet number"
                                required>
                            <p class="text-sm text-gray-500 mt-1">Your wallet/account number for receiving payments</p>
                        </div>

                        <!-- Wallet Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Wallet Type</label>
                            <select name="wallet_type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500" required>
                                <option value="">Select wallet type</option>
                                <option value="easypaisa" <?= ($wallet_details['wallet_type'] ?? '') === 'easypaisa' ? 'selected' : '' ?>>Easypaisa</option>
                                <option value="jazz_cash" <?= ($wallet_details['wallet_type'] ?? '') === 'jazz_cash' ? 'selected' : '' ?>>Jazz Cash</option>
                                <option value="bank" <?= ($wallet_details['wallet_type'] ?? '') === 'bank' ? 'selected' : '' ?>>Bank Account</option>
                                <option value="hbl" <?= ($wallet_details['wallet_type'] ?? '') === 'hbl' ? 'selected' : '' ?>>HBL Bank</option>
                                <option value="ubank" <?= ($wallet_details['wallet_type'] ?? '') === 'ubank' ? 'selected' : '' ?>>UBL Bank</option>
                                <option value="mcb" <?= ($wallet_details['wallet_type'] ?? '') === 'mcb' ? 'selected' : '' ?>>MCB Bank</option>
                                <option value="abank" <?= ($wallet_details['wallet_type'] ?? '') === 'abank' ? 'selected' : '' ?>>Allied Bank</option>
                                <option value="nbp" <?= ($wallet_details['wallet_type'] ?? '') === 'nbp' ? 'selected' : '' ?>>National Bank of Pakistan</option>
                                <option value="sbank" <?= ($wallet_details['wallet_type'] ?? '') === 'sbank' ? 'selected' : '' ?>>Standard Chartered Bank</option>
                                <option value="citi" <?= ($wallet_details['wallet_type'] ?? '') === 'citi' ? 'selected' : '' ?>>Citibank Pakistan</option>
                                <option value="hsbc" <?= ($wallet_details['wallet_type'] ?? '') === 'hsbc' ? 'selected' : '' ?>>HSBC Pakistan</option>
                                <option value="payoneer" <?= ($wallet_details['wallet_type'] ?? '') === 'payoneer' ? 'selected' : '' ?>>Payoneer</option>
                                <option value="skrill" <?= ($wallet_details['wallet_type'] ?? '') === 'skrill' ? 'selected' : '' ?>>Skrill</option>
                                <option value="neteller" <?= ($wallet_details['wallet_type'] ?? '') === 'neteller' ? 'selected' : '' ?>>Neteller</option>
                                <option value="other" <?= ($wallet_details['wallet_type'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                            <p class="text-sm text-gray-500 mt-1">Select the type of wallet or bank you're using</p>
                        </div>

                        <!-- Bank Name (only for bank types) -->
                        <div id="bank_name_field" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                            <input type="text"
                                name="bank_name"
                                value="<?= esc($wallet_details['bank_name'] ?? '') ?>"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Enter bank name">
                            <p class="text-sm text-gray-500 mt-1">Required for bank transfer types</p>
                        </div>

                        <!-- Special User Status -->
                        <?php if ($user['is_special_user'] ?? false): ?>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <i class="fas fa-star text-blue-600 mr-2"></i>
                                    <span class="text-sm font-medium text-blue-800">Special User Status</span>
                                    <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Active
                                    </span>
                                </div>
                                <p class="text-xs text-blue-700 mt-1">Your wallet information is displayed to other users for topups</p>
                            </div>
                        <?php endif; ?>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-wallet mr-2"></i>
                            Update Wallet Details
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Information Display -->
            <div class="space-y-6">
                <!-- Current Wallet Status -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Current Balance</h3>
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-wallet text-white text-xl"></i>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-900 mb-2">
                            Rs. <?= number_format($wallet['balance'], 2) ?>
                        </div>
                        <p class="text-sm text-gray-500">Available for draws and transfers</p>
                    </div>
                </div>

                <!-- User Profile Summary -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Summary</h3>

                    <div class="space-y-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-100 border-2 border-gray-200">
                                <?php if (!empty($user['profile_image'])): ?>
                                    <img src="<?= base_url('uploads/profiles/' . $user['profile_image']) ?>"
                                        alt="Profile" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                        <i class="fas fa-user text-white text-xl"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900"><?= esc($user['full_name']) ?></h4>
                                <p class="text-sm text-gray-600">@<?= esc($user['username']) ?></p>
                                <p class="text-sm text-gray-500"><?= esc($user['email']) ?></p>
                                <?php if ($user['phone']): ?>
                                    <p class="text-sm text-gray-500"><?= esc($user['phone']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Member Since:</span>
                                    <p class="font-medium text-gray-900"><?= date('M Y', strtotime($user['created_at'])) ?></p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Last Login:</span>
                                    <p class="font-medium text-gray-900"><?= $user['last_login'] ? date('M j, Y', strtotime($user['last_login'])) : 'Never' ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wallet Information Display -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Wallet Information</h3>

                    <?php if (!empty($wallet_details['wallet_name']) && !empty($wallet_details['wallet_number'])): ?>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-600">Wallet Name:</span>
                                <span class="text-sm font-semibold text-gray-900"><?= esc($wallet_details['wallet_name']) ?></span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-600">Wallet Number:</span>
                                <div class="flex items-center space-x-2">
                                    <span class="font-mono text-sm font-semibold text-gray-900"><?= esc($wallet_details['wallet_number']) ?></span>
                                    <button onclick="copyToClipboard('<?= esc($wallet_details['wallet_number']) ?>')"
                                        class="text-purple-600 hover:text-purple-800 transition-colors">
                                        <i class="fas fa-copy text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-600">Wallet Type:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <?= ucfirst($wallet_details['wallet_type']) ?>
                                </span>
                            </div>

                            <?php if ($wallet_details['wallet_type'] === 'bank' && !empty($wallet_details['bank_name'])): ?>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-600">Bank Name:</span>
                                    <span class="text-sm font-semibold text-gray-900"><?= esc($wallet_details['bank_name']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-6">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-exclamation-circle text-gray-400 text-xl"></i>
                            </div>
                            <h4 class="font-medium text-gray-900 mb-1">Wallet Details Not Set</h4>
                            <p class="text-sm text-gray-500">Please fill in your wallet details above to receive payments from other users.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Benefits of Setting Wallet Details -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-star text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-blue-900 mb-2">Benefits of Setting Wallet Details</h3>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Other users can send you money directly</li>
                                <li>• Faster payment processing</li>
                                <li>• Your wallet appears in random top-up displays</li>
                                <li>• Easier to receive payments from draws and transfers</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Show/hide bank name field based on wallet type
    document.querySelector('select[name="wallet_type"]').addEventListener('change', function() {
        const bankNameField = document.getElementById('bank_name_field');
        const bankNameInput = document.querySelector('input[name="bank_name"]');

        if (this.value === 'bank' || this.value.startsWith('bank') || ['hbl', 'ubank', 'mcb', 'abank', 'nbp', 'sbank', 'citi', 'hsbc'].includes(this.value)) {
            bankNameField.classList.remove('hidden');
            bankNameInput.required = true;
        } else {
            bankNameField.classList.add('hidden');
            bankNameInput.required = false;
        }
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const walletType = document.querySelector('select[name="wallet_type"]');
        if (walletType.value === 'bank' || walletType.value.startsWith('bank') || ['hbl', 'ubank', 'mcb', 'abank', 'nbp', 'sbank', 'citi', 'hsbc'].includes(walletType.value)) {
            document.getElementById('bank_name_field').classList.remove('hidden');
            document.querySelector('input[name="bank_name"]').required = true;
        }
    });

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Show success message
            const button = event.target.closest('button');
            const icon = button.querySelector('i');
            const originalClass = icon.className;

            icon.className = 'fas fa-check text-green-600 text-sm';
            button.classList.add('text-green-600');

            setTimeout(() => {
                icon.className = originalClass;
                button.classList.remove('text-green-600');
            }, 2000);
        }).catch(function(err) {
            console.error('Could not copy text: ', err);
        });
    }
</script>
<?= $this->endSection() ?>