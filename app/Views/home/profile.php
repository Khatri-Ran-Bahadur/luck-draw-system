<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
            <p class="text-gray-600 mt-2">Manage your personal information and wallet details</p>
        </div>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-800"><?= session()->getFlashdata('success') ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800"><?= session()->getFlashdata('error') ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>
                    <p class="text-sm text-gray-600 mt-1">Update your personal details</p>
                </div>
                <div class="p-6">
                    <form action="<?= base_url('update-profile') ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <!-- Profile Image -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Image</label>
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <?php if ($user['profile_image']): ?>
                                        <?php
                                        // Handle both full path and filename cases
                                        $imagePath = $user['profile_image'];
                                        if (strpos($imagePath, 'uploads/') === 0) {
                                            // Already has uploads/ prefix
                                            $imageSrc = base_url($imagePath);
                                        } else {
                                            // Just filename, add uploads/profiles/ prefix
                                            $imageSrc = base_url('uploads/profiles/' . $imagePath);
                                        }
                                        ?>
                                        <img class="h-20 w-20 rounded-full object-cover"
                                            src="<?= $imageSrc ?>"
                                            alt="Profile"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="h-20 w-20 rounded-full bg-blue-100 flex items-center justify-center" style="display: none;">
                                            <i class="fas fa-user text-blue-600 text-2xl"></i>
                                        </div>
                                    <?php else: ?>
                                        <div class="h-20 w-20 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-user text-blue-600 text-2xl"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <input type="file" name="profile_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="text-xs text-gray-500 mt-1">JPG, PNG or GIF. Max 5MB.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Full Name -->
                        <div class="mb-4">
                            <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" id="full_name" name="full_name" value="<?= esc($user['full_name']) ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Username (Read-only) -->
                        <div class="mb-4">
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                            <input type="text" id="username" value="<?= esc($user['username']) ?>" disabled class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
                            <p class="text-xs text-gray-500 mt-1">Username cannot be changed</p>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" value="<?= esc($user['email']) ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Phone -->
                        <div class="mb-6">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="<?= esc($user['phone'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-save mr-2"></i>Update Profile
                        </button>
                    </form>
                </div>
            </div>

            <!-- Wallet Information (Special Users Only) -->
            <?php if ($user['is_special_user']): ?>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Wallet Information</h3>
                                <p class="text-sm text-gray-600 mt-1">Update your wallet details for topups</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-star mr-1"></i>Special User
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <form action="<?= base_url('update-wallet') ?>" method="POST">
                            <!-- Wallet Name -->
                            <div class="mb-4">
                                <label for="wallet_name" class="block text-sm font-medium text-gray-700 mb-2">Wallet Name</label>
                                <input type="text" id="wallet_name" name="wallet_name" value="<?= esc($user['wallet_name'] ?? '') ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter the name on your wallet">
                            </div>

                            <!-- Wallet Number -->
                            <div class="mb-4">
                                <label for="wallet_number" class="block text-sm font-medium text-gray-700 mb-2">Wallet Number</label>
                                <input type="text" id="wallet_number" name="wallet_number" value="<?= esc($user['wallet_number'] ?? '') ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter your wallet number">
                            </div>

                            <!-- Wallet Type -->
                            <div class="mb-4">
                                <label for="wallet_type" class="block text-sm font-medium text-gray-700 mb-2">Wallet Type</label>
                                <select id="wallet_type" name="wallet_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select wallet type</option>
                                    <option value="easypaisa" <?= ($user['wallet_type'] ?? '') === 'easypaisa' ? 'selected' : '' ?>>EasyPaisa</option>
                                    <option value="jazz_cash" <?= ($user['wallet_type'] ?? '') === 'jazz_cash' ? 'selected' : '' ?>>Jazz Cash</option>
                                    <option value="bank" <?= ($user['wallet_type'] ?? '') === 'bank' ? 'selected' : '' ?>>Bank Transfer</option>
                                    <option value="hbl" <?= ($user['wallet_type'] ?? '') === 'hbl' ? 'selected' : '' ?>>HBL Bank</option>
                                    <option value="ubank" <?= ($user['wallet_type'] ?? '') === 'ubank' ? 'selected' : '' ?>>UBank</option>
                                    <option value="mcb" <?= ($user['wallet_type'] ?? '') === 'mcb' ? 'selected' : '' ?>>MCB Bank</option>
                                    <option value="abank" <?= ($user['wallet_type'] ?? '') === 'abank' ? 'selected' : '' ?>>ABank</option>
                                    <option value="nbp" <?= ($user['wallet_type'] ?? '') === 'nbp' ? 'selected' : '' ?>>NBP</option>
                                    <option value="sbank" <?= ($user['wallet_type'] ?? '') === 'sbank' ? 'selected' : '' ?>>SBank</option>
                                    <option value="citi" <?= ($user['wallet_type'] ?? '') === 'citi' ? 'selected' : '' ?>>Citibank</option>
                                    <option value="hsbc" <?= ($user['wallet_type'] ?? '') === 'hsbc' ? 'selected' : '' ?>>HSBC</option>
                                    <option value="payoneer" <?= ($user['wallet_type'] ?? '') === 'payoneer' ? 'selected' : '' ?>>Payoneer</option>
                                    <option value="skrill" <?= ($user['wallet_type'] ?? '') === 'skrill' ? 'selected' : '' ?>>Skrill</option>
                                    <option value="neteller" <?= ($user['wallet_type'] ?? '') === 'neteller' ? 'selected' : '' ?>>Neteller</option>
                                    <option value="other" <?= ($user['wallet_type'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>

                            <!-- Bank Name (Conditional) -->
                            <div class="mb-6" id="bank_name_field" style="display: none;">
                                <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                                <input type="text" id="bank_name" name="bank_name" value="<?= esc($user['bank_name'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter bank name">
                            </div>

                            <button type="submit" class="w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors">
                                <i class="fas fa-wallet mr-2"></i>Update Wallet Information
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Profile Summary -->
        <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Profile Summary</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <h4 class="font-medium text-gray-900">Account Status</h4>
                        <p class="text-sm text-gray-600"><?= ucfirst($user['status']) ?></p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-calendar text-green-600"></i>
                        </div>
                        <h4 class="font-medium text-gray-900">Member Since</h4>
                        <p class="text-sm text-gray-600"><?= date('M d, Y', strtotime($user['created_at'])) ?></p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-star text-purple-600"></i>
                        </div>
                        <h4 class="font-medium text-gray-900">Special User</h4>
                        <p class="text-sm text-gray-600"><?= $user['is_special_user'] ? 'Yes' : 'No' ?></p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-users text-yellow-600"></i>
                        </div>
                        <h4 class="font-medium text-gray-900">Referral Bonus</h4>
                        <p class="text-sm text-gray-600">Rs. <?= number_format($user['referral_bonus_earned'] ?? 0, 2) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Referral Section -->
        <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Referral Program</h3>
                <p class="text-sm text-gray-600 mt-1">Invite friends and earn bonuses</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Referral Code -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-900">Your Referral Code</h4>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-link mr-1"></i>Unique
                            </span>
                        </div>

                        <?php if (!empty($user['referral_code'])): ?>
                            <div class="bg-white rounded-lg p-4 border border-blue-300">
                                <div class="flex items-center justify-between">
                                    <code class="text-2xl font-mono font-bold text-blue-600 select-all cursor-pointer" onclick="selectReferralCode(this)" title="Click to select"><?= esc($user['referral_code']) ?></code>
                                    <button id="referralCodeBtn" onclick="copyReferralCode()" class="ml-3 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-copy mr-1"></i>Copy
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Click on the code to select it, or use the copy button</p>
                            </div>

                          
                            <p class="text-sm text-gray-600 mt-3">Share this code with friends to earn referral bonuses!</p>
                        <?php else: ?>
                            <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-300">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                                    <div>
                                        <p class="text-yellow-800 font-medium">No Referral Code</p>
                                        <p class="text-yellow-700 text-sm">Contact admin to generate your referral code</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Referral Link -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200">
                        <h4 class="font-semibold text-gray-900 mb-4">Your Referral Link</h4>

                        <?php if (!empty($user['referral_code'])): ?>
                            <div class="bg-white rounded-lg p-4 border border-green-300">
                                <div class="flex items-center justify-between">
                                    <input type="text" id="referralLink" value="<?= base_url('referral/' . $user['referral_code']) ?>" readonly class="flex-1 bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-600 cursor-pointer" onclick="selectReferralLink(this)" title="Click to select all text">
                                    <button id="referralLinkBtn" onclick="copyReferralLink()" class="ml-3 px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                        <i class="fas fa-copy mr-1"></i>Copy
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Click on the link to select it, or use the copy button</p>
                            </div>
                            <p class="text-sm text-gray-600 mt-3">Share this link directly with friends!</p>
                        <?php else: ?>
                            <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-300">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                                    <div>
                                        <p class="text-yellow-800 font-medium">No Referral Link</p>
                                        <p class="text-yellow-700 text-sm">Referral link will be available once you have a referral code</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Referral Stats -->
                <?php if (!empty($user['referral_code'])): ?>
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="font-semibold text-gray-900 mb-4">Referral Statistics</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-blue-600"><?= number_format($referralStats['total_bonus_earned'] ?? 0, 2) ?></div>
                                <div class="text-sm text-gray-600">Total Bonus Earned (Rs.)</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-green-600"><?= $referralStats['total_referrals'] ?? 0 ?></div>
                                <div class="text-sm text-gray-600">Total Referrals</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-purple-600"><?= ($referralStats['total_referrals'] ?? 0) > 0 ? 'Active' : 'No Referrals' ?></div>
                                <div class="text-sm text-gray-600">Status</div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Show/hide bank name field based on wallet type
    document.getElementById('wallet_type').addEventListener('change', function() {
        const bankNameField = document.getElementById('bank_name_field');
        if (this.value === 'bank') {
            bankNameField.style.display = 'block';
            document.getElementById('bank_name').required = true;
        } else {
            bankNameField.style.display = 'none';
            document.getElementById('bank_name').required = false;
        }
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const walletType = document.getElementById('wallet_type');
        if (walletType.value === 'bank') {
            document.getElementById('bank_name_field').style.display = 'block';
            document.getElementById('bank_name').required = true;
        }
    });

    // Copy referral code to clipboard
    function copyReferralCode() {
        const referralCode = '<?= esc($user['referral_code'] ?? '') ?>';
        if (referralCode) {
            copyToClipboard(referralCode, 'referralCodeBtn');
        }
    }

    // Copy referral link to clipboard
    function copyReferralLink() {
        const referralLink = document.getElementById('referralLink').value;
        if (referralLink) {
            copyToClipboard(referralLink, 'referralLinkBtn');
        }
    }

    // Copy wallet ID to clipboard
    function copyWalletId() {
        const walletId = '<?= esc($user['wallet_id'] ?? '') ?>';
        if (walletId && walletId !== 'Not Generated') {
            copyToClipboard(walletId, 'walletIdBtn');
        }
    }

    // Universal copy to clipboard function that works in all browsers
    function copyToClipboard(text, buttonId) {
        // Try modern clipboard API first
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(function() {
                showCopySuccess(buttonId);
            }).catch(function(err) {
                console.error('Clipboard API failed:', err);
                fallbackCopyToClipboard(text, buttonId);
            });
        } else {
            // Fallback for older browsers or non-HTTPS
            fallbackCopyToClipboard(text, buttonId);
        }
    }

    // Fallback copy method using document.execCommand
    function fallbackCopyToClipboard(text, buttonId) {
        try {
            // Create a temporary textarea element
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);

            // Select and copy the text
            textArea.focus();
            textArea.select();
            const successful = document.execCommand('copy');

            // Remove the temporary element
            document.body.removeChild(textArea);

            if (successful) {
                showCopySuccess(buttonId);
            } else {
                showCopyError(buttonId);
            }
        } catch (err) {
            console.error('Fallback copy failed:', err);
            showCopyError(buttonId);
        }
    }

    // Show success message on copy button
    function showCopySuccess(buttonId) {
        const button = document.getElementById(buttonId);
        if (button) {
            const originalText = button.innerHTML;
            const originalClasses = button.className;

            // Change button appearance
            button.innerHTML = '<i class="fas fa-check mr-1"></i>Copied!';
            button.className = originalClasses.replace('bg-blue-600', 'bg-green-600').replace('bg-green-600', 'bg-green-600');
            button.classList.remove('hover:bg-blue-700');
            button.classList.add('hover:bg-green-700');

            // Reset after 2 seconds
            setTimeout(function() {
                button.innerHTML = originalText;
                button.className = originalClasses;
            }, 2000);
        }
    }

    // Show error message on copy button
    function showCopyError(buttonId) {
        const button = document.getElementById(buttonId);
        if (button) {
            const originalText = button.innerHTML;
            const originalClasses = button.className;

            // Change button appearance to show error
            button.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i>Failed!';
            button.className = originalClasses.replace('bg-blue-600', 'bg-red-600').replace('bg-green-600', 'bg-red-600');
            button.classList.remove('hover:bg-blue-700', 'hover:bg-green-700');
            button.classList.add('hover:bg-red-700');

            // Reset after 2 seconds
            setTimeout(function() {
                button.innerHTML = originalText;
                button.className = originalClasses;
            }, 2000);
        }
    }

    // Select referral code text
    function selectReferralCode(element) {
        const range = document.createRange();
        range.selectNodeContents(element);
        const selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);

        // Show a brief message
        showSelectionMessage('Referral code selected!');
    }

    // Select referral link text
    function selectReferralLink(input) {
        input.select();
        input.setSelectionRange(0, input.value.length);

        // Show a brief message
        showSelectionMessage('Referral link selected!');
    }

    // Select wallet ID text
    function selectWalletId(element) {
        const range = document.createRange();
        range.selectNodeContents(element);
        const selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);

        // Show a brief message
        showSelectionMessage('Wallet ID selected!');
    }

    // Show selection message
    function showSelectionMessage(message) {
        // Create a temporary message element
        const messageDiv = document.createElement('div');
        messageDiv.className = 'fixed top-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        messageDiv.innerHTML = `<i class="fas fa-check mr-2"></i>${message}`;
        document.body.appendChild(messageDiv);

        // Remove after 2 seconds
        setTimeout(function() {
            document.body.removeChild(messageDiv);
        }, 2000);
    }

    // Image preview functionality
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.querySelector('input[name="profile_image"]');
        const profileImage = document.querySelector('.flex-shrink-0 img');
        const defaultIcon = document.querySelector('.flex-shrink-0 .bg-blue-100');

        if (imageInput) {
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
                        if (profileImage) {
                            profileImage.src = e.target.result;
                            profileImage.style.display = 'block';
                        }
                        if (defaultIcon) {
                            defaultIcon.style.display = 'none';
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>