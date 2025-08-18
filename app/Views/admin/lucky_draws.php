<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Lucky Draws Management</h2>
                <p class="text-gray-600 mt-1">Create and manage cash and product lucky draws</p>
            </div>
            <a href="<?= base_url('admin/lucky-draws/create') ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Create New Draw
            </a>
        </div>
    </div>

    <!-- Lucky Draws Table -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">All Lucky Draws</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Draw Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entry Fee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Winners</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entries</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($draws)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No lucky draws found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($draws as $draw): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900"><?= esc($draw['title']) ?></div>
                                        <div class="text-sm text-gray-500"><?= esc(substr($draw['description'], 0, 100)) ?>...</div>
                                        <div class="text-xs text-gray-400 mt-1">
                                            <i class="fas fa-calendar mr-1"></i>
                                            <?= date('M d, Y H:i', strtotime($draw['draw_date'])) ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $draw['draw_type'] === 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' ?>">
                                        <i class="fas <?= $draw['draw_type'] === 'cash' ? 'fa-dollar-sign' : 'fa-gift' ?> mr-1"></i>
                                        <?= ucfirst($draw['draw_type']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">Rs. <?= number_format($draw['entry_fee'], 2) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($draw['draw_type'] === 'cash'): ?>
                                        <div class="text-sm text-gray-900"><?= $draw['total_winners'] ?> winner<?= $draw['total_winners'] > 1 ? 's' : '' ?></div>
                                        <?php if ($draw['is_manual_selection']): ?>
                                            <div class="text-xs text-blue-600">Manual Selection</div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="text-sm text-gray-900">1 winner</div>
                                        <div class="text-xs text-blue-600">Product Prize</div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= number_format($draw['total_entries'] ?? 0) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $draw['status'] === 'active' ? 'bg-green-100 text-green-800' : ($draw['status'] === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') ?>">
                                        <?= ucfirst($draw['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="<?= base_url('admin/lucky-draws/edit/' . $draw['id']) ?>" class="text-blue-600 hover:text-blue-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($draw['status'] === 'active'): ?>
                                            <a href="<?= base_url('admin/lucky-draws/select-winners/' . $draw['id']) ?>" class="text-green-600 hover:text-green-900" title="Select Winners">
                                                <i class="fas fa-trophy"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= base_url('admin/lucky-draws/delete/' . $draw['id']) ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this lucky draw?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Cash Draws</p>
                    <p class="text-2xl font-bold text-gray-900"><?= count(array_filter($draws, function ($d) {
                                                                    return $d['draw_type'] === 'cash';
                                                                })) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-gift text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Product Draws</p>
                    <p class="text-2xl font-bold text-gray-900"><?= count(array_filter($draws, function ($d) {
                                                                    return $d['draw_type'] === 'product';
                                                                })) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-play text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-gray-900"><?= count(array_filter($draws, function ($d) {
                                                                    return $d['status'] === 'active';
                                                                })) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-gray-900"><?= count(array_filter($draws, function ($d) {
                                                                    return $d['status'] === 'completed';
                                                                })) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>