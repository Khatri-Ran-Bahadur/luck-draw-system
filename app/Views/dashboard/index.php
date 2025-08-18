<?= $this->extend('layouts/main') ?>

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
<!-- Recent Activity -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-100">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900">Recent Activity</h2>
    </div>
    <div class="p-6">
        <?php if (empty($recent_transactions)): ?>
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-history text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-500">No recent activity</p>
            </div>
        <?php else: ?>
            <div class="space-y-4">
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
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- My Winnings Section -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-100">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-900">My Winnings</h2>
            <a href="<?= base_url('my-winnings') ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                View All →
            </a>
        </div>
    </div>
    <div class="p-6">
        <?php
        // Get user's recent winnings (limit to 3 for dashboard)
        $recentWinnings = array_slice($winnings ?? [], 0, 3);
        ?>

        <?php if (empty($recentWinnings)): ?>
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-trophy text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-500 mb-4">No winnings yet</p>
                <a href="<?= base_url('cash-draws') ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all">
                    <i class="fas fa-play mr-2"></i>Join Draws
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($recentWinnings as $winning): ?>
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
            </div>

            <?php if (count($winnings ?? []) > 3): ?>
                <div class="text-center mt-4">
                    <a href="<?= base_url('my-winnings') ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-sm font-medium rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all">
                        <i class="fas fa-trophy mr-2"></i>View All Winnings
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>