<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Manual Wallet Top-up</h1>
            <p class="text-gray-600">Upload payment proof to top up your wallet</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Top-up Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-upload text-blue-600 mr-3"></i>
                        Upload Payment Proof
                    </h2>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('wallet/manual-topup') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <!-- Amount Input -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Top-up Amount (PKR)
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                    Rs.
                                </span>
                                <input type="number" 
                                       id="amount" 
                                       name="amount" 
                                       step="0.01" 
                                       min="<?= $settingModel->getMinTopupAmount() ?>" 
                                       max="<?= $settingModel->getMaxTopupAmount() ?>"
                                       class="block w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter amount"
                                       required>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                Min: Rs. <?= number_format($settingModel->getMinTopupAmount(), 2) ?> | 
                                Max: Rs. <?= number_format($settingModel->getMaxTopupAmount(), 2) ?>
                            </p>
                        </div>

                        <!-- Payment Method Selection -->
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Method
                            </label>
                            <select id="payment_method" name="payment_method" class="block w-full py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Select payment method</option>
                                <?php if ($settingModel->getJazzCashEnabled()): ?>
                                    <option value="jazz_cash">Jazz Cash</option>
                                <?php endif; ?>
                                <?php if ($settingModel->getBankTransferEnabled()): ?>
                                    <option value="bank">Bank Transfer</option>
                                <?php endif; ?>
                                <?php if ($settingModel->getEasypaisaEnabled()): ?>
                                    <option value="easypaisa">Easypaisa</option>
                                <?php endif; ?>
                                <?php if ($settingModel->getPayPalEnabled()): ?>
                                    <option value="paypal">PayPal</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Payment Proof Upload -->
                        <div>
                            <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Proof (Slip/Screenshot)
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                                <input type="file" 
                                       id="payment_proof" 
                                       name="payment_proof" 
                                       accept="image/*,.pdf"
                                       class="hidden"
                                       required>
                                <label for="payment_proof" class="cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-lg font-medium text-gray-700 mb-2">Click to upload payment proof</p>
                                    <p class="text-sm text-gray-500">PNG, JPG, PDF up to 5MB</p>
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                Upload a clear screenshot or photo of your payment slip/receipt
                            </p>
                        </div>

                        <!-- Additional Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Notes (Optional)
                            </label>
                            <textarea id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      class="block w-full py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Any additional information about your payment..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Submit Top-up Request
                        </button>
                    </form>

                    <!-- Important Notice -->
                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-1">Important:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Your top-up request will be reviewed by our admin team</li>
                                    <li>Processing time: 1-24 hours (depending on payment method)</li>
                                    <li>Make sure your payment proof clearly shows the transaction details</li>
                                    <li>You'll receive a notification once your request is processed</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Random Wallet Details Sidebar -->
            <?php if ($settingModel->getRandomWalletDisplay() && !empty($randomWallets)): ?>
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-wallet text-green-600 mr-2"></i>
                        Send Money To
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Use any of these wallet details to send your payment:
                    </p>

                    <div class="space-y-4">
                        <?php foreach ($randomWallets as $wallet): ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                                    <?= ucfirst($wallet['wallet_type']) ?>
                                </span>
                                <button onclick="copyToClipboard('<?= $wallet['wallet_number'] ?>')" 
                                        class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-copy mr-1"></i>Copy
                                </button>
                            </div>
                            
                            <div class="space-y-2">
                                <div>
                                    <label class="text-xs font-medium text-gray-500">Wallet Name:</label>
                                    <p class="text-sm font-semibold text-gray-900"><?= esc($wallet['wallet_name']) ?></p>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-500">Wallet Number:</label>
                                    <p class="text-sm font-mono text-gray-900 bg-gray-50 px-2 py-1 rounded">
                                        <?= esc($wallet['wallet_number']) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-lightbulb text-green-600 mt-1 mr-2"></i>
                            <div class="text-xs text-green-800">
                                <p class="font-medium">Tip:</p>
                                <p>Send the exact amount you entered above to avoid delays in processing.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check mr-1"></i>Copied!';
        button.classList.add('text-green-600');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('text-green-600');
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        alert('Failed to copy to clipboard');
    });
}
</script>
<?= $this->endSection() ?>
