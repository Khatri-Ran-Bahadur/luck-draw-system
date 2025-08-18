<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-pink-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="w-24 h-24 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-2xl">
                <i class="fas fa-trophy text-4xl text-white"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">🎉 Congratulations! You Won!</h1>
            <p class="text-xl text-gray-600">Please provide your details to claim your prize</p>
        </div>

        <!-- Winner Details Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 mb-8">
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Prize Information -->
                    <div class="space-y-4">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Prize Details</h2>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Draw:</span>
                                <span class="font-semibold text-gray-900"><?= esc($winner['draw_title'] ?? 'Unknown Draw') ?></span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Position:</span>
                                <span class="font-bold text-purple-600"><?= getOrdinal($winner['position']) ?> Place</span>
                            </div>
                            
                            <?php if ($winner['draw_type'] === 'cash'): ?>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Prize Amount:</span>
                                    <span class="text-2xl font-bold text-green-600">Rs. <?= number_format($winner['prize_amount'], 2) ?></span>
                                </div>
                            <?php else: ?>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Product:</span>
                                    <span class="font-semibold text-blue-600"><?= esc($winner['product_name'] ?? 'Product') ?></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Value:</span>
                                    <span class="font-semibold text-blue-600">Rs. <?= number_format($winner['prize_amount'], 2) ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Won On:</span>
                                <span class="font-medium text-gray-900"><?= date('M d, Y', strtotime($winner['created_at'])) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Status Information -->
                    <div class="space-y-4">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Claim Status</h2>
                        
                        <?php if ($winner['is_claimed']): ?>
                            <?php if ($winner['claim_approved']): ?>
                                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                                        <div>
                                            <h3 class="font-semibold text-green-800">Claim Approved!</h3>
                                            <p class="text-green-600 text-sm">Your prize will be processed soon.</p>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock text-yellow-500 text-xl mr-3"></i>
                                        <div>
                                            <h3 class="font-semibold text-yellow-800">Claim Submitted</h3>
                                            <p class="text-yellow-600 text-sm">Waiting for admin approval.</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <div class="flex items-center">
                                    <i class="fas fa-info-circle text-blue-500 text-xl mr-3"></i>
                                    <div>
                                        <h3 class="font-semibold text-blue-800">Ready to Claim</h3>
                                        <p class="text-blue-600 text-sm">Fill out the form below to claim your prize.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!$winner['is_claimed']): ?>
            <!-- Claim Form -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Claim Your Prize</h2>
                    
                    <form action="<?= base_url('lucky-draw/claim/' . $winner['id']) ?>" method="post" class="space-y-6">
                        <?= csrf_field() ?>
                        
                        <!-- Contact Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-2">
                                    WhatsApp Number <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="whatsapp" id="whatsapp" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all"
                                    placeholder="+92 300 1234567"
                                    value="<?= esc($winner['claim_details']['whatsapp'] ?? '') ?>">
                                <p class="text-sm text-gray-500 mt-1">We'll contact you on WhatsApp for prize delivery</p>
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Phone Number
                                </label>
                                <input type="tel" name="phone" id="phone"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all"
                                    placeholder="+92 300 1234567"
                                    value="<?= esc($winner['claim_details']['phone'] ?? '') ?>">
                                <p class="text-sm text-gray-500 mt-1">Alternative contact number</p>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Delivery Address <span class="text-red-500">*</span>
                            </label>
                            <textarea name="address" id="address" rows="4" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all"
                                placeholder="Enter your complete delivery address including city, postal code, etc."><?= esc($winner['claim_details']['address'] ?? '') ?></textarea>
                            <p class="text-sm text-gray-500 mt-1">We'll deliver your prize to this address</p>
                        </div>

                        <!-- Additional Information -->
                        <div>
                            <label for="additional_info" class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Information
                            </label>
                            <textarea name="additional_info" id="additional_info" rows="3"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all"
                                placeholder="Any special delivery instructions or additional information..."><?= esc($winner['claim_details']['additional_info'] ?? '') ?></textarea>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="flex items-start space-x-3">
                                <input type="hidden" name="terms_accepted" value="0">
                                <input type="checkbox" name="terms_accepted" id="terms_accepted" value="1" required
                                    class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <div>
                                    <label for="terms_accepted" class="text-sm text-gray-700">
                                        I agree to the <a href="<?= base_url('terms') ?>" target="_blank" class="text-blue-600 hover:text-blue-800 underline">Terms and Conditions</a>
                                        and confirm that all information provided is accurate.
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-center">
                            <button type="submit"
                                class="bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-bold py-4 px-8 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <i class="fas fa-trophy mr-2"></i>
                                Claim My Prize
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- Information Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mt-8">
            <div class="flex items-start space-x-3">
                <i class="fas fa-info-circle text-blue-500 text-xl mt-1"></i>
                <div>
                    <h3 class="font-semibold text-blue-800 mb-2">What happens next?</h3>
                    <ul class="text-blue-700 space-y-1 text-sm">
                        <li>• Submit your claim with accurate contact details</li>
                        <li>• Our team will review and verify your information</li>
                        <li>• You'll receive a confirmation on WhatsApp</li>
                        <li>• For cash prizes: Money will be sent to your wallet</li>
                        <li>• For products: We'll arrange delivery to your address</li>
                        <li>• Processing usually takes 24-48 hours</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const whatsappInput = document.getElementById('whatsapp');
    const addressInput = document.getElementById('address');
    const termsCheckbox = document.getElementById('terms_accepted');
    
    // Real-time validation
    function validateField(input, minLength = 0) {
        const value = input.value.trim();
        const isValid = value.length >= minLength;
        
        if (input === termsCheckbox) {
            // For checkbox, check if it's checked
            return termsCheckbox.checked;
        }
        
        if (input.required && !isValid) {
            input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-100');
            input.classList.remove('border-gray-200', 'focus:border-blue-500', 'focus:ring-blue-100');
        } else {
            input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-100');
            input.classList.add('border-gray-200', 'focus:border-blue-500', 'focus:ring-blue-100');
        }
        
        return isValid;
    }
    
    // Add event listeners for real-time validation
    whatsappInput.addEventListener('input', () => validateField(whatsappInput, 10));
    addressInput.addEventListener('input', () => validateField(addressInput, 10));
    termsCheckbox.addEventListener('change', () => validateField(termsCheckbox));
    
    // Form submission validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validate all required fields
        if (!validateField(whatsappInput, 10)) {
            isValid = false;
        }
        
        if (!validateField(addressInput, 10)) {
            isValid = false;
        }
        
        if (!validateField(termsCheckbox)) {
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill all required fields correctly before submitting.');
            return false;
        }
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...';
    });
});
</script>

<?php
// Helper function to get ordinal suffix
function getOrdinal($n)
{
    if ($n >= 11 && $n <= 13) {
        return $n . 'th';
    }
    switch ($n % 10) {
        case 1:
            return $n . 'st';
        case 2:
            return $n . 'nd';
        case 3:
            return $n . 'rd';
        default:
            return $n . 'th';
    }
}
?>

<?= $this->endSection() ?>