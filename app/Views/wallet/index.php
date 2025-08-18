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

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <a href="<?= base_url('wallet/topup') ?>" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-xl p-6 text-center hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-plus text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Top Up Wallet</h3>
                    <p class="text-blue-100 text-sm">Add money via PayPal or Easypaisa</p>
                </a>

                <a href="<?= base_url('wallet/withdraw') ?>" class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-xl p-6 text-center hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-arrow-down text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Withdraw Funds</h3>
                    <p class="text-green-100 text-sm">Request withdrawal to your account</p>
                </a>

                <a href="<?= base_url('wallet/transactions') ?>" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-xl p-6 text-center hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-history text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Transaction History</h3>
                    <p class="text-purple-100 text-sm">View all your wallet activities</p>
                </a>
            </div>

            <!-- Payment System Status -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Payment System Status</h2>
                    <a href="<?= base_url('wallet/payment-status') ?>" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        View Details <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fab fa-paypal text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">PayPal</p>
                            <p class="text-sm text-gray-600">Ready for payments</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-mobile-alt text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Easypaisa</p>
                            <p class="text-sm text-gray-600">Ready for payments</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Topups -->
            <?php if (!empty($pendingTopups)): ?>
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-8">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-4">
                        <i class="fas fa-clock mr-2"></i>
                        Pending Topups
                    </h3>
                    <div class="space-y-3">
                        <?php foreach ($pendingTopups as $topup): ?>
                            <div class="flex items-center justify-between p-4 bg-yellow-100 rounded-lg">
                                <div>
                                    <p class="font-semibold text-yellow-800">Rs. <?= number_format($topup['amount'], 2) ?></p>
                                    <p class="text-sm text-yellow-600"><?= ucfirst($topup['payment_method']) ?> • <?= date('M j, Y g:i A', strtotime($topup['created_at'])) ?></p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-200 text-yellow-800">
                                    Pending
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Recent Transactions -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                <div class="px-8 py-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-gray-900">Recent Transactions</h3>
                        <a href="<?= base_url('wallet/transactions') ?>" class="text-blue-600 hover:text-blue-700 font-medium">
                            View All
                        </a>
                    </div>
                </div>

                <div class="p-8">
                    <?php if (empty($transactions)): ?>
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-receipt text-2xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-500 text-lg">No transactions yet</p>
                            <p class="text-gray-400 mt-2">Your transaction history will appear here</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($transactions as $transaction): ?>
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center <?= $transaction['type'] === 'topup' || $transaction['type'] === 'draw_win' ? 'bg-green-100' : 'bg-red-100' ?>">
                                            <i class="fas <?= $transaction['type'] === 'topup' ? 'fa-plus' : ($transaction['type'] === 'draw_win' ? 'fa-trophy' : 'fa-minus') ?> text-lg <?= $transaction['type'] === 'topup' || $transaction['type'] === 'draw_win' ? 'text-green-600' : 'text-red-600' ?>"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900"><?= esc($transaction['description']) ?></p>
                                            <p class="text-sm text-gray-500">
                                                <?= date('M j, Y g:i A', strtotime($transaction['created_at'])) ?>
                                                <?php if ($transaction['payment_method'] && $transaction['payment_method'] !== 'wallet'): ?>
                                                    • <?= ucfirst($transaction['payment_method']) ?>
                                                <?php endif; ?>
                                            </p>
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
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
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
                                    }, $transactions)), 2) ?>
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
                                    }, $transactions)), 2) ?>
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
                            <p class="text-2xl font-bold text-gray-900"><?= count($transactions) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= $this->endSection() ?>