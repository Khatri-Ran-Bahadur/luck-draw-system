<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fab fa-paypal text-blue-600 text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">PayPal Payment</h1>
            <p class="text-gray-600 mt-2">Complete your wallet topup securely</p>
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
                    <span class="text-2xl font-bold text-gray-900">$<?= number_format($amount, 2) ?></span>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-gray-600">Payment Method:</span>
                    <span class="text-gray-900">PayPal</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Transaction ID:</span>
                    <span class="text-sm text-gray-500 font-mono"><?= $transaction_id ?></span>
                </div>
            </div>
            
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                <div class="flex items-start space-x-3">
                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-info-circle text-blue-600 text-sm"></i>
                    </div>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">Demo Mode</p>
                        <p>This is a demonstration of the PayPal payment flow. In production, you would be redirected to the actual PayPal payment page.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- PayPal Payment Form -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <div class="text-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">PayPal Checkout</h3>
                <p class="text-gray-600">Complete your payment to top up your wallet</p>
            </div>
            
            <!-- Payment Options -->
            <div class="space-y-4 mb-6">
                <div class="bg-gray-50 rounded-xl p-4">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="radio" name="payment_option" value="paypal_account" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" checked>
                        <div class="flex items-center space-x-3">
                            <i class="fab fa-paypal text-blue-600 text-xl"></i>
                            <span class="font-medium text-gray-900">Pay with PayPal Account</span>
                        </div>
                    </label>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-4">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="radio" name="payment_option" value="credit_card" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-credit-card text-gray-600 text-xl"></i>
                            <span class="font-medium text-gray-900">Pay with Credit/Debit Card</span>
                        </div>
                    </label>
                </div>
            </div>
            
            <!-- PayPal Account Form -->
            <div id="paypal-account-form" class="space-y-4 mb-6">
                <div class="bg-gray-50 rounded-xl p-4">
                    <label for="paypal_email" class="block text-sm font-medium text-gray-700 mb-2">PayPal Email Address</label>
                    <input type="email" id="paypal_email" placeholder="your-email@example.com" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="bg-gray-50 rounded-xl p-4">
                    <label for="paypal_password" class="block text-sm font-medium text-gray-700 mb-2">PayPal Password</label>
                    <input type="password" id="paypal_password" placeholder="Enter your PayPal password" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <!-- Credit Card Form -->
            <div id="credit-card-form" class="space-y-4 mb-6 hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <label for="card_number" class="block text-sm font-medium text-gray-700 mb-2">Card Number</label>
                        <input type="text" id="card_number" placeholder="1234 5678 9012 3456" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-4">
                        <label for="card_expiry" class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                        <input type="text" id="card_expiry" placeholder="MM/YY" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <label for="card_cvv" class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                        <input type="text" id="card_cvv" placeholder="123" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-4">
                        <label for="card_name" class="block text-sm font-medium text-gray-700 mb-2">Cardholder Name</label>
                        <input type="text" id="card_name" placeholder="John Doe" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>
            
            <!-- Payment Button -->
            <button onclick="processPayment()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-6 rounded-xl transition-colors duration-200 mb-4">
                <i class="fab fa-paypal mr-2"></i>
                <span id="payment-button-text">Pay with PayPal</span>
            </button>
            
            <div class="text-center">
                <a href="<?= base_url('wallet/paypal/cancel') ?>" class="text-gray-500 hover:text-gray-700 text-sm">
                    Cancel Payment
                </a>
            </div>
        </div>

        <!-- Security Features -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-shield-alt text-green-600 text-xl"></i>
                </div>
                <h4 class="font-semibold text-gray-900 mb-1">Secure</h4>
                <p class="text-sm text-gray-600">256-bit encryption</p>
            </div>
            
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-lock text-blue-600 text-xl"></i>
                </div>
                <h4 class="font-semibold text-gray-900 mb-1">Protected</h4>
                <p class="text-sm text-gray-600">Buyer protection</p>
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
    const paymentOptions = document.querySelectorAll('input[name="payment_option"]');
    const paypalForm = document.getElementById('paypal-account-form');
    const creditCardForm = document.getElementById('credit-card-form');
    const paymentButtonText = document.getElementById('payment-button-text');
    
    // Handle payment option selection
    paymentOptions.forEach(option => {
        option.addEventListener('change', function() {
            if (this.value === 'paypal_account') {
                paypalForm.classList.remove('hidden');
                creditCardForm.classList.add('hidden');
                paymentButtonText.textContent = 'Pay with PayPal';
            } else {
                paypalForm.classList.add('hidden');
                creditCardForm.classList.remove('hidden');
                paymentButtonText.textContent = 'Pay with Card';
            }
        });
    });
    
    // Auto-fill demo data
    document.getElementById('paypal_email').value = 'demo@example.com';
    document.getElementById('paypal_password').value = 'demo123';
    document.getElementById('card_number').value = '4111 1111 1111 1111';
    document.getElementById('card_expiry').value = '12/25';
    document.getElementById('card_cvv').value = '123';
    document.getElementById('card_name').value = 'John Doe';
});

function processPayment() {
    const selectedOption = document.querySelector('input[name="payment_option"]:checked');
    let isValid = true;
    let errorMessage = '';
    
    if (selectedOption.value === 'paypal_account') {
        const email = document.getElementById('paypal_email').value.trim();
        const password = document.getElementById('paypal_password').value.trim();
        
        if (!email || !password) {
            isValid = false;
            errorMessage = 'Please fill in all PayPal account fields';
        }
    } else {
        const cardNumber = document.getElementById('card_number').value.trim();
        const cardExpiry = document.getElementById('card_expiry').value.trim();
        const cardCvv = document.getElementById('card_cvv').value.trim();
        const cardName = document.getElementById('card_name').value.trim();
        
        if (!cardNumber || !cardExpiry || !cardCvv || !cardName) {
            isValid = false;
            errorMessage = 'Please fill in all credit card fields';
        }
    }
    
    if (!isValid) {
        showError(errorMessage);
        return;
    }
    
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing Payment...';
    button.disabled = true;
    
    // Simulate payment processing
    setTimeout(() => {
        // Redirect to success page
        window.location.href = '<?= base_url('wallet/paypal/success') ?>';
    }, 3000);
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
    const paymentButton = document.querySelector('button[onclick="processPayment()"]');
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
