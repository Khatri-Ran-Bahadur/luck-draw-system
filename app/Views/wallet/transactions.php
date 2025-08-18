<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="<?= base_url('wallet') ?>" class="text-blue-600 hover:text-blue-700 mr-4">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Transaction History</h1>
            </div>
            <p class="text-gray-600">View all your wallet transactions and payment history</p>
        </div>

        <!-- Transaction Filters -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <label class="text-sm font-medium text-gray-700">Filter by:</label>
                    <select id="type-filter" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Types</option>
                        <option value="topup">Topups</option>
                        <option value="deduction">Deductions</option>
                        <option value="draw_entry">Draw Entries</option>
                        <option value="draw_win">Winnings</option>
                        <option value="withdrawal">Withdrawals</option>
                    </select>

                    <select id="status-filter" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="failed">Failed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">
                        Showing <?= $pagination['total_records'] ?> transactions
                    </span>
                    <a href="<?= base_url('wallet') ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-wallet mr-2"></i>
                        Back to Wallet
                    </a>
                </div>
            </div>
        </div>

        <!-- Transactions List -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
            <div class="px-8 py-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">All Transactions</h3>
            </div>

            <div class="p-8">
                <?php if (empty($transactions)): ?>
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-receipt text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 text-lg">No transactions found</p>
                        <p class="text-gray-400 mt-2">Your transaction history will appear here once you start using your wallet</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($transactions as $transaction): ?>
                            <div class="bg-gray-50 rounded-xl p-6 hover:bg-gray-100 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center <?= $transaction['amount'] >= 0 ? 'bg-green-100' : 'bg-red-100' ?>">
                                            <i class="fas <?= getTransactionIcon($transaction['type']) ?> text-lg <?= $transaction['amount'] >= 0 ? 'text-green-600' : 'text-red-600' ?>"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900"><?= esc($transaction['description']) ?></h4>
                                            <div class="flex items-center space-x-4 mt-1 text-sm text-gray-500">
                                                <span>
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    <?= date('M j, Y g:i A', strtotime($transaction['created_at'])) ?>
                                                </span>
                                                <?php if ($transaction['payment_method'] && $transaction['payment_method'] !== 'wallet'): ?>
                                                    <span>
                                                        <i class="fas fa-credit-card mr-1"></i>
                                                        <?= ucfirst($transaction['payment_method']) ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($transaction['payment_reference']): ?>
                                                    <span class="font-mono text-xs">
                                                        Ref: <?= substr($transaction['payment_reference'], 0, 12) ?>...
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <div class="flex items-center space-x-3">
                                            <div class="text-right">
                                                <p class="font-semibold <?= $transaction['amount'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                                                    <?= $transaction['amount'] >= 0 ? '+' : '' ?>Rs. <?= number_format(abs($transaction['amount']), 2) ?>
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    Balance: Rs. <?= number_format($transaction['balance_after'], 2) ?>
                                                </p>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= getStatusColor($transaction['status']) ?>">
                                                <?= ucfirst($transaction['status']) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($transaction['metadata']): ?>
                                    <?php $metadata = json_decode($transaction['metadata'], true); ?>
                                    <?php if ($metadata && !empty($metadata)): ?>
                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                            <div class="flex flex-wrap gap-2">
                                                <?php foreach ($metadata as $key => $value): ?>
                                                    <?php if ($key !== 'timestamp'): ?>
                                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                                            <?= ucfirst(str_replace('_', ' ', $key)) ?>: <?= esc($value) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($pagination['total_pages'] > 1): ?>
                        <div class="mt-8 flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Showing page <?= $pagination['current_page'] ?> of <?= $pagination['total_pages'] ?>
                            </div>

                            <div class="flex items-center space-x-2">
                                <?php if ($pagination['current_page'] > 1): ?>
                                    <a href="?page=<?= $pagination['current_page'] - 1 ?>" class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-chevron-left mr-1"></i>
                                        Previous
                                    </a>
                                <?php endif; ?>

                                <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++): ?>
                                    <a href="?page=<?= $i ?>" class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium <?= $i == $pagination['current_page'] ? 'bg-blue-600 text-white border-blue-600' : 'text-gray-700 hover:bg-gray-50' ?> transition-colors">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>

                                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                    <a href="?page=<?= $pagination['current_page'] + 1 ?>" class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                        Next
                                        <i class="fas fa-chevron-right ml-1"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Transaction Summary -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
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
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-arrow-down text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Deductions</p>
                        <p class="text-2xl font-bold text-gray-900">
                            Rs. <?= number_format(abs(array_sum(array_map(function ($t) {
                                    return $t['amount'] < 0 ? $t['amount'] : 0;
                                }, $transactions))), 2) ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-trophy text-purple-600 text-xl"></i>
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
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-receipt text-blue-600 text-xl"></i>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeFilter = document.getElementById('type-filter');
        const statusFilter = document.getElementById('status-filter');

        // Handle filters
        function applyFilters() {
            const type = typeFilter.value;
            const status = statusFilter.value;

            const params = new URLSearchParams(window.location.search);
            if (type) params.set('type', type);
            else params.delete('type');
            if (status) params.set('status', status);
            else params.delete('status');
            params.delete('page'); // Reset to first page when filtering

            window.location.search = params.toString();
        }

        typeFilter.addEventListener('change', applyFilters);
        statusFilter.addEventListener('change', applyFilters);

        // Set current filter values from URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('type')) {
            typeFilter.value = urlParams.get('type');
        }
        if (urlParams.has('status')) {
            statusFilter.value = urlParams.get('status');
        }
    });
</script>

<?php
function getTransactionIcon($type)
{
    switch ($type) {
        case 'topup':
            return 'fa-plus';
        case 'deduction':
        case 'draw_entry':
            return 'fa-minus';
        case 'draw_win':
            return 'fa-trophy';
        case 'withdrawal':
            return 'fa-arrow-down';
        default:
            return 'fa-receipt';
    }
}

function getStatusColor($status)
{
    switch ($status) {
        case 'completed':
            return 'bg-green-100 text-green-800';
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'failed':
            return 'bg-red-100 text-red-800';
        case 'cancelled':
            return 'bg-gray-100 text-gray-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}
?>
<?= $this->endSection() ?>