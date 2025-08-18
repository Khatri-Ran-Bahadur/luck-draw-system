<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Product Lucky Draws Management</h2>
                <p class="text-gray-600 mt-1">Create and manage product lucky draws with image uploads</p>
            </div>
            <a href="<?= base_url('admin/products/create') ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Add New Product
            </a>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($products)): ?>
            <div class="col-span-full text-center py-12">
                <div class="text-gray-500">
                    <i class="fas fa-gift text-4xl mb-4"></i>
                    <p class="text-lg font-medium">No products found</p>
                    <p class="text-sm">Start by adding your first product lucky draw</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <!-- Product Image -->
                    <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                        <?php if ($product['product_image']): ?>
                            <img src="<?= base_url($product['product_image']) ?>" alt="<?= esc($product['title']) ?>" class="w-full h-48 object-cover">
                        <?php else: ?>
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-3xl"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Product Details -->
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-lg font-medium text-gray-900"><?= esc($product['title']) ?></h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $product['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                <?= ucfirst($product['status']) ?>
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 mb-4"><?= esc(substr($product['description'], 0, 100)) ?>...</p>

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-dollar-sign mr-2"></i>
                                <span>Entry Fee: Rs. <?= number_format($product['entry_fee'], 2) ?></span>
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-calendar mr-2"></i>
                                <span>Draw Date: <?= date('M d, Y H:i', strtotime($product['draw_date'])) ?></span>
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-trophy mr-2"></i>
                                <span>1 Winner</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <a href="<?= base_url('admin/products/edit/' . $product['id']) ?>" class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                <i class="fas fa-edit mr-2"></i>
                                Edit
                            </a>
                            <a href="<?= base_url('admin/products/delete/' . $product['id']) ?>" class="inline-flex justify-center items-center px-3 py-2 border border-red-300 text-sm font-medium rounded-lg text-red-700 bg-white hover:bg-red-50 transition-colors" onclick="return confirm('Are you sure you want to delete this product?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>