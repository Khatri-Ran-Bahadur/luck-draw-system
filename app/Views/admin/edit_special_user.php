<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Special User</h1>
            <p class="text-gray-600">Update wallet information for <?= esc($user['full_name']) ?></p>
        </div>
        <a href="<?= base_url('admin/special-users') ?>" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>Back to Special Users
        </a>
    </div>

    <!-- User Info Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">User Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <p class="text-sm text-gray-900"><?= esc($user['full_name']) ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <p class="text-sm text-gray-900">@<?= esc($user['username']) ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <p class="text-sm text-gray-900"><?= esc($user['email']) ?></p>
            </div>
        </div>
    </div>

    <!-- Wallet Information Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Wallet Information</h3>

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

        <form action="<?= base_url('admin/edit-special-user/' . $user['id']) ?>" method="POST" class="space-y-6">
            <!-- Wallet Name -->
            <div>
                <label for="wallet_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Wallet/Account Name
                </label>
                <input type="text"
                    id="wallet_name"
                    name="wallet_name"
                    value="<?= esc($user['wallet_name'] ?? '') ?>"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter wallet or account name"
                    required>
                <p class="mt-1 text-sm text-gray-500">
                    The name that will be displayed for users to send money to
                </p>
            </div>

            <!-- Wallet Number -->
            <div>
                <label for="wallet_number" class="block text-sm font-medium text-gray-700 mb-2">
                    Wallet/Account Number
                </label>
                <input type="text"
                    id="wallet_number"
                    name="wallet_number"
                    value="<?= esc($user['wallet_number'] ?? '') ?>"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter wallet or account number"
                    required>
                <p class="mt-1 text-sm text-gray-500">
                    Phone number for mobile wallets, account number for bank transfers
                </p>
            </div>

            <!-- Wallet Type -->
            <div>
                <label for="wallet_type" class="block text-sm font-medium text-gray-700 mb-2">
                    Wallet Type
                </label>
                <select id="wallet_type"
                    name="wallet_type"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required>
                    <option value="">Select wallet type</option>
                    <option value="easypaisa" <?= ($user['wallet_type'] ?? '') === 'easypaisa' ? 'selected' : '' ?>>Easypaisa</option>
                    <option value="jazz_cash" <?= ($user['wallet_type'] ?? '') === 'jazz_cash' ? 'selected' : '' ?>>Jazz Cash</option>
                    <option value="bank" <?= ($user['wallet_type'] ?? '') === 'bank' ? 'selected' : '' ?>>Bank Transfer</option>
                    <option value="hbl" <?= ($user['wallet_type'] ?? '') === 'hbl' ? 'selected' : '' ?>>HBL Bank</option>
                    <option value="ubank" <?= ($user['wallet_type'] ?? '') === 'ubank' ? 'selected' : '' ?>>UBL Bank</option>
                    <option value="mcb" <?= ($user['wallet_type'] ?? '') === 'mcb' ? 'selected' : '' ?>>MCB Bank</option>
                    <option value="abank" <?= ($user['wallet_type'] ?? '') === 'abank' ? 'selected' : '' ?>>Allied Bank</option>
                    <option value="nbp" <?= ($user['wallet_type'] ?? '') === 'nbp' ? 'selected' : '' ?>>National Bank of Pakistan</option>
                    <option value="sbank" <?= ($user['wallet_type'] ?? '') === 'sbank' ? 'selected' : '' ?>>Standard Chartered Bank</option>
                    <option value="citi" <?= ($user['wallet_type'] ?? '') === 'citi' ? 'selected' : '' ?>>Citibank Pakistan</option>
                    <option value="hsbc" <?= ($user['wallet_type'] ?? '') === 'hsbc' ? 'selected' : '' ?>>HSBC Pakistan</option>
                    <option value="payoneer" <?= ($user['wallet_type'] ?? '') === 'payoneer' ? 'selected' : '' ?>>Payoneer</option>
                    <option value="skrill" <?= ($user['wallet_type'] ?? '') === 'skrill' ? 'selected' : '' ?>>Skrill</option>
                    <option value="neteller" <?= ($user['wallet_type'] ?? '') === 'neteller' ? 'selected' : '' ?>>Neteller</option>
                    <option value="other" <?= ($user['wallet_type'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>

            <!-- Bank Name (only for bank type) -->
            <div id="bank_name_field" class="hidden">
                <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Bank Name
                </label>
                <input type="text"
                    id="bank_name"
                    name="bank_name"
                    value="<?= esc($user['bank_name'] ?? '') ?>"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter bank name">
                <p class="mt-1 text-sm text-gray-500">
                    Required for bank transfer type
                </p>
            </div>

            <!-- User Status -->
            <div>
                <label for="user_status" class="block text-sm font-medium text-gray-700 mb-2">
                    User Account Status
                </label>
                <select id="user_status"
                    name="user_status"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="active" <?= ($user['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="suspended" <?= ($user['status'] ?? 'active') === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                    <option value="inactive" <?= ($user['status'] ?? 'active') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
                <p class="mt-1 text-sm text-gray-500">
                    Suspended users cannot login. Inactive users are hidden from topup lists.
                </p>
            </div>

            <!-- Wallet Active Status -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox"
                        name="is_active"
                        value="1"
                        <?= ($user['wallet_active'] ?? false) ? 'checked' : '' ?>
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <span class="ml-2 text-sm text-gray-700">Wallet Active - Display this wallet for topups</span>
                </label>
                <p class="mt-1 text-sm text-gray-500">
                    Inactive wallets won't be shown to users for topups
                </p>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="<?= base_url('admin/special-users') ?>"
                    class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200">
                    <i class="fas fa-save mr-2"></i>Update Wallet Information
                </button>
            </div>
        </form>
    </div>

    <!-- Current Wallet Balance -->
    <?php if (isset($wallet) && $wallet): ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Current Wallet Status</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Balance</label>
                    <p class="text-2xl font-bold text-green-600">Rs. <?= number_format($wallet['balance'] ?? 0, 2) ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                    <p class="text-sm text-gray-900"><?= esc($wallet['currency'] ?? 'PKR') ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    document.getElementById('wallet_type').addEventListener('change', function() {
        const bankNameField = document.getElementById('bank_name_field');
        const bankNameInput = document.getElementById('bank_name');

        if (this.value === 'bank') {
            bankNameField.classList.remove('hidden');
            bankNameInput.required = true;
        } else {
            bankNameField.classList.add('hidden');
            bankNameInput.required = false;
        }
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const walletType = document.getElementById('wallet_type');
        if (walletType.value === 'bank') {
            document.getElementById('bank_name_field').classList.remove('hidden');
            document.getElementById('bank_name').required = true;
        }
    });
</script>
<?= $this->endSection() ?>