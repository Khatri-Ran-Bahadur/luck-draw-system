<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="bg-white">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-blue-600 to-purple-600 py-16">
        <div class="absolute inset-0">
            <img src="<?= base_url('images/about-hero.jpg') ?>" alt="About Us" class="w-full h-full object-cover opacity-20">
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold text-white mb-4">About Lucky Draw System</h1>
            <p class="text-xl text-white/90 max-w-3xl mx-auto">Your trusted platform for exciting lucky draws and amazing prizes.</p>
        </div>
    </div>

    <!-- Mission Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Our Mission</h2>
                <p class="mt-4 text-lg text-gray-600 max-w-3xl mx-auto">
                    To provide a fair, transparent, and exciting platform for users to participate in lucky draws and win amazing prizes.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-shield-alt text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Secure & Fair</h3>
                    <p class="text-gray-600">Our platform ensures complete security and fairness in all lucky draws.</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-hand-holding-usd text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Easy Payments</h3>
                    <p class="text-gray-600">Multiple payment options including PayPal and EasyPaisa for your convenience.</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-gift text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Amazing Prizes</h3>
                    <p class="text-gray-600">Win cash prizes or exciting products through our various lucky draws.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works -->
    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">How It Works</h2>
                <p class="mt-4 text-lg text-gray-600">Simple steps to participate and win</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-plus text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">1. Sign Up</h3>
                    <p class="text-gray-600">Create your account with email or Google</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-wallet text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">2. Add Funds</h3>
                    <p class="text-gray-600">Load your wallet using PayPal or EasyPaisa</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-ticket-alt text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">3. Enter Draws</h3>
                    <p class="text-gray-600">Participate in your favorite lucky draws</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-trophy text-orange-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">4. Win Prizes</h3>
                    <p class="text-gray-600">Get cash or products directly to your wallet</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Trust & Security -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Trust & Security</h2>
                <p class="mt-4 text-lg text-gray-600">Your security is our top priority</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-lock text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Secure Transactions</h3>
                    <p class="text-gray-600">All payments are processed through secure payment gateways with encryption.</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-random text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Fair Selection</h3>
                    <p class="text-gray-600">Winners are selected using a transparent and random selection process.</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-headset text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">24/7 Support</h3>
                    <p class="text-gray-600">Our support team is always available to help you with any issues.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact CTA -->
    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Have Questions?</h2>
            <p class="text-lg text-gray-600 mb-8">Our team is here to help you with any questions or concerns.</p>
            <a href="<?= base_url('contact') ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700">
                Contact Us
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>