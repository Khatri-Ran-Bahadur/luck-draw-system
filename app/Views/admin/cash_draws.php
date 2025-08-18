<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-8">
    <!-- Enhanced Page Header -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-700 rounded-2xl shadow-lg p-8 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-money-bill-wave text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Cash Lucky Draws</h1>
                    <p class="text-green-100 text-lg mt-1">Manage cash prize lucky draws</p>
                </div>
            </div>
            <a href="<?= base_url('admin/cash-draws/create') ?>" class="inline-flex items-center px-6 py-3 bg-white text-green-600 font-semibold rounded-xl hover:bg-green-50 transition-all duration-200 shadow-lg">
                <i class="fas fa-plus mr-2"></i>
                Create Cash Draw
            </a>
        </div>
    </div>

    <!-- Cash Draws Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-list mr-3 text-green-600"></i>
                All Cash Lucky Draws
            </h2>
        </div>

        <?php if (empty($draws)): ?>
            <div class="p-8 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-money-bill-wave text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Cash Draws Yet</h3>
                <p class="text-gray-600 mb-6">Start by creating your first cash lucky draw</p>
                <a href="<?= base_url('admin/cash-draws/create') ?>" class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Create First Cash Draw
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Draw Details</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prize & Fee</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Draw Date</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($draws as $draw): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-dollar-sign text-green-600 text-lg"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900"><?= esc($draw['title']) ?></div>
                                            <div class="text-sm text-gray-500"><?= esc(substr($draw['description'], 0, 60)) ?>...</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        <div class="font-semibold text-green-600">Rs. <?= number_format($draw['prize_amount']) ?></div>
                                        <div class="text-gray-500">Entry: Rs. <?= number_format($draw['entry_fee']) ?></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        <?= date('M j, Y', strtotime($draw['draw_date'])) ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?= date('h:i A', strtotime($draw['draw_date'])) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($draw['status'] === 'active'): ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5"></span>
                                            Active
                                        </span>
                                    <?php elseif ($draw['status'] === 'completed'): ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <span class="w-1.5 h-1.5 bg-blue-400 rounded-full mr-1.5"></span>
                                            Completed
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></span>
                                            Inactive
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <a href="<?= base_url('admin/cash-draws/view/' . $draw['id']) ?>"
                                            class="text-green-600 hover:text-green-900 transition-colors"
                                            title="View Details">
                                            <i class="fas fa-eye text-lg"></i>
                                        </a>
                                        <?php if ($draw['status'] !== 'completed'): ?>
                                            <a href="<?= base_url('admin/cash-draws/edit/' . $draw['id']) ?>"
                                                class="text-blue-600 hover:text-blue-900 transition-colors"
                                                title="Edit">
                                                <i class="fas fa-edit text-lg"></i>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-gray-400 cursor-not-allowed" title="Cannot edit completed draw">
                                                <i class="fas fa-edit text-lg"></i>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($draw['status'] === 'active'): ?>
                                            <a href="<?= base_url('admin/select-winners/' . $draw['id']) ?>"
                                                class="text-purple-600 hover:text-purple-900 transition-colors"
                                                title="Select Winners">
                                                <i class="fas fa-trophy text-lg"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($draw['status'] !== 'completed'): ?>
                                            <a href="<?= base_url('admin/cash-draws/delete/' . $draw['id']) ?>"
                                                class="text-red-600 hover:text-red-900 transition-colors"
                                                title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this cash draw?')">
                                                <i class="fas fa-trash text-lg"></i>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-gray-400 cursor-not-allowed" title="Cannot delete completed draw">
                                                <i class="fas fa-trash text-lg"></i>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>