<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-orange-600 to-red-600 rounded-2xl shadow-lg p-8 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-clock text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Pending Withdraw Requests</h1>
                    <p class="text-orange-100 text-lg mt-1">New withdrawal requests awaiting approval</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-sm text-orange-100">Total Pending</div>
                <div class="text-2xl font-bold"><?= $stats['total_pending'] ?></div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Pending</p>
                    <p class="text-3xl font-bold text-orange-600"><?= $stats['total_pending'] ?></p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending Amount</p>
                    <p class="text-3xl font-bold text-blue-600">Rs. <?= number_format($stats['total_amount'], 2) ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Today's Count</p>
                    <p class="text-3xl font-bold text-purple-600"><?= $stats['today_count'] ?></p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-day text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Today's Amount</p>
                    <p class="text-3xl font-bold text-purple-600">Rs. <?= number_format($stats['today_amount'], 2) ?></p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-coins text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdraw Requests Table -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-clock text-orange-500 mr-3"></i>
                        Pending Withdrawal Requests
                    </h2>
                    <p class="text-gray-600 mt-1">
                        Showing <?= $pagination['start_withdrawal'] ?> - <?= $pagination['end_withdrawal'] ?> of <?= $pagination['total_withdrawals'] ?> pending requests
                    </p>
                </div>
                <div class="text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Requests awaiting admin approval
                </div>
            </div>
        </div>
        <div class="p-6">
            <?php if (empty($withdrawals)): ?>
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Pending Requests</h3>
                    <p class="text-gray-500">No pending withdrawal requests found.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($withdrawals as $withdrawal): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        <div class="font-medium"><?= date('M j, Y', strtotime($withdrawal['created_at'])) ?></div>
                                        <div class="text-xs text-gray-500"><?= date('g:i A', strtotime($withdrawal['created_at'])) ?></div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                <?= strtoupper(substr($withdrawal['full_name'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900"><?= esc($withdrawal['full_name']) ?></div>
                                                <div class="text-sm text-gray-500">@<?= esc($withdrawal['username']) ?></div>
                                                <div class="text-xs text-gray-400"><?= esc($withdrawal['email']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-lg font-bold text-red-600">
                                            Rs. <?= number_format(abs($withdrawal['amount']), 2) ?>
                                        </div>
                                        <div class="text-xs text-gray-500">Requested</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-<?= $withdrawal['payment_method'] === 'paypal' ? 'paypal' : ($withdrawal['payment_method'] === 'easypaisa' ? 'mobile-alt' : 'credit-card') ?> 
                                                text-<?= $withdrawal['payment_method'] === 'paypal' ? 'blue' : ($withdrawal['payment_method'] === 'easypaisa' ? 'green' : 'gray') ?>-500"></i>
                                            <span class="text-sm font-medium text-gray-900">
                                                <?= ucfirst($withdrawal['payment_method']) ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            Pending
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex space-x-2">
                                            <form method="POST" action="<?= base_url('admin/approve-withdrawal/' . $withdrawal['id']) ?>" class="inline">
                                                <button type="submit"
                                                    onclick="return confirm('Are you sure you want to approve this withdrawal request?')"
                                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="<?= base_url('admin/reject-withdrawal/' . $withdrawal['id']) ?>" class="inline">
                                                <button type="submit"
                                                    onclick="return confirm('Are you sure you want to reject this withdrawal request? The amount will be refunded to the user.')"
                                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    <i class="fas fa-times mr-1"></i>
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Controls -->
                <?php if ($pagination['total_pages'] > 1): ?>
                    <div class="flex items-center justify-between border-t border-gray-200 bg-gray-50 px-4 py-3 sm:px-6 rounded-b-xl mt-6">
                        <div class="flex flex-1 justify-between sm:hidden">
                            <?php if ($pagination['has_previous']): ?>
                                <a href="<?= current_url() ?>?page=<?= $pagination['previous_page'] ?>" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
                            <?php else: ?>
                                <span class="relative inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed">Previous</span>
                            <?php endif; ?>
                            <?php if ($pagination['has_next']): ?>
                                <a href="<?= current_url() ?>?page=<?= $pagination['next_page'] ?>" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
                            <?php else: ?>
                                <span class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed">Next</span>
                            <?php endif; ?>
                        </div>
                        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium"><?= $pagination['start_withdrawal'] ?></span> to <span class="font-medium"><?= $pagination['end_withdrawal'] ?></span> of <span class="font-medium"><?= $pagination['total_withdrawals'] ?></span> results
                                </p>
                            </div>
                            <div>
                                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                    <?php if ($pagination['has_previous']): ?>
                                        <a href="<?= current_url() ?>?page=<?= $pagination['previous_page'] ?>" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                            <span class="sr-only">Previous</span>
                                            <i class="fas fa-chevron-left text-sm"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-300 ring-1 ring-inset ring-gray-300 cursor-not-allowed">
                                            <i class="fas fa-chevron-left text-sm"></i>
                                        </span>
                                    <?php endif; ?>

                                    <?php
                                    $start = max(1, $pagination['current_page'] - 2);
                                    $end = min($pagination['total_pages'], $pagination['current_page'] + 2);

                                    if ($start > 1): ?>
                                        <a href="<?= current_url() ?>?page=1" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">1</a>
                                        <?php if ($start > 2): ?>
                                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">...</span>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php for ($i = $start; $i <= $end; $i++): ?>
                                        <?php if ($i == $pagination['current_page']): ?>
                                            <span class="relative z-10 inline-flex items-center bg-green-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600"><?= $i ?></span>
                                        <?php else: ?>
                                            <a href="<?= current_url() ?>?page=<?= $i ?>" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"><?= $i ?></a>
                                        <?php endif; ?>
                                    <?php endfor; ?>

                                    <?php if ($end < $pagination['total_pages']): ?>
                                        <?php if ($end < $pagination['total_pages'] - 1): ?>
                                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">...</span>
                                        <?php endif; ?>
                                        <a href="<?= current_url() ?>?page=<?= $pagination['total_pages'] ?>" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"><?= $pagination['total_pages'] ?></a>
                                    <?php endif; ?>

                                    <?php if ($pagination['has_next']): ?>
                                        <a href="<?= current_url() ?>?page=<?= $pagination['next_page'] ?>" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                            <span class="sr-only">Next</span>
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

    <!-- Additional Information -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex items-start space-x-3">
            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-blue-900 mb-2">About Pending Withdraw Requests</h3>
                <div class="text-blue-800 space-y-2">
                    <p>• This page shows only <strong>pending</strong> withdrawal requests awaiting admin approval</p>
                    <p>• Use the <strong>Approve</strong> button to process and complete a withdrawal</p>
                    <p>• Use the <strong>Reject</strong> button to decline a request and refund the amount to user's wallet</p>
                    <p>• Users can withdraw their winnings and wallet balance through PayPal or Easypaisa</p>
                    <p>• Once approved, the withdrawal will be processed and marked as completed</p>
                    <p>• Rejected withdrawals will automatically refund the amount back to the user's wallet</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>