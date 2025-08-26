<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl shadow-lg p-8 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-gift text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold"><?= esc($draw['title']) ?></h1>
                    <p class="text-purple-100 text-lg mt-1">Product Draw Details & Results</p>
                    <div class="flex items-center space-x-4 mt-2 text-sm">
                        <span class="bg-white/20 px-3 py-1 rounded-full">
                            <i class="fas fa-calendar mr-1"></i>
                            <?= date('M j, Y g:i A', strtotime($draw['draw_date'])) ?>
                        </span>
                        <span class="bg-white/20 px-3 py-1 rounded-full">
                            <i class="fas fa-<?= $draw['status'] === 'completed' ? 'check-circle' : ($draw['status'] === 'active' ? 'play-circle' : 'pause-circle') ?> mr-1"></i>
                            <?= ucfirst($draw['status']) ?>
                        </span>
                    </div>
                </div>
            </div>
            <a href="<?= base_url('admin/product-draws') ?>" class="inline-flex items-center px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-xl transition-all duration-200 backdrop-blur-sm">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Product Draws
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Entries</p>
                    <p class="text-3xl font-bold text-gray-900"><?= $stats['total_entries'] ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-ticket-alt text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-3xl font-bold text-green-600">Rs. <?= number_format($stats['total_revenue'], 2) ?></p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Product Value</p>
                    <p class="text-3xl font-bold text-purple-600">Rs. <?= number_format($stats['product_value'], 2) ?></p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-gift text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Information -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-box text-purple-500 mr-3"></i>
                Product Information
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Product Image -->
                <div class="flex justify-center">
                    <div class="w-64 h-64 rounded-xl shadow-lg overflow-hidden">
                        <?= get_product_image($draw['product_image'], 'max-w-full h-64 object-cover rounded-xl shadow-lg', 'fas fa-image') ?>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900"><?= esc($draw['product_name']) ?></h3>
                        <p class="text-gray-600 mt-2"><?= esc($draw['description']) ?></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Product Value</p>
                            <p class="text-xl font-bold text-gray-900">Rs. <?= number_format($draw['product_price'], 2) ?></p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Entry Fee</p>
                            <p class="text-xl font-bold text-gray-900">Rs. <?= number_format($draw['entry_fee'], 2) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Participation Analysis -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-chart-pie text-blue-500 mr-3"></i>
                Participation Analysis
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-700">Total Participants</p>
                            <p class="text-2xl font-bold text-blue-900"><?= $stats['total_entries'] ?></p>
                        </div>
                        <i class="fas fa-users text-blue-500 text-2xl"></i>
                    </div>
                </div>
                <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-yellow-700">Winners Selected</p>
                            <p class="text-2xl font-bold text-yellow-900"><?= count($winners) ?></p>
                        </div>
                        <i class="fas fa-trophy text-yellow-500 text-2xl"></i>
                    </div>
                </div>
                <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-700">Win Rate</p>
                            <p class="text-2xl font-bold text-green-900">
                                <?= $stats['total_entries'] > 0 ? round((count($winners) / $stats['total_entries']) * 100, 1) : 0 ?>%
                            </p>
                        </div>
                        <i class="fas fa-percentage text-green-500 text-2xl"></i>
                    </div>
                </div>
            </div>

            <?php if ($stats['total_entries'] > 0): ?>
                <!-- Entry Timeline -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Entry Timeline</h3>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                            <span>Showing entries <?= $pagination['start_entry'] ?> - <?= $pagination['end_entry'] ?> of <?= $pagination['total_entries'] ?></span>
                            <span>Page <?= $pagination['current_page'] ?> of <?= $pagination['total_pages'] ?></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Winners Section -->
    <?php if (!empty($winners)): ?>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-trophy text-yellow-500 mr-3"></i>
                    Winners (<?= count($winners) ?>)
                </h2>
                <p class="text-gray-600 mt-1">List of all winners with their prizes</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($winners as $winner): ?>
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl p-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full flex items-center justify-center text-white font-bold">
                                        🏆
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-900 truncate">
                                        <?= esc($winner['full_name']) ?>
                                        <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Winner
                                        </span>
                                    </p>
                                    <p class="text-sm text-gray-600 truncate">@<?= esc($winner['username']) ?></p>
                                    <p class="text-xs text-gray-500 truncate"><?= esc($winner['email']) ?></p>
                                    <p class="text-sm font-bold text-green-600 mt-1">
                                        <i class="fas fa-gift mr-1"></i>
                                        Won: <?= esc($draw['product_name']) ?>
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        Status:
                                        <?php if ($winner['claim_approved']): ?>
                                            <span class="text-green-600 font-medium">Approved</span>
                                        <?php elseif ($winner['is_claimed']): ?>
                                            <span class="text-yellow-600 font-medium">Pending</span>
                                        <?php else: ?>
                                            <span class="text-gray-600 font-medium">Not Claimed</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- No Winners Yet -->
        <?php if ($draw['status'] === 'completed'): ?>
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-trophy text-yellow-500 mr-3"></i>
                        Winners
                    </h2>
                </div>
                <div class="p-6">
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-trophy text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Winner Found</h3>
                        <p class="text-gray-500">This draw is marked as completed but no winner was recorded.</p>
                        <p class="text-gray-400 text-sm mt-2">This might happen if the winner selection process was interrupted.</p>
                    </div>
                </div>
            </div>
        <?php elseif ($draw['status'] === 'active'): ?>
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-blue-900">Winner Not Selected Yet</h3>
                        <p class="text-blue-700">This draw is still active. The winner will appear here after you select them.</p>
                        <p class="text-blue-600 text-sm mt-1">
                            Go to <a href="<?= base_url('admin/product-draws') ?>" class="underline font-medium">Product Draws</a>
                            and click the trophy icon to select a winner.
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- All Participants -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-users text-blue-500 mr-3"></i>
                        All Participants (<?= $stats['total_entries'] ?>)
                    </h2>
                    <p class="text-gray-600 mt-1">
                        Showing <?= $pagination['start_entry'] ?> - <?= $pagination['end_entry'] ?> of <?= $pagination['total_entries'] ?> participants
                    </p>
                </div>
                <?php if (!empty($entries)): ?>
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Winners are highlighted in gold
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="p-6">
            <?php if (empty($entries)): ?>
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Participants</h3>
                    <p class="text-gray-500">No one participated in this draw yet.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participant</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entry Details</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entry Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($entries as $index => $entry): ?>
                                <?php
                                $isWinner = false;
                                foreach ($winners as $winner) {
                                    if ($winner['user_id'] == $entry['user_id']) {
                                        $isWinner = true;
                                        break;
                                    }
                                }
                                ?>
                                <tr class="<?= $isWinner ? 'bg-gradient-to-r from-yellow-50 to-orange-50' : 'hover:bg-gray-50' ?> transition-colors">
                                    <td class="px-4 py-4 text-sm font-medium text-gray-900">
                                        <?= ($pagination['current_page'] - 1) * $pagination['per_page'] + $index + 1 ?>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 <?= $isWinner ? 'bg-gradient-to-r from-yellow-500 to-orange-500' : 'bg-gradient-to-r from-blue-500 to-purple-500' ?> rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                <?= $isWinner ? '🏆' : strtoupper(substr($entry['full_name'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900"><?= esc($entry['full_name']) ?></div>
                                                <div class="text-sm text-gray-500">@<?= esc($entry['username']) ?></div>
                                                <div class="text-xs text-gray-400"><?= esc($entry['email']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-900">Entry #<?= $entry['entry_number'] ?? (($pagination['current_page'] - 1) * $pagination['per_page'] + $index + 1) ?></div>
                                            <div class="text-gray-500">Paid: Rs. <?= number_format($entry['amount_paid'] ?? $draw['entry_fee'], 2) ?></div>
                                            <div class="text-xs text-gray-400">
                                                Method: <?= ucfirst($entry['payment_method'] ?? 'N/A') ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <?php if ($isWinner): ?>
                                            <div class="space-y-1">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-trophy mr-1"></i>
                                                    Winner
                                                </span>
                                                <div class="text-xs font-bold text-green-600">
                                                    Won: <?= esc($draw['product_name']) ?>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-user mr-1"></i>
                                                Participant
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500">
                                        <div><?= date('M j, Y', strtotime($entry['created_at'])) ?></div>
                                        <div class="text-xs"><?= date('g:i A', strtotime($entry['created_at'])) ?></div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Controls -->
                <?php if ($pagination['total_pages'] > 1): ?>
                    <div class="flex items-center justify-between border-t border-gray-200 bg-gray-50 px-4 py-3 sm:px-6 rounded-b-xl">
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
                                    Showing <span class="font-medium"><?= $pagination['start_entry'] ?></span> to <span class="font-medium"><?= $pagination['end_entry'] ?></span> of <span class="font-medium"><?= $pagination['total_entries'] ?></span> results
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
                                            <span class="relative z-10 inline-flex items-center bg-blue-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"><?= $i ?></span>
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
</div>
<?= $this->endSection() ?>