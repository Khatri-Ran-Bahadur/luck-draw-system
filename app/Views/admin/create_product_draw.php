<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-8">
    <!-- Enhanced Page Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-lg p-8 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-gift text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Create Product Draw</h1>
                    <p class="text-blue-100 text-lg mt-1">Set up a single product lucky draw</p>
                </div>
            </div>
            <a href="<?= base_url('admin/product-draws') ?>" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-medium rounded-xl transition-all duration-200 backdrop-blur-sm border border-white border-opacity-30">
                <i class="fas fa-arrow-left mr-3"></i>
                Back to Product Draws
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Create Form -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-plus-circle mr-3 text-blue-600"></i>
                    Product Draw Setup
                </h2>
            </div>

            <form action="<?= base_url('admin/product-draws/create') ?>" method="post" enctype="multipart/form-data" class="p-6 space-y-6">
                <?= csrf_field() ?>

                <!-- Draw Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Draw Information</h3>

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-heading text-gray-400 mr-1"></i>
                            Draw Title
                        </label>
                        <input type="text" name="title" id="title"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="e.g., Win a Honda Bike" required>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left text-gray-400 mr-1"></i>
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="Describe this lucky draw..." required></textarea>
                    </div>
                </div>

                <!-- Product Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Product Details</h3>

                    <div>
                        <label for="product_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag text-gray-400 mr-1"></i>
                            Product Name
                        </label>
                        <input type="text" name="product_name" id="product_name"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="e.g., Honda CD 70" required>
                    </div>

                    <div>
                        <label for="product_price" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-dollar-sign text-gray-400 mr-1"></i>
                            Product Value (Rs.)
                        </label>
                        <input type="number" name="product_price" id="product_price" step="0.01" min="1"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="150000" required>
                    </div>

                    <div>
                        <label for="product_image" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-image text-gray-400 mr-1"></i>
                            Product Image
                        </label>
                        <input type="file" name="product_image" id="product_image" accept="image/*"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <p class="text-sm text-gray-500 mt-1">Upload a clear image of the product</p>
                    </div>
                </div>

                <!-- Draw Settings -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Draw Settings</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="entry_fee" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-coins text-gray-400 mr-1"></i>
                                Entry Fee (Rs.)
                            </label>
                            <input type="number" name="entry_fee" id="entry_fee" step="0.01" min="0.01" value="1"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                required>
                        </div>

                        <div>
                            <label for="draw_date" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar text-gray-400 mr-1"></i>
                                Draw Date & Time
                            </label>
                            <input type="datetime-local" name="draw_date" id="draw_date"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                required>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-6 border-t border-gray-200">
                    <button type="submit"
                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-save mr-2"></i>
                        Create Product Draw
                    </button>
                </div>
            </form>
        </div>

        <!-- Live Preview -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-eye mr-3 text-green-600"></i>
                    Live Preview
                </h2>
                <p class="text-gray-600 text-sm mt-1">See how your product will appear to users</p>
            </div>

            <div class="p-6">
                <!-- Product Card Preview (Honda Bike Style) -->
                <div id="productPreview" class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-2xl p-6 border-2 border-blue-200 shadow-lg">
                    <div class="text-center">
                        <!-- Product Image -->
                        <div id="previewImageContainer" class="w-48 h-48 mx-auto mb-6 bg-white rounded-xl shadow-md flex items-center justify-center overflow-hidden">
                            <div id="placeholderIcon" class="text-gray-400">
                                <i class="fas fa-image text-6xl"></i>
                                <p class="text-sm mt-2">Upload Image</p>
                            </div>
                            <img id="previewImage" src="" alt="Product" class="w-full h-full object-cover hidden">
                        </div>

                        <!-- Product Name -->
                        <h3 id="previewProductName" class="text-2xl font-bold text-gray-800 mb-2">Product Name</h3>

                        <!-- Product Price -->
                        <div class="mb-4">
                            <span class="text-sm text-gray-600">Worth</span>
                            <div id="previewProductPrice" class="text-3xl font-bold text-green-600">Rs. 0</div>
                        </div>

                        <!-- Buy Button -->
                        <div class="mb-4">
                            <button class="w-full bg-gradient-to-r from-green-500 to-emerald-500 text-white font-bold py-4 px-6 rounded-xl text-lg shadow-lg hover:shadow-xl transition-all duration-200">
                                <span id="previewBuyButton">Buy for Rs. 0</span>
                            </button>
                        </div>

                        <!-- Draw Date -->
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-clock mr-1"></i>
                            Draw Date: <span id="previewDrawDate">Select date</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form elements
        const productNameInput = document.getElementById('product_name');
        const productPriceInput = document.getElementById('product_price');
        const entryFeeInput = document.getElementById('entry_fee');
        const drawDateInput = document.getElementById('draw_date');
        const productImageInput = document.getElementById('product_image');

        // Preview elements
        const previewProductName = document.getElementById('previewProductName');
        const previewProductPrice = document.getElementById('previewProductPrice');
        const previewBuyButton = document.getElementById('previewBuyButton');
        const previewDrawDate = document.getElementById('previewDrawDate');
        const previewImage = document.getElementById('previewImage');
        const placeholderIcon = document.getElementById('placeholderIcon');

        // Update preview functions
        function updatePreviewName() {
            const name = productNameInput.value || 'Product Name';
            previewProductName.textContent = name;
        }

        function updatePreviewPrice() {
            const price = productPriceInput.value || '0';
            previewProductPrice.textContent = 'Rs. ' + parseInt(price).toLocaleString();
        }

        function updatePreviewEntryFee() {
            const entryFee = entryFeeInput.value || '0';
            previewBuyButton.textContent = 'Buy for Rs. ' + parseInt(entryFee).toLocaleString();
        }

        function updatePreviewDate() {
            const date = drawDateInput.value;
            if (date) {
                const formatted = new Date(date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                previewDrawDate.textContent = formatted;
            } else {
                previewDrawDate.textContent = 'Select date';
            }
        }

        function updatePreviewImage() {
            const file = productImageInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('hidden');
                    placeholderIcon.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                previewImage.classList.add('hidden');
                placeholderIcon.classList.remove('hidden');
            }
        }

        // Event listeners
        productNameInput.addEventListener('input', updatePreviewName);
        productPriceInput.addEventListener('input', updatePreviewPrice);
        entryFeeInput.addEventListener('input', updatePreviewEntryFee);
        drawDateInput.addEventListener('change', updatePreviewDate);
        productImageInput.addEventListener('change', updatePreviewImage);

        // Initialize preview
        updatePreviewName();
        updatePreviewPrice();
        updatePreviewEntryFee();
        updatePreviewDate();

        // Set minimum date to today
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        drawDateInput.min = now.toISOString().slice(0, 16);
    });
</script>
<?= $this->endSection() ?>