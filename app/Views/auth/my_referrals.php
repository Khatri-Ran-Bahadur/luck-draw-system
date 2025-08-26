<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">My Referrals</h1>
            <p class="text-xl text-gray-600">People you've successfully referred</p>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Referrals</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $referral_stats['total_referrals'] ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Referrals</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $referral_stats['active_referrals'] ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i class="fas fa-money-bill-wave text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Earned</p>
                        <p class="text-2xl font-bold text-gray-900">Rs. <?= number_format($referral_stats['total_bonus_earned'], 2) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Referrals List -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100">
            <div class="px-8 py-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900">Your Referrals</h3>
                    <a href="<?= base_url('referral-stats') ?>" 
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700">
                        <i class="fas fa-chart-bar mr-2"></i>
                        View Full Stats
                    </a>
                </div>
            </div>

            <div class="p-8">
                <?php if (empty($referred_users)): ?>
                    <div class="text-center py-12">
                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-users text-gray-400 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No referrals yet</h3>
                        <p class="text-gray-500 mb-6">Start sharing your referral code to earn bonuses!</p>
                        
                        <!-- Referral Code Display -->
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl p-6 mb-6 max-w-md mx-auto">
                            <p class="text-white text-sm mb-2">Your referral code:</p>
                            <div class="bg-white rounded-lg p-4">
                                <span class="text-2xl font-bold text-gray-900 tracking-widest"><?= $user['referral_code'] ?></span>
                            </div>
                        </div>

                        <!-- Share Instructions -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 max-w-md mx-auto">
                            <h4 class="font-medium text-blue-800 mb-2">How to get referrals:</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Share your referral code on social media</li>
                                <li>• Send it to friends and family</li>
                                <li>• Post it in relevant online communities</li>
                                <li>• Include it in your email signature</li>
                            </ul>
                        </div>

                        <!-- Quick Share Buttons -->
                        <div class="mt-6 flex justify-center space-x-4">
                            <button onclick="shareOnWhatsApp()" 
                                    class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                                <i class="fab fa-whatsapp mr-2"></i>Share on WhatsApp
                            </button>
                            <button onclick="shareOnFacebook()" 
                                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fab fa-facebook mr-2"></i>Share on Facebook
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($referred_users as $referred_user): ?>
                            <div class="flex items-center justify-between p-6 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold text-lg">
                                            <?= strtoupper(substr($referred_user['username'], 0, 2)) ?>
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900"><?= esc($referred_user['full_name']) ?></h4>
                                        <p class="text-sm text-gray-500">@<?= esc($referred_user['username']) ?></p>
                                        <p class="text-xs text-gray-400">Joined <?= date('M j, Y', strtotime($referred_user['created_at'])) ?></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Active
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">Referral successful</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Referral Success Message -->
                    <div class="mt-8 bg-green-50 border border-green-200 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Great job!</h3>
                                <div class="mt-2 text-sm text-green-700">
                                    <p>You've successfully referred <?= count($referred_users) ?> people. Keep sharing your referral code to earn more bonuses!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Referral Tips -->
        <div class="mt-8 bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Tips for More Referrals</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-3">Social Media Strategy</h4>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            Post your referral code on Facebook, Twitter, and Instagram
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            Create engaging posts about your winnings
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            Use relevant hashtags to reach more people
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-3">Personal Network</h4>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            Share with friends and family directly
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            Send personalized messages explaining the benefits
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            Offer to help them get started
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 flex justify-center space-x-4">
            <a href="<?= base_url('referral-stats') ?>" 
               class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                <i class="fas fa-chart-bar mr-2"></i>
                View Full Statistics
            </a>
            
            <a href="<?= base_url('dashboard') ?>" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Dashboard
            </a>
        </div>
    </div>
</div>

<script>
function shareOnWhatsApp() {
    const text = `Join me on Lucky Draw System and get amazing prizes! Use my referral code: <?= $user['referral_code'] ?>\n\nSign up here: <?= base_url('referral/' . $user['referral_code']) ?>`;
    const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
    window.open(url, '_blank');
}

function shareOnFacebook() {
    const url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent('<?= base_url('referral/' . $user['referral_code']) ?>')}`;
    window.open(url, '_blank');
}
</script>

<?= $this->endSection() ?>
