<?= $this->extend('layouts/admin') ?>

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

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-yellow-600 to-orange-600 rounded-2xl shadow-lg p-8 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-trophy text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Winners Management</h1>
                    <p class="text-yellow-100 text-lg mt-1">Approve or reject winner prize claims from completed draws</p>
                    <p class="text-yellow-200 text-sm mt-1">💡 When users win draws and claim their prizes, they appear here for your approval</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-sm text-yellow-100">Total Claims</div>
                <div class="text-2xl font-bold"><?= count($pending_claims) + count($approved_claims) ?></div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Winners</p>
                    <p class="text-3xl font-bold text-blue-600"><?= count($all_winners) ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-trophy text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending Claims</p>
                    <p class="text-3xl font-bold text-orange-600"><?= count($pending_claims) ?></p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Approved Claims</p>
                    <p class="text-3xl font-bold text-green-600"><?= count($approved_claims) ?></p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- All Winners Section -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-trophy text-yellow-500 mr-3"></i>
                All Winners (<?= count($all_winners) ?>)
            </h2>
            <p class="text-gray-600 mt-1">All winners from completed draws</p>
        </div>

        <?php if (empty($all_winners)): ?>
            <div class="p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-trophy text-gray-400 text-4xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Winners Yet</h3>
                <p class="text-gray-500">Winners will appear here after draws are completed</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Winner</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Draw Details</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prize</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($all_winners as $winner): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                            <?= strtoupper(substr($winner['full_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900"><?= esc($winner['full_name']) ?></div>
                                            <div class="text-sm text-gray-500">@<?= esc($winner['username']) ?></div>
                                            <div class="text-xs text-gray-400"><?= esc($winner['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900"><?= esc($winner['title'] ?? 'Unknown Draw') ?></div>
                                    <div class="text-sm">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?= $winner['draw_type'] === 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' ?>">
                                            <i class="fas fa-<?= $winner['draw_type'] === 'cash' ? 'dollar-sign' : 'gift' ?> mr-1"></i>
                                            <?= ucfirst($winner['draw_type']) ?> Draw
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Position: <?= getOrdinal($winner['position']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($winner['draw_type'] === 'cash'): ?>
                                        <div class="text-lg font-bold text-green-600">Rs. <?= number_format($winner['prize_amount'], 2) ?></div>
                                        <div class="text-xs text-gray-500">Cash Prize</div>
                                    <?php else: ?>
                                        <div class="text-lg font-bold text-blue-600">Product</div>
                                        <div class="text-xs text-gray-500">Physical Prize</div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col space-y-1">
                                        <?php if ($winner['is_claimed']): ?>
                                            <?php if ($winner['claim_approved']): ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>Approved
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i>Pending Approval
                                                </span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                <i class="fas fa-hourglass-start mr-1"></i>Not Claimed
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <?php if ($winner['is_claimed'] && !$winner['claim_approved']): ?>
                                            <a href="<?= base_url('admin/winners/approve/' . $winner['id']) ?>"
                                                class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors"
                                                onclick="return confirm('Approve this claim? This action cannot be undone.')">
                                                <i class="fas fa-check mr-1"></i>Approve
                                            </a>
                                            <a href="<?= base_url('admin/winners/reject/' . $winner['id']) ?>"
                                                class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700 transition-colors"
                                                onclick="return confirm('Reject this claim? This action cannot be undone.')">
                                                <i class="fas fa-times mr-1"></i>Reject
                                            </a>
                                        <?php elseif (!$winner['is_claimed']): ?>
                                            <span class="text-xs text-gray-400">Waiting for user to claim</span>
                                        <?php else: ?>
                                            <span class="text-xs text-green-600 font-medium">✓ Already Approved</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pending Claims -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-hourglass-half text-orange-500 mr-3"></i>
                Pending Claims (<?= count($pending_claims) ?>)
            </h2>
            <p class="text-gray-600 mt-1">Claims waiting for your approval</p>
        </div>

        <?php if (empty($pending_claims)): ?>
            <div class="p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-gray-400 text-4xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Pending Claims</h3>
                <p class="text-gray-500">All winner claims have been processed!</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Winner</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Draw Details</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prize</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Claim Details</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($pending_claims as $claim): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                            <?= strtoupper(substr($claim['full_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900"><?= esc($claim['full_name']) ?></div>
                                            <div class="text-sm text-gray-500">@<?= esc($claim['username']) ?></div>
                                            <div class="text-xs text-gray-400"><?= esc($claim['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900"><?= esc($claim['draw_title'] ?? 'Unknown Draw') ?></div>
                                    <div class="text-sm">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?= $claim['draw_type'] === 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' ?>">
                                            <i class="fas fa-<?= $claim['draw_type'] === 'cash' ? 'dollar-sign' : 'gift' ?> mr-1"></i>
                                            <?= ucfirst($claim['draw_type']) ?> Draw
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Position: <?= getOrdinal($claim['position']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($claim['draw_type'] === 'cash'): ?>
                                        <div class="text-lg font-bold text-green-600">Rs. <?= number_format($claim['prize_amount'], 2) ?></div>
                                        <div class="text-xs text-gray-500">Cash Prize</div>
                                    <?php else: ?>
                                        <div class="text-lg font-bold text-blue-600">Product</div>
                                        <div class="text-xs text-gray-500">Physical Prize</div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 max-w-xs">
                                    <div class="text-sm text-gray-900">
                                        <?php if ($claim['claim_details']): ?>
                                            <?php
                                            $details = json_decode($claim['claim_details'], true);
                                            if (is_array($details)):
                                            ?>
                                                <div class="space-y-1">
                                                    <?php if (isset($details['whatsapp'])): ?>
                                                        <div class="flex items-center text-xs">
                                                            <i class="fab fa-whatsapp text-green-500 mr-2"></i>
                                                            <span class="font-medium">WhatsApp:</span>
                                                            <span class="ml-1"><?= esc($details['whatsapp']) ?></span>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (isset($details['phone'])): ?>
                                                        <div class="flex items-center text-xs">
                                                            <i class="fas fa-phone text-blue-500 mr-2"></i>
                                                            <span class="font-medium">Phone:</span>
                                                            <span class="ml-1"><?= esc($details['phone']) ?></span>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (isset($details['address'])): ?>
                                                        <div class="flex items-start text-xs">
                                                            <i class="fas fa-map-marker-alt text-red-500 mr-2 mt-0.5"></i>
                                                            <div>
                                                                <span class="font-medium">Address:</span><br>
                                                                <span class="text-gray-600"><?= esc($details['address']) ?></span>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="text-xs text-gray-600"><?= esc($claim['claim_details']) ?></div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-400 italic">No details provided</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <a href="<?= base_url('admin/winners/approve/' . $claim['id']) ?>"
                                            class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors"
                                            onclick="return confirm('Approve this claim? This action cannot be undone.')">
                                            <i class="fas fa-check mr-1"></i>
                                            Approve
                                        </a>
                                        <a href="<?= base_url('admin/winners/reject/' . $claim['id']) ?>"
                                            class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700 transition-colors"
                                            onclick="return confirm('Reject this claim? This action cannot be undone.')">
                                            <i class="fas fa-times mr-1"></i>
                                            Reject
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Approved Claims -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                Approved Claims (<?= count($approved_claims) ?>)
            </h2>
            <p class="text-gray-600 mt-1">Recently approved winner claims</p>
        </div>

        <?php if (empty($approved_claims)): ?>
            <div class="p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-trophy text-gray-400 text-4xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Approved Claims Yet</h3>
                <p class="text-gray-500">Approved winner claims will appear here</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Winner</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Draw Details</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prize</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($approved_claims as $claim): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold">
                                            <?= strtoupper(substr($claim['full_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900"><?= esc($claim['full_name']) ?></div>
                                            <div class="text-sm text-gray-500">@<?= esc($claim['username']) ?></div>
                                            <div class="text-xs text-gray-400"><?= esc($claim['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900"><?= esc($claim['draw_title'] ?? 'Unknown Draw') ?></div>
                                    <div class="text-sm">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?= $claim['draw_type'] === 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' ?>">
                                            <i class="fas fa-<?= $claim['draw_type'] === 'cash' ? 'dollar-sign' : 'gift' ?> mr-1"></i>
                                            <?= ucfirst($claim['draw_type']) ?> Draw
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Position: <?= getOrdinal($claim['position']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($claim['draw_type'] === 'cash'): ?>
                                        <div class="text-lg font-bold text-green-600">Rs. <?= number_format($claim['prize_amount'], 2) ?></div>
                                        <div class="text-xs text-gray-500">Cash Prize</div>
                                    <?php else: ?>
                                        <div class="text-lg font-bold text-blue-600">Product</div>
                                        <div class="text-xs text-gray-500">Physical Prize</div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        <?= date('M j, Y', strtotime($claim['approved_at'])) ?>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <?= date('g:i A', strtotime($claim['approved_at'])) ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>