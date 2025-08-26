<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">My Referral Dashboard</h1>
            <p class="text-xl text-gray-600">Track your referrals and earnings</p>
        </div>

        <!-- Referral Code Section -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 mb-8">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Your Referral Code</h2>
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl p-6 mb-6">
                    <p class="text-white text-sm mb-2">Share this code with friends to earn bonuses</p>
                    <div class="bg-white rounded-lg p-4 inline-block">
                        <span class="text-3xl font-bold text-gray-900 tracking-widest"><?= $user['referral_code'] ?></span>
                    </div>
                </div>
                
                <!-- Referral Link -->
                <div class="mb-6">
                    <p class="text-gray-600 mb-3">Your referral link:</p>
                    <div class="flex items-center justify-center space-x-2">
                        <input type="text" 
                               value="<?= base_url('referral/' . $user['referral_code']) ?>" 
                               readonly
                               class="flex-1 max-w-md px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 text-sm">
                        <button onclick="copyReferralLink()" 
                                class="px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-copy mr-2"></i>Copy
                        </button>
                    </div>
                </div>

                <!-- Share Buttons -->
                <div class="flex justify-center space-x-4">
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
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Completed</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $referral_stats['completed_referrals'] ?></p>
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

        <!-- Referred Users List -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100">
            <div class="px-8 py-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">People You've Referred</h3>
            </div>

            <div class="p-8">
                <?php if (empty($referred_users)): ?>
                    <div class="text-center py-12">
                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-users text-gray-400 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No referrals yet</h3>
                        <p class="text-gray-500 mb-6">Start sharing your referral code to earn bonuses!</p>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 max-w-md mx-auto">
                            <h4 class="font-medium text-blue-800 mb-2">How to get referrals:</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Share your referral code on social media</li>
                                <li>• Send it to friends and family</li>
                                <li>• Post it in relevant online communities</li>
                                <li>• Include it in your email signature</li>
                            </ul>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($referred_users as $referred_user): ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
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
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- How It Works -->
        <div class="mt-8 bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">How the Referral System Works</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-share-alt text-blue-600 text-xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">1. Share Your Code</h4>
                    <p class="text-gray-600">Share your unique referral code with friends, family, and on social media</p>
                </div>
                <div class="text-center">
                    <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-user-plus text-green-600 text-xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">2. They Register</h4>
                    <p class="text-gray-600">When someone uses your code to register, they become your referral</p>
                </div>
                <div class="text-center">
                    <div class="mx-auto w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-gift text-purple-600 text-xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">3. Earn Bonuses</h4>
                    <p class="text-gray-600">You earn referral bonuses that are added directly to your wallet</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 flex justify-center space-x-4">
            <a href="<?= base_url('dashboard') ?>" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Dashboard
            </a>
            
            <a href="<?= base_url('wallet') ?>" 
               class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                <i class="fas fa-wallet mr-2"></i>
                View Wallet
            </a>
        </div>
    </div>
</div>

<script>
function copyReferralLink() {
    const linkInput = document.querySelector('input[readonly]');
    linkInput.select();
    document.execCommand('copy');
    
    // Show feedback
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
    button.classList.add('bg-green-600');
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.classList.remove('bg-green-600');
    }, 2000);
}

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
