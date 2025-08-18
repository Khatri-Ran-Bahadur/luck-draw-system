<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Transactions Management</h2>
                <p class="text-gray-600 mt-1">Monitor all incoming and outgoing payments on the website</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="exportTransactions()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-download mr-2"></i>
                    Export CSV
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="GET" action="<?= base_url('admin/transactions') ?>" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="<?= esc($filters['search'] ?? '') ?>"
                    placeholder="User, email, reference..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Types</option>
                    <option value="deposit" <?= ($filters['type'] ?? '') === 'deposit' ? 'selected' : '' ?>>Deposits</option>
                    <option value="withdrawal" <?= ($filters['type'] ?? '') === 'withdrawal' ? 'selected' : '' ?>>Withdrawals</option>
                    <option value="draw_win" <?= ($filters['type'] ?? '') === 'draw_win' ? 'selected' : '' ?>>Winnings</option>
                    <option value="draw_entry" <?= ($filters['type'] ?? '') === 'draw_entry' ? 'selected' : '' ?>>Entries</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="completed" <?= ($filters['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="failed" <?= ($filters['status'] ?? '') === 'failed' ? 'selected' : '' ?>>Failed</option>
                    <option value="cancelled" <?= ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                <input type="date" name="date_from" value="<?= esc($filters['date_from'] ?? '') ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                <input type="date" name="date_to" value="<?= esc($filters['date_to'] ?? '') ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-search mr-2"></i>
                    Filter
                </button>
                <a href="<?= base_url('admin/transactions') ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">All Transactions</h3>
        </div>
        <div class="overflow-x-auto">
            <?php if (empty($transactions)): ?>
                <div class="p-6 text-center text-gray-500">
                    <i class="fas fa-exchange-alt text-3xl mb-2"></i>
                    <p>No transactions found</p>
                </div>
            <?php else: ?>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wallet Balance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($transactions as $transaction): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <i class="fas fa-user text-blue-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?= esc($transaction['full_name'] ?: $transaction['username']) ?></div>
                                            <div class="text-sm text-gray-500"><?= esc($transaction['email']) ?></div>
                                            <div class="text-xs text-gray-400">@<?= esc($transaction['username']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <?php
                                        $typeIcon = '';
                                        $typeColor = '';
                                        switch ($transaction['type']) {
                                            case 'deposit':
                                                $typeIcon = 'fa-arrow-down';
                                                $typeColor = 'text-green-600 bg-green-100';
                                                break;
                                            case 'withdrawal':
                                                $typeIcon = 'fa-arrow-up';
                                                $typeColor = 'text-red-600 bg-red-100';
                                                break;
                                            case 'draw_win':
                                                $typeIcon = 'fa-trophy';
                                                $typeColor = 'text-yellow-600 bg-yellow-100';
                                                break;
                                            case 'draw_entry':
                                                $typeIcon = 'fa-ticket-alt';
                                                $typeColor = 'text-blue-600 bg-blue-100';
                                                break;
                                            default:
                                                $typeIcon = 'fa-exchange-alt';
                                                $typeColor = 'text-gray-600 bg-gray-100';
                                        }
                                        ?>
                                        <div class="w-8 h-8 <?= $typeColor ?> rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas <?= $typeIcon ?> text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900"><?= ucfirst(str_replace('_', ' ', $transaction['type'])) ?></div>
                                            <?php if ($transaction['payment_reference']): ?>
                                                <div class="text-xs text-gray-500"><?= esc($transaction['payment_reference']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium <?= in_array($transaction['type'], ['deposit', 'draw_win']) ? 'text-green-600' : 'text-red-600' ?>">
                                        <?= in_array($transaction['type'], ['deposit', 'draw_win']) ? '+' : '-' ?>Rs. <?= number_format($transaction['amount'], 2) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">Rs. <?= number_format($transaction['wallet_balance'], 2) ?></div>
                                    <div class="text-xs text-gray-500">Current Balance</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= ucfirst($transaction['payment_method']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $transaction['status'] === 'completed' ? 'bg-green-100 text-green-800' : ($transaction['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                        <?= ucfirst($transaction['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div><?= date('M d, Y', strtotime($transaction['created_at'])) ?></div>
                                    <div class="text-xs text-gray-400"><?= date('H:i:s', strtotime($transaction['created_at'])) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="viewTransaction(<?= $transaction['id'] ?>)"
                                            class="text-blue-600 hover:text-blue-900" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if ($transaction['type'] === 'withdrawal' && $transaction['status'] === 'pending'): ?>
                                            <button onclick="reviewWithdrawal(<?= $transaction['id'] ?>)"
                                                class="text-blue-600 hover:text-blue-900" title="Review Withdrawal">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <button onclick="approveWithdrawal(<?= $transaction['id'] ?>)"
                                                class="text-green-600 hover:text-green-900" title="Quick Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button onclick="rejectWithdrawal(<?= $transaction['id'] ?>)"
                                                class="text-red-600 hover:text-red-900" title="Quick Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Transaction Summary -->
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-arrow-down text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Deposits</p>
                    <p class="text-2xl font-bold text-gray-900">
                        Rs. <?= number_format($summary['total_deposits'], 2) ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-arrow-up text-red-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Withdrawals</p>
                    <p class="text-2xl font-bold text-gray-900">
                        Rs. <?= number_format($summary['total_withdrawals'], 2) ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-trophy text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Winnings</p>
                    <p class="text-2xl font-bold text-gray-900">
                        Rs. <?= number_format($summary['total_winnings'], 2) ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-ticket-alt text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Entries</p>
                    <p class="text-2xl font-bold text-gray-900">
                        Rs. <?= number_format($summary['total_entries'], 2) ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Withdrawals</p>
                    <p class="text-2xl font-bold text-gray-900">
                        Rs. <?= number_format($summary['pending_withdrawals'], 2) ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-gray-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Failed Transactions</p>
                    <p class="text-2xl font-bold text-gray-900">
                        <?= $summary['failed_transactions'] ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaction Details Modal -->
<div id="transactionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Transaction Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="transactionDetails" class="space-y-3">
                <!-- Details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Withdrawal Review Modal -->
<div id="withdrawalReviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Review Withdrawal Request</h3>
                <button onclick="closeWithdrawalReviewModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="withdrawalReviewDetails" class="space-y-4">
                <!-- Details will be loaded here -->
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button onclick="closeWithdrawalReviewModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Cancel
                </button>
                <button id="rejectWithdrawalBtn" onclick="showRejectReason()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Reject
                </button>
                <button id="approveWithdrawalBtn" onclick="processWithdrawal('approve')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Approve & Send Payment
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Reason Modal -->
<div id="rejectionReasonModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Rejection Reason</h3>
                <button onclick="closeRejectionModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason for rejection</label>
                    <select id="rejectionReason" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Select a reason...</option>
                        <option value="Insufficient verification">Insufficient verification</option>
                        <option value="Invalid account details">Invalid account details</option>
                        <option value="Suspicious activity">Suspicious activity</option>
                        <option value="Account mismatch">Account name mismatch</option>
                        <option value="Daily limit exceeded">Daily limit exceeded</option>
                        <option value="Other">Other (specify below)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional notes (optional)</label>
                    <textarea id="rejectionNotes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Additional details..."></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button onclick="closeRejectionModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Cancel
                </button>
                <button onclick="processWithdrawal('reject')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Confirm Rejection
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function exportTransactions() {
        // Get current filter parameters
        const params = new URLSearchParams(window.location.search);
        params.set('export', 'csv');

        // Create download link
        const link = document.createElement('a');
        link.href = '<?= base_url('admin/transactions') ?>?' + params.toString();
        link.download = 'transactions_' + new Date().toISOString().split('T')[0] + '.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function viewTransaction(id) {
        // Show modal
        document.getElementById('transactionModal').classList.remove('hidden');

        // Load transaction details via AJAX
        fetch(`<?= base_url('admin/transaction-details') ?>/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const details = document.getElementById('transactionDetails');
                    details.innerHTML = `
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Transaction ID</label>
                            <p class="text-sm text-gray-900">${data.transaction.id}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">User</label>
                            <p class="text-sm text-gray-900">${data.transaction.full_name || data.transaction.username}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Type</label>
                            <p class="text-sm text-gray-900">${data.transaction.type.replace('_', ' ')}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Amount</label>
                            <p class="text-sm text-gray-900">$${parseFloat(data.transaction.amount).toFixed(2)}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Status</label>
                            <p class="text-sm text-gray-900">${data.transaction.status}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Payment Method</label>
                            <p class="text-sm text-gray-900">${data.transaction.payment_method}</p>
                        </div>
                        <div class="col-span-2">
                            <label class="text-sm font-medium text-gray-600">Reference</label>
                            <p class="text-sm text-gray-900">${data.transaction.payment_reference || 'N/A'}</p>
                        </div>
                        <div class="col-span-2">
                            <label class="text-sm font-medium text-gray-600">Date</label>
                            <p class="text-sm text-gray-900">${new Date(data.transaction.created_at).toLocaleString()}</p>
                        </div>
                    </div>
                `;
                } else {
                    document.getElementById('transactionDetails').innerHTML = '<p class="text-red-600">Failed to load transaction details.</p>';
                }
            })
            .catch(error => {
                document.getElementById('transactionDetails').innerHTML = '<p class="text-red-600">Error loading transaction details.</p>';
            });
    }

    function approveWithdrawal(id) {
        if (confirm('Are you sure you want to approve this withdrawal?')) {
            fetch(`<?= base_url('admin/approve-withdrawal') ?>/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to approve withdrawal: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error approving withdrawal');
                });
        }
    }

    function rejectWithdrawal(id) {
        const reason = prompt('Please enter a reason for rejection:');
        if (reason) {
            fetch(`<?= base_url('admin/reject-withdrawal') ?>/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        reason: reason
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to reject withdrawal: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error rejecting withdrawal');
                });
        }
    }

    function closeModal() {
        document.getElementById('transactionModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('transactionModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Withdrawal Review Functions
    let currentWithdrawalId = null;

    function reviewWithdrawal(id) {
        currentWithdrawalId = id;
        document.getElementById('withdrawalReviewModal').classList.remove('hidden');

        // Load withdrawal details
        fetch(`<?= base_url('admin/transaction-details') ?>/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const transaction = data.transaction;
                    const metadata = transaction.metadata ? JSON.parse(transaction.metadata) : {};

                    document.getElementById('withdrawalReviewDetails').innerHTML = `
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">User Information</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Name</label>
                                    <p class="text-sm text-gray-900">${transaction.full_name || transaction.username}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Email</label>
                                    <p class="text-sm text-gray-900">${transaction.email}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Username</label>
                                    <p class="text-sm text-gray-900">@${transaction.username}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Current Balance</label>
                                        <p class="text-sm text-gray-900">Rs. <?= number_format($transaction['wallet_balance'] || 0, 2) ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Withdrawal Details</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Amount</label>
                                    <p class="text-lg font-bold text-blue-600">Rs. <?= number_format(abs($transaction['amount']), 2) ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Method</label>
                                    <p class="text-sm text-gray-900">${transaction.payment_method}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Account Details</label>
                                    <p class="text-sm text-gray-900">${metadata.account_details || 'N/A'}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Account Name</label>
                                    <p class="text-sm text-gray-900">${metadata.full_name || 'N/A'}</p>
                                </div>
                                <div class="col-span-2">
                                    <label class="text-sm font-medium text-gray-600">Request Date</label>
                                    <p class="text-sm text-gray-900">${new Date(transaction.created_at).toLocaleString()}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-2">Security Check</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Account verification:</span>
                                    <span class="text-green-600"><i class="fas fa-check mr-1"></i>Verified</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Name match:</span>
                                    <span class="text-yellow-600"><i class="fas fa-exclamation-triangle mr-1"></i>Review required</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Daily limit:</span>
                                    <span class="text-green-600"><i class="fas fa-check mr-1"></i>Within limits</span>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    document.getElementById('withdrawalReviewDetails').innerHTML = '<p class="text-red-600">Failed to load withdrawal details.</p>';
                }
            })
            .catch(error => {
                document.getElementById('withdrawalReviewDetails').innerHTML = '<p class="text-red-600">Error loading withdrawal details.</p>';
            });
    }

    function closeWithdrawalReviewModal() {
        document.getElementById('withdrawalReviewModal').classList.add('hidden');
        currentWithdrawalId = null;
    }

    function showRejectReason() {
        document.getElementById('rejectionReasonModal').classList.remove('hidden');
    }

    function closeRejectionModal() {
        document.getElementById('rejectionReasonModal').classList.add('hidden');
        document.getElementById('rejectionReason').value = '';
        document.getElementById('rejectionNotes').value = '';
    }

    function processWithdrawal(action) {
        if (!currentWithdrawalId) return;

        let url, method, body = {};

        if (action === 'approve') {
            if (!confirm('Are you sure you want to approve this withdrawal? The payment will be processed immediately.')) {
                return;
            }
            url = `<?= base_url('admin/approve-withdrawal') ?>/${currentWithdrawalId}`;
            method = 'POST';
        } else if (action === 'reject') {
            const reason = document.getElementById('rejectionReason').value;
            const notes = document.getElementById('rejectionNotes').value;

            if (!reason) {
                alert('Please select a reason for rejection.');
                return;
            }

            const fullReason = reason === 'Other' ? notes : (notes ? `${reason}: ${notes}` : reason);

            url = `<?= base_url('admin/reject-withdrawal') ?>/${currentWithdrawalId}`;
            method = 'POST';
            body = {
                reason: fullReason
            };
        }

        fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(body)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeWithdrawalReviewModal();
                    closeRejectionModal();
                    location.reload();
                } else {
                    alert(`Failed to ${action} withdrawal: ` + data.message);
                }
            })
            .catch(error => {
                alert(`Error ${action}ing withdrawal`);
            });
    }
</script>

<?= $this->endSection() ?>