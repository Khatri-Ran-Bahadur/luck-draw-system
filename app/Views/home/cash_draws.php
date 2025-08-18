<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">💰 Cash Lucky Draws</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Win real cash prizes! Enter our exciting cash draws and get a chance to win amazing cash rewards.
            </p>
        </div>

        <!-- Recent Winners Section -->
        <?php if (!empty($recent_winners)): ?>
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">🏆 Recent Cash Winners</h2>
                    <a href="<?= base_url('winners') ?>" class="text-blue-600 hover:text-blue-700 font-medium">
                        View All Winners <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($recent_winners as $winner): ?>
                        <div class="bg-gradient-to-r from-green-400 to-blue-500 rounded-xl p-6 text-white">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <i class="fas fa-trophy text-xl"></i>
                                </div>
                                <span class="text-sm opacity-90"><?= date('M d', strtotime($winner['created_at'])) ?></span>
                            </div>
                            <h4 class="text-lg font-semibold mb-2"><?= esc($winner['title']) ?></h4>
                            <p class="text-2xl font-bold mb-2">Rs. <?= number_format($winner['prize_amount'], 2) ?></p>
                            <p class="text-sm opacity-90">Winner: @<?= esc($winner['username']) ?></p>
                            <p class="text-xs opacity-75"><?= getOrdinal($winner['position']) ?> Place</p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Active Cash Draws -->
        <div class="mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Active Cash Draws</h2>

            <?php if (empty($cash_draws)): ?>
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-12 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-coins text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Active Cash Draws</h3>
                    <p class="text-gray-600 mb-6">Check back soon for exciting cash draw opportunities!</p>
                    <a href="<?= base_url('product-draws') ?>" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                        <i class="fas fa-gift mr-2"></i>
                        View Product Draws
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php foreach ($cash_draws as $draw): ?>
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <!-- Participant Count Badge -->
                            <div class="absolute top-3 left-3 z-10">
                                <div class="bg-green-500 text-white px-3 py-1 rounded-full shadow-md">
                                    <span class="text-xs font-bold">⭐ <?= number_format($draw['participant_count'] ?? 0) ?> joined</span>
                                </div>
                            </div>

                            <!-- Cash Prize Display -->
                            <div class="h-40 bg-gradient-to-br from-green-50 to-blue-50 flex items-center justify-center relative">
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-dollar-sign text-2xl text-white"></i>
                                    </div>
                                    <div class="text-2xl font-bold text-green-600">
                                        Rs. <?= number_format($draw['prize_amount'], 0) ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Draw Details -->
                            <div class="p-4">
                                <h3 class="text-lg font-bold text-gray-900 mb-2 text-center truncate">
                                    <?= esc($draw['title']) ?>
                                </h3>

                                <!-- Join Button -->
                                <?php
                                // Check if user has already entered this draw
                                $userEntry = null;
                                if (session()->get('user_id')) {
                                    $entryModel = new \App\Models\EntryModel();
                                    $userEntry = $entryModel->checkUserCashDrawEntry(session()->get('user_id'), $draw['id']);
                                }
                                ?>

                                <?php if ($userEntry): ?>
                                    <!-- Already entered -->
                                    <button class="w-full bg-green-100 text-green-800 font-semibold py-3 px-6 rounded-xl cursor-not-allowed" disabled>
                                        <i class="fas fa-check-circle mr-2"></i>Already Entered
                                    </button>
                                <?php else: ?>
                                    <!-- Not entered yet -->
                                    <a href="<?= base_url('cash-draw/' . $draw['id']) ?>" class="block w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold py-3 px-6 rounded-xl text-center transition-all duration-200 transform hover:-translate-y-1">
                                        <i class="fas fa-dollar-sign mr-2"></i>Join Cash Draw
                                    </a>
                                <?php endif; ?>

                                <!-- Quick Info -->
                                <div class="mt-3 text-xs text-gray-600 text-center">
                                    <?= $draw['total_winners'] ?> winner<?= $draw['total_winners'] > 1 ? 's' : '' ?> • <?= date('M d', strtotime($draw['draw_date'])) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Completed Draws with Winners -->
        <?php if (!empty($completed_draws)): ?>
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Recently Completed Cash Draws</h2>

                <div class="space-y-4">
                    <?php foreach (array_slice($completed_draws, 0, 5) as $draw): ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-900"><?= esc($draw['title']) ?></h4>
                                    <p class="text-sm text-gray-600">Prize: Rs. <?= number_format($draw['prize_amount'], 2) ?> • Winners: <?= $draw['winner_count'] ?></p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-500"><?= date('M d, Y', strtotime($draw['draw_date'])) ?></div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Completed
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-6 text-center">
                    <a href="<?= base_url('winners') ?>" class="text-blue-600 hover:text-blue-700 font-medium">
                        View All Winners <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Join Cash Draw Function
    async function joinCashDraw(drawId, entryFee) {
        try {
            const response = await fetch(`<?= base_url('cash-draw/enter/') ?>${drawId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    draw_id: drawId,
                    entry_fee: entryFee
                })
            });

            const result = await response.json();

            if (result.success) {
                // Show success message
                alert('🎉 Successfully joined the cash draw! Good luck!');
                // Reload page to update participant count
                location.reload();
            } else {
                // Show error message
                alert('❌ ' + (result.message || 'Failed to join draw. Please try again.'));
            }
        } catch (error) {
            console.error('Error joining draw:', error);
            alert('❌ An error occurred. Please try again.');
        }
    }

    // Countdown Timer Function
    function updateCountdowns() {
        document.querySelectorAll('.countdown-timer').forEach(function(timer) {
            const drawDate = new Date(timer.dataset.drawDate).getTime();
            const now = new Date().getTime();
            const distance = drawDate - now;

            if (distance > 0) {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                timer.querySelector('.days').textContent = String(days).padStart(2, '0');
                timer.querySelector('.hours').textContent = String(hours).padStart(2, '0');
                timer.querySelector('.minutes').textContent = String(minutes).padStart(2, '0');
                timer.querySelector('.seconds').textContent = String(seconds).padStart(2, '0');
            } else {
                timer.innerHTML = '<span class="text-red-600 font-bold">Draw Ended</span>';
            }
        });
    }

    // Update countdowns every second
    setInterval(updateCountdowns, 1000);
    updateCountdowns(); // Initial call

    // Helper function for ordinal numbers
    function getOrdinal(n) {
        const s = ["th", "st", "nd", "rd"];
        const v = n % 100;
        return n + (s[(v - 20) % 10] || s[v] || s[0]);
    }
</script>

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