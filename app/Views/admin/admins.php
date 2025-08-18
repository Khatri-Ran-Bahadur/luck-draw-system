<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Admin Management</h2>
                <p class="text-gray-600 mt-1">Manage all admin accounts in the system</p>
            </div>
            <a href="<?= base_url('admin/admins/create') ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-purple-600 hover:bg-purple-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Add New Admin
            </a>
        </div>
    </div>

    <!-- Admins Table -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">All Admins</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($admins)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No admins found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($admins as $admin): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <?php if ($admin['profile_image']): ?>
                                                <img class="h-10 w-10 rounded-full object-cover" src="<?= base_url($admin['profile_image']) ?>" alt="<?= esc($admin['full_name']) ?>">
                                            <?php else: ?>
                                                <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                                    <i class="fas fa-user-shield text-purple-600"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?= esc($admin['full_name']) ?></div>
                                            <div class="text-sm text-gray-500">@<?= esc($admin['username']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= esc($admin['email']) ?></div>
                                    <?php if ($admin['phone']): ?>
                                        <div class="text-sm text-gray-500"><?= esc($admin['phone']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $admin['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= ucfirst($admin['status']) ?>
                                    </span>
                                    <?php if ($admin['id'] == session()->get('user_id')): ?>
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            You
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('M d, Y', strtotime($admin['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="<?= base_url('admin/admins/edit/' . $admin['id']) ?>" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($admin['id'] != session()->get('user_id')): ?>
                                            <a href="<?= base_url('admin/admins/delete/' . $admin['id']) ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this admin?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
