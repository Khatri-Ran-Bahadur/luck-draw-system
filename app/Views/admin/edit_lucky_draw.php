<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Edit Lucky Draw</h2>
                <p class="text-gray-600 mt-1">Update lucky draw information and settings</p>
            </div>
            <a href="<?= base_url('admin/lucky-draws') ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Draws
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-lg shadow-sm">
        <form action="<?= base_url('admin/lucky-draws/edit/' . $draw['id']) ?>" method="post" class="p-6 space-y-6" id="editLuckDrawForm">
            <?= csrf_field() ?>

            <!-- Draw Type Display (Read-only) -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Draw Type</h3>
                <div class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-100 text-gray-700">
                    <i class="fas <?= $draw['draw_type'] === 'cash' ? 'fa-dollar-sign text-green-600' : 'fa-gift text-blue-600' ?> mr-2"></i>
                    <?= ucfirst($draw['draw_type']) ?> Lucky Draw
                </div>
                <p class="mt-2 text-sm text-gray-500">Draw type cannot be changed after creation</p>
            </div>

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Draw Title</label>
                    <input type="text" name="title" id="title" required
                        value="<?= esc($draw['title']) ?>"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="e.g., Win $1000, iPhone Giveaway">
                </div>

                <div>
                    <label for="entry_fee" class="block text-sm font-medium text-gray-700">Entry Fee (Rs.)</label>
                    <div class="mt-1 relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rs.</span>
                        </div>
                        <input type="number" name="entry_fee" id="entry_fee" step="0.01" min="0.01" required
                            value="<?= $draw['entry_fee'] ?>"
                            class="pl-7 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="0.00">
                    </div>
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="4" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Describe the lucky draw, prizes, and any special conditions..."><?= esc($draw['description']) ?></textarea>
            </div>

            <!-- Dynamic Fields Based on Draw Type -->
            <?php if ($draw['draw_type'] === 'cash'): ?>
                <div id="cashFields" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="total_winners" class="block text-sm font-medium text-gray-700">Total Winners</label>
                            <input type="number" name="total_winners" id="total_winners" min="1" max="99"
                                value="<?= $draw['total_winners'] ?>" required
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-sm text-gray-500">How many winners will be selected for this draw</p>
                        </div>

                        <div>
                            <label for="draw_date" class="block text-sm font-medium text-gray-700">Draw Date & Time</label>
                            <input type="datetime-local" name="draw_date" id="draw_date" required
                                value="<?= date('Y-m-d\TH:i', strtotime($draw['draw_date'])) ?>"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_manual_selection" id="is_manual_selection"
                                <?= $draw['is_manual_selection'] ? 'checked' : '' ?>
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Manual Winner Selection</span>
                        </label>
                        <p class="mt-1 text-sm text-gray-500">Check this if you want to manually select winners instead of automatic selection</p>
                    </div>

                    <!-- Winner Prize Configuration -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Winner Prize Configuration</h3>
                        <p class="text-sm text-gray-600 mb-4">Configure the prize amounts for each winner position. You can adjust these amounts as needed.</p>

                        <div id="winner-prizes" class="space-y-4">
                            <!-- Prize inputs will be dynamically generated here -->
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div id="productFields" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="product_draw_date" class="block text-sm font-medium text-gray-700">Draw Date & Time</label>
                            <input type="datetime-local" name="product_draw_date" id="product_draw_date"
                                value="<?= date('Y-m-d\TH:i', strtotime($draw['draw_date'])) ?>" required
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="product_value" class="block text-sm font-medium text-gray-700">Product Value (Rs.)</label>
                            <div class="mt-1 relative rounded-lg shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rs.</span>
                                </div>
                                <input type="number" name="product_value" id="product_value" step="0.01" min="0.01"
                                    value="<?= $draw['product_value'] ?? '' ?>"
                                    class="pl-7 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="product_details" class="block text-sm font-medium text-gray-700">Product Details</label>
                        <textarea name="product_details" id="product_details" rows="4"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Describe the product, specifications, brand, model, etc..."><?= esc($draw['product_details'] ?? '') ?></textarea>
                    </div>

                    <div>
                        <label for="product_image" class="block text-sm font-medium text-gray-700">Product Image</label>
                        <div class="mt-1 flex items-center space-x-6">
                            <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                                <?php if ($draw['product_image']): ?>
                                    <img id="product-image-preview" src="<?= base_url($draw['product_image']) ?>" alt="Product Image" class="w-full h-full object-cover">
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
                </div>
            <?php endif; ?>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="active" <?= $draw['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $draw['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    <option value="completed" <?= $draw['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="<?= base_url('admin/lucky-draws') ?>" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                    Update Lucky Draw
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const totalWinnersInput = document.getElementById('total_winners');
        const winnerPrizesContainer = document.getElementById('winner-prizes');
        const productImageInput = document.getElementById('product_image');
        const productImagePreview = document.getElementById('product-image-preview');
        const productDefaultIcon = document.getElementById('product-default-icon');

        <?php if ($draw['draw_type'] === 'cash'): ?>
            // Update winner prizes when total winners changes
            if (totalWinnersInput) {
                totalWinnersInput.addEventListener('change', updateWinnerPrizes);
                updateWinnerPrizes();
            }
        <?php endif; ?>

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

        // Form validation
        const form = document.getElementById('editLuckDrawForm');
        form.addEventListener('submit', function(e) {
            const drawType = '<?= $draw['draw_type'] ?>';

            if (drawType === 'cash') {
                // Validate cash draw fields
                if (totalWinnersInput && (!totalWinnersInput.value || totalWinnersInput.value < 1)) {
                    e.preventDefault();
                    alert('Please enter a valid number of winners');
                    return false;
                }

                // Validate prize amounts
                const prizeInputs = document.querySelectorAll('input[name="prize_amounts[]"]');
                let totalPrize = 0;
                prizeInputs.forEach(input => {
                    if (input.value) {
                        totalPrize += parseFloat(input.value);
                    }
                });

                if (totalPrize <= 0) {
                    e.preventDefault();
                    alert('Please configure prize amounts for winners');
                    return false;
                }
            } else {
                // Validate product draw fields
                const productDrawDate = document.getElementById('product_draw_date');
                if (productDrawDate && !productDrawDate.value) {
                    e.preventDefault();
                    alert('Please select a draw date for the product draw');
                    return false;
                }
            }
        });

        <?php if ($draw['draw_type'] === 'cash'): ?>

            function updateWinnerPrizes() {
                const totalWinners = parseInt(totalWinnersInput.value) || 1;
                winnerPrizesContainer.innerHTML = '';

                // Get existing prize amounts if available
                const existingPrizes = <?= json_encode($draw['prize_amounts'] ?? []) ?>;

                for (let i = 1; i <= totalWinners; i++) {
                    const prizeDiv = document.createElement('div');
                    prizeDiv.className = 'grid grid-cols-1 md:grid-cols-2 gap-4';

                    const positionDiv = document.createElement('div');
                    const existingAmount = existingPrizes[i - 1] ?? '';
                    positionDiv.innerHTML = `
                <label class="block text-sm font-medium text-gray-700">${getOrdinal(i)} Place</label>
                <div class="mt-1 relative rounded-lg shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rs.</span>
                    </div>
                    <input type="number" name="prize_amounts[]" step="0.01" min="0.01" required 
                           value="${existingAmount}"
                           class="pl-7 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                           placeholder="0.00">
                </div>
            `;

                    prizeDiv.appendChild(positionDiv);
                    winnerPrizesContainer.appendChild(prizeDiv);
                }
            }
        <?php endif; ?>

        function getOrdinal(n) {
            const s = ["th", "st", "nd", "rd"];
            const v = n % 100;
            return n + (s[(v - 20) % 10] || s[v] || s[0]);
        }
    });
</script>
<?= $this->endSection() ?>