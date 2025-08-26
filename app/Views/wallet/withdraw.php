<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Withdraw Money</h1>
                    <p class="text-gray-600 mt-2">
                        <?php if ($user['is_special_user'] ?? false): ?>
                            Request a withdrawal from your wallet (Special User Priority)
                        <?php else: ?>
                            Request a withdrawal from your wallet (Admin Approval Required)
                        <?php endif; ?>
                    </p>
                </div>
                <a href="<?= base_url('wallet') ?>" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Wallet
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Withdrawal Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Withdrawal Request</h2>
                    
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('wallet/withdraw') ?>" method="POST" class="space-y-6">
                        <!-- Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Withdrawal Amount (Rs.)</label>
                            <input type="number" 
                                   name="amount" 
                                   min="1000" 
                                   step="100"
                                   value="<?= old('amount') ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="Enter amount (min: Rs. 1,000)"
                                   required>
                            <p class="text-sm text-gray-500 mt-1">Minimum withdrawal amount: Rs. 1,000</p>
                        </div>

                        <!-- Withdrawal Method -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Withdrawal Method</label>
                            <select name="withdraw_method" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                    required>
                                <option value="">Select withdrawal method</option>
                                <option value="easypaisa" <?= old('withdraw_method') === 'easypaisa' ? 'selected' : '' ?>>EasyPaisa</option>
                                <option value="jazz_cash" <?= old('withdraw_method') === 'jazz_cash' ? 'selected' : '' ?>>Jazz Cash</option>
                                <option value="bank" <?= old('withdraw_method') === 'bank' ? 'selected' : '' ?>>Bank Transfer</option>
                                <option value="hbl" <?= old('withdraw_method') === 'hbl' ? 'selected' : '' ?>>HBL Bank</option>
                                <option value="ubank" <?= old('withdraw_method') === 'ubank' ? 'selected' : '' ?>>UBank</option>
                                <option value="mcb" <?= old('withdraw_method') === 'mcb' ? 'selected' : '' ?>>MCB Bank</option>
                                <option value="abank" <?= old('withdraw_method') === 'abank' ? 'selected' : '' ?>>ABank</option>
                                <option value="nbp" <?= old('withdraw_method') === 'nbp' ? 'selected' : '' ?>>NBP</option>
                                <option value="sbank" <?= old('withdraw_method') === 'sbank' ? 'selected' : '' ?>>Sindh Bank</option>
                                <option value="citi" <?= old('withdraw_method') === 'citi' ? 'selected' : '' ?>>Citibank</option>
                                <option value="hsbc" <?= old('withdraw_method') === 'hsbc' ? 'selected' : '' ?>>HSBC</option>
                                <option value="payoneer" <?= old('withdraw_method') === 'payoneer' ? 'selected' : '' ?>>Payoneer</option>
                                <option value="skrill" <?= old('withdraw_method') === 'skrill' ? 'selected' : '' ?>>Skrill</option>
                                <option value="neteller" <?= old('withdraw_method') === 'neteller' ? 'selected' : '' ?>>Neteller</option>
                                <option value="other" <?= old('withdraw_method') === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>

                        <!-- Account Details -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Account Details</label>
                            <textarea name="account_details" 
                                      rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                      placeholder="Enter your account number, phone number, or other payment details"
                                      required><?= old('account_details') ?></textarea>
                            <p class="text-sm text-gray-500 mt-1">Provide the account details where you want to receive the money</p>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                            <textarea name="notes" 
                                      rows="2"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                      placeholder="Any additional information for the admin"><?= old('notes') ?></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-paper-plane mr-2"></i>
                                <?php if ($user['is_special_user'] ?? false): ?>
                                    Submit Withdrawal Request
                                <?php else: ?>
                                    Request Withdrawal
                                <?php endif; ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-6">
                <!-- Wallet Balance -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Wallet Balance</h3>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-900 mb-2">
                            Rs. <?= number_format($wallet['balance'] ?? 0, 2) ?>
                        </div>
                        <p class="text-sm text-gray-600">Available for withdrawal</p>
                    </div>
                </div>

                <!-- Special User Status -->
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl shadow-md p-6 text-white">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-star text-xl mr-3"></i>
                        <span class="font-semibold">Special User Status</span>
                    </div>
                    <p class="text-yellow-100 text-sm">You have access to withdrawal and user request management features.</p>
                </div>

                <!-- Withdrawal Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-3">Withdrawal Information</h3>
                    <div class="space-y-3 text-sm text-blue-800">
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-2"></i>
                            <span>Processing time: 24-48 hours</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt mr-2"></i>
                            <span>Admin approval required</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span>Minimum amount: Rs. 1,000</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Withdrawals -->
        <?php if (!empty($recentWithdrawals)): ?>
        <div class="mt-8">
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Withdrawal Requests</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($recentWithdrawals as $withdrawal): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= date('M d, Y H:i', strtotime($withdrawal['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Rs. <?= number_format(abs($withdrawal['amount']), 2) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= ucfirst(str_replace('_', ' ', $withdrawal['payment_method'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $statusClass = '';
                                    $statusText = '';
                                    switch ($withdrawal['status']) {
                                        case 'pending':
                                            $statusClass = 'bg-yellow-100 text-yellow-800';
                                            $statusText = 'Pending';
                                            break;
                                        case 'approved':
                                            $statusClass = 'bg-green-100 text-green-800';
                                            $statusText = 'Approved';
                                            break;
                                        case 'rejected':
                                            $statusClass = 'bg-red-100 text-red-800';
                                            $statusText = 'Rejected';
                                            break;
                                        default:
                                            $statusClass = 'bg-gray-100 text-gray-800';
                                            $statusText = ucfirst($withdrawal['status']);
                                    }
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $statusClass ?>">
                                        <?= $statusText ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>