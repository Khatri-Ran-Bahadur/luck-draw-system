<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Create Cash Lucky Draw</h1>
                <p class="text-green-100 mt-1">Set up a cash prize draw</p>
            </div>
            <a href="<?= base_url('admin/cash-draws') ?>" class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Back
            </a>
        </div>
    </div>

    <!-- Create Form -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Cash Draw Details</h2>
        </div>

        <form action="<?= base_url('admin/cash-draws/create') ?>" method="post" class="p-6 space-y-6">
            <?= csrf_field() ?>

            <!-- Essential Fields Only -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Draw Title</label>
                    <input type="text" name="title" id="title" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="e.g., Win $1000 Cash Prize">
                </div>

                <div>
                    <label for="prize_amount" class="block text-sm font-medium text-gray-700 mb-2">Prize Amount</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-500">Rs.</span>
                        <input type="number" name="prize_amount" id="prize_amount" step="0.01" min="1" required
                            class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="1000.00">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="entry_fee" class="block text-sm font-medium text-gray-700 mb-2">Entry Fee</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-500">Rs.</span>
                        <input type="number" name="entry_fee" id="entry_fee" step="0.01" min="0.01" required
                            class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="1.00">
                    </div>
                </div>

                <div>
                    <label for="total_winners" class="block text-sm font-medium text-gray-700 mb-2">Total Winners</label>
                    <input type="number" name="total_winners" id="total_winners" min="1" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="1" value="1">
                    <p class="text-xs text-gray-500 mt-1">Number of winners for this draw</p>
                </div>

                <div>
                    <label for="draw_date" class="block text-sm font-medium text-gray-700 mb-2">Draw Date & Time</label>
                    <input type="datetime-local" name="draw_date" id="draw_date" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="3" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="Brief description of the cash draw..."></textarea>
            </div>

            <!-- Winner Selection Method -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Winner Selection Method</label>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input type="radio" name="is_manual_selection" value="0" id="auto_selection" checked
                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                        <label for="auto_selection" class="ml-3 block text-sm text-gray-700">
                            <span class="font-medium">Automatic Selection</span>
                            <span class="block text-gray-500">System will randomly select winners</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" name="is_manual_selection" value="1" id="manual_selection"
                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                        <label for="manual_selection" class="ml-3 block text-sm text-gray-700">
                            <span class="font-medium">Manual Selection</span>
                            <span class="block text-gray-500">Admin will manually select winners</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                <a href="<?= base_url('admin/cash-draws') ?>"
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Create Cash Draw
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set default draw date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        tomorrow.setHours(12, 0, 0, 0);
        document.getElementById('draw_date').value = tomorrow.toISOString().slice(0, 16);
    });
</script>
<?= $this->endSection() ?>