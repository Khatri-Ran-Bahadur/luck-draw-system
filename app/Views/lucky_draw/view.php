<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Draw Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 mb-8">
            <div class="text-center">
                <div class="w-16 h-16 <?= $draw['draw_type'] === 'cash' ? 'bg-green-100' : 'bg-blue-100' ?> rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas <?= $draw['draw_type'] === 'cash' ? 'fa-dollar-sign text-green-600' : 'fa-gift text-blue-600' ?> text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2"><?= esc($draw['title']) ?></h1>
                <p class="text-lg text-gray-600 mb-4"><?= esc($draw['description']) ?></p>

                <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium <?= $draw['status'] === 'active' ? 'bg-green-100 text-green-800' : ($draw['status'] === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') ?>">
                    <?= ucfirst($draw['status']) ?>
                </div>
            </div>
        </div>

        <!-- Draw Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Draw Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Type:</span>
                        <span class="font-medium"><?= ucfirst($draw['draw_type']) ?> Lucky Draw</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Entry Fee:</span>
                        <span class="font-semibold <?= $draw['draw_type'] === 'cash' ? 'text-green-600' : 'text-blue-600' ?>">Rs. <?= number_format($draw['entry_fee'], 2) ?></span>
                        <span class="font-semibold <?= $draw['draw_type'] === 'cash' ? 'text-green-600' : 'text-blue-600' ?>">Rs. <?= number_format($draw['entry_fee'], 2) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Winners:</span>
                        <span class="font-medium"><?= $draw['total_winners'] ?? 1 ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Draw Date:</span>
                        <span class="font-medium"><?= date('M d, Y H:i', strtotime($draw['draw_date'])) ?></span>
                    </div>
                </div>
            </div>

            <!-- User Entry Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Entry Status</h3>
                <?php if ($userEntry): ?>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check text-green-600 text-2xl"></i>
                        </div>
                        <p class="text-lg font-semibold text-green-600 mb-2">Entry Confirmed!</p>
                        <p class="text-gray-600">You have successfully entered this lucky draw</p>
                        <p class="text-sm text-gray-500 mt-2">Entry Date: <?= date('M d, Y', strtotime($userEntry['created_at'])) ?></p>
                    </div>
                <?php else: ?>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-ticket-alt text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-lg font-semibold text-gray-600 mb-2">Not Entered</p>
                        <p class="text-gray-600">You haven't entered this lucky draw yet</p>
                        <?php if ($draw['status'] === 'active'): ?>
                            <a href="<?= base_url('lucky-draw/enter/' . $draw['id']) ?>" class="inline-flex items-center mt-4 px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-medium hover:from-blue-700 hover:to-blue-800 transition-all">
                                <i class="fas fa-plus mr-2"></i>
                                Enter Now
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Winners Section (if draw is completed) -->
        <?php if ($draw['status'] === 'completed' && !empty($winners)): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">🏆 Winners Announced! 🏆</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($winners as $winner): ?>
                        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl p-6 text-white text-center">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-trophy text-2xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold mb-2"><?= getOrdinal($winner['position']) ?> Place</h4>

                            <?php if ($draw['draw_type'] === 'cash'): ?>
                                <p class="text-2xl font-bold mb-2">Rs. <?= number_format($winner['prize_amount'], 2) ?></p>
                            <?php else: ?>
                                <p class="text-lg font-semibold mb-2">Product Prize</p>
                            <?php endif; ?>

                            <p class="text-sm opacity-90">@<?= esc($winner['username']) ?></p>

                            <?php if ($winner['user_id'] == session()->get('user_id')): ?>
                                <?php if (!$winner['is_claimed']): ?>
                                    <a href="<?= base_url('lucky-draw/claim/' . $winner['id']) ?>" class="inline-flex items-center mt-4 px-4 py-2 bg-white bg-opacity-20 rounded-lg text-white hover:bg-opacity-30 transition-all">
                                        <i class="fas fa-gift mr-2"></i>
                                        Claim Prize
                                    </a>
                                <?php elseif (!$winner['claim_approved']): ?>
                                    <span class="inline-flex items-center mt-4 px-4 py-2 bg-yellow-500 bg-opacity-20 rounded-lg text-yellow-200">
                                        <i class="fas fa-clock mr-2"></i>
                                        Claim Pending
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center mt-4 px-4 py-2 bg-green-500 bg-opacity-20 rounded-lg text-green-200">
                                        <i class="fas fa-check mr-2"></i>
                                        Claim Approved
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php elseif ($draw['status'] === 'active'): ?>
            <!-- Active Draw Message -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clock text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-blue-800 mb-2">Draw in Progress</h3>
                <p class="text-blue-700">This lucky draw is still active. Winners will be announced after the draw date.</p>
                <p class="text-sm text-blue-600 mt-2">Draw Date: <?= date('M d, Y H:i', strtotime($draw['draw_date'])) ?></p>
            </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="flex justify-center space-x-4">
            <a href="<?= base_url('dashboard') ?>" class="px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Dashboard
            </a>

            <?php if ($draw['draw_type'] === 'cash'): ?>
                <a href="<?= base_url('lucky-draw/cash') ?>" class="px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors">
                    <i class="fas fa-dollar-sign mr-2"></i>
                    View Cash Draws
                </a>
            <?php else: ?>
                <a href="<?= base_url('lucky-draw/products') ?>" class="px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                    <i class="fas fa-gift mr-2"></i>
                    View Product Draws
                </a>
            <?php endif; ?>
        </div>
    </div>
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