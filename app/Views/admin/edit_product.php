<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Edit Product</h2>
                <p class="text-gray-600 mt-1">Update product information and details</p>
            </div>
            <a href="<?= base_url('admin/products') ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Products
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-lg shadow-sm">
        <form action="<?= base_url('admin/products/edit/' . $product['id']) ?>" method="post" class="p-6 space-y-6" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Product Title</label>
                    <input type="text" name="title" id="title" required
                        value="<?= esc($product['title']) ?>"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter product title">
                </div>

                <div>
                    <label for="entry_fee" class="block text-sm font-medium text-gray-700">Entry Fee (Rs.)</label>
                    <div class="mt-1 relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rs.</span>
                        </div>
                        <input type="number" name="entry_fee" id="entry_fee" step="0.01" min="0.01" required
                            value="<?= $product['entry_fee'] ?>"
                            class="pl-7 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="0.00">
                    </div>
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="4" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Describe the product..."><?= esc($product['description']) ?></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="draw_date" class="block text-sm font-medium text-gray-700">Draw Date & Time</label>
                    <input type="datetime-local" name="draw_date" id="draw_date" required
                        value="<?= date('Y-m-d\TH:i', strtotime($product['draw_date'])) ?>"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="product_value" class="block text-sm font-medium text-gray-700">Product Value (Rs.)</label>
                    <div class="mt-1 relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rs.</span>
                        </div>
                        <input type="number" name="product_value" id="product_value" step="0.01" min="0.01"
                            value="<?= $product['product_value'] ?? '' ?>"
                            class="pl-7 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="0.00">
                    </div>
                </div>
            </div>

            <div>
                <label for="product_details" class="block text-sm font-medium text-gray-700">Product Details</label>
                <textarea name="product_details" id="product_details" rows="4"
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Describe the product, specifications, brand, model, etc..."><?= esc($product['product_details'] ?? '') ?></textarea>
            </div>

            <div>
                <label for="product_image" class="block text-sm font-medium text-gray-700">Product Image</label>
                <div class="mt-1 flex items-center space-x-6">
                    <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                        <?php if ($product['product_image']): ?>
                            <img id="product-image-preview" src="<?= base_url($product['product_image']) ?>" alt="Product Image" class="w-full h-full object-cover">
                        <?php else: ?>
                            <img id="product-image-preview" src="" alt="" class="w-full h-full object-cover hidden">
                            <i class="fas fa-image text-3xl text-gray-400" id="product-default-icon"></i>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1">
                        <input type="file" name="product_image" id="product_image" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF up to 5MB. Leave empty to keep current image.</p>
                    </div>
                </div>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="active" <?= $product['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $product['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    <option value="completed" <?= $product['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="<?= base_url('admin/products') ?>" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productImageInput = document.getElementById('product_image');
        const productImagePreview = document.getElementById('product-image-preview');
        const productDefaultIcon = document.getElementById('product-default-icon');

        // Product image preview
        if (productImageInput) {
            productImageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.size > 5 * 1024 * 1024) { // 5MB limit
                        alert('File size must be less than 5MB');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        productImagePreview.src = e.target.result;
                        productImagePreview.classList.remove('hidden');
                        if (productDefaultIcon) productDefaultIcon.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    // Keep current image if no new file selected
                    if (productImagePreview.src && !productImagePreview.src.includes('data:')) {
                        productImagePreview.classList.remove('hidden');
                        if (productDefaultIcon) productDefaultIcon.classList.add('hidden');
                    } else {
                        productImagePreview.classList.add('hidden');
                        if (productDefaultIcon) productDefaultIcon.classList.remove('hidden');
                    }
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>