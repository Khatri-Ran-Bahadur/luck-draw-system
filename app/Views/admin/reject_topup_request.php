<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-8">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-red-600 to-pink-700 rounded-2xl shadow-lg p-8 text-white">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="fas fa-times-circle text-3xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold">Reject Top-up Request</h1>
                <p class="text-red-100 text-lg mt-1">Review and reject special user top-up request</p>
            </div>
        </div>
    </div>

    <!-- Request Details -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Request Information</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                        <?= strtoupper(substr($request['username'] ?? 'U', 0, 1)) ?>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900"><?= esc($request['full_name'] ?? 'N/A') ?></p>
                        <p class="text-sm text-gray-500">@<?= esc($request['username'] ?? 'unknown') ?></p>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                <p class="text-2xl font-bold text-red-600">Rs. <?= number_format($request['amount'], 2) ?></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    <?= ucfirst($request['payment_method']) ?>
                </span>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Request Date</label>
                <p class="text-gray-900"><?= date('M j, Y g:i A', strtotime($request['created_at'])) ?></p>
            </div>
        </div>

        <?php if ($request['payment_proof']): ?>
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Proof</label>
                <div class="border border-gray-200 rounded-lg p-4">
                    <a href="<?= base_url($request['payment_proof']) ?>" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-eye mr-2"></i>View Payment Proof
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($request['admin_notes']): ?>
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Previous Admin Notes</label>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-700"><?= esc($request['admin_notes']) ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Rejection Form -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Reject Request</h2>

        <form action="<?= base_url('admin/reject-topup-request/' . $request['id']) ?>" method="POST">
            <div class="mb-6">
                <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Rejection Reason (Required)
                </label>
                <textarea
                    id="admin_notes"
                    name="admin_notes"
                    rows="4"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                    placeholder="Please provide a reason for rejecting this request..."
                    required></textarea>
                <p class="text-sm text-gray-500 mt-1">This reason will be visible to the user.</p>
            </div>

            <div class="flex items-center justify-between">
                <a href="<?= base_url('admin/topup-requests') ?>" class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Requests
                </a>

                <button type="submit" class="inline-flex items-center px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Reject Request
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>