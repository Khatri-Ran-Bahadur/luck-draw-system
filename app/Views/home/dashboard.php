<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Welcome Back!</h1>
                <p class="text-gray-600 mt-2">Manage your lucky draws and wallet</p>
            </div>
            <a href="<?= base_url('profile') ?>" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                <i class="fas fa-user-circle mr-2"></i>
                My Profile
            </a>
        </div>

        <!-- Wallet Balance Card -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl shadow-xl p-8 text-white mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold mb-2">Wallet Balance</h2>
                    <p class="text-4xl font-bold">Rs. <?= number_format($walletBalance, 2) ?></p>
                    <p class="text-blue-100 mt-2">Available for lucky draw entries</p>
                </div>
                <div class="text-right">
                    <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-wallet text-3xl"></i>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex flex-wrap gap-4 mt-6">
                <a href="<?= base_url('wallet/topup') ?>" class="inline-flex items-center px-6 py-3 bg-white text-blue-600 font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Top Up Wallet
                </a>
                <a href="<?= base_url('wallet/withdraw') ?>" class="inline-flex items-center px-6 py-3 bg-white/20 text-white font-semibold rounded-xl hover:bg-white/30 transition-colors">
                    <i class="fas fa-arrow-down mr-2"></i>
                    Withdraw Funds
                </a>
                <a href="<?= base_url('wallet/transactions') ?>" class="inline-flex items-center px-6 py-3 bg-white/20 text-white font-semibold rounded-xl hover:bg-white/30 transition-colors">
                    <i class="fas fa-history mr-2"></i>
                    View Transactions
                </a>

                <!-- Special User Top-up Request Button -->
                <?php if (isset($user['is_special_user']) && $user['is_special_user']): ?>
                    <a href="<?= base_url('wallet/topup') ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-semibold rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-star mr-2"></i>
                        Request Top-up
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Special User Section -->
        <?php if (isset($user['is_special_user']) && $user['is_special_user']): ?>
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-2xl shadow-xl p-6 text-white mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mr-4">
                            <i class="fas fa-star text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold">Special User Dashboard</h3>
                            <p class="text-yellow-100">Manage user requests and request  funding</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <a href="<?= base_url('wallet/topup') ?>" class="bg-white/20 rounded-xl p-4 hover:bg-white/30 transition-all duration-300 transform hover:scale-105">
                        <div class="flex items-center">
                            <i class="fas fa-plus text-xl mr-3"></i>
                            <div>
                                <h4 class="font-semibold">Request  Top-up</h4>
                                <p class="text-yellow-100 text-sm">Get funding from admin</p>
                            </div>
                        </div>
                    </a>

                    <a href="<?= base_url('wallet/user-requests') ?>" class="bg-white/20 rounded-xl p-4 hover:bg-white/30 transition-all duration-300 transform hover:scale-105">
                        <div class="flex items-center">
                            <i class="fas fa-list-alt text-xl mr-3"></i>
                            <div>
                                <h4 class="font-semibold">User Requests</h4>
                                <p class="text-yellow-100 text-sm">Manage pending requests</p>
                            </div>
                        </div>
                    </a>

                    <a href="<?= base_url('profile') ?>" class="bg-white/20 rounded-xl p-4 hover:bg-white/30 transition-all duration-300 transform hover:scale-105">
                        <div class="flex items-center">
                            <i class="fas fa-user-cog text-xl mr-3"></i>
                            <div>
                                <h4 class="font-semibold">Wallet Settings</h4>
                                <p class="text-yellow-100 text-sm">Update wallet details</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Active Lucky Draws -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Cash Draws -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">Cash Lucky Draws</h3>
                    </div>
                    <a href="<?= base_url('cash-draws') ?>" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        View All
                    </a>
                </div>

                <?php if (empty($cashDraws)): ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-dice text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500">No active cash draws</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach (array_slice($cashDraws, 0, 3) as $draw): ?>
                            <div class="bg-gray-50 rounded-xl p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-semibold text-gray-900"><?= esc($draw['title']) ?></h4>
                                    <span class="text-sm text-gray-500">Rs. <?= number_format($draw['entry_fee'], 2) ?></span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3"><?= esc(substr($draw['description'], 0, 80)) ?>...</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        <?= date('M j, Y', strtotime($draw['draw_date'])) ?>
                                    </span>
                                    <?php if ($walletBalance >= $draw['entry_fee']): ?>
                                        <a href="<?= base_url('cash-draw/' . $draw['id']) ?>" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                                            <i class="fas fa-dice mr-2"></i>
                                            ENTER
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= base_url('wallet/topup') ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                            <i class="fas fa-plus mr-2"></i>
                                            TOP UP
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Product Draws -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-gift text-purple-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">Product Lucky Draws</h3>
                    </div>
                    <a href="<?= base_url('product-draws') ?>" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        View All
                    </a>
                </div>

                <?php if (empty($productDraws)): ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-gift text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500">No active product draws</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach (array_slice($productDraws, 0, 3) as $draw): ?>
                            <div class="bg-gray-50 rounded-xl p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-semibold text-gray-900"><?= esc($draw['title']) ?></h4>
                                    <span class="text-sm text-gray-500">$<?= number_format($draw['entry_fee'], 2) ?></span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3"><?= esc(substr($draw['description'], 0, 80)) ?>...</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        <?= date('M j, Y', strtotime($draw['draw_date'])) ?>
                                    </span>
                                    <?php if ($walletBalance >= $draw['entry_fee']): ?>
                                        <a href="<?= base_url('product-draw/' . $draw['id']) ?>" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                                            <i class="fas fa-gift mr-2"></i>
                                            ENTER
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= base_url('wallet/topup') ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                            <i class="fas fa-plus mr-2"></i>
                                            TOP UP
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-wallet text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Wallet Balance</p>
                        <p class="text-2xl font-bold text-gray-900">Rs. <?= number_format($walletBalance, 2) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-dice text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Draws</p>
                        <p class="text-2xl font-bold text-gray-900"><?= count($cashDraws) + count($productDraws) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-trophy text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">My Entries</p>
                        <p class="text-2xl font-bold text-gray-900"><?= count($userEntries ?? []) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-star text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Winnings</p>
                        <p class="text-2xl font-bold text-gray-900">Rs. <?= number_format(array_sum(array_column($userWinnings ?? [], 'prize_amount')), 2) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
            <div class="px-8 py-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">Recent Activity</h3>
            </div>

            <div class="p-8">
                <?php if (empty($recent_transactions) && empty($winnings)): ?>
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-activity text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 text-lg">No recent activity</p>
                        <p class="text-gray-400 mt-2">Start participating in lucky draws to see your activity here</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php if (!empty($recent_transactions)): ?>
                            <h4 class="font-medium text-gray-900 mb-3">Recent Transactions</h4>
                            <?php foreach (array_slice($recent_transactions, 0, 5) as $transaction): ?>
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                            <?= strtoupper(substr($transaction['type'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900"><?= ucfirst(str_replace('_', ' ', $transaction['type'])) ?></p>
                                            <p class="text-sm text-gray-500"><?= date('M j, Y g:i A', strtotime($transaction['created_at'])) ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold <?= $transaction['amount'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                                            <?= $transaction['amount'] >= 0 ? '+' : '' ?>Rs. <?= number_format(abs($transaction['amount']), 2) ?>
                                        </p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $transaction['status'] === 'completed' ? 'bg-green-100 text-green-800' : ($transaction['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                            <?= ucfirst($transaction['status']) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if (!empty($winnings)): ?>
                            <h4 class="font-medium text-gray-900 mb-3 mt-6">Recent Winnings</h4>
                            <?php foreach (array_slice($winnings, 0, 3) as $winning): ?>
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border border-yellow-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white font-bold">
                                            <i class="fas fa-crown"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900"><?= getOrdinal($winning['position']) ?> Place</p>
                                            <p class="text-sm text-gray-500"><?= esc($winning['draw_title'] ?? 'Unknown Draw') ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <?php if ($winning['draw_type'] === 'cash'): ?>
                                            <p class="font-semibold text-green-600">Rs. <?= number_format($winning['prize_amount'], 2) ?></p>
                                        <?php else: ?>
                                            <p class="font-semibold text-blue-600">Product Prize</p>
                                        <?php endif; ?>

                                        <?php if (!$winning['is_claimed']): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Ready to Claim
                                            </span>
                                        <?php elseif ($winning['claim_approved']): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Approved
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
// Helper function to get ordinal suffix
function getOrdinal($n)
{
    if ($n >= 11 && $n <= 13) {
        return $n . 'th';
    }
    switch ($n % 10) {
        case 1:
            return $n . 'st';
        case 2:
            return $n . 'nd';
        case 3:
            return $n . 'rd';
        default:
            return $n . 'th';
    }
}
?>

<?= $this->endSection() ?>