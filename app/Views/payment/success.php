<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-200">
            <!-- Success Icon -->
            <div class="w-20 h-20 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-6">
                <i class="fas fa-check text-4xl text-green-600"></i>
            </div>

            <!-- Success Message -->
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Payment Successful!</h2>
                <p class="text-gray-600">Your payment has been processed successfully.</p>
            </div>

            <!-- Transaction Details -->
            <div class="space-y-4 mb-8">
                <div class="flex justify-between py-3 border-b border-gray-200">
                    <span class="text-gray-600">Amount</span>
                    <span class="font-medium text-gray-900">
                        Rs. <?= number_format($transaction['amount'], 2) ?>
                    </span>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-200">
                    <span class="text-gray-600">Payment Method</span>
                    <span class="font-medium text-gray-900">
                        <?php if ($transaction['payment_method'] === 'paypal'): ?>
                            <i class="fab fa-paypal text-blue-600 mr-1"></i> PayPal
                        <?php else: ?>
                            <i class="fas fa-mobile-alt text-green-600 mr-1"></i> EasyPaisa
                        <?php endif; ?>
                    </span>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-200">
                    <span class="text-gray-600">Transaction ID</span>
                    <span class="font-medium text-gray-900"><?= $transaction['id'] ?></span>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-200">
                    <span class="text-gray-600">Date</span>
                    <span class="font-medium text-gray-900">
                        <?= date('M d, Y H:i', strtotime($transaction['created_at'])) ?>
                    </span>
                </div>
            </div>

            <?php if (isset($paymentId) && isset($payerId)): ?>
                <input type="hidden" id="paymentId" value="<?= $paymentId ?>">
                <input type="hidden" id="payerId" value="<?= $payerId ?>">
                <input type="hidden" id="transactionId" value="<?= $transaction['id'] ?>">

                <script>
                    // Process PayPal payment
                    fetch('<?= base_url('payment/process-paypal') ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                paymentId: document.getElementById('paymentId').value,
                                PayerID: document.getElementById('payerId').value,
                                transactionId: document.getElementById('transactionId').value
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                window.location.href = '<?= base_url('wallet') ?>?error=' + encodeURIComponent(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            window.location.href = '<?= base_url('wallet') ?>?error=Payment processing failed';
                        });
                </script>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="flex flex-col space-y-4">
                <a href="<?= base_url('wallet') ?>" class="w-full flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-wallet mr-2"></i>
                    Back to Wallet
                </a>
                <a href="<?= base_url('lucky-draw') ?>" class="w-full flex justify-center items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-dice mr-2"></i>
                    Join Lucky Draw
                </a>
            </div>
        </div>

        <!-- Support Info -->
        <div class="text-center mt-6">
            <p class="text-gray-600 text-sm">
                Having trouble? <a href="<?= base_url('contact') ?>" class="text-blue-600 hover:text-blue-800">Contact support</a>
            </p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>