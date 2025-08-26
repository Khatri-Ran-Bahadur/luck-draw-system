<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Referral System Settings</h1>
        <p class="text-gray-600">Configure referral bonus amounts and system parameters</p>
    </div>

    <!-- Settings Form -->
    <div class="max-w-4xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Referral Configuration</h3>
            </div>

            <form method="POST" action="<?= base_url('admin/referral-settings') ?>" class="p-6 space-y-6">
                <!-- Referral Bonus Amount -->
                <div>
                    <label for="referral_bonus_amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Referral Bonus Amount (PKR)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rs.</span>
                        </div>
                        <input type="number" 
                               id="referral_bonus_amount" 
                               name="referral_bonus_amount" 
                               step="0.01" 
                               min="0" 
                               required
                               value="<?= $referral_bonus_amount ?>"
                               class="block w-full pl-12 pr-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                               placeholder="100.00">
                    </div>
                    <p class="mt-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Amount given to users when they successfully refer someone
                    </p>
                </div>

                <!-- Referral Bonus Conditions -->
                <div>
                    <label for="referral_bonus_conditions" class="block text-sm font-medium text-gray-700 mb-2">
                        When to Give Referral Bonus
                    </label>
                    <select id="referral_bonus_conditions" 
                            name="referral_bonus_conditions" 
                            required
                            class="block w-full px-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="registration" <?= $referral_bonus_conditions === 'registration' ? 'selected' : '' ?>>
                            On Registration
                        </option>
                        <option value="first_purchase" <?= $referral_bonus_conditions === 'first_purchase' ? 'selected' : '' ?>>
                            On First Purchase
                        </option>
                        <option value="verification" <?= $referral_bonus_conditions === 'verification' ? 'selected' : '' ?>>
                            On Account Verification
                        </option>
                        <option value="manual" <?= $referral_bonus_conditions === 'manual' ? 'selected' : '' ?>>
                            Manual Approval Only
                        </option>
                    </select>
                    <p class="mt-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Choose when referral bonuses are automatically given
                    </p>
                </div>

                <!-- Referral Code Length -->
                <div>
                    <label for="referral_code_length" class="block text-sm font-medium text-gray-700 mb-2">
                        Referral Code Length
                    </label>
                    <input type="number" 
                           id="referral_code_length" 
                           name="referral_code_length" 
                           min="4" 
                           max="20" 
                           required
                           value="<?= $referral_code_length ?>"
                           class="block w-full px-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           placeholder="8">
                    <p class="mt-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Length of referral codes generated for users (4-20 characters)
                    </p>
                </div>

                <!-- Maximum Referrals Per User -->
                <div>
                    <label for="max_referrals_per_user" class="block text-sm font-medium text-gray-700 mb-2">
                        Maximum Referrals Per User
                    </label>
                    <input type="number" 
                           id="max_referrals_per_user" 
                           name="max_referrals_per_user" 
                           min="0" 
                           required
                           value="<?= $max_referrals_per_user ?>"
                           class="block w-full px-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           placeholder="0">
                    <p class="mt-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Set to 0 for unlimited referrals, or specify a maximum number
                    </p>
                </div>

                <!-- Information Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">How the Referral System Works</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Users get a unique referral code when they register</li>
                                    <li>When someone uses their referral code during registration, both users benefit</li>
                                    <li>The referrer gets a bonus amount added to their wallet</li>
                                    <li>Referral bonuses can be configured to trigger automatically or require manual approval</li>
                                    <li>All referral activities are tracked and can be monitored from the admin panel</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="<?= base_url('admin/referrals') ?>" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Referrals
                    </a>
                    
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>
                        Save Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Current System Status -->
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Current System Status</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Referral Bonus Amount</h4>
                        <p class="text-2xl font-bold text-green-600">Rs. <?= number_format($referral_bonus_amount, 2) ?></p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Bonus Trigger</h4>
                        <p class="text-lg font-semibold text-gray-900"><?= ucfirst(str_replace('_', ' ', $referral_bonus_conditions)) ?></p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Code Length</h4>
                        <p class="text-lg font-semibold text-gray-900"><?= $referral_code_length ?> characters</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Max Referrals Per User</h4>
                        <p class="text-lg font-semibold text-gray-900">
                            <?= $max_referrals_per_user === 0 ? 'Unlimited' : $max_referrals_per_user ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 flex space-x-4">
            <a href="<?= base_url('admin/referrals') ?>" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-users mr-2"></i>
                View Referrals
            </a>
            
            <a href="<?= base_url('admin/dashboard') ?>" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Dashboard
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
