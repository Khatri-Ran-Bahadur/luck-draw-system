<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-lg p-8 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <?php if (!empty($user['profile_image'])): ?>
                        <img src="<?= base_url($user['profile_image']) ?>" alt="<?= esc($user['full_name']) ?>" class="w-full h-full object-cover rounded-2xl">
                    <?php else: ?>
                        <i class="fas fa-user text-3xl"></i>
                    <?php endif; ?>
                </div>
                <div>
                    <h1 class="text-3xl font-bold"><?= esc($user['full_name']) ?></h1>
                    <p class="text-blue-100 text-lg mt-1">@<?= esc($user['username']) ?></p>
                    <div class="flex items-center space-x-4 mt-2 text-sm">
                        <span class="bg-white/20 px-3 py-1 rounded-full">
                            <i class="fas fa-envelope mr-1"></i>
                            <?= esc($user['email']) ?>
                        </span>
                        <span class="bg-white/20 px-3 py-1 rounded-full">
                            <i class="fas fa-<?= $user['status'] === 'active' ? 'check-circle' : 'pause-circle' ?> mr-1"></i>
                            <?= ucfirst($user['status']) ?>
                        </span>
                        <?php if (!empty($user['phone'])): ?>
                            <span class="bg-white/20 px-3 py-1 rounded-full">
                                <i class="fas fa-phone mr-1"></i>
                                <?= esc($user['phone']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="<?= base_url('admin/users/edit/' . $user['id']) ?>" class="inline-flex items-center px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-xl transition-all duration-200 backdrop-blur-sm">
                    <i class="fas fa-edit mr-2"></i>
                    Edit User
                </a>
                <a href="<?= base_url('admin/users') ?>" class="inline-flex items-center px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-xl transition-all duration-200 backdrop-blur-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Users
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Wallet Balance</p>
                    <p class="text-3xl font-bold text-green-600">Rs. <?= number_format($stats['wallet_balance'], 2) ?></p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-wallet text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Spent</p>
                    <p class="text-3xl font-bold text-red-600">Rs. <?= number_format($stats['total_spent'], 2) ?></p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-credit-card text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Entries</p>
                    <p class="text-3xl font-bold text-blue-600"><?= $stats['total_entries'] ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-ticket-alt text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Winnings</p>
                    <p class="text-3xl font-bold text-yellow-600"><?= $stats['total_winnings'] ?></p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-trophy text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Prize Statistics -->
    <?php if ($stats['total_winnings'] > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Prize Won</p>
                        <p class="text-2xl font-bold text-green-600">Rs. <?= number_format($stats['total_prize_won'], 2) ?></p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pending Claims</p>
                        <p class="text-2xl font-bold text-orange-600"><?= $stats['pending_claims'] ?></p>
                    </div>
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Approved Claims</p>
                        <p class="text-2xl font-bold text-green-600"><?= $stats['approved_claims'] ?></p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Wallet Transactions -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-history text-blue-500 mr-3"></i>
                        Wallet Transactions
                    </h2>
                    <p class="text-gray-600 mt-1">Recent wallet activity and transaction history</p>
                </div>
                <div class="text-sm text-gray-500">
                    Total: <?= $transaction_pagination['total_transactions'] ?> transactions
                </div>
            </div>
        </div>
        <div class="p-6">
            <?php if (empty($transactions)): ?>
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-history text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Transactions</h3>
                    <p class="text-gray-500">This user hasn't made any wallet transactions yet.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($transactions as $transaction): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        <?= date('M j, Y g:i A', strtotime($transaction['created_at'])) ?>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            <?= $transaction['type'] === 'topup' ? 'bg-green-100 text-green-800' : ($transaction['type'] === 'withdraw' ? 'bg-red-100 text-red-800' : ($transaction['type'] === 'draw_win' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) ?>">
                                            <i class="fas fa-<?= $transaction['type'] === 'topup' ? 'plus' : ($transaction['type'] === 'withdraw' ? 'minus' : ($transaction['type'] === 'draw_win' ? 'trophy' : 'exchange-alt')) ?> mr-1"></i>
                                            <?= ucfirst(str_replace('_', ' ', $transaction['type'])) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm font-bold <?= $transaction['amount'] > 0 ? 'text-green-600' : 'text-red-600' ?>">
                                        <?= $transaction['amount'] > 0 ? '+' : '' ?>Rs. <?= number_format($transaction['amount'], 2) ?>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        <?= ucfirst($transaction['payment_method']) ?>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            <?= $transaction['status'] === 'completed' ? 'bg-green-100 text-green-800' : ($transaction['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                            <?= ucfirst($transaction['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500">
                                        <?= esc($transaction['payment_reference']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Transaction Pagination -->
                <?php if ($transaction_pagination['total_pages'] > 1): ?>
                    <div class="flex items-center justify-between border-t border-gray-200 bg-gray-50 px-4 py-3 sm:px-6 rounded-b-xl mt-4">
                        <div class="flex flex-1 justify-between sm:hidden">
                            <?php if ($transaction_pagination['has_previous']): ?>
                                <a href="<?= current_url() ?>?transactions_page=<?= $transaction_pagination['previous_page'] ?>" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
                            <?php else: ?>
                                <span class="relative inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed">Previous</span>
                            <?php endif; ?>
                            <?php if ($transaction_pagination['has_next']): ?>
                                <a href="<?= current_url() ?>?transactions_page=<?= $transaction_pagination['next_page'] ?>" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
                            <?php else: ?>
                                <span class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed">Next</span>
                            <?php endif; ?>
                        </div>
                        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium"><?= $transaction_pagination['start_transaction'] ?></span> to <span class="font-medium"><?= $transaction_pagination['end_transaction'] ?></span> of <span class="font-medium"><?= $transaction_pagination['total_transactions'] ?></span> results
                                </p>
                            </div>
                            <div>
                                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                    <?php if ($transaction_pagination['has_previous']): ?>
                                        <a href="<?= current_url() ?>?transactions_page=<?= $transaction_pagination['previous_page'] ?>" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                            <i class="fas fa-chevron-left text-sm"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-300 ring-1 ring-inset ring-gray-300 cursor-not-allowed">
                                            <i class="fas fa-chevron-left text-sm"></i>
                                        </span>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $transaction_pagination['total_pages']; $i++): ?>
                                        <?php if ($i == $transaction_pagination['current_page']): ?>
                                            <span class="relative z-10 inline-flex items-center bg-blue-600 px-4 py-2 text-sm font-semibold text-white focus:z-20"><?= $i ?></span>
                                        <?php else: ?>
                                            <a href="<?= current_url() ?>?transactions_page=<?= $i ?>" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20"><?= $i ?></a>
                                        <?php endif; ?>
                                    <?php endfor; ?>

                                    <?php if ($transaction_pagination['has_next']): ?>
                                        <a href="<?= current_url() ?>?transactions_page=<?= $transaction_pagination['next_page'] ?>" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                            <i class="fas fa-chevron-right text-sm"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-300 ring-1 ring-inset ring-gray-300 cursor-not-allowed">
                                            <i class="fas fa-chevron-right text-sm"></i>
                                        </span>
                                    <?php endif; ?>
                                </nav>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Lucky Draw Participation -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Cash Draw Entries -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-coins text-green-500 mr-3"></i>
                    Cash Draw Entries (<?= count($cash_entries) ?>)
                </h2>
                <p class="text-gray-600 mt-1">Participation in cash lucky draws</p>
            </div>
            <div class="p-6">
                <?php if (empty($cash_entries)): ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-coins text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500">No cash draw entries</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        <?php foreach ($cash_entries as $entry): ?>
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900"><?= esc($entry['title']) ?></h4>
                                        <p class="text-sm text-gray-600">Entry Fee: Rs. <?= number_format($entry['entry_fee'], 2) ?></p>
                                        <p class="text-xs text-gray-500">
                                            <?= date('M j, Y g:i A', strtotime($entry['created_at'])) ?>
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            <?= $entry['status'] === 'completed' ? 'bg-green-100 text-green-800' : ($entry['status'] === 'active' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') ?>">
                                            <?= ucfirst($entry['status']) ?>
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Draw: <?= date('M j, Y', strtotime($entry['draw_date'])) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Draw Entries -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-gift text-purple-500 mr-3"></i>
                    Product Draw Entries (<?= count($product_entries) ?>)
                </h2>
                <p class="text-gray-600 mt-1">Participation in product lucky draws</p>
            </div>
            <div class="p-6">
                <?php if (empty($product_entries)): ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-gift text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500">No product draw entries</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        <?php foreach ($product_entries as $entry): ?>
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900"><?= esc($entry['title']) ?></h4>
                                        <p class="text-sm text-gray-600">Product: <?= esc($entry['product_name']) ?></p>
                                        <p class="text-sm text-gray-600">Entry Fee: Rs. <?= number_format($entry['entry_fee'], 2) ?></p>
                                        <p class="text-xs text-gray-500">
                                            <?= date('M j, Y g:i A', strtotime($entry['created_at'])) ?>
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            <?= $entry['status'] === 'completed' ? 'bg-green-100 text-green-800' : ($entry['status'] === 'active' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') ?>">
                                            <?= ucfirst($entry['status']) ?>
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Draw: <?= date('M j, Y', strtotime($entry['draw_date'])) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Winnings -->
    <?php if (!empty($cash_winnings) || !empty($product_winnings)): ?>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-trophy text-yellow-500 mr-3"></i>
                    User Winnings (<?= count($cash_winnings) + count($product_winnings) ?>)
                </h2>
                <p class="text-gray-600 mt-1">All prizes won by this user</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Cash Winnings -->
                    <?php if (!empty($cash_winnings)): ?>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-coins text-green-500 mr-2"></i>
                                Cash Winnings (<?= count($cash_winnings) ?>)
                            </h3>
                            <div class="space-y-3">
                                <?php foreach ($cash_winnings as $winning): ?>
                                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="font-semibold text-gray-900"><?= esc($winning['title']) ?></h4>
                                                <p class="text-sm text-gray-600">Position: <?= getOrdinal($winning['position']) ?></p>
                                                <p class="text-xs text-gray-500">
                                                    <?= date('M j, Y', strtotime($winning['draw_date'])) ?>
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-lg font-bold text-green-600">Rs. <?= number_format($winning['prize_amount'], 2) ?></p>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                <?= $winning['claim_approved'] ? 'bg-green-100 text-green-800' : ($winning['is_claimed'] ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') ?>">
                                                    <?= $winning['claim_approved'] ? 'Approved' : ($winning['is_claimed'] ? 'Pending' : 'Not Claimed') ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Product Winnings -->
                    <?php if (!empty($product_winnings)): ?>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-gift text-purple-500 mr-2"></i>
                                Product Winnings (<?= count($product_winnings) ?>)
                            </h3>
                            <div class="space-y-3">
                                <?php foreach ($product_winnings as $winning): ?>
                                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="font-semibold text-gray-900"><?= esc($winning['title']) ?></h4>
                                                <p class="text-sm text-gray-600">Product: <?= esc($winning['product_name']) ?></p>
                                                <p class="text-xs text-gray-500">
                                                    <?= date('M j, Y', strtotime($winning['draw_date'])) ?>
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                <?= $winning['claim_approved'] ? 'bg-green-100 text-green-800' : ($winning['is_claimed'] ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') ?>">
                                                    <?= $winning['claim_approved'] ? 'Approved' : ($winning['is_claimed'] ? 'Pending' : 'Not Claimed') ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
function getOrdinal($n)
{
    $s = ["th", "st", "nd", "rd"];
    $v = $n % 100;
    return $n . ($s[($v - 20) % 10] ?? $s[$v] ?? $s[0]);
}
?>
<?= $this->endSection() ?>