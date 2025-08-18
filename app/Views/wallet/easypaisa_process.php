<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-mobile-alt text-green-600 text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Easypaisa Payment</h1>
            <p class="text-gray-600 mt-2">Complete your wallet topup via mobile payment</p>
        </div>

        <!-- Payment Details -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-8">
            <div class="text-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Payment Summary</h2>
                <p class="text-gray-600">Review your payment details before proceeding</p>
            </div>

            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-gray-600">Amount:</span>
                    <span class="text-2xl font-bold text-gray-900">Rs. <?= number_format($amount, 2) ?></span>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-gray-600">Payment Method:</span>
                    <span class="text-gray-900">Easypaisa</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Transaction ID:</span>
                    <span class="text-sm text-gray-500 font-mono"><?= $transaction_id ?></span>
                </div>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                <div class="flex items-start space-x-3">
                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-info-circle text-green-600 text-sm"></i>
                    </div>
                    <div class="text-sm text-green-800">
                        <p class="font-semibold mb-1">Demo Mode</p>
                        <p>This is a demonstration of the Easypaisa payment flow. In production, you would be redirected to the actual Easypaisa payment page.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Easypaisa Payment Form -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <div class="text-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Easypaisa Checkout</h3>
                <p class="text-gray-600">Enter your mobile number to receive payment request</p>
            </div>

            <!-- Payment Form -->
            <div class="space-y-4 mb-6">
                <div class="bg-gray-50 rounded-xl p-4">
                    <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-2">Mobile Number</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-lg font-medium">+92</span>
                        </div>
                        <input type="tel" id="mobile_number" placeholder="300 1234567"
                            class="pl-16 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            pattern="[0-9]{10}" maxlength="10">
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Enter your 10-digit mobile number (without country code)</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Type</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative">
                            <input type="radio" name="payment_type" value="easypaisa" class="sr-only peer" checked>
                            <div class="p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 hover:border-gray-300 transition-all">
                                <i class="fas fa-mobile-alt text-green-600 text-lg mb-1"></i>
                                <p class="text-sm font-medium">Easypaisa</p>
                            </div>
                        </label>

                        <label class="relative">
                            <input type="radio" name="payment_type" value="jazzcash" class="sr-only peer">
                            <div class="p-3 border-2 border-gray-200 rounded-lg text-center cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 hover:border-gray-300 transition-all">
                                <i class="fas fa-mobile-alt text-blue-600 text-lg mb-1"></i>
                                <p class="text-sm font-medium">JazzCash</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" id="full_name" placeholder="Enter your full name"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>

            <!-- Payment Button -->
            <button onclick="sendPaymentRequest()" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-4 px-6 rounded-xl transition-colors duration-200 mb-4">
                <i class="fas fa-mobile-alt mr-2"></i>
                <span id="payment-button-text">Send Payment Request</span>
            </button>

            <div class="text-center">
                <a href="<?= base_url('wallet/easypaisa/cancel') ?>" class="text-gray-500 hover:text-gray-700 text-sm">
                    Cancel Payment
                </a>
            </div>
        </div>

        <!-- How It Works -->
        <div class="mt-8 bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 text-center">How Easypaisa Works</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-green-600">1</span>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-2">Enter Number</h4>
                    <p class="text-sm text-gray-600">Provide your mobile number registered with Easypaisa</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-green-600">2</span>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-2">Receive SMS</h4>
                    <p class="text-sm text-gray-600">You'll get an SMS with payment request details</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-green-600">3</span>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-2">Confirm Payment</h4>
                    <p class="text-sm text-gray-600">Reply to SMS to confirm and complete payment</p>
                </div>
            </div>
        </div>

        <!-- Security Features -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-shield-alt text-green-600 text-xl"></i>
                </div>
                <h4 class="font-semibold text-gray-900 mb-1">Secure</h4>
                <p class="text-sm text-gray-600">SMS verification</p>
            </div>

            <div class="text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-mobile-alt text-blue-600 text-xl"></i>
                </div>
                <h4 class="font-semibold text-gray-900 mb-1">Mobile</h4>
                <p class="text-sm text-gray-600">No bank account needed</p>
            </div>

            <div class="text-center">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-bolt text-purple-600 text-xl"></i>
                </div>
                <h4 class="font-semibold text-gray-900 mb-1">Instant</h4>
                <p class="text-sm text-gray-600">Quick processing</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-fill demo data
        document.getElementById('mobile_number').value = '3001234567';
        document.getElementById('full_name').value = 'John Doe';

        // Handle payment type selection
        const paymentTypes = document.querySelectorAll('input[name="payment_type"]');
        paymentTypes.forEach(type => {
            type.addEventListener('change', function() {
                // Update button text based on selection
                const buttonText = document.getElementById('payment-button-text');
                if (this.value === 'easypaisa') {
                    buttonText.textContent = 'Send Easypaisa Request';
                } else {
                    buttonText.textContent = 'Send JazzCash Request';
                }
            });
        });
    });

    function sendPaymentRequest() {
        const mobileNumber = document.getElementById('mobile_number').value.trim();
        const fullName = document.getElementById('full_name').value.trim();
        const paymentType = document.querySelector('input[name="payment_type"]:checked').value;

        // Validation
        if (!mobileNumber || mobileNumber.length !== 10) {
            showError('Please enter a valid 10-digit mobile number');
            return;
        }

        if (!fullName) {
            showError('Please enter your full name');
            return;
        }

        // Show loading state
        const button = event.target;
        const buttonText = document.getElementById('payment-button-text');
        const originalText = buttonText.textContent;
        buttonText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending Request...';
        button.disabled = true;

        // Simulate payment request
        setTimeout(() => {
            // Show success message
            buttonText.innerHTML = '<i class="fas fa-check mr-2"></i>Request Sent!';
            button.classList.remove('bg-green-600', 'hover:bg-green-700');
            button.classList.add('bg-green-500');

            // Show payment instructions
            showPaymentInstructions(mobileNumber, paymentType);
        }, 2000);
    }

    function showPaymentInstructions(mobileNumber, paymentType) {
        const instructions = `
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-mobile-alt text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Payment Request Sent!</h3>
                    <p class="text-gray-600">Check your mobile for payment instructions</p>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-4 mb-6">
                    <div class="text-sm text-gray-600 space-y-2">
                        <p><strong>Mobile:</strong> +92 ${mobileNumber}</p>
                                <p><strong>Amount:</strong> Rs. <?= number_format($amount, 2) ?></p>
                        <p><strong>Service:</strong> ${paymentType === 'easypaisa' ? 'Easypaisa' : 'JazzCash'}</p>
                    </div>
                    <p class="mt-3 text-sm text-gray-600">You should receive an SMS shortly with payment details.</p>
                    <p class="text-sm text-gray-600">Reply to the SMS to confirm your payment.</p>
                </div>
                
                <div class="space-y-3">
                    <button onclick="completePayment()" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-xl transition-colors">
                        Complete Payment
                    </button>
                    <button onclick="closeInstructions()" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-4 rounded-xl transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    `;

        document.body.insertAdjacentHTML('beforeend', instructions);
    }

    function completePayment() {
        // Simulate payment completion
        setTimeout(() => {
            window.location.href = '<?= base_url('wallet/easypaisa/success') ?>';
        }, 1000);
    }

    function closeInstructions() {
        document.querySelector('.fixed').remove();
    }

    function showError(message) {
        // Remove existing error messages
        const existingError = document.querySelector('.payment-error');
        if (existingError) {
            existingError.remove();
        }

        // Create and show error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'payment-error bg-red-50 border border-red-200 rounded-xl p-4 mt-4';
        errorDiv.innerHTML = `
        <div class="flex items-center space-x-3">
            <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-600 text-sm"></i>
            </div>
            <p class="text-red-800 font-medium">${message}</p>
        </div>
    `;

        // Insert error before the payment button
        const paymentButton = document.querySelector('button[onclick="sendPaymentRequest()"]');
        paymentButton.parentNode.insertBefore(errorDiv, paymentButton);

        // Auto-remove error after 5 seconds
        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.remove();
            }
        }, 5000);
    }
</script>
<?= $this->endSection() ?>