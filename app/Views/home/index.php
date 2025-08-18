<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="gradient-bg text-white py-24 relative overflow-hidden">
    <!-- Background decoration -->
    <div class="absolute inset-0">
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-blue-900/20 to-purple-900/20"></div>
        <div class="absolute top-20 left-10 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h1 class="text-5xl md:text-7xl font-bold mb-8 leading-tight">
            Win Amazing Prizes with Our
            <span class="text-yellow-300 block">Lucky Draw System</span>
        </h1>
        <p class="text-xl md:text-2xl mb-12 text-blue-100 max-w-4xl mx-auto leading-relaxed">
            Join exciting lucky draws and stand a chance to win incredible cash prizes and amazing products!
            Choose between cash draws for instant money or product draws for exclusive items.
        </p>
        <div class="flex flex-col sm:flex-row gap-6 justify-center">
            <?php if (session()->get('user_id')): ?>
                <a href="/cash-draws" class="relative overflow-hidden bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 hover:from-amber-600 hover:via-orange-600 hover:to-red-600 text-white font-bold text-lg px-12 py-5 rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl border border-white/20 group">
                    <span class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></span>
                    <span class="relative flex items-center justify-center">
                        <i class="fas fa-dollar-sign mr-3"></i>Join Cash Draw
                    </span>
                </a>
                <a href="/product-draws" class="relative overflow-hidden bg-gradient-to-r from-emerald-500 via-green-500 to-teal-500 hover:from-emerald-600 hover:via-green-600 hover:to-teal-600 text-white font-bold text-lg px-12 py-5 rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl border border-white/20 group">
                    <span class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></span>
                    <span class="relative flex items-center justify-center">
                        <i class="fas fa-gift mr-3"></i>Join Product Draw
                    </span>
                </a>
            <?php else: ?>
                <a href="/register" class="relative overflow-hidden bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 hover:from-amber-600 hover:via-orange-600 hover:to-red-600 text-white font-bold text-lg px-12 py-5 rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl border border-white/20 group">
                    <span class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></span>
                    <span class="relative flex items-center justify-center">
                        <i class="fas fa-user-plus mr-3"></i>Get Started
                    </span>
                </a>
                <a href="/login" class="relative overflow-hidden bg-white/10 backdrop-blur-md border-2 border-white/30 text-white hover:bg-white/20 hover:border-white/50 font-bold py-5 px-12 rounded-2xl text-lg transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl group">
                    <span class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/10 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></span>
                    <span class="relative flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-3"></i>Login
                    </span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="card-hover bg-gradient-to-br from-blue-50 to-blue-100 p-8 rounded-2xl text-center border border-blue-200">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fas fa-users text-2xl text-white"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-2"><?= number_format($totalUsers) ?></h3>
                <p class="text-gray-600 font-medium">Happy Users</p>
            </div>
            <div class="card-hover bg-gradient-to-br from-green-50 to-green-100 p-8 rounded-2xl text-center border border-green-200">
                <div class="w-16 h-16 bg-gradient-to-r from-green-600 to-green-700 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fas fa-dice text-2xl text-white"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-2"><?= number_format($totalDraws) ?></h3>
                <p class="text-gray-600 font-medium">Lucky Draws</p>
            </div>
            <div class="card-hover bg-gradient-to-br from-purple-50 to-purple-100 p-8 rounded-2xl text-center border border-purple-200">
                <div class="w-16 h-16 bg-gradient-to-r from-purple-600 to-purple-700 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fas fa-trophy text-2xl text-white"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-2"><?= number_format($totalWinners) ?></h3>
                <p class="text-gray-600 font-medium">Winners</p>
            </div>
            <div class="card-hover bg-gradient-to-br from-orange-50 to-orange-100 p-8 rounded-2xl text-center border border-orange-200">
                <div class="w-16 h-16 bg-gradient-to-r from-orange-600 to-orange-700 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fas fa-star text-2xl text-white"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-2">100%</h3>
                <p class="text-gray-600 font-medium">Fair & Secure</p>
            </div>
        </div>
    </div>
</section>

<!-- Draw Types Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Choose Your Lucky Draw Type</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">We offer two exciting types of lucky draws to suit your preferences</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Cash Draws -->
            <div class="bg-white rounded-2xl shadow-xl p-8 card-hover border border-gray-100">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-dollar-sign text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Cash Draws</h3>
                    <p class="text-gray-600">Win instant cash prizes and build your wealth</p>
                </div>

                <div class="space-y-4 mb-8">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-check text-green-600 text-sm"></i>
                        </div>
                        <span class="text-gray-700">Instant cash transfers to your account</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-check text-green-600 text-sm"></i>
                        </div>
                        <span class="text-gray-700">No waiting time for prize delivery</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-check text-green-600 text-sm"></i>
                        </div>
                        <span class="text-gray-700">Use your winnings however you want</span>
                    </div>
                </div>

                <div class="text-center">
                    <?php if (session()->get('user_id')): ?>
                        <a href="/cash-draws" class="relative overflow-hidden w-full bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 hover:from-amber-600 hover:via-orange-600 hover:to-red-600 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl border border-orange-200 group inline-block">
                            <span class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></span>
                            <span class="relative flex items-center justify-center">
                                <i class="fas fa-dollar-sign mr-2"></i>Join Cash Draw
                            </span>
                        </a>
                    <?php else: ?>
                        <a href="/login" class="relative overflow-hidden w-full bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 hover:from-amber-600 hover:via-orange-600 hover:to-red-600 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl border border-orange-200 group inline-block">
                            <span class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></span>
                            <span class="relative flex items-center justify-center">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login to Join
                            </span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Draws -->
            <div class="bg-white rounded-2xl shadow-xl p-8 card-hover border border-gray-100">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-r from-green-500 to-teal-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-gift text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Product Draws</h3>
                    <p class="text-gray-600">Win exclusive products and amazing items</p>
                </div>

                <div class="space-y-4 mb-8">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-check text-green-600 text-sm"></i>
                        </div>
                        <span class="text-gray-700">Exclusive and limited edition products</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-check text-green-600 text-sm"></i>
                        </div>
                        <span class="text-gray-700">High-value electronics and gadgets</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-check text-green-600 text-sm"></i>
                        </div>
                        <span class="text-gray-700">Fast shipping to your doorstep</span>
                    </div>
                </div>

                <div class="text-center">
                    <?php if (session()->get('user_id')): ?>
                        <a href="/product-draws" class="relative overflow-hidden w-full bg-gradient-to-r from-emerald-500 via-green-500 to-teal-500 hover:from-emerald-600 hover:via-green-600 hover:to-teal-600 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl border border-green-200 group inline-block">
                            <span class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></span>
                            <span class="relative flex items-center justify-center">
                                <i class="fas fa-gift mr-2"></i>Join Product Draw
                            </span>
                        </a>
                    <?php else: ?>
                        <a href="/login" class="relative overflow-hidden w-full bg-gradient-to-r from-emerald-500 via-green-500 to-teal-500 hover:from-emerald-600 hover:via-green-600 hover:to-teal-600 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl border border-green-200 group inline-block">
                            <span class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></span>
                            <span class="relative flex items-center justify-center">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login to Join
                            </span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Current Lucky Draws Section -->
<?php if ($currentDraw): ?>
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Current Lucky Draws</h2>
                <p class="text-xl text-gray-600">Don't miss out on these exciting opportunities!</p>
            </div>

            <div class="max-w-4xl mx-auto">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl shadow-xl p-8 border border-green-200 card-hover">
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <i class="fas fa-dollar-sign text-2xl text-white"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-3"><?= esc($currentDraw['title']) ?></h3>
                        <p class="text-lg text-gray-600 mb-4"><?= esc($currentDraw['description']) ?></p>
                        <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-dollar-sign mr-2"></i>
                            Cash Draw
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="text-center">
                            <div class="bg-blue-100 rounded-2xl w-16 h-16 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-calendar text-blue-600 text-xl"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">Draw Date</h4>
                            <p class="text-gray-600"><?= date('M d, Y', strtotime($currentDraw['draw_date'])) ?></p>
                        </div>
                        <div class="text-center">
                            <div class="bg-green-100 rounded-2xl w-16 h-16 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">Entry Fee</h4>
                            <p class="text-gray-600">Rs. <?= number_format($currentDraw['entry_fee'], 2) ?></p>
                        </div>
                        <div class="text-center">
                            <div class="bg-orange-100 rounded-2xl w-16 h-16 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-trophy text-orange-600 text-xl"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">Prize</h4>
                            <p class="text-gray-600">Rs. <?= number_format($currentDraw['prize_amount'], 2) ?></p>
                        </div>
                    </div>

                    <div class="text-center">
                        <?php if (session()->get('user_id')): ?>
                            <a href="/cash-draw/<?= $currentDraw['id'] ?>" class="relative overflow-hidden bg-gradient-to-r from-emerald-500 via-green-500 to-teal-500 hover:from-emerald-600 hover:via-green-600 hover:to-teal-600 text-white font-bold text-lg px-12 py-5 rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl border border-green-200 group inline-block">
                                <span class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></span>
                                <span class="relative flex items-center justify-center">
                                    <i class="fas fa-dollar-sign mr-3"></i>Join Now
                                </span>
                            </a>
                        <?php else: ?>
                            <a href="/login" class="relative overflow-hidden bg-gradient-to-r from-emerald-500 via-green-500 to-teal-500 hover:from-emerald-600 hover:via-green-600 hover:to-teal-600 text-white font-bold text-lg px-12 py-5 rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl border border-green-200 group inline-block">
                                <span class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></span>
                                <span class="relative flex items-center justify-center">
                                    <i class="fas fa-sign-in-alt mr-3"></i>Login to Join
                                </span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- How It Works Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">How It Works</h2>
            <p class="text-xl text-gray-600">Simple steps to join and win amazing prizes!</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-24 h-24 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <span class="text-3xl font-bold text-white">1</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Register</h3>
                <p class="text-gray-600">Create your account in just a few minutes</p>
            </div>
            <div class="text-center">
                <div class="w-24 h-24 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <span class="text-3xl font-bold text-white">2</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Choose Draw</h3>
                <p class="text-gray-600">Select between cash or product draws</p>
            </div>
            <div class="text-center">
                <div class="w-24 h-24 bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <span class="text-3xl font-bold text-white">3</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Pay & Wait</h3>
                <p class="text-gray-600">Pay entry fee and wait for draw date</p>
            </div>
            <div class="text-center">
                <div class="w-24 h-24 bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <span class="text-3xl font-bold text-white">4</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Win!</h3>
                <p class="text-gray-600">Get notified if you're the lucky winner!</p>
            </div>
        </div>
    </div>
</section>

<!-- Payment Methods Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Secure Payment Methods</h2>
            <p class="text-xl text-gray-600">We accept multiple secure payment options for your convenience</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center card-hover border border-gray-100">
                <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fab fa-paypal text-3xl text-white"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">PayPal</h3>
                <p class="text-gray-600">Fast and secure payments with PayPal worldwide</p>
            </div>
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center card-hover border border-gray-100">
                <div class="w-20 h-20 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fas fa-mobile-alt text-3xl text-white"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">EasyPaisa</h3>
                <p class="text-gray-600">Convenient mobile payment solution</p>
            </div>
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center card-hover border border-gray-100">
                <div class="w-20 h-20 bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fas fa-credit-card text-3xl text-white"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Credit Cards</h3>
                <p class="text-gray-600">Secure payment with major credit cards</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 gradient-bg-alt text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold mb-6">Ready to Try Your Luck?</h2>
        <p class="text-xl mb-10 text-pink-100 max-w-3xl mx-auto">Join thousands of users who are already participating in our exciting lucky draws! Choose your preferred draw type and start winning today.</p>
        <div class="flex flex-col sm:flex-row gap-6 justify-center">
            <?php if (session()->get('user_id')): ?>
                <a href="/cash-draws" class="relative overflow-hidden bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 hover:from-amber-600 hover:via-orange-600 hover:to-red-600 text-white font-bold text-lg px-12 py-5 rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl border border-white/20 group">
                    <span class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></span>
                    <span class="relative flex items-center justify-center">
                        <i class="fas fa-dollar-sign mr-3"></i>Join Cash Draw
                    </span>
                </a>
                <a href="/product-draws" class="relative overflow-hidden bg-gradient-to-r from-emerald-500 via-green-500 to-teal-500 hover:from-emerald-600 hover:via-green-600 hover:to-teal-600 text-white font-bold text-lg px-12 py-5 rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl border border-white/20 group">
                    <span class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></span>
                    <span class="relative flex items-center justify-center">
                        <i class="fas fa-gift mr-3"></i>Join Product Draw
                    </span>
                </a>
            <?php else: ?>
                <a href="/register" class="relative overflow-hidden bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 hover:from-amber-600 hover:via-orange-600 hover:to-red-600 text-white font-bold text-lg px-12 py-5 rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl border border-white/20 group">
                    <span class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></span>
                    <span class="relative flex items-center justify-center">
                        <i class="fas fa-user-plus mr-3"></i>Get Started Now
                    </span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?= $this->endSection() ?>