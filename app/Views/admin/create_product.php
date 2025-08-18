<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Add New Product Lucky Draw</h2>
                <p class="text-gray-600 mt-1">Create a new product lucky draw with image and details</p>
            </div>
            <a href="<?= base_url('admin/products') ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Products
            </a>
        </div>
    </div>

    <!-- Create Form -->
    <div class="bg-white rounded-lg shadow-sm">
        <form action="<?= base_url('admin/products/create') ?>" method="post" enctype="multipart/form-data" class="p-6 space-y-6">
            <?= csrf_field() ?>

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Product Title</label>
                    <input type="text" name="title" id="title" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>

                <div>
                    <label for="entry_fee" class="block text-sm font-medium text-gray-700">Entry Fee (Rs.)</label>
                    <input type="number" name="entry_fee" id="entry_fee" step="0.01" min="0.01" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Product Description</label>
                <textarea name="description" id="description" rows="4" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500" placeholder="Describe the product, its features, and what makes it special..."></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="draw_date" class="block text-sm font-medium text-gray-700">Draw Date & Time</label>
                    <input type="datetime-local" name="draw_date" id="draw_date" required class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>

                <div>
                    <label for="product_image" class="block text-sm font-medium text-gray-700">Product Image</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-green-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="product_image" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                        <span>Upload a file</span>
                                        <input id="product_image" name="product_image" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            </div>
                        </div>
                    </div>
                    <div id="image-preview" class="mt-2 hidden">
                        <img id="preview-img" src="" alt="Preview" class="w-32 h-32 object-cover rounded-lg">
                    </div>
                </div>
            </div>

            <div>
                <label for="product_details" class="block text-sm font-medium text-gray-700">Additional Product Details</label>
                <textarea name="product_details" id="product_details" rows="3" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500" placeholder="Any additional details, specifications, or terms for this product..."></textarea>
                <p class="mt-1 text-sm text-gray-500">Optional: Add more specific details about the product</p>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="<?= base_url('admin/products') ?>" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors">
                    Create Product Draw
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('product_image');
        const imagePreview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');

        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert('Please select a valid image file.');
                    imageInput.value = '';
                }
            } else {
                imagePreview.classList.add('hidden');
            }
        });

        // Drag and drop functionality
        const dropZone = document.querySelector('.border-dashed');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('border-green-400', 'bg-green-50');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-green-400', 'bg-green-50');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                imageInput.files = files;
                imageInput.dispatchEvent(new Event('change'));
            }
        }
    });
</script>
<?= $this->endSection() ?>