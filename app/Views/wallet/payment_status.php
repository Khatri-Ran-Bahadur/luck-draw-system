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
                <h1 class="text-3xl font-bold text-gray-900">Payment System Status</h1>
            </div>
            <p class="text-gray-600">Check the status of your payment gateways and configuration</p>
        </div>

        <!-- Payment Methods Status -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- PayPal Status -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fab fa-paypal text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">PayPal</h3>
                        <div class="flex items-center space-x-2">
                            <?php if ($paymentStatus['paypal']['configured']): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>
                                    Configured
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Not Configured
                                </span>
                            <?php endif; ?>
                            
                            <?php if ($paymentStatus['paypal']['demo']): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-flask mr-1"></i>
                                    Demo Mode
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Mode:</span>
                        <span class="font-medium"><?= ucfirst($paymentStatus['paypal']['mode']) ?></span>
                    </div>
                    
                    <?php if ($paymentStatus['paypal']['configured']): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Client ID:</span>
                            <span class="font-mono text-xs"><?= substr($config->paypal['client_id'], 0, 8) ?>...</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Status:</span>
                            <span class="text-green-600 font-medium">Ready for Production</span>
                        </div>
                    <?php else: ?>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                            <p class="text-sm text-yellow-800">
                                <i class="fas fa-info-circle mr-1"></i>
                                PayPal is not configured. Currently running in demo mode.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Easypaisa Status -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-mobile-alt text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Easypaisa</h3>
                        <div class="flex items-center space-x-2">
                            <?php if ($paymentStatus['easypaisa']['configured']): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>
                                    Configured
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Not Configured
                                </span>
                            <?php endif; ?>
                            
                            <?php if ($paymentStatus['easypaisa']['demo']): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-flask mr-1"></i>
                                    Demo Mode
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Environment:</span>
                        <span class="font-medium"><?= ucfirst($paymentStatus['easypaisa']['mode']) ?></span>
                    </div>
                    
                    <?php if ($paymentStatus['easypaisa']['configured']): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Store ID:</span>
                            <span class="font-mono text-xs"><?= substr($config->easypaisa['store_id'], 0, 8) ?>...</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Status:</span>
                            <span class="text-green-600 font-medium">Ready for Production</span>
                        </div>
                    <?php else: ?>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                            <p class="text-sm text-yellow-800">
                                <i class="fas fa-info-circle mr-1"></i>
                                Easypaisa is not configured. Currently running in demo mode.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Configuration Instructions -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Configuration Instructions</h2>
            
            <div class="space-y-6">
                <!-- PayPal Setup -->
                <div class="border-l-4 border-blue-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">PayPal Setup</h3>
                    <div class="space-y-3 text-sm text-gray-600">
                        <p>1. Go to <a href="https://developer.paypal.com" target="_blank" class="text-blue-600 hover:underline">PayPal Developer Portal</a></p>
                        <p>2. Create a new app and get your Client ID and Secret</p>
                        <p>3. Add these environment variables to your <code class="bg-gray-100 px-2 py-1 rounded">.env</code> file:</p>
                        <div class="bg-gray-50 rounded-lg p-4 font-mono text-xs">
                            PAYPAL_CLIENT_ID=your_client_id_here<br>
                            PAYPAL_CLIENT_SECRET=your_client_secret_here<br>
                            PAYPAL_MODE=sandbox<br>
                            PAYMENT_DEMO_MODE=false
                        </div>
                    </div>
                </div>
                
                <!-- Easypaisa Setup -->
                <div class="border-l-4 border-green-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Easypaisa Setup</h3>
                    <div class="space-y-3 text-sm text-gray-600">
                        <p>1. Contact <a href="https://easypaisa.com.pk" target="_blank" class="text-green-600 hover:underline">Easypaisa Business</a></p>
                        <p>2. Get your Store ID and Hash Key</p>
                        <p>3. Add these environment variables to your <code class="bg-gray-100 px-2 py-1 rounded">.env</code> file:</p>
                        <div class="bg-gray-50 rounded-lg p-4 font-mono text-xs">
                            EASYPAISA_STORE_ID=your_store_id_here<br>
                            EASYPAISA_HASH_KEY=your_hash_key_here<br>
                            EASYPAISA_ENV=sandbox<br>
                            PAYMENT_DEMO_MODE=false
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Demo Mode Notice -->
        <?php if ($config->demo_mode): ?>
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
            <div class="flex items-start space-x-3">
                <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fas fa-info-circle text-blue-600 text-sm"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-blue-900 mb-2">Demo Mode Active</h4>
                    <p class="text-blue-800 text-sm mb-3">
                        Your payment system is currently running in demo mode. This means:
                    </p>
                    <ul class="text-blue-800 text-sm space-y-1">
                        <li>• All payments are simulated for testing purposes</li>
                        <li>• No real money is processed</li>
                        <li>• Perfect for development and testing</li>
                        <li>• Configure real payment gateways to go live</li>
                    </ul>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="flex justify-center space-x-4">
            <a href="<?= base_url('wallet') ?>" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl transition-colors">
                Back to Wallet
            </a>
            <a href="<?= base_url('wallet/topup') ?>" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors">
                Try Topup
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
