<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-teal-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-16">
            <div class="w-24 h-24 bg-gradient-to-r from-green-500 to-teal-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-2xl animate-float">
                <i class="fas fa-gift text-4xl text-white"></i>
            </div>
            <h1 class="text-5xl font-bold text-gray-900 mb-6">Product Lucky Draws</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Win amazing products and exclusive items! Join our exciting product draws and get a chance to win the latest gadgets, electronics, and more.</p>
        </div>

        <!-- Featured Product Draw -->
        <?php if ($featuredDraw): ?>
            <div class="max-w-4xl mx-auto mb-16">
                <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-3xl shadow-2xl p-8 border border-green-200 relative overflow-hidden">
                    <!-- Background decoration -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-400/20 to-teal-400/20 rounded-full blur-2xl"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-teal-400/20 to-blue-400/20 rounded-full blur-xl"></div>

                    <div class="relative z-10">
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center px-6 py-3 rounded-full text-sm font-bold bg-green-100 text-green-800 mb-4">
                                <i class="fas fa-star mr-2"></i>
                                Featured Product
                            </div>
                            <h2 class="text-4xl font-bold text-gray-900 mb-4"><?= esc($featuredDraw['title']) ?></h2>
                            <p class="text-lg text-gray-600 mb-6"><?= esc($featuredDraw['description']) ?></p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                            <div class="text-center">
                                <div class="bg-green-100 rounded-2xl w-16 h-16 flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-calendar text-green-600 text-xl"></i>
                                </div>
                                <h4 class="font-semibold text-gray-900">Draw Date</h4>
                                <p class="text-gray-600"><?= date('M d, Y', strtotime($featuredDraw['draw_date'])) ?></p>
                            </div>
                            <div class="text-center">
                                <div class="bg-blue-100 rounded-2xl w-16 h-16 flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-dollar-sign text-blue-600 text-xl"></i>
                                </div>
                                <h4 class="font-semibold text-gray-900">Entry Fee</h4>
                                <p class="text-gray-600">Rs. <?= number_format($featuredDraw['entry_fee'], 2) ?></p>
                            </div>
                            <div class="text-center">
                                <div class="bg-purple-100 rounded-2xl w-16 h-16 flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-users text-purple-600 text-xl"></i>
                                </div>
                                <h4 class="font-semibold text-gray-900">Max Entries</h4>
                                <p class="text-gray-600"><?= $featuredDraw['max_entries'] ?: 'Unlimited' ?></p>
                            </div>
                            <div class="text-center">
                                <div class="bg-orange-100 rounded-2xl w-16 h-16 flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-trophy text-orange-600 text-xl"></i>
                                </div>
                                <h4 class="font-semibold text-gray-900">Prize Value</h4>
                                <p class="text-gray-600 font-bold text-lg">Rs. <?= number_format($featuredDraw['prize_value'], 2) ?></p>
                            </div>
                        </div>

                        <!-- Prize Description -->
                        <div class="bg-white rounded-2xl p-6 mb-8 shadow-lg">
                            <h3 class="text-xl font-bold text-gray-900 mb-3">What You'll Win</h3>
                            <p class="text-gray-700"><?= esc($featuredDraw['prize_description']) ?></p>
                        </div>

                        <div class="text-center">
                            <?php if (session()->get('user_id')): ?>
                                <a href="/lucky-draw/join/<?= $featuredDraw['id'] ?>" class="btn-primary text-lg px-12 py-4 bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600">
                                    <i class="fas fa-gift mr-3"></i>Join This Draw
                                </a>
                            <?php else: ?>
                                <a href="/login" class="btn-primary text-lg px-12 py-4 bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600">
                                    <i class="fas fa-sign-in-alt mr-3"></i>Login to Join
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- All Product Draws -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">All Product Draws</h2>

            <?php if (empty($productDraws)): ?>
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-gift text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No Product Draws Available</h3>
                    <p class="text-gray-500">Check back later for new product draws!</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($productDraws as $draw): ?>
                        <div class="bg-white rounded-2xl shadow-xl p-6 card-hover border border-gray-100 relative overflow-hidden">
                            <!-- Gradient overlay -->
                            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-green-400/10 to-teal-400/10 rounded-full blur-lg"></div>

                            <div class="relative z-10">
                                <div class="text-center mb-6">
                                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                                        <i class="fas fa-gift text-2xl text-white"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?= esc($draw['title']) ?></h3>
                                    <p class="text-gray-600 text-sm"><?= esc($draw['description']) ?></p>
                                </div>

                                <!-- Prize Description -->
                                <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-lg p-4 mb-4">
                                    <h4 class="font-semibold text-gray-900 text-sm mb-2">Prize Details:</h4>
                                    <p class="text-gray-700 text-sm"><?= esc($draw['prize_description']) ?></p>
                                </div>

                                <div class="space-y-3 mb-6">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 text-sm">Draw Date:</span>
                                        <span class="font-medium text-gray-900"><?= date('M d, Y', strtotime($draw['draw_date'])) ?></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 text-sm">Entry Fee:</span>
                                        <span class="font-medium text-blue-600">Rs. <?= number_format($draw['entry_fee'], 2) ?></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 text-sm">Prize Value:</span>
                                        <span class="font-bold text-xl text-green-600">Rs. <?= number_format($draw['prize_value'], 2) ?></span>
                                    </div>
                                    <?php if ($draw['max_entries']): ?>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 text-sm">Max Entries:</span>
                                            <span class="font-medium text-gray-900"><?= $draw['max_entries'] ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="text-center">
                                    <?php if (session()->get('user_id')): ?>
                                        <a href="/lucky-draw/join/<?= $draw['id'] ?>" class="btn-primary w-full bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600">
                                            <i class="fas fa-gift mr-2"></i>Join Draw
                                        </a>
                                    <?php else: ?>
                                        <a href="/login" class="btn-primary w-full bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600">
                                            <i class="fas fa-sign-in-alt mr-2"></i>Login to Join
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Benefits Section -->
        <div class="bg-white rounded-3xl shadow-xl p-8 mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Why Choose Product Draws?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-box-open text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Exclusive Products</h3>
                    <p class="text-gray-600">Win the latest gadgets, electronics, and exclusive items not available in stores.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-shipping-fast text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Free Shipping</h3>
                    <p class="text-gray-600">All prizes are shipped directly to your door at no extra cost to you.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-certificate text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Authentic & New</h3>
                    <p class="text-gray-600">All products are brand new, authentic, and come with full manufacturer warranty.</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Ready to Win Amazing Products?</h2>
            <p class="text-xl text-gray-600 mb-8">Join our product draws today and get your hands on the latest tech!</p>
            <?php if (session()->get('user_id')): ?>
                <a href="/lucky-draw/products" class="btn-primary text-lg px-12 py-4 bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600">
                    <i class="fas fa-gift mr-3"></i>View All Product Draws
                </a>
            <?php else: ?>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/register" class="btn-primary text-lg px-12 py-4 bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600">
                        <i class="fas fa-user-plus mr-3"></i>Get Started
                    </a>
                    <a href="/login" class="btn-primary text-lg px-12 py-4 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800">
                        <i class="fas fa-sign-in-alt mr-3"></i>Login
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Background decoration -->
<div class="fixed inset-0 -z-10 overflow-hidden">
    <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-green-200 to-teal-200 rounded-full opacity-20 blur-3xl"></div>
    <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-teal-200 to-blue-200 rounded-full opacity-20 blur-3xl"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-r from-blue-200/10 to-purple-200/10 rounded-full blur-3xl"></div>
</div>

<?= $this->endSection() ?>