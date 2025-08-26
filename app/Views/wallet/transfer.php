<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Send Money</h1>
                    <p class="text-gray-600 mt-2">Transfer money to another user (Special Users Only)</p>
                </div>
                <a href="<?= base_url('wallet') ?>" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Wallet
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Transfer Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Send Money to User</h2>
                    
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

                    <form action="<?= base_url('wallet/transfer') ?>" method="POST" class="space-y-6">
                        <!-- Recipient Username -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Recipient Username</label>
                            <input type="text" 
                                   name="to_username" 
                                   value="<?= old('to_username') ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="Enter recipient's username"
                                   required>
                            <p class="text-sm text-gray-500 mt-1">Enter the exact username of the person you want to send money to</p>
                        </div>

                        <!-- Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Amount (Rs.)</label>
                            <input type="number" 
                                   name="amount" 
                                   min="1" 
                                   step="0.01"
                                   value="<?= old('amount') ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="Enter amount to transfer"
                                   required>
                            <p class="text-sm text-gray-500 mt-1">Minimum transfer amount: Rs. 1.00</p>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                            <textarea name="notes" 
                                      rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                      placeholder="Add a note about this transfer"><?= old('notes') ?></textarea>
                            <p class="text-sm text-gray-500 mt-1">Optional message for the recipient</p>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-paper-plane mr-2"></i>Submit Transfer Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-6">
                <!-- Wallet Balance -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Wallet Balance</h3>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-900 mb-2">
                            Rs. <?= number_format($wallet['balance'] ?? 0, 2) ?>
                        </div>
                        <p class="text-sm text-gray-600">Available for transfer</p>
                    </div>
                </div>

                <!-- Special User Status -->
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl shadow-md p-6 text-white">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-star text-xl mr-3"></i>
                        <span class="font-semibold">Special User Status</span>
                    </div>
                    <p class="text-yellow-100 text-sm">You have permission to transfer money to other users.</p>
                </div>

                <!-- Transfer Info -->
                <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-green-900 mb-3">Transfer Information</h3>
                    <div class="space-y-3 text-sm text-green-800">
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-2"></i>
                            <span>Admin approval required</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt mr-2"></i>
                            <span>Secure transfer process</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span>Minimum: Rs. 1.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transfers -->
        <?php if (!empty($recentTransfers)): ?>
        <div class="mt-8">
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Transfer Requests</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">To User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($recentTransfers as $transfer): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= date('M d, Y H:i', strtotime($transfer['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= esc($transfer['to_username'] ?? 'Unknown') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Rs. <?= number_format($transfer['amount'], 2) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $statusClass = '';
                                    $statusText = '';
                                    switch ($transfer['status']) {
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
                                            $statusText = ucfirst($transfer['status']);
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
