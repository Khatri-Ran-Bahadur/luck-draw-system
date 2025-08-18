<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="<?= base_url('product-draws') ?>" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Product Draws
            </a>
        </div>

        <!-- Draw Header -->
        <div class="bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl shadow-lg p-8 text-white mb-8">
            <div class="flex items-center justify-between mb-6">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-gift text-2xl"></i>
                </div>
                <span class="bg-white bg-opacity-20 px-4 py-2 rounded-full text-sm font-medium">
                    Product Draw
                </span>
            </div>

            <h1 class="text-3xl font-bold mb-4"><?= esc($draw['title']) ?></h1>
            <p class="text-lg opacity-90 mb-6"><?= esc($draw['description']) ?></p>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold"><?= esc($draw['product_name']) ?></div>
                    <div class="text-sm opacity-80">Product Prize</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold">Rs. <?= number_format($draw['product_price'], 2) ?></div>
                    <div class="text-sm opacity-80">Product Value</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold"><?= $draw['participant_count'] ?? 0 ?></div>
                    <div class="text-sm opacity-80">Participants</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold">Rs. <?= number_format($draw['entry_fee'], 2) ?></div>
                    <div class="text-sm opacity-80">Entry Fee</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Product Image -->
                <?php if (!empty($draw['product_image'])): ?>
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                        <img src="<?= base_url($draw['product_image']) ?>"
                            alt="<?= esc($draw['product_name']) ?>"
                            class="w-full h-64 md:h-80 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2"><?= esc($draw['product_name']) ?></h3>
                            <p class="text-2xl font-bold text-purple-600">Worth Rs. <?= number_format($draw['product_price'], 2) ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Draw Status -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                    <?php if ($draw['status'] === 'active'): ?>
                        <div class="text-center mb-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Draw Status</h3>

                            <!-- Countdown Timer -->
                            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                                <div class="text-sm text-gray-600 mb-2">Time Remaining</div>
                                <div class="countdown-timer text-2xl font-bold text-red-600" data-draw-date="<?= $draw['draw_date'] ?>">
                                    <span class="days">00</span>d
                                    <span class="hours">00</span>h
                                    <span class="minutes">00</span>m
                                    <span class="seconds">00</span>s
                                </div>
                            </div>

                            <!-- Entry Status -->
                            <?php if (session()->get('user_id')): ?>
                                <?php if ($user_entry): ?>
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                                        <div class="flex items-center justify-center mb-4">
                                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-check text-green-600 text-xl"></i>
                                            </div>
                                        </div>
                                        <h4 class="text-lg font-semibold text-green-800 mb-2">You're Entered!</h4>
                                        <p class="text-green-700 mb-2">Entry Number: <strong><?= esc($user_entry['entry_number']) ?></strong></p>
                                        <p class="text-sm text-green-600">Good luck! Winners will be announced after the draw date.</p>
                                    </div>
                                <?php else: ?>
                                    <!-- Join Button Section -->
                                    <div class="mt-8">
                                        <?php if (isset($user_entry) && $user_entry): ?>
                                            <!-- User has already entered this draw -->
                                            <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                                                <div class="flex items-center justify-center space-x-3 mb-4">
                                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-check text-green-600 text-xl"></i>
                                                    </div>
                                                    <div>
                                                        <h3 class="text-lg font-semibold text-green-900">Already Entered!</h3>
                                                        <p class="text-green-600">You have successfully entered this draw</p>
                                                    </div>
                                                </div>

                                                <div class="bg-white rounded-lg p-4 mb-4">
                                                    <p class="text-sm text-gray-600 mb-2">Your Entry Details:</p>
                                                    <div class="flex items-center justify-center space-x-4 text-sm">
                                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                                                            <i class="fas fa-ticket mr-1"></i>Entry #<?= esc($user_entry['entry_number']) ?>
                                                        </span>
                                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full">
                                                            <i class="fas fa-calendar mr-1"></i><?= date('M j, Y', strtotime($user_entry['entry_date'])) ?>
                                                        </span>
                                                    </div>
                                                </div>

                                                <button class="w-full bg-gray-400 text-white font-semibold py-4 px-6 rounded-xl cursor-not-allowed" disabled>
                                                    <i class="fas fa-check-circle mr-2"></i>Already Entered
                                                </button>
                                            </div>
                                        <?php else: ?>
                                            <!-- User hasn't entered yet - show join button -->
                                            <?php if ($draw['status'] === 'active' && strtotime($draw['draw_date']) > time()): ?>
                                                <form action="<?= base_url('product-draw/enter/' . $draw['id']) ?>" method="post" class="space-y-4">
                                                    <?= csrf_field() ?>

                                                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                                                        <div class="flex items-center justify-between">
                                                            <div>
                                                                <h3 class="text-lg font-semibold text-blue-900">Ready to Join?</h3>
                                                                <p class="text-blue-600">Entry fee: Rs. <?= number_format($draw['entry_fee'], 2) ?></p>
                                                            </div>
                                                            <div class="text-right">
                                                                <p class="text-sm text-gray-600">Draw Date:</p>
                                                                <p class="font-semibold text-gray-900"><?= date('M j, Y', strtotime($draw['draw_date'])) ?></p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                                                        <i class="fas fa-ticket mr-2"></i>Join This Draw
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <!-- Draw is not active or has ended -->
                                                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 text-center">
                                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                                        <i class="fas fa-clock text-gray-400 text-xl"></i>
                                                    </div>
                                                    <h3 class="text-lg font-semibold text-gray-900">
                                                        <?= $draw['status'] !== 'active' ? 'Draw Not Active' : 'Draw Has Ended' ?>
                                                    </h3>
                                                    <p class="text-gray-600">
                                                        <?= $draw['status'] !== 'active' ? 'This draw is currently not available for entries.' : 'The entry period for this draw has ended.' ?>
                                                    </p>
                                                    <button class="w-full bg-gray-400 text-white font-semibold py-4 px-6 rounded-xl cursor-not-allowed mt-4" disabled>
                                                        <i class="fas fa-times mr-2"></i>Cannot Join
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Join This Draw</h4>
                                    <p class="text-gray-600 mb-4">Please login to enter this product draw</p>
                                    <a href="<?= base_url('login') ?>" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                                        <i class="fas fa-sign-in-alt mr-2"></i>
                                        Login to Enter
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($draw['status'] === 'completed'): ?>
                        <!-- Winners Display -->
                        <div class="text-center">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">🏆 Draw Completed - Winners Announced! 🏆</h3>

                            <?php if (!empty($winners)): ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php foreach ($winners as $winner): ?>
                                        <div class="bg-gradient-to-r from-purple-400 to-pink-500 rounded-xl p-6 text-white">
                                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <i class="fas fa-gift text-xl"></i>
                                            </div>
                                            <h4 class="text-lg font-semibold mb-2"><?= getOrdinal($winner['position']) ?> Place</h4>
                                            <p class="text-lg font-bold mb-2"><?= esc($draw['product_name']) ?></p>
                                            <p class="text-sm opacity-90">@<?= esc($winner['username']) ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-gray-600">Winners information not available.</p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-pause text-2xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-600 mb-2">Draw Not Active</h3>
                            <p class="text-gray-500">This draw is currently inactive.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Draw Details -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Draw Details</h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-600">Draw Date:</span>
                            <span class="font-semibold text-gray-900"><?= date('M d, Y \a\t H:i', strtotime($draw['draw_date'])) ?></span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-600">Product Prize:</span>
                            <span class="font-semibold text-purple-600"><?= esc($draw['product_name']) ?></span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-600">Product Value:</span>
                            <span class="font-semibold text-purple-600">Rs. <?= number_format($draw['product_price'], 2) ?></span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-600">Entry Fee:</span>
                            <span class="font-semibold text-gray-900">Rs. <?= number_format($draw['entry_fee'], 2) ?></span>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <span class="text-gray-600">Current Participants:</span>
                            <span class="font-semibold text-blue-600"><?= $draw['participant_count'] ?? 0 ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Recent Participants -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Recent Participants</h3>

                    <?php if (!empty($participants)): ?>
                        <div class="space-y-3">
                            <?php foreach ($participants as $participant): ?>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-purple-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">@<?= esc($participant['username']) ?></div>
                                            <div class="text-xs text-gray-500"><?= date('M d, H:i', strtotime($participant['created_at'])) ?></div>
                                        </div>
                                    </div>
                                    <div class="w-2 h-2 bg-purple-400 rounded-full"></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-users text-gray-400"></i>
                            </div>
                            <p class="text-gray-500 text-sm">No participants yet</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- How It Works -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">How It Works</h3>

                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                <span class="text-xs font-bold text-purple-600">1</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 text-sm">Enter the Draw</h4>
                                <p class="text-xs text-gray-600">Pay the entry fee from your wallet</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                <span class="text-xs font-bold text-purple-600">2</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 text-sm">Wait for Draw</h4>
                                <p class="text-xs text-gray-600">Winners selected on draw date</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                <span class="text-xs font-bold text-purple-600">3</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 text-sm">Win Products</h4>
                                <p class="text-xs text-gray-600">Products shipped to winners</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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