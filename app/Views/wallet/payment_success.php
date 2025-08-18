<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check-circle text-green-600 text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Payment Successful!</h1>
            <p class="text-gray-600 mt-2">Your wallet has been topped up successfully</p>
        </div>

        <!-- Success Details -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-8">
            <div class="text-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Transaction Details</h2>
                <p class="text-gray-600">Your payment has been processed successfully</p>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-gray-600">Amount Added:</span>
                    <span class="text-2xl font-bold text-green-600">Rs. <?= number_format($amount, 2) ?></span>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-gray-600">Payment Method:</span>
                    <span class="text-gray-900"><?= ucfirst($payment_method) ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Transaction ID:</span>
                    <span class="text-sm text-gray-500 font-mono"><?= $transaction_id ?></span>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-start space-x-3">
                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-info-circle text-blue-600 text-sm"></i>
                    </div>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">What happens next?</p>
                        <ul class="space-y-1">
                            <li>• Your wallet balance has been updated instantly</li>
                            <li>• You can now participate in lucky draws</li>
                            <li>• Transaction details are available in your wallet history</li>
                            <li>• A confirmation email has been sent to your account</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <a href="<?= base_url('wallet') ?>" class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-wallet text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">View Wallet</h3>
                <p class="text-gray-600 text-sm">Check your updated wallet balance and transaction history</p>
            </a>

            <a href="<?= base_url('dashboard') ?>" class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow text-center">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-dice text-green-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Join Lucky Draws</h3>
                <p class="text-gray-600 text-sm">Start participating in exciting lucky draws with your topped up wallet</p>
            </a>
        </div>

        <!-- Transaction Summary -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 text-center">Transaction Summary</h3>

            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <span class="text-gray-600">Payment Amount:</span>
                    <span class="font-semibold text-gray-900">Rs. <?= number_format($amount, 2) ?></span>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <span class="text-gray-600">Payment Method:</span>
                    <span class="font-semibold text-gray-900"><?= ucfirst($payment_method) ?></span>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <span class="text-gray-600">Transaction ID:</span>
                    <span class="font-semibold text-gray-900 text-sm font-mono"><?= $transaction_id ?></span>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <span class="text-gray-600">Status:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check mr-1"></i>
                        Completed
                    </span>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <span class="text-gray-600">Date & Time:</span>
                    <span class="font-semibold text-gray-900"><?= date('M j, Y g:i A') ?></span>
                </div>
            </div>
        </div>

        <!-- Back to Wallet Button -->
        <div class="text-center mt-8">
            <a href="<?= base_url('wallet') ?>" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                <i class="fas fa-wallet mr-3"></i>
                Back to Wallet
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>