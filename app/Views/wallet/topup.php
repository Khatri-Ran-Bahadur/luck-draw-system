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
                <h1 class="text-3xl font-bold text-gray-900">Top Up Wallet</h1>
            </div>
            <p class="text-gray-600">Add money to your wallet using PayPal or Easypaisa</p>
        </div>

        <!-- Error Messages -->
        <?php if (isset($error) && $error): ?>
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex items-center space-x-3">
                    <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-red-800 font-medium"><?= esc($error) ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Success Messages -->
        <?php if (isset($success) && $success): ?>
            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
                <div class="flex items-center space-x-3">
                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check-circle text-green-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-green-800 font-medium"><?= esc($success) ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Topup Form -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
            <div class="px-8 py-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Payment Details</h2>
            </div>

            <form action="<?= base_url('wallet/topup') ?>" method="post" class="p-8 space-y-8" id="topupForm">
                <?= csrf_field() ?>

                <!-- Amount Selection -->
                <div class="space-y-4">
                    <label class="block text-lg font-semibold text-gray-900">Amount to Add (PKR)</label>

                    <!-- PayPal Conversion Notice -->
                    <div id="paypalConversionNotice" class="hidden bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-blue-800 text-sm">
                                    <strong>PayPal Conversion:</strong>
                                    <span id="conversionText">Rs. 500 PKR = $1.77 USD</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <label class="relative">
                            <input type="radio" name="amount" value="500" class="sr-only peer" id="amount-500">
                            <div class="p-4 border-2 border-gray-200 rounded-xl text-center cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-gray-300 transition-all">
                                <span class="text-2xl font-bold text-gray-900">Rs. 500</span>
                                <p class="text-sm text-gray-600 mt-1">Quick Topup</p>
                            </div>
                        </label>

                        <label class="relative">
                            <input type="radio" name="amount" value="1000" class="sr-only peer" id="amount-1000">
                            <div class="p-4 border-2 border-gray-200 rounded-xl text-center cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-gray-300 transition-all">
                                <span class="text-2xl font-bold text-gray-900">Rs. 1,000</span>
                                <p class="text-sm text-gray-600 mt-1">Popular</p>
                            </div>
                        </label>

                        <label class="relative">
                            <input type="radio" name="amount" value="1500" class="sr-only peer" id="amount-1500">
                            <div class="p-4 border-2 border-gray-200 rounded-xl text-center cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-gray-300 transition-all">
                                <span class="text-2xl font-bold text-gray-900">Rs. 1,500</span>
                                <p class="text-sm text-gray-600 mt-1">Value</p>
                            </div>
                        </label>

                        <label class="relative">
                            <input type="radio" name="amount" value="2000" class="sr-only peer" id="amount-2000">
                            <div class="p-4 border-2 border-gray-200 rounded-xl text-center cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-gray-300 transition-all">
                                <span class="text-2xl font-bold text-gray-900">Rs. 2,000</span>
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
                            <input type="number" name="custom_amount" id="custom_amount" min="500" step="100"
                                class="pl-16 w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 text-lg"
                                placeholder="0">
                        </div>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Minimum amount: Rs. 500</p>
                            <p class="text-xs text-blue-600" id="paypalConversionInfo">PayPal: Rs. 500 = $1.77 USD</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Method Selection -->
                <div class="space-y-4">
                    <label class="block text-lg font-semibold text-gray-900">Payment Method</label>

                    <!-- PayPal Option -->
                    <label class="relative">
                        <input type="radio" name="payment_method" value="paypal" class="sr-only peer" id="paypal-method" required>
                        <div class="p-6 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-gray-300 transition-all">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <i class="fab fa-paypal text-blue-600 text-2xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">PayPal</h3>
                                    <p class="text-gray-600">Secure payment with PayPal account or credit card</p>
                                    <div class="flex items-center mt-2 space-x-4 text-sm text-gray-500">
                                        <span><i class="fas fa-shield-alt mr-1"></i>Secure</span>
                                        <span><i class="fas fa-globe mr-1"></i>International</span>
                                        <span><i class="fas fa-credit-card mr-1"></i>Cards Accepted</span>
                                    </div>
                                    <div class="mt-2 text-sm text-blue-600">
                                        <i class="fas fa-info-circle mr-1"></i>Minimum: Rs. 500 PKR
                                    </div>
                                    <div class="mt-1 text-xs text-gray-500">
                                        <i class="fas fa-exchange-alt mr-1"></i>Exchange rate: 1 USD = Rs. <?= $currencyService->getPayPalExchangeRate() ?> PKR
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
                        <input type="radio" name="payment_method" value="easypaisa" class="sr-only peer" id="easypaisa-method" required>
                        <div class="p-6 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-gray-300 transition-all">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-mobile-alt text-green-600 text-2xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">Easypaisa</h3>
                                    <p class="text-gray-600">Quick mobile payment for Pakistan users</p>
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

                <!-- Security Notice -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-shield-alt text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-blue-900 mb-2">Secure Payment</h4>
                            <ul class="text-sm text-blue-800 space-y-1">
                                <li>• All payments are processed in Pakistani Rupees (PKR)</li>
                                <li>• We never store your payment information</li>
                                <li>• Instant wallet credit after successful payment</li>
                                <li>• 24/7 customer support available</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="<?= base_url('wallet') ?>" class="px-8 py-4 border-2 border-gray-300 text-lg font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                        Cancel
                    </a>
                    <button type="submit" id="submitBtn" class="px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                        <i class="fas fa-credit-card mr-3"></i>
                        <span id="submitText">Proceed to Payment</span>
                    </button>
                </div>

                <!-- Loading Overlay -->
                <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
                    <div class="bg-white rounded-2xl p-8 text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-spinner fa-spin text-blue-600 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Processing Payment</h3>
                        <p class="text-gray-600">Please wait while we redirect you to the payment gateway...</p>
                    </div>
                </div>

            </form>

            <!-- Note: All amounts are in PKR - No currency conversion needed -->
        </div>


    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const amountInputs = document.querySelectorAll('input[name="amount"]');
        const customAmountInput = document.getElementById('custom_amount');
        const paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');
        const form = document.getElementById('topupForm');
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const paypalConversionNotice = document.getElementById('paypalConversionNotice');
        const conversionText = document.getElementById('conversionText');
        const paypalConversionInfo = document.getElementById('paypalConversionInfo');

        // Get exchange rate from PHP
        const exchangeRate = <?= $currencyService->getPayPalExchangeRate() ?>;

        // Update PayPal conversion display
        function updatePayPalConversion() {
            const customAmount = document.getElementById('custom_amount').value;
            const checkedAmount = document.querySelector('input[name="amount"]:checked');

            let amount = 0;
            if (checkedAmount) {
                amount = parseFloat(checkedAmount.value);
            } else if (customAmount) {
                amount = parseFloat(customAmount);
            }

            if (amount >= 500) {
                const usdAmount = (amount / exchangeRate).toFixed(2);
                document.getElementById('conversionText').textContent = `Rs. ${amount.toLocaleString()} PKR = $${usdAmount} USD`;
                document.getElementById('paypalConversionInfo').textContent = `PayPal: Rs. ${amount.toLocaleString()} PKR = $${usdAmount} USD`;
            }
        }

        // Handle amount selection
        amountInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (this.checked) {
                    customAmountInput.value = '';
                    customAmountInput.classList.remove('border-red-500', 'ring-red-100');
                    customAmountInput.classList.add('border-gray-200');
                    updatePayPalConversion();
                }
            });
        });

        // Handle custom amount input
        customAmountInput.addEventListener('input', function() {
            if (this.value) {
                // Uncheck all radio buttons
                amountInputs.forEach(input => input.checked = false);

                // Remove error styling
                this.classList.remove('border-red-500', 'ring-red-100');
                this.classList.add('border-gray-200');
                updatePayPalConversion();
            }
        });

        // Handle payment method selection
        paymentMethodInputs.forEach(input => {
            input.addEventListener('change', function() {
                // Remove selected styling from all payment method containers
                <?= $this->extend('layouts/main') ?>
                document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
                    const container = radio.closest('label').querySelector('div');
                    container.classList.remove('border-red-500', 'bg-red-50', 'border-blue-500', 'bg-blue-50');
                    container.classList.add('border-gray-200');
                });

                // Add selected styling to the checked one
                if (this.checked) {
                    const container = this.closest('label').querySelector('div');
                    container.classList.remove('border-gray-200');
                    container.classList.add('border-blue-500', 'bg-blue-50');
                }
            });
        });

        // Form validation
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            let isValid = true;
            let selectedAmount = null;
            let selectedPaymentMethod = null;

            // Check amount selection
            const checkedAmount = document.querySelector('input[name="amount"]:checked');
            const customAmount = customAmountInput.value.trim();

            if (!checkedAmount && !customAmount) {
                isValid = false;
                showError('Please select an amount or enter a custom amount');

                // Highlight custom amount field
                if (!customAmount) {
                    customAmountInput.classList.remove('border-gray-200');
                    customAmountInput.classList.add('border-red-500', 'ring-red-100');
                }
            } else if (checkedAmount) {
                selectedAmount = checkedAmount.value;
            } else if (customAmount) {
                const amount = parseFloat(customAmount);
                if (isNaN(amount) || amount < 500) {
                    isValid = false;
                    showError('Custom amount must be at least Rs. 500');
                    customAmountInput.classList.remove('border-gray-200');
                    customAmountInput.classList.add('border-red-500', 'ring-red-100');
                } else {
                    selectedAmount = customAmount;
                }
            }

            // Check payment method
            const checkedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (!checkedPaymentMethod) {
                isValid = false;
                showError('Please select a payment method');

                // Highlight payment method containers
                paymentMethodInputs.forEach(radio => {
                    const container = radio.closest('label').querySelector('div');
                    container.classList.remove('border-gray-200');
                    container.classList.add('border-red-500', 'bg-red-50');
                });
            } else {
                selectedPaymentMethod = checkedPaymentMethod.value;
            }

            if (isValid && selectedAmount && selectedPaymentMethod) {
                console.log('Form validation passed:', {
                    selectedAmount,
                    selectedPaymentMethod
                });

                // Create hidden input for the final amount
                let amountInput = form.querySelector('input[name="final_amount"]');
                if (!amountInput) {
                    amountInput = document.createElement('input');
                    amountInput.type = 'hidden';
                    amountInput.name = 'final_amount';
                    form.appendChild(amountInput);
                }
                amountInput.value = selectedAmount;

                console.log('Submitting form with data:', {
                    amount: selectedAmount,
                    payment_method: selectedPaymentMethod,
                    final_amount: amountInput.value
                });

                // Show loading overlay
                loadingOverlay.classList.remove('hidden');
                submitBtn.disabled = true;
                submitText.textContent = 'Processing...';

                // Submit the form
                form.submit();
            } else {
                console.log('Form validation failed:', {
                    isValid,
                    selectedAmount,
                    selectedPaymentMethod
                });
            }
        });

        function showError(message) {
            // Remove existing error messages
            const existingError = document.querySelector('.error-message');
            if (existingError) {
                existingError.remove();
            }

            // Create and show error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message bg-red-50 border border-red-200 rounded-xl p-4 mt-4';
            errorDiv.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-600 text-sm"></i>
                </div>
                <p class="text-red-800 font-medium">${message}</p>
            </div>
        `;

            form.insertBefore(errorDiv, form.querySelector('.flex.justify-end'));

            // Auto-remove error after 5 seconds
            setTimeout(() => {
                if (errorDiv.parentNode) {
                    errorDiv.remove();
                }
            }, 5000);
        }

        // Auto-remove error styling when user starts typing/selecting
        customAmountInput.addEventListener('focus', function() {
            this.classList.remove('border-red-500', 'ring-red-100');
            this.classList.add('border-gray-200');
        });

        // Initial call to update conversion display on page load
        updatePayPalConversion();

    });
</script>
<?= $this->endSection() ?>