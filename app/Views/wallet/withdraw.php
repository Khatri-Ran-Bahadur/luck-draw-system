<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="<?= base_url('wallet') ?>" class="text-blue-600 hover:text-blue-700 mr-4">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Withdraw Funds</h1>
            </div>
            <p class="text-gray-600">Request a withdrawal from your wallet to your preferred payment method</p>
        </div>

        <!-- Withdrawal Form -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
            <div class="px-8 py-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Withdrawal Request</h2>
            </div>

            <form action="<?= base_url('wallet/withdraw') ?>" method="post" class="p-8 space-y-8">
                <?= csrf_field() ?>

                <!-- Current Balance -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-blue-900">Available Balance</h3>
                            <p class="text-3xl font-bold text-blue-600">Rs. <?= number_format($wallet['balance'], 2) ?></p>
                        </div>
                        <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-wallet text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Amount Selection -->
                <div class="space-y-4">
                    <label class="block text-lg font-semibold text-gray-900">Withdrawal Amount</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <label class="relative">
                            <input type="radio" name="amount" value="10" class="sr-only peer" required>
                            <div class="p-4 border-2 border-gray-200 rounded-xl text-center cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-gray-300 transition-all">
                                <span class="text-2xl font-bold text-gray-900">Rs. 10</span>
                                <p class="text-sm text-gray-600 mt-1">Minimum</p>
                            </div>
                        </label>

                        <label class="relative">
                            <input type="radio" name="amount" value="25" class="sr-only peer" required>
                            <div class="p-4 border-2 border-gray-200 rounded-xl text-center cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-gray-300 transition-all">
                                <span class="text-2xl font-bold text-gray-900">Rs. 25</span>
                                <p class="text-sm text-gray-600 mt-1">Popular</p>
                            </div>
                        </label>

                        <label class="relative">
                            <input type="radio" name="amount" value="50" class="sr-only peer" required>
                            <div class="p-4 border-2 border-gray-200 rounded-xl text-center cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-gray-300 transition-all">
                                <span class="text-2xl font-bold text-gray-900">Rs. 50</span>
                                <p class="text-sm text-gray-600 mt-1">Value</p>
                            </div>
                        </label>

                        <label class="relative">
                            <input type="radio" name="amount" value="100" class="sr-only peer" required>
                            <div class="p-4 border-2 border-gray-200 rounded-xl text-center cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-gray-300 transition-all">
                                <span class="text-2xl font-bold text-gray-900">Rs. 100</span>
                                <p class="text-sm text-gray-600 mt-1">Premium</p>
                            </div>
                        </label>
                    </div>

                    <!-- Custom Amount -->
                    <div class="mt-6">
                        <label for="custom_amount" class="block text-sm font-medium text-gray-700 mb-2">Or enter custom amount</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-lg font-medium">Rs.</span>
                            </div>
                            <input type="number" name="custom_amount" id="custom_amount" min="10" max="<?= $wallet['balance'] ?>" step="0.01"
                                class="pl-12 w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 text-lg"
                                placeholder="0.00">
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Minimum: Rs. 10.00 | Maximum: Rs. <?= number_format($wallet['balance'], 2) ?></p>
                    </div>
                </div>

                <!-- Withdrawal Method Selection -->
                <div class="space-y-4">
                    <label class="block text-lg font-semibold text-gray-900">Withdrawal Method</label>

                    <!-- PayPal Option -->
                    <label class="relative">
                        <input type="radio" name="withdrawal_method" value="paypal" class="sr-only peer" required>
                        <div class="p-6 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-gray-300 transition-all">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <i class="fab fa-paypal text-blue-600 text-2xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">PayPal</h3>
                                    <p class="text-gray-600">Withdraw to your PayPal account (International)</p>
                                    <div class="flex items-center mt-2 space-x-4 text-sm text-gray-500">
                                        <span><i class="fas fa-globe mr-1"></i>International</span>
                                        <span><i class="fas fa-bolt mr-1"></i>Instant</span>
                                        <span><i class="fas fa-shield-alt mr-1"></i>Secure</span>
                                    </div>
                                </div>
                                <div class="w-6 h-6 border-2 border-gray-300 rounded-full peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center">
                                    <div class="w-3 h-3 bg-white rounded-full peer-checked:block hidden"></div>
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- Easypaisa Option -->
                    <label class="relative">
                        <input type="radio" name="withdrawal_method" value="easypaisa" class="sr-only peer" required>
                        <div class="p-6 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-gray-300 transition-all">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-mobile-alt text-green-600 text-2xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">Easypaisa</h3>
                                    <p class="text-gray-600">Withdraw to your Easypaisa mobile wallet (Pakistan)</p>
                                    <div class="flex items-center mt-2 space-x-4 text-sm text-gray-500">
                                        <span><i class="fas fa-mobile-alt mr-1"></i>Mobile</span>
                                        <span><i class="fas fa-bolt mr-1"></i>Instant</span>
                                        <span><i class="fas fa-map-marker-alt mr-1"></i>Pakistan</span>
                                    </div>
                                </div>
                                <div class="w-6 h-6 border-2 border-gray-300 rounded-full peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center">
                                    <div class="w-3 h-3 bg-white rounded-full peer-checked:block hidden"></div>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>

                <!-- Account Details -->
                <div class="space-y-4">
                    <label class="block text-lg font-semibold text-gray-900">Account Details</label>
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="space-y-4">
                            <div>
                                <label for="account_details" class="block text-sm font-medium text-gray-700 mb-2">PayPal Email / Easypaisa Number</label>
                                <input type="text" name="account_details" id="account_details" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 text-lg"
                                    placeholder="Enter your PayPal email or Easypaisa mobile number">
                                <p class="mt-2 text-sm text-gray-500">For PayPal: your email address | For Easypaisa: your mobile number</p>
                            </div>

                            <div>
                                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name (as registered)</label>
                                <input type="text" name="full_name" id="full_name" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 text-lg"
                                    placeholder="Enter your full name as registered with the payment method">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms and Processing Time -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-info-circle text-yellow-600 text-sm"></i>
                        </div>
                        <div class="text-sm text-yellow-800">
                            <h4 class="font-semibold mb-2">Important Information</h4>
                            <ul class="space-y-1">
                                <li>• Minimum withdrawal amount: Rs. 10.00</li>
                                <li>• Processing time: 24-48 hours (business days)</li>
                                <li>• Withdrawal requests are reviewed by admin for security</li>
                                <li>• Ensure account details match your payment method registration</li>
                                <li>• Processing fees may apply depending on the withdrawal method</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="<?= base_url('wallet') ?>" class="px-8 py-4 border-2 border-gray-300 text-lg font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                        <i class="fas fa-arrow-down mr-3"></i>
                        Submit Withdrawal Request
                    </button>
                </div>
            </form>
        </div>

        <!-- Recent Withdrawals -->
        <div class="mt-8 bg-white rounded-2xl shadow-lg border border-gray-100">
            <div class="px-8 py-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">Recent Withdrawal Requests</h3>
            </div>

            <div class="p-8">
                <?php if (empty($withdrawal_history)): ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-history text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500">No withdrawal requests yet</p>
                        <p class="text-gray-400 mt-2">Your withdrawal history will appear here</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($withdrawal_history as $withdrawal): ?>
                            <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 <?= $withdrawal['status'] === 'completed' ? 'bg-green-100' : ($withdrawal['status'] === 'pending' ? 'bg-yellow-100' : 'bg-red-100') ?> rounded-xl flex items-center justify-center">
                                            <i class="fas <?= $withdrawal['status'] === 'completed' ? 'fa-check text-green-600' : ($withdrawal['status'] === 'pending' ? 'fa-clock text-yellow-600' : 'fa-times text-red-600') ?> text-lg"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900">Rs. <?= number_format(abs($withdrawal['amount']), 2) ?></h4>
                                            <p class="text-sm text-gray-600"><?= ucfirst($withdrawal['payment_method']) ?> Withdrawal</p>
                                            <p class="text-xs text-gray-500"><?= date('M d, Y \a\t H:i', strtotime($withdrawal['created_at'])) ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= $withdrawal['status'] === 'completed' ? 'bg-green-100 text-green-800' : ($withdrawal['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                            <?= ucfirst($withdrawal['status']) ?>
                                        </span>
                                        <?php if ($withdrawal['status'] === 'pending'): ?>
                                            <p class="text-xs text-gray-500 mt-1">Processing...</p>
                                        <?php elseif ($withdrawal['status'] === 'completed'): ?>
                                            <p class="text-xs text-green-600 mt-1">Sent successfully</p>
                                        <?php elseif ($withdrawal['status'] === 'failed'): ?>
                                            <p class="text-xs text-red-600 mt-1">Rejected</p>
                                            <?php if (strpos($withdrawal['payment_reference'], 'Rejected:') === 0): ?>
                                                <p class="text-xs text-gray-500 mt-1"><?= esc(substr($withdrawal['payment_reference'], 10)) ?></p>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if ($withdrawal['metadata']): ?>
                                    <?php $metadata = json_decode($withdrawal['metadata'], true); ?>
                                    <?php if (isset($metadata['account_details'])): ?>
                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-600">Account:</span>
                                                <span class="text-gray-900 font-medium"><?= esc($metadata['account_details']) ?></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-6 text-center">
                        <a href="<?= base_url('wallet/transactions') ?>" class="text-blue-600 hover:text-blue-700 font-medium">
                            View All Transactions <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const amountInputs = document.querySelectorAll('input[name="amount"]');
        const customAmountInput = document.getElementById('custom_amount');
        const withdrawalMethodInputs = document.querySelectorAll('input[name="withdrawal_method"]');
        const accountDetailsInput = document.getElementById('account_details');

        // Handle amount selection
        amountInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (this.checked) {
                    customAmountInput.value = '';
                }
            });
        });

        // Handle custom amount input
        customAmountInput.addEventListener('input', function() {
            if (this.value) {
                amountInputs.forEach(input => input.checked = false);
            }
        });

        // Handle withdrawal method selection
        withdrawalMethodInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (this.checked) {
                    updateAccountDetailsPlaceholder(this.value);
                }
            });
        });

        function updateAccountDetailsPlaceholder(method) {
            if (method === 'paypal') {
                accountDetailsInput.placeholder = 'Enter your PayPal email address';
            } else if (method === 'easypaisa') {
                accountDetailsInput.placeholder = 'Enter your Easypaisa mobile number';
            }
        }

        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const selectedAmount = document.querySelector('input[name="amount"]:checked');
            const customAmount = customAmountInput.value;
            const withdrawalMethod = document.querySelector('input[name="withdrawal_method"]:checked');
            const accountDetails = accountDetailsInput.value;
            const fullName = document.getElementById('full_name').value;

            if (!selectedAmount && !customAmount) {
                e.preventDefault();
                alert('Please select an amount or enter a custom amount');
                return false;
            }

            if (!withdrawalMethod) {
                e.preventDefault();
                alert('Please select a withdrawal method');
                return false;
            }

            if (!accountDetails) {
                e.preventDefault();
                alert('Please enter your account details');
                return false;
            }

            if (!fullName) {
                e.preventDefault();
                alert('Please enter your full name');
                return false;
            }

            const amount = customAmount || selectedAmount.value;
            if (parseFloat(amount) < 10) {
                e.preventDefault();
                alert('Minimum withdrawal amount is Rs. 10.00');
                return false;
            }

            if (parseFloat(amount) > <?= $wallet['balance'] ?>) {
                e.preventDefault();
                alert('Withdrawal amount cannot exceed your available balance');
                return false;
            }

            return true;
        });
    });
</script>
<?= $this->endSection() ?>