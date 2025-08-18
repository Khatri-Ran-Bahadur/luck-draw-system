<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen bg-gradient-to-br from-yellow-50 via-white to-orange-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-16">
            <div class="w-24 h-24 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-2xl">
                <i class="fas fa-dollar-sign text-4xl text-white"></i>
            </div>
            <h1 class="text-5xl font-bold text-gray-900 mb-6">Cash Lucky Draws</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Win instant cash prizes and build your wealth! Join our exciting cash draws and stand a chance to win big money.</p>
        </div>

        <!-- Featured Cash Draw -->
        <?php if ($featuredDraw): ?>
            <div class="max-w-4xl mx-auto mb-16">
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-3xl shadow-2xl p-8 border border-yellow-200 relative overflow-hidden">
                    <!-- Background decoration -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-yellow-400/20 to-orange-400/20 rounded-full blur-2xl"></div>

                    <div class="relative z-10">
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center px-6 py-3 rounded-full text-sm font-bold bg-yellow-100 text-yellow-800 mb-4">
                                <i class="fas fa-star mr-2"></i>
                                Featured Draw
                            </div>
                            <h2 class="text-4xl font-bold text-gray-900 mb-4"><?= esc($featuredDraw['title']) ?></h2>
                            <p class="text-lg text-gray-600 mb-6"><?= esc($featuredDraw['description']) ?></p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                            <div class="text-center">
                                <div class="bg-yellow-100 rounded-2xl w-16 h-16 flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-calendar text-yellow-600 text-xl"></i>
                                </div>
                                <h4 class="font-semibold text-gray-900">Draw Date</h4>
                                <p class="text-gray-600"><?= date('M d, Y', strtotime($featuredDraw['draw_date'])) ?></p>
                            </div>
                            <div class="text-center">
                                <div class="bg-green-100 rounded-2xl w-16 h-16 flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
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
                                <h4 class="font-semibold text-gray-900">Prize Money</h4>
                                <p class="text-gray-600 font-bold text-lg">Rs. <?= number_format($featuredDraw['prize_value'], 2) ?></p>
                            </div>
                        </div>

                        <div class="text-center">
                            <?php if (session()->get('user_id')): ?>
                                <a href="/lucky-draw/join/<?= $featuredDraw['id'] ?>" class="btn-primary text-lg px-12 py-4 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600">
                                    <i class="fas fa-dollar-sign mr-3"></i>Join This Draw
                                </a>
                            <?php else: ?>
                                <a href="/login" class="btn-primary text-lg px-12 py-4 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600">
                                    <i class="fas fa-sign-in-alt mr-3"></i>Login to Join
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- All Cash Draws -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">All Cash Draws</h2>

            <?php if (empty($cashDraws)): ?>
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-dollar-sign text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No Cash Draws Available</h3>
                    <p class="text-gray-500">Check back later for new cash draws!</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($cashDraws as $draw): ?>
                        <div class="bg-white rounded-2xl shadow-xl p-6 card-hover border border-gray-100">
                            <div class="text-center mb-6">
                                <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                                    <i class="fas fa-dollar-sign text-2xl text-white"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2"><?= esc($draw['title']) ?></h3>
                                <p class="text-gray-600 text-sm"><?= esc($draw['description']) ?></p>
                            </div>

                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 text-sm">Draw Date:</span>
                                    <span class="font-medium text-gray-900"><?= date('M d, Y', strtotime($draw['draw_date'])) ?></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 text-sm">Entry Fee:</span>
                                    <span class="font-medium text-green-600">Rs. <?= number_format($draw['entry_fee'], 2) ?></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 text-sm">Prize Money:</span>
                                    <span class="font-bold text-xl text-yellow-600">Rs. <?= number_format($draw['prize_value'], 2) ?></span>
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
                                    <a href="/lucky-draw/join/<?= $draw['id'] ?>" class="btn-primary w-full bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600">
                                        <i class="fas fa-dollar-sign mr-2"></i>Join Draw
                                    </a>
                                <?php else: ?>
                                    <a href="/login" class="btn-primary w-full bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600">
                                        <i class="fas fa-sign-in-alt mr-2"></i>Login to Join
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Benefits Section -->
        <div class="bg-white rounded-3xl shadow-xl p-8 mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Why Choose Cash Draws?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-bolt text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Instant Payouts</h3>
                    <p class="text-gray-600">Get your winnings instantly transferred to your account. No waiting time!</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-shield-alt text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Secure & Safe</h3>
                    <p class="text-gray-600">All transactions are encrypted and secure. Your money is always safe with us.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-chart-line text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Build Wealth</h3>
                    <p class="text-gray-600">Use your winnings to invest, save, or spend however you want. It's your money!</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Ready to Win Big?</h2>
            <p class="text-xl text-gray-600 mb-8">Join our cash draws today and start building your fortune!</p>
            <?php if (session()->get('user_id')): ?>
                <a href="/lucky-draw/cash" class="btn-primary text-lg px-12 py-4 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600">
                    <i class="fas fa-dollar-sign mr-3"></i>View All Cash Draws
                </a>
            <?php else: ?>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/register" class="btn-primary text-lg px-12 py-4 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600">
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
    <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-yellow-200 to-orange-200 rounded-full opacity-20 blur-3xl"></div>
    <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-orange-200 to-red-200 rounded-full opacity-20 blur-3xl"></div>
</div>

<?= $this->endSection() ?>