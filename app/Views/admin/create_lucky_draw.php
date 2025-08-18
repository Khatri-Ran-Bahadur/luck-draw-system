<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-8">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-lg p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold mb-2">Create New Lucky Draw</h2>
                <p class="text-blue-100 text-lg">Set up a new lucky draw with custom entry fees and multiple winners</p>
            </div>
            <a href="<?= base_url('admin/lucky-draws') ?>" class="inline-flex items-center px-6 py-3 bg-white/20 hover:bg-white/30 text-white font-medium rounded-xl transition-all duration-200 backdrop-blur-sm border border-white/30">
                <i class="fas fa-arrow-left mr-3"></i>
                Back to Draws
            </a>
        </div>
    </div>

    <!-- Create Form -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100">
        <form action="<?= base_url('admin/lucky-draws/create') ?>" method="post" class="p-8 space-y-8" id="luckyDrawForm">
            <?= csrf_field() ?>

            <!-- Draw Type Selection -->
            <div class="border-b border-gray-200 pb-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Draw Type</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <label class="relative flex cursor-pointer rounded-xl border-2 border-gray-200 bg-white p-6 shadow-sm hover:border-blue-300 hover:shadow-md transition-all duration-200 focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-100">
                        <input type="radio" name="draw_type" value="cash" class="sr-only" checked>
                        <span class="flex flex-1">
                            <span class="flex flex-col">
                                <span class="block text-lg font-semibold text-gray-900 mb-2">Cash Lucky Draw</span>
                                <span class="flex items-center text-gray-600">
                                    <i class="fas fa-dollar-sign text-green-500 mr-3 text-xl"></i>
                                    Users pay to win cash prizes
                                </span>
                            </span>
                        </span>
                        <span class="pointer-events-none absolute -inset-px rounded-xl border-2 border-transparent" aria-hidden="true"></span>
                    </label>
                    <label class="relative flex cursor-pointer rounded-xl border-2 border-gray-200 bg-white p-6 shadow-sm hover:border-blue-300 hover:shadow-md transition-all duration-200 focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-100">
                        <input type="radio" name="draw_type" value="product" class="sr-only">
                        <span class="flex flex-1">
                            <span class="flex flex-col">
                                <span class="block text-lg font-semibold text-gray-900 mb-2">Product Lucky Draw</span>
                                <span class="flex items-center text-gray-600">
                                    <i class="fas fa-gift text-blue-500 mr-3 text-xl"></i>
                                    Users pay to win products
                                </span>
                            </span>
                        </span>
                        <span class="pointer-events-none absolute -inset-px rounded-xl border-2 border-transparent" aria-hidden="true"></span>
                    </label>
                </div>
            </div>

            <!-- Basic Information -->
            <div class="space-y-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Basic Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label for="title" class="block text-sm font-semibold text-gray-700">Draw Title</label>
                        <input type="text" name="title" id="title" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 text-lg"
                            placeholder="e.g., Win $1000, iPhone Giveaway">
                    </div>

                    <div class="space-y-3">
                        <label for="entry_fee" class="block text-sm font-semibold text-gray-700">Entry Fee ($)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-lg font-medium">$</span>
                            </div>
                            <input type="number" name="entry_fee" id="entry_fee" step="0.01" min="0.01" required
                                class="pl-12 w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 text-lg"
                                placeholder="0.00">
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <label for="description" class="block text-sm font-semibold text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="5" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 text-lg resize-none"
                        placeholder="Describe the lucky draw, prizes, and any special conditions..."></textarea>
                </div>
            </div>

            <!-- Dynamic Fields Based on Draw Type -->
            <div id="cashFields" class="space-y-8">
                <div class="space-y-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Cash Draw Configuration</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label for="total_winners" class="block text-sm font-semibold text-gray-700">Total Winners</label>
                            <input type="number" name="total_winners" id="total_winners" min="1" max="99" value="3" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 text-lg">
                            <p class="text-sm text-gray-500 mt-2">How many winners will be selected for this draw</p>
                        </div>

                        <div class="space-y-3">
                            <label for="draw_date" class="block text-sm font-semibold text-gray-700">Draw Date & Time</label>
                            <input type="datetime-local" name="draw_date" id="draw_date" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 text-lg">
                        </div>
                    </div>

                    <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_manual_selection" id="is_manual_selection"
                                class="w-5 h-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-4 focus:ring-blue-100 focus:ring-offset-0">
                            <span class="ml-3 text-lg font-semibold text-blue-900">Manual Winner Selection</span>
                        </label>
                        <p class="mt-2 text-blue-700">Check this if you want to manually select winners instead of automatic selection</p>
                    </div>

                    <!-- Winner Prize Configuration -->
                    <div class="border-t-2 border-gray-200 pt-8">
                        <h4 class="text-xl font-bold text-gray-900 mb-4">Winner Prize Configuration</h4>
                        <p class="text-gray-600 mb-6">Configure the prize amounts for each winner position. You can adjust these amounts as needed.</p>

                        <div id="winner-prizes" class="space-y-6">
                            <!-- Prize inputs will be dynamically generated here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Fields (Hidden by default) -->
            <div id="productFields" class="space-y-8 hidden">
                <div class="space-y-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Product Draw Configuration</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label for="product_draw_date" class="block text-sm font-semibold text-gray-700">Draw Date & Time</label>
                            <input type="datetime-local" name="product_draw_date" id="product_draw_date"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 text-lg">
                        </div>

                        <div class="space-y-3">
                            <label for="product_value" class="block text-sm font-semibold text-gray-700">Product Value ($)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-lg font-medium">$</span>
                                </div>
                                <input type="number" name="product_value" id="product_value" step="0.01" min="0.01"
                                    class="pl-12 w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 text-lg"
                                    placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label for="product_details" class="block text-sm font-semibold text-gray-700">Product Details</label>
                        <textarea name="product_details" id="product_details" rows="5"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 text-lg resize-none"
                            placeholder="Describe the product, specifications, brand, model, etc..."></textarea>
                    </div>

                    <div class="space-y-3">
                        <label for="product_image" class="block text-sm font-semibold text-gray-700">Product Image</label>
                        <div class="flex items-center space-x-8">
                            <div class="w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300">
                                <img id="product-image-preview" src="" alt="" class="w-full h-full object-cover hidden">
                                <i class="fas fa-image text-4xl text-gray-400" id="product-default-icon"></i>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="product_image" id="product_image" accept="image/*"
                                    class="block w-full text-lg text-gray-500 file:mr-6 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-lg file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all duration-200">
                                <p class="mt-3 text-sm text-gray-500">PNG, JPG, GIF up to 5MB. Optional.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4 pt-8 border-t-2 border-gray-200">
                <a href="<?= base_url('admin/lucky-draws') ?>" class="px-8 py-4 border-2 border-gray-300 text-lg font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                    <i class="fas fa-plus mr-3"></i>
                    Create Lucky Draw
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const drawTypeInputs = document.querySelectorAll('input[name="draw_type"]');
        const cashFields = document.getElementById('cashFields');
        const productFields = document.getElementById('productFields');
        const totalWinnersInput = document.getElementById('total_winners');
        const winnerPrizesContainer = document.getElementById('winner-prizes');
        const drawDateInput = document.getElementById('draw_date');
        const productDrawDateInput = document.getElementById('product_draw_date');
        const productImageInput = document.getElementById('product_image');
        const productImagePreview = document.getElementById('product-image-preview');
        const productDefaultIcon = document.getElementById('product-default-icon');

        // Draw type change handler
        drawTypeInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (this.value === 'cash') {
                    cashFields.classList.remove('hidden');
                    productFields.classList.add('hidden');
                    // Sync dates
                    if (productDrawDateInput.value) {
                        drawDateInput.value = productDrawDateInput.value;
                    }
                } else {
                    cashFields.classList.add('hidden');
                    productFields.classList.remove('hidden');
                    // Sync dates
                    if (drawDateInput.value) {
                        productDrawDateInput.value = drawDateInput.value;
                    }
                }
            });
        });

        // Update winner prizes when total winners changes
        totalWinnersInput.addEventListener('change', updateWinnerPrizes);

        // Initialize winner prizes
        updateWinnerPrizes();

        // Product image preview
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
                    productDefaultIcon.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                productImagePreview.classList.add('hidden');
                productDefaultIcon.classList.remove('hidden');
            }
        });

        // Form validation
        const form = document.getElementById('luckyDrawForm');
        form.addEventListener('submit', function(e) {
            const selectedType = document.querySelector('input[name="draw_type"]:checked').value;

            if (selectedType === 'cash') {
                // Validate cash draw fields
                if (!totalWinnersInput.value || totalWinnersInput.value < 1) {
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
                if (!productDrawDateInput.value) {
                    e.preventDefault();
                    alert('Please select a draw date for the product draw');
                    return false;
                }
            }
        });

        function updateWinnerPrizes() {
            const totalWinners = parseInt(totalWinnersInput.value) || 1;
            winnerPrizesContainer.innerHTML = '';

            for (let i = 1; i <= totalWinners; i++) {
                const prizeDiv = document.createElement('div');
                prizeDiv.className = 'bg-gray-50 border-2 border-gray-200 rounded-xl p-6';

                const positionDiv = document.createElement('div');
                positionDiv.innerHTML = `
                <label class="block text-lg font-semibold text-gray-700 mb-3">${getOrdinal(i)} Place Prize</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-gray-500 text-lg font-medium">$</span>
                    </div>
                    <input type="number" name="prize_amounts[]" step="0.01" min="0.01" required 
                           class="pl-12 w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 text-lg"
                           placeholder="0.00">
                </div>
            `;

                prizeDiv.appendChild(positionDiv);
                winnerPrizesContainer.appendChild(prizeDiv);
            }
        }

        function getOrdinal(n) {
            const s = ["th", "st", "nd", "rd"];
            const v = n % 100;
            return n + (s[(v - 20) % 10] || s[v] || s[0]);
        }

        // Set default draw date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        tomorrow.setHours(12, 0, 0, 0);
        const tomorrowString = tomorrow.toISOString().slice(0, 16);
        drawDateInput.value = tomorrowString;
        productDrawDateInput.value = tomorrowString;
    });
</script>
<?= $this->endSection() ?>