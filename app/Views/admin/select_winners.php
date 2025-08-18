<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl shadow-lg p-8 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-trophy text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Select Winners</h1>
                    <p class="text-purple-100 text-lg mt-1"><?= esc($draw['title']) ?></p>
                    <div class="flex items-center space-x-4 mt-2 text-sm">
                        <span class="bg-white/20 px-3 py-1 rounded-full">
                            <i class="fas fa-dollar-sign mr-1"></i>
                            Prize: Rs. <?= number_format($draw['prize_amount'], 2) ?>
                        </span>
                        <span class="bg-white/20 px-3 py-1 rounded-full">
                            <i class="fas fa-users mr-1"></i>
                            Winners: <?= $draw['total_winners'] ?>
                        </span>
                        <span class="bg-white/20 px-3 py-1 rounded-full">
                            <i class="fas fa-ticket-alt mr-1"></i>
                            Entries: <?= count($entries) ?>
                        </span>
                    </div>
                </div>
            </div>
            <a href="<?= base_url('admin/cash-draws') ?>" class="inline-flex items-center px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-xl transition-all duration-200 backdrop-blur-sm">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Cash Draws
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <p class="text-green-700 font-medium"><?= session()->getFlashdata('success') ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                <p class="text-red-700 font-medium"><?= session()->getFlashdata('error') ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Winner Selection Methods -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Random Selection -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-random text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Random Selection</h2>
                        <p class="text-gray-600">Let the system randomly pick winners</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h3 class="font-semibold text-green-800 mb-2">How it works:</h3>
                        <ul class="text-sm text-green-700 space-y-1">
                            <li>• System randomly selects <?= $draw['total_winners'] ?> winner(s)</li>
                            <li>• Prizes are automatically distributed</li>
                            <li>• First place gets the largest share</li>
                            <li>• Fair and transparent selection</li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-2">Suggested Prize Distribution:</h4>
                        <div class="space-y-2">
                            <?php foreach ($suggested_prizes as $position => $amount): ?>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600"><?= getOrdinal($position + 1) ?> Place:</span>
                                    <span class="font-semibold text-gray-900">Rs. <?= number_format($amount, 2) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <button id="randomSelectBtn" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="fas fa-dice mr-2"></i>
                        Select Random Winners
                    </button>
                </div>
            </div>
        </div>

        <!-- Manual Selection -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-hand-pointer text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Manual Selection</h2>
                        <p class="text-gray-600">Choose winners manually</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="font-semibold text-blue-800 mb-2">How it works:</h3>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• You choose each winner manually</li>
                            <li>• Set custom prize amounts</li>
                            <li>• Full control over selection</li>
                            <li>• Review entries before deciding</li>
                        </ul>
                    </div>

                    <button id="manualSelectBtn" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="fas fa-edit mr-2"></i>
                        Manual Selection
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Manual Selection Form (Hidden by default) -->
    <div id="manualSelectionForm" class="hidden bg-white rounded-2xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Manual Winner Selection</h2>
            <p class="text-gray-600 mt-1">Choose winners from the entries below and set their prize amounts</p>
        </div>

        <form action="<?= base_url('admin/select-winners/' . $draw['id']) ?>" method="post" class="p-6 space-y-6">
            <?= csrf_field() ?>

            <!-- Winner Selection -->
            <div class="space-y-4">
                <?php for ($i = 0; $i < $draw['total_winners']; $i++): ?>
                    <div class="border border-gray-200 rounded-xl p-6 bg-gray-50">
                        <h4 class="font-bold text-lg text-gray-900 mb-4 flex items-center">
                            <span class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">
                                <?= $i + 1 ?>
                            </span>
                            <?= getOrdinal($i + 1) ?> Place Winner
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Winner</label>
                                <select name="winners[]" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 p-3">
                                    <option value="">Choose a winner...</option>
                                    <?php foreach ($entries as $entry): ?>
                                        <option value="<?= $entry['user_id'] ?>" <?= isset($existing_winners[$i]) && $existing_winners[$i]['user_id'] == $entry['user_id'] ? 'selected' : '' ?>>
                                            <?= esc($entry['full_name']) ?> (@<?= esc($entry['username']) ?>) - <?= esc($entry['email']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Prize Amount (Rs.)</label>
                                <input type="number" name="prize_amounts[]" step="0.01" min="0.01" required
                                    value="<?= isset($existing_winners[$i]) ? $existing_winners[$i]['prize_amount'] : (isset($suggested_prizes[$i]) ? $suggested_prizes[$i] : '') ?>"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 p-3"
                                    placeholder="0.00">
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <button type="button" id="cancelManualBtn" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-lg transition-all duration-200 font-bold shadow-lg hover:shadow-xl">
                    <i class="fas fa-trophy mr-2"></i>
                    Select Winners
                </button>
            </div>
        </form>
    </div>

    <!-- All Entries -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">All Entries (<?= count($entries) ?>)</h2>
            <p class="text-gray-600 mt-1">Complete list of participants in this draw</p>
        </div>
        <div class="p-6">
            <?php if (empty($entries)): ?>
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-ticket-alt text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Entries Yet</h3>
                    <p class="text-gray-500">No one has entered this draw yet.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($entries as $index => $entry): ?>
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-4 rounded-xl border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                        <?= strtoupper(substr($entry['full_name'], 0, 1)) ?>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-900 truncate"><?= esc($entry['full_name']) ?></p>
                                    <p class="text-sm text-gray-600 truncate">@<?= esc($entry['username']) ?></p>
                                    <p class="text-xs text-gray-500 truncate"><?= esc($entry['email']) ?></p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        <i class="fas fa-calendar mr-1"></i>
                                        <?= date('M j, Y g:i A', strtotime($entry['created_at'])) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Random Selection Modal -->
<div id="randomModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
        <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-dice text-white text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Random Winner Selection</h3>
            <p class="text-gray-600 mb-6">Are you sure you want to randomly select winners? This action cannot be undone.</p>
            <div class="flex space-x-3">
                <button id="cancelRandomBtn" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button id="confirmRandomBtn" class="flex-1 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const manualSelectBtn = document.getElementById('manualSelectBtn');
        const randomSelectBtn = document.getElementById('randomSelectBtn');
        const manualSelectionForm = document.getElementById('manualSelectionForm');
        const cancelManualBtn = document.getElementById('cancelManualBtn');
        const randomModal = document.getElementById('randomModal');
        const cancelRandomBtn = document.getElementById('cancelRandomBtn');
        const confirmRandomBtn = document.getElementById('confirmRandomBtn');

        // Show manual selection form
        manualSelectBtn.addEventListener('click', function() {
            manualSelectionForm.classList.remove('hidden');
            manualSelectionForm.scrollIntoView({
                behavior: 'smooth'
            });
        });

        // Hide manual selection form
        cancelManualBtn.addEventListener('click', function() {
            manualSelectionForm.classList.add('hidden');
        });

        // Show random selection modal
        randomSelectBtn.addEventListener('click', function() {
            randomModal.classList.remove('hidden');
            randomModal.classList.add('flex');
        });

        // Hide random selection modal
        cancelRandomBtn.addEventListener('click', function() {
            randomModal.classList.add('hidden');
            randomModal.classList.remove('flex');
        });

        // Confirm random selection
        confirmRandomBtn.addEventListener('click', function() {
            confirmRandomBtn.disabled = true;
            confirmRandomBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Selecting...';

            fetch('<?= base_url('admin/select-random-winners/' . $draw['id']) ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message and redirect
                        alert(data.message);
                        window.location.href = '<?= base_url('admin/cash-draws') ?>';
                    } else {
                        alert('Error: ' + data.message);
                        confirmRandomBtn.disabled = false;
                        confirmRandomBtn.innerHTML = 'Confirm';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while selecting winners.');
                    confirmRandomBtn.disabled = false;
                    confirmRandomBtn.innerHTML = 'Confirm';
                });
        });
    });
</script>

<?php
function getOrdinal($n)
{
    $s = ["th", "st", "nd", "rd"];
    $v = $n % 100;
    return $n . ($s[($v - 20) % 10] ?? $s[$v] ?? $s[0]);
}
?>
<?= $this->endSection() ?>