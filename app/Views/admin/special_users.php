<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Special Users Management</h1>
            <p class="text-gray-600">Manage users who can display wallet information for topups</p>
        </div>
        <div class="flex space-x-3">
            <a href="<?= base_url('admin/users') ?>" class="btn-secondary">
                <i class="fas fa-users mr-2"></i>Manage All Users
            </a>
            <a href="<?= base_url('admin/dashboard') ?>" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['total_users']) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-wallet text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Users with Wallets</p>
                    <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['users_with_wallets']) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-star text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Wallets</p>
                    <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['active_wallets']) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Special Users Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Special Users</h3>
            <p class="text-sm text-gray-600 mt-1">Users who can display wallet information for topups</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            User
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Wallet Type
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Wallet Details
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Wallet Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Account Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Balance
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <i class="fas fa-user text-blue-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= esc($user['full_name']) ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                @<?= esc($user['username']) ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?= esc($user['email']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($user['wallet_type'] && $user['wallet_type'] !== 'Pending'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <?= ucfirst($user['wallet_type']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pending Setup
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <?php if ($user['wallet_name'] && $user['wallet_name'] !== 'Pending' && $user['wallet_number'] && $user['wallet_number'] !== 'Pending'): ?>
                                            <div><strong>Name:</strong> <?= esc($user['wallet_name']) ?></div>
                                            <div><strong>Number:</strong> <?= esc($user['wallet_number']) ?></div>
                                            <?php if ($user['wallet_type'] === 'bank' && isset($user['bank_name']) && $user['bank_name']): ?>
                                                <div><strong>Bank:</strong> <?= esc($user['bank_name']) ?></div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="text-yellow-600">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Wallet details pending setup
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                User needs to complete wallet profile
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($user['wallet_active'] && $user['wallet_name'] && $user['wallet_name'] !== 'Pending' && $user['wallet_number'] && $user['wallet_number'] !== 'Pending'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>Active
                                        </span>
                                    <?php elseif ($user['wallet_active']): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Pending Setup
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i>Inactive
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($user['status'] === 'active'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>Active
                                        </span>
                                    <?php elseif ($user['status'] === 'suspended'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-ban mr-1"></i>Suspended
                                        </span>
                                    <?php elseif ($user['status'] === 'inactive'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-pause mr-1"></i>Inactive
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-question mr-1"></i>Unknown
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        Rs. <?= number_format($user['balance'] ?? 0, 2) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="<?= base_url('admin/edit-special-user/' . $user['id']) ?>"
                                        class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    <a href="<?= base_url('admin/users/view/' . $user['id']) ?>"
                                        class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                <div class="py-8">
                                    <i class="fas fa-wallet text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-medium text-gray-900 mb-2">No Special Users Found</p>
                                    <p class="text-gray-600">No users have been designated as special users yet.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if (isset($pager) && $pager): ?>
            <div class="px-6 py-4 border-t border-gray-200">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
            <div class="text-sm text-blue-800">
                <p class="font-medium mb-2">How Special Users Work:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Special users are regular users who can display their wallet information for topups</li>
                    <li>When made special, users get basic wallet setup and can complete their profile</li>
                    <li>Only special users with complete wallet information appear on the topup page</li>
                    <li>Users with "Pending Setup" status need to complete their wallet profile</li>
                    <li>You can edit wallet details and activate/deactivate special users as needed</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>