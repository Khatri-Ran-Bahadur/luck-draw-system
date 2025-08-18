<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-pink-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="w-24 h-24 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-2xl">
                <i class="fas fa-trophy text-4xl text-white"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">🏆 My Winnings</h1>
            <p class="text-xl text-gray-600">Track all your lucky draw prizes and claim status</p>
        </div>

        <!-- Statistics Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 mb-8">
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-trophy text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900"><?= count($winnings) ?></h3>
                        <p class="text-gray-600">Total Wins</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-dollar-sign text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-green-600">Rs. <?= number_format($total_winnings, 2) ?></h3>
                        <p class="text-gray-600">Total Value</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check-circle text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-purple-600">
                            <?= count(array_filter($winnings, function ($w) {
                                return $w['claim_approved'];
                            })) ?>
                        </h3>
                        <p class="text-gray-600">Approved Claims</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Winnings List -->
        <?php if (empty($winnings)): ?>
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-trophy text-gray-400 text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No Winnings Yet</h3>
                <p class="text-gray-500 mb-6">Start participating in lucky draws to win amazing prizes!</p>
                <a href="<?= base_url('cash-draws') ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all">
                    <i class="fas fa-play mr-2"></i>Join Draws
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($winnings as $winning): ?>
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 p-6 text-white text-center">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-crown text-white text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-bold"><?= getOrdinal($winning['position']) ?> Place</h3>
                            <p class="text-yellow-100 text-sm"><?= ucfirst($winning['draw_type']) ?> Draw</p>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 text-sm">Draw:</span>
                                    <span class="font-medium text-gray-900 text-right"><?= esc($winning['draw_title'] ?? 'Unknown') ?></span>
                                </div>

                                <?php if ($winning['draw_type'] === 'cash'): ?>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 text-sm">Prize:</span>
                                        <span class="font-bold text-green-600">Rs. <?= number_format($winning['prize_amount'], 2) ?></span>
                                    </div>
                                <?php else: ?>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 text-sm">Product:</span>
                                        <span class="font-semibold text-blue-600"><?= esc($winning['product_name'] ?? 'Product') ?></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 text-sm">Value:</span>
                                        <span class="font-semibold text-blue-600">Rs. <?= number_format($winning['prize_amount'], 2) ?></span>
                                    </div>
                                <?php endif; ?>

                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 text-sm">Won On:</span>
                                    <span class="font-medium text-gray-900"><?= date('M d, Y', strtotime($winning['created_at'])) ?></span>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="mb-4">
                                <?php if ($winning['is_claimed']): ?>
                                    <?php if ($winning['claim_approved']): ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Approved
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Pending Approval
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-gift mr-1"></i>Ready to Claim
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Action Button -->
                            <div class="text-center">
                                <?php if (!$winning['is_claimed']): ?>
                                    <a href="<?= base_url('winner/' . $winning['id']) ?>"
                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-sm font-medium rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all">
                                        <i class="fas fa-trophy mr-2"></i>Claim Prize
                                    </a>
                                <?php elseif ($winning['claim_approved']): ?>
                                    <span class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 text-sm font-medium rounded-lg">
                                        <i class="fas fa-check mr-2"></i>Processing
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-lg">
                                        <i class="fas fa-clock mr-2"></i>Under Review
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Information Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mt-8">
            <div class="flex items-start space-x-3">
                <i class="fas fa-info-circle text-blue-500 text-xl mt-1"></i>
                <div>
                    <h3 class="font-semibold text-blue-800 mb-2">How the claiming process works:</h3>
                    <ul class="text-blue-700 space-y-1 text-sm">
                        <li>• <strong>Ready to Claim:</strong> Click "Claim Prize" to submit your claim</li>
                        <li>• <strong>Pending Approval:</strong> Your claim is being reviewed by our team</li>
                        <li>• <strong>Approved:</strong> Your prize is being processed for delivery</li>
                        <li>• <strong>Cash Prizes:</strong> Will be added to your wallet after approval</li>
                        <li>• <strong>Product Prizes:</strong> Will be delivered to your address</li>
                    </ul>
                </div>
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