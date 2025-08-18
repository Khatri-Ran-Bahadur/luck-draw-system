<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Join Lucky Draw</h1>
            <p class="text-lg text-gray-600">Complete your entry by selecting a payment method</p>
        </div>

        <!-- Lucky Draw Details -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-2"><?= esc($draw['title']) ?></h2>
                <p class="text-gray-600"><?= esc($draw['description']) ?></p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="text-center">
                    <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-calendar text-blue-600 text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900">Draw Date</h4>
                    <p class="text-gray-600"><?= date('M d, Y', strtotime($draw['draw_date'])) ?></p>
                </div>
                <div class="text-center">
                    <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900">Entry Fee</h4>
                    <p class="text-gray-600">$<?= number_format($draw['entry_fee'], 2) ?></p>
                </div>
                <div class="text-center">
                    <div class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-users text-purple-600 text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900">Max Entries</h4>
                    <p class="text-gray-600"><?= $draw['max_entries'] ?: 'Unlimited' ?></p>
                </div>
            </div>
        </div>

        <!-- Payment Method Selection -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Select Payment Method</h3>
            
            <form id="paymentForm" class="space-y-6">
                <input type="hidden" name="draw_id" value="<?= $draw['id'] ?>">
                <input type="hidden" name="amount" value="<?= $draw['entry_fee'] ?>">
                
                <!-- Payment Methods -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="payment-method-option">
                        <input type="radio" id="easypaisa" name="payment_method" value="easypaisa" class="sr-only" required>
                        <label for="easypaisa" class="block cursor-pointer">
                            <div class="border-2 border-gray-200 rounded-lg p-6 hover:border-blue-500 transition duration-300 payment-method-card">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-mobile-alt text-4xl text-green-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-semibold text-gray-900">EasyPaisa</h4>
                                        <p class="text-gray-600">Mobile payment solution</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                    
                    <div class="payment-method-option">
                        <input type="radio" id="paypal" name="payment_method" value="paypal" class="sr-only" required>
                        <label for="paypal" class="block cursor-pointer">
                            <div class="border-2 border-gray-200 rounded-lg p-6 hover:border-blue-500 transition duration-300 payment-method-card">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fab fa-paypal text-4xl text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-semibold text-gray-900">PayPal</h4>
                                        <p class="text-gray-600">Secure online payments</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Payment Summary</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Lucky Draw Entry</span>
                            <span class="text-gray-900">$<?= number_format($draw['entry_fee'], 2) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Processing Fee</span>
                            <span class="text-gray-900">$0.00</span>
                        </div>
                        <hr class="border-gray-300">
                        <div class="flex justify-between">
                            <span class="text-lg font-semibold text-gray-900">Total Amount</span>
                            <span class="text-lg font-semibold text-gray-900">$<?= number_format($draw['entry_fee'], 2) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-start">
                    <input id="terms" name="terms" type="checkbox" required 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1">
                    <label for="terms" class="ml-2 text-sm text-gray-700">
                        I agree to the
                        <a href="/terms" class="text-blue-600 hover:text-blue-500">Terms & Conditions</a>
                        and understand that this is a non-refundable entry fee.
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" id="submitBtn" 
                            class="btn-primary text-lg px-8 py-3 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-credit-card mr-2"></i>
                        Proceed to Payment
                    </button>
                </div>
            </form>
        </div>

        <!-- Important Notes -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mt-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Important Information</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Entry fees are non-refundable once payment is completed</li>
                            <li>You can only enter once per lucky draw</li>
                            <li>Winners will be notified via email and on the website</li>
                            <li>Draw results are final and binding</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.payment-method-option input[type="radio"]:checked + label .payment-method-card {
    border-color: #3b82f6;
    background-color: #eff6ff;
}

.payment-method-card:hover {
    border-color: #3b82f6;
    background-color: #f8fafc;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('paymentForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Handle payment method selection
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            // Remove active class from all cards
            document.querySelectorAll('.payment-method-card').forEach(card => {
                card.classList.remove('border-blue-500', 'bg-blue-50');
            });
            
            // Add active class to selected card
            if (this.checked) {
                const card = this.closest('.payment-method-option').querySelector('.payment-method-card');
                card.classList.add('border-blue-500', 'bg-blue-50');
            }
        });
    });
    
    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const paymentMethod = formData.get('payment_method');
        
        if (!paymentMethod) {
            alert('Please select a payment method');
            return;
        }
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
        
        // Submit form data
        fetch('/lucky-draw/process-payment', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to payment gateway
                window.location.href = data.redirect_url;
            } else {
                alert(data.message || 'An error occurred. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-credit-card mr-2"></i>Proceed to Payment';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-credit-card mr-2"></i>Proceed to Payment';
        });
    });
});
</script>

<?= $this->endSection() ?>
