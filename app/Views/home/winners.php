<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-pink-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-16">
            <div class="w-24 h-24 bg-gradient-to-r from-purple-500 to-pink-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-2xl">
                <i class="fas fa-trophy text-4xl text-white"></i>
            </div>
            <h1 class="text-5xl font-bold text-gray-900 mb-6">Our Lucky Winners</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Congratulations to all our lucky winners! See who has won amazing prizes in our lucky draws.</p>
        </div>

        <!-- Winners List -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Recent Winners</h2>

            <?php if (empty($winners)): ?>
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-trophy text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No Winners Yet</h3>
                    <p class="text-gray-500">Be the first to win! Join our lucky draws today.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($winners as $winner): ?>
                        <div class="bg-white rounded-2xl shadow-xl p-6 card-hover border border-gray-100">
                            <div class="text-center mb-6">
                                <div class="w-20 h-20 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                                    <i class="fas fa-crown text-2xl text-white"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2"><?= esc($winner['full_name'] ?: $winner['username']) ?></h3>
                                <p class="text-gray-600 text-sm">@<?= esc($winner['username']) ?></p>
                            </div>

                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 text-sm">Draw:</span>
                                    <span class="font-medium text-gray-900 text-right"><?= esc($winner['title']) ?></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 text-sm">Prize:</span>
                                    <?php if (isset($winner['prize_amount'])): ?>
                                        <span class="font-bold text-green-600">Rs. <?= number_format($winner['prize_amount'], 2) ?></span>
                                    <?php elseif (isset($winner['product_name'])): ?>
                                        <span class="font-bold text-purple-600"><?= esc($winner['product_name']) ?></span>
                                    <?php else: ?>
                                        <span class="font-bold text-blue-600">Prize</span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 text-sm">Position:</span>
                                    <span class="font-medium text-gray-900"><?= getOrdinal($winner['position']) ?> Place</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 text-sm">Won On:</span>
                                    <span class="font-medium text-gray-900"><?= date('M d, Y', strtotime($winner['created_at'])) ?></span>
                                </div>
                            </div>

                            <div class="text-center">
                                <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-star mr-2"></i>
                                    Winner!
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Separate sections for cash and product winners -->
                <div class="mt-16 grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Cash Winners -->
                    <?php if (!empty($cash_winners)): ?>
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">Cash Draw Winners</h3>
                            </div>
                            <div class="space-y-4">
                                <?php foreach (array_slice($cash_winners, 0, 5) as $winner): ?>
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">@<?= esc($winner['username']) ?></h4>
                                            <p class="text-sm text-gray-600"><?= esc($winner['title']) ?></p>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-bold text-green-600">Rs. <?= number_format($winner['prize_amount'], 2) ?></div>
                                            <div class="text-xs text-gray-500"><?= date('M d', strtotime($winner['created_at'])) ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Product Winners -->
                    <?php if (!empty($product_winners)): ?>
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-gift text-purple-600 text-xl"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">Product Draw Winners</h3>
                            </div>
                            <div class="space-y-4">
                                <?php foreach (array_slice($product_winners, 0, 5) as $winner): ?>
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">@<?= esc($winner['username']) ?></h4>
                                            <p class="text-sm text-gray-600"><?= esc($winner['title']) ?></p>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-bold text-purple-600"><?= esc($winner['product_name']) ?></div>
                                            <div class="text-xs text-gray-500">Rs. <?= number_format($winner['product_price'], 2) ?> value</div>
                                            <div class="text-xs text-gray-500"><?= date('M d', strtotime($winner['created_at'])) ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- CTA Section -->
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Want to Be a Winner Too?</h2>
            <p class="text-xl text-gray-600 mb-8">Join our lucky draws and you could be the next winner!</p>
            <?php if (session()->get('user_id')): ?>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/cash-draws" class="btn-primary text-lg px-10 py-4 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600">
                        <i class="fas fa-dollar-sign mr-3"></i>Join Cash Draw
                    </a>
                    <a href="/product-draws" class="btn-primary text-lg px-10 py-4 bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600">
                        <i class="fas fa-gift mr-3"></i>Join Product Draw
                    </a>
                </div>
            <?php else: ?>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/register" class="btn-primary text-lg px-10 py-4 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600">
                        <i class="fas fa-user-plus mr-3"></i>Get Started
                    </a>
                    <a href="/login" class="btn-primary text-lg px-10 py-4 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800">
                        <i class="fas fa-sign-in-alt mr-3"></i>Login
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Background decoration -->
<div class="fixed inset-0 -z-10 overflow-hidden">
    <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-purple-200 to-pink-200 rounded-full opacity-20 blur-3xl"></div>
    <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-pink-200 to-red-200 rounded-full opacity-20 blur-3xl"></div>
</div>

<?php
// Helper function for ordinal numbers
function getOrdinal($number)
{
    $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
    if ((($number % 100) >= 11) && (($number % 100) <= 13))
        return $number . 'th';
    else
        return $number . $ends[$number % 10];
}
?>

<?= $this->endSection() ?>