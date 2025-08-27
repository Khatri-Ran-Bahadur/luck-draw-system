<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Wallet</h1>
            <p class="text-gray-600 mt-2">Manage your wallet balance and transactions</p>
        </div>

        <!-- Wallet Balance Card -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl shadow-xl p-8 text-white mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold mb-2">Current Balance</h2>
                    <p class="text-4xl font-bold">Rs. <?= number_format($wallet['balance'], 2) ?></p>
                    <p class="text-blue-100 mt-2">Currency: PKR</p>
                </div>
                <div class="text-right">
                    <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-wallet text-3xl"></i>
                    </div>
                </div>
            </div>

            <!-- Wallet ID Display -->
            <div class="bg-white bg-opacity-20 rounded-xl p-4 mt-6 border border-white border-opacity-30">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-white mb-1">Your Wallet ID</h4>
                        <code class="text-lg font-mono font-bold text-yellow-300"><?= esc($user['wallet_id'] ?? 'Not Generated') ?></code>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white">
                        <i class="fas fa-id-card mr-1"></i>Wallet ID
                    </span>
                </div>
                <p class="text-xs text-blue-100 mt-2">Share this ID with others for wallet transfers</p>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <a href="<?= base_url('wallet/topup') ?>" class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-lg p-6 text-white hover:from-blue-700 hover:to-blue-800 transition-all duration-300 transform hover:scale-105">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 rounded-full p-3 mr-4">
                            <i class="fas fa-plus text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Top Up Wallet</h3>
                            <p class="text-blue-100 text-sm">Add money to your wallet</p>
                        </div>
                    </div>
                </a>



                <a href="<?= base_url('wallet/withdraw') ?>" class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-2xl shadow-lg p-6 text-white hover:from-purple-700 hover:to-purple-800 transition-all duration-300 transform hover:scale-105">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 rounded-full p-3 mr-4">
                            <i class="fas fa-money-bill-wave text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Withdraw Money</h3>
                            <p class="text-purple-100 text-sm">Request withdrawal from your wallet</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Special User Actions (Only for Special Users) -->
            <?php if ($user['is_special_user'] ?? false): ?>
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-2xl shadow-lg p-6 text-white mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-star text-2xl mr-3"></i>
                            <div>
                                <h3 class="text-xl font-semibold">Special User Actions</h3>
                                <p class="text-yellow-100">Manage user requests and withdrawals</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <a href="<?= base_url('wallet/user-requests') ?>" class="bg-white bg-opacity-20 rounded-xl p-4 hover:bg-opacity-30 transition-all duration-300">
                            <div class="flex items-center">
                                <i class="fas fa-list-alt text-xl mr-3"></i>
                                <div>
                                    <h4 class="font-semibold">User Requests</h4>
                                    <p class="text-yellow-100 text-sm">View pending topups & transfers</p>
                                </div>
                            </div>
                        </a>

                        <a href="<?= base_url('profile') ?>" class="bg-white bg-opacity-20 rounded-xl p-4 hover:bg-opacity-30 transition-all duration-300">
                            <div class="flex items-center">
                                <i class="fas fa-user-cog text-xl mr-3"></i>
                                <div>
                                    <h4 class="font-semibold">My Profile</h4>
                                    <p class="text-yellow-100 text-sm">Manage your profile & wallet details</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Special User Dashboard (Only for Special Users) -->
            <?php if ($user['is_special_user'] ?? false): ?>
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-chart-line text-2xl text-purple-600 mr-3"></i>
                            <h3 class="text-xl font-semibold text-gray-900">Special User Dashboard</h3>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                            <i class="fas fa-star mr-1"></i>Special User
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Pending Topup Requests -->
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-clock text-blue-600 text-xl"></i>
                                </div>
                                <span class="text-2xl font-bold text-blue-600">
                                    <?= count($pendingTopups) ?>
                                </span>
                            </div>
                            <h4 class="font-semibold text-blue-900 mb-1">Pending Topups</h4>
                            <p class="text-sm text-blue-700">Awaiting your approval</p>
                            <a href="<?= base_url('wallet/user-requests') ?>" class="inline-flex items-center text-blue-600 hover:text-blue-700 text-sm font-medium mt-2">
                                View Requests <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>

                        <!-- Topup Requests -->
                        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-upload text-green-600 text-xl"></i>
                                </div>
                                <span class="text-2xl font-bold text-green-600">
                                    <?= count($pendingTopups) ?>
                                </span>
                            </div>
                            <h4 class="font-semibold text-green-900 mb-1">Topup Requests</h4>
                            <p class="text-sm text-green-700">Approve user topups</p>
                            <a href="<?= base_url('wallet/user-requests') ?>" class="inline-flex items-center text-green-600 hover:text-green-700 text-sm font-medium mt-2">
                                View Requests <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>

                        <!-- User Requests -->
                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-users text-purple-600 text-xl"></i>
                                </div>
                                <span class="text-2xl font-bold text-purple-600">
                                    <i class="fas fa-list text-lg"></i>
                                </span>
                            </div>
                            <h4 class="font-semibold text-purple-900 mb-1">User Requests</h4>
                            <p class="text-sm text-purple-700">Manage all requests</p>
                            <a href="<?= base_url('wallet/user-requests') ?>" class="inline-flex items-center text-purple-600 hover:text-purple-700 text-sm font-medium mt-2">
                                View All <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Recent Transactions -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Transactions</h3>
                    <a href="<?= base_url('wallet/transactions') ?>" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <?php if (empty($recentTransactions)): ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-receipt text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500">No transactions yet</p>
                        <p class="text-gray-400 mt-2">Your transaction history will appear here</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($recentTransactions as $transaction): ?>
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 <?= $transaction['type'] === 'topup' ? 'bg-green-100' : ($transaction['type'] === 'withdrawal' ? 'bg-red-100' : ($transaction['type'] === 'commission' ? 'bg-yellow-100' : 'bg-blue-100')) ?> rounded-full flex items-center justify-center">
                                        <i class="fas <?= $transaction['type'] === 'topup' ? 'fa-plus text-green-600' : ($transaction['type'] === 'withdrawal' ? 'fa-minus text-red-600' : ($transaction['type'] === 'commission' ? 'fa-gift text-yellow-600' : 'fa-exchange-alt text-blue-600')) ?> text-sm"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900"><?= esc($transaction['description']) ?></h4>
                                        <p class="text-xs text-gray-500"><?= date('M d, Y H:i', strtotime($transaction['created_at'])) ?></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-semibold <?= $transaction['amount'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                                        <?= $transaction['amount'] >= 0 ? '+' : '' ?>Rs. <?= number_format($transaction['amount'], 2) ?>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium <?= $transaction['status'] === 'completed' ? 'bg-green-100 text-green-800' : ($transaction['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                        <?= ucfirst($transaction['status']) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pending Topups -->
            <?php if (!empty($pendingTopups)): ?>
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Pending Topup Requests</h3>
                    <div class="space-y-4">
                        <?php foreach ($pendingTopups as $topup): ?>
                            <div class="flex items-center justify-between p-4 border border-yellow-200 rounded-lg bg-yellow-50">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-clock text-yellow-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">Topup Request</h4>
                                        <p class="text-xs text-gray-500">Amount: Rs. <?= number_format($topup['amount'], 2) ?></p>
                                        <p class="text-xs text-gray-500">Submitted: <?= date('M d, Y H:i', strtotime($topup['created_at'])) ?></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pending Approval
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-arrow-up text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Topups</p>
                            <p class="text-2xl font-bold text-gray-900">
                                Rs. <?= number_format(array_sum(array_map(function ($t) {
                                        return $t['type'] === 'topup' && $t['status'] === 'completed' ? $t['amount'] : 0;
                                    }, $recentTransactions)), 2) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-trophy text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Winnings</p>
                            <p class="text-2xl font-bold text-gray-900">
                                Rs. <?= number_format(array_sum(array_map(function ($t) {
                                        return $t['type'] === 'draw_win' && $t['status'] === 'completed' ? $t['amount'] : 0;
                                    }, $recentTransactions)), 2) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-receipt text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Transactions</p>
                            <p class="text-2xl font-bold text-gray-900"><?= count($recentTransactions) ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-gift text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Commissions</p>
                            <p class="text-2xl font-bold text-gray-900">
                                Rs. <?= number_format(array_sum(array_map(function ($t) {
                                        return $t['type'] === 'commission' && $t['status'] === 'completed' ? $t['amount'] : 0;
                                    }, $recentTransactions)), 2) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= $this->endSection() ?>