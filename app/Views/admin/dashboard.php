<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Users Card -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                        <dd class="text-2xl font-bold text-gray-900"><?= number_format($total_users) ?></dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm <?= $user_growth >= 0 ? 'text-green-600' : 'text-red-600' ?> flex items-center">
                    <i class="fas fa-arrow-<?= $user_growth >= 0 ? 'up' : 'down' ?> mr-1"></i>
                    <?= $user_growth >= 0 ? '+' : '' ?><?= $user_growth ?>%
                </div>
            </div>
        </div>

        <!-- Total Draws Card -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-dice text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Draws</dt>
                        <dd class="text-2xl font-bold text-gray-900"><?= number_format($total_cash_draws + $total_product_draws) ?></dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm <?= $draw_growth >= 0 ? 'text-green-600' : 'text-red-600' ?> flex items-center">
                    <i class="fas fa-arrow-<?= $draw_growth >= 0 ? 'up' : 'down' ?> mr-1"></i>
                    <?= $draw_growth >= 0 ? '+' : '' ?><?= $draw_growth ?>%
                </div>
            </div>
        </div>

        <!-- Total Entries Card -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-ticket-alt text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Entries</dt>
                        <dd class="text-2xl font-bold text-gray-900"><?= number_format($total_entries) ?></dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm text-blue-600 flex items-center">
                    <i class="fas fa-info-circle mr-1"></i>
                    Paid entries
                </div>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-rupee-sign text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                        <dd class="text-2xl font-bold text-gray-900">Rs. <?= number_format($total_revenue, 2) ?></dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm <?= $revenue_growth >= 0 ? 'text-green-600' : 'text-red-600' ?> flex items-center">
                    <i class="fas fa-arrow-<?= $revenue_growth >= 0 ? 'up' : 'down' ?> mr-1"></i>
                    <?= $revenue_growth >= 0 ? '+' : '' ?><?= $revenue_growth ?>%
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Overview -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Monthly Overview</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Monthly Revenue -->
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Monthly Revenue</h3>
                    <i class="fas fa-calendar-alt text-gray-400"></i>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-2">$<?= number_format($monthly_revenue, 2) ?></div>
                <div class="text-sm text-gray-600"><?= date('F Y') ?></div>
            </div>

            <!-- Test Database Button -->
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Database Test</h3>
                    <i class="fas fa-database text-gray-400"></i>
                </div>
                <a href="<?= base_url('admin/test-database') ?>"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-vial mr-2"></i>Test Database
                </a>
                <div class="text-sm text-gray-600 mt-2">Test winner creation and database structure</div>
            </div>

            <!-- Pending Claims -->
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Pending Claims</h3>
                    <i class="fas fa-clock text-gray-400"></i>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-2"><?= number_format($pending_claims) ?></div>
                <div class="text-sm text-gray-600">
                    <?php if ($pending_claims > 0): ?>
                        <a href="<?= base_url('admin/winners') ?>" class="text-blue-600 hover:text-blue-800">Review claims</a>
                    <?php else: ?>
                        All claims processed
                    <?php endif; ?>
                </div>
            </div>

            <!-- New Users This Month -->
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">New Users</h3>
                    <i class="fas fa-user-plus text-gray-400"></i>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-2">
                    <?php
                    $newUsersThisMonth = 0;
                    foreach ($recent_users as $user) {
                        if (date('Y-m', strtotime($user['created_at'])) === date('Y-m')) {
                            $newUsersThisMonth++;
                        }
                    }
                    echo number_format($newUsersThisMonth);
                    ?>
                </div>
                <div class="text-sm text-gray-600">This month</div>
            </div>

            <!-- Transaction Growth -->
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Transaction Growth</h3>
                    <i class="fas fa-chart-line text-gray-400"></i>
                </div>
                <div class="text-2xl font-bold <?= $transaction_growth >= 0 ? 'text-green-600' : 'text-red-600' ?> mb-2">
                    <?= $transaction_growth >= 0 ? '+' : '' ?><?= $transaction_growth ?>%
                </div>
                <div class="text-sm text-gray-600">vs last month</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Transactions -->
        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recent Transactions</h3>
                <a href="<?= base_url('admin/transactions') ?>" class="text-blue-600 hover:text-blue-800 text-sm">
                    <i class="fas fa-external-link-alt"></i>
                </a>
            </div>
            <div class="space-y-4">
                <?php if (empty($recent_transactions)): ?>
                    <div class="text-center py-8">
                        <i class="fas fa-receipt text-gray-300 text-3xl mb-3"></i>
                        <p class="text-gray-500">No recent transactions</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($recent_transactions as $transaction): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50/50 rounded-lg backdrop-blur-sm">
                            <div>
                                <p class="text-sm font-medium text-gray-900"><?= esc($transaction['full_name'] ?? $transaction['username']) ?></p>
                                <p class="text-sm text-gray-500">
                                    <?= ucfirst($transaction['type']) ?> •
                                    <?= date('M j, Y', strtotime($transaction['created_at'])) ?>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">
                                    <?= $transaction['amount'] >= 0 ? '+' : '' ?>Rs. <?= number_format(abs($transaction['amount']), 2) ?>
                                </p>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    <?php
                                    switch ($transaction['status']) {
                                        case 'completed':
                                            echo 'bg-green-100 text-green-800';
                                            break;
                                        case 'pending':
                                            echo 'bg-yellow-100 text-yellow-800';
                                            break;
                                        case 'failed':
                                            echo 'bg-red-100 text-red-800';
                                            break;
                                        default:
                                            echo 'bg-gray-100 text-gray-800';
                                    }
                                    ?>">
                                    <?= ucfirst($transaction['status']) ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Low Entry Draws -->
        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Low Entry Draws</h3>
                <a href="<?= base_url('admin/product-draws') ?>" class="text-blue-600 hover:text-blue-800 text-sm">
                    <i class="fas fa-external-link-alt"></i>
                </a>
            </div>
            <div class="space-y-4">
                <?php if (empty($low_stock_products)): ?>
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-green-300 text-3xl mb-3"></i>
                        <p class="text-gray-500">All draws have good participation</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($low_stock_products as $product): ?>
                        <div class="flex items-center justify-between p-3 bg-yellow-50/50 rounded-lg backdrop-blur-sm">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-yellow-500 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900"><?= esc($product['title']) ?></p>
                                    <p class="text-sm text-gray-500">
                                        Product Draw • Rs. <?= number_format($product['entry_fee'], 2) ?> entry
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-yellow-600"><?= $product['entry_count'] ?> entries</p>
                                <p class="text-xs text-gray-500">Need more</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Additional Status Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Active Draws -->
        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Active Draws</h3>
                <i class="fas fa-dice text-gray-400"></i>
            </div>
            <div class="text-3xl font-bold text-gray-900 mb-2"><?= number_format($active_cash_draws + $active_product_draws) ?></div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Cash Draws</span>
                    <span class="text-gray-900"><?= number_format($active_cash_draws) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Product Draws</span>
                    <span class="text-gray-900"><?= number_format($active_product_draws) ?></span>
                </div>
            </div>
        </div>

        <!-- Transaction Summary -->
        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Transactions</h3>
                <i class="fas fa-credit-card text-gray-400"></i>
            </div>
            <div class="text-3xl font-bold text-gray-900 mb-2"><?= number_format($transaction_stats['total']) ?></div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Completed</span>
                    <span class="text-green-600"><?= $transaction_stats['completed_percentage'] ?>%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Pending</span>
                    <span class="text-yellow-600"><?= $transaction_stats['pending_percentage'] ?>%</span>
                </div>
                <?php if ($transaction_stats['failed_percentage'] > 0): ?>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Failed</span>
                        <span class="text-red-600"><?= $transaction_stats['failed_percentage'] ?>%</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- System Health -->
        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">System Health</h3>
                <i class="fas fa-heartbeat text-gray-400"></i>
            </div>
            <div class="text-3xl font-bold <?= $system_health['overall_score'] >= 90 ? 'text-green-600' : ($system_health['overall_score'] >= 70 ? 'text-yellow-600' : 'text-red-600') ?> mb-2">
                <?= $system_health['overall_score'] ?>%
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Uptime</span>
                    <span class="text-green-600"><?= $system_health['uptime'] ?>%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Response Time</span>
                    <span class="text-blue-600"><?= $system_health['response_time'] ?>ms</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Additional animations for dashboard cards */
    .stat-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .stat-card:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    /* Pulse animation for metrics */
    .stat-card .text-2xl {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.8;
        }
    }

    /* Gradient background for better visual hierarchy */
    .glass-card {
        position: relative;
        overflow: hidden;
    }

    .glass-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.5), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .glass-card:hover::before {
        opacity: 1;
    }
</style>
<?= $this->endSection() ?>