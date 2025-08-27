<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Wallet Top-up</h1>
            <p class="text-gray-600">Choose your preferred top-up method</p>
        </div>



        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Top-up Form -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-upload text-blue-600 mr-3"></i>
                        Manual Top-up Request
                    </h2>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>



                    <form action="<?= base_url('wallet/topup') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <!-- Wallet Selection -->
                        <?php if (!empty($special_user_wallets ?? [])): ?>


                            <div>
                                <label for="wallet_selection" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-wallet text-green-600 mr-2"></i>
                                    Select Wallet to Send Money To
                                </label>
                                <select id="wallet_selection" name="wallet_selection" required
                                    class="block w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                    <option value="">Choose a wallet...</option>
                                    <?php
                                    $wallets = $special_user_wallets ?? [];
                                    if (is_array($wallets) && !empty($wallets)):
                                        foreach ($wallets as $wallet):
                                    ?>
                                            <option value="<?= $wallet['user_id'] ?? $wallet['id'] ?? '' ?>"
                                                data-wallet-name="<?= esc($wallet['wallet_name'] ?? 'N/A') ?>"
                                                data-wallet-number="<?= esc($wallet['wallet_number'] ?? 'N/A') ?>"
                                                data-wallet-type="<?= esc($wallet['wallet_type'] ?? 'N/A') ?>"
                                                data-bank-name="<?= esc($wallet['bank_name'] ?? 'N/A') ?>"
                                                data-user-name="<?= esc($wallet['full_name'] ?? $wallet['username'] ?? 'Unknown User') ?>">
                                                <?= esc($wallet['full_name'] ?? $wallet['username'] ?? 'Unknown User') ?> -
                                                <?= esc($wallet['wallet_type'] ?? 'Unknown Type') ?>
                                                (<?= esc($wallet['wallet_number'] ?? 'N/A') ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <p class="mt-1 text-sm text-gray-500">Select the wallet where you will send your payment</p>
                            </div>
                            <!-- Selected Wallet Details -->
                            <div id="selected_wallet_details" class="hidden bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-xl p-6">
                                <h3 class="text-lg font-semibold text-green-900 mb-4 flex items-center">
                                    <i class="fas fa-info-circle text-green-600 mr-2"></i>
                                    Send Money To This Wallet
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-3">
                                        <div>
                                            <label class="text-xs font-medium text-gray-600 uppercase tracking-wide">Wallet Owner</label>
                                            <p class="text-lg font-semibold text-gray-900" id="wallet_owner_name">-</p>
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-600 uppercase tracking-wide">Wallet Type</label>
                                            <p class="text-sm font-medium text-gray-900" id="wallet_type_display">-</p>
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-600 uppercase tracking-wide">Bank Name</label>
                                            <p class="text-sm font-medium text-gray-900" id="bank_name_display">-</p>
                                        </div>
                                    </div>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="text-xs font-medium text-gray-600 uppercase tracking-wide">Wallet Name</label>
                                            <p class="text-sm font-semibold text-gray-900" id="wallet_name_display">-</p>
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-600 uppercase tracking-wide">Wallet Number</label>
                                            <div class="flex items-center space-x-2">
                                                <p class="text-sm font-mono text-gray-900 bg-white px-3 py-2 rounded border" id="wallet_number_display">-</p>
                                                <button type="button" onclick="copyWalletNumber()"
                                                    class="px-3 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors">
                                                    <i class="fas fa-copy mr-1"></i>Copy
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 p-3 bg-green-100 border border-green-300 rounded-lg">
                                    <div class="flex items-start">
                                        <i class="fas fa-lightbulb text-green-600 mt-1 mr-2"></i>
                                        <div class="text-sm text-green-800">
                                            <p class="font-medium">Important:</p>
                                            <p>Send the exact amount you enter below to this wallet. Make sure to keep your payment proof for verification.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                                <i class="fas fa-exclamation-triangle text-yellow-600 text-3xl mb-4"></i>
                                <h3 class="text-lg font-medium text-yellow-800 mb-2">No Special User Wallets Available</h3>
                                <p class="text-yellow-700 mb-4">
                                    Currently, there are no active special user wallets available for topups.
                                    Only special users with complete wallet information are shown here.
                                </p>
                                <a href="<?= base_url('dashboard') ?>" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- Amount Input -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Top-up Amount (PKR)
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                    Rs.
                                </span>
                                <input type="number"
                                    id="amount"
                                    name="amount"
                                    step="0.01"
                                    min="500"
                                    max="50000"
                                    class="block w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter amount"
                                    required>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                Min: Rs. 500 | Max: Rs. 50,000
                            </p>
                        </div>

                        <!-- Payment Proof Upload -->
                        <div>
                            <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Proof (Slip/Screenshot)
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                                <input type="file"
                                    id="payment_proof"
                                    name="payment_proof"
                                    accept="image/*,.pdf"
                                    class="hidden"
                                    required>
                                <label for="payment_proof" class="cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-lg font-medium text-gray-700 mb-2">Click to upload payment proof</p>
                                    <p class="text-sm text-gray-500">PNG, JPG, PDF up to 5MB</p>
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                Upload a clear screenshot or photo of your payment slip/receipt
                            </p>
                        </div>

                        <!-- Additional Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Notes (Optional)
                            </label>
                            <textarea id="notes"
                                name="notes"
                                rows="3"
                                class="block w-full py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Any additional information about your payment..."></textarea>
                        </div>

                        <!-- Hidden wallet selection field -->
                        <input type="hidden" name="selected_wallet_id" id="selected_wallet_id" value="">

                        <!-- Submit Button -->
                        <button type="submit" id="submit_btn"
                            class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Submit Top-up Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Show success message
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check mr-1"></i>Copied!';
            button.classList.add('text-green-600');

            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('text-green-600');
            }, 2000);
        }).catch(function(err) {
            console.error('Could not copy text: ', err);
            alert('Failed to copy to clipboard');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const walletSelection = document.getElementById('wallet_selection');
        const selectedWalletDetails = document.getElementById('selected_wallet_details');
        const walletOwnerName = document.getElementById('wallet_owner_name');
        const walletTypeDisplay = document.getElementById('wallet_type_display');
        const bankNameDisplay = document.getElementById('bank_name_display');
        const walletNameDisplay = document.getElementById('wallet_name_display');
        const walletNumberDisplay = document.getElementById('wallet_number_display');

        walletSelection.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const walletId = selectedOption.value;
            const walletName = selectedOption.dataset.walletName;
            const walletNumber = selectedOption.dataset.walletNumber;
            const walletType = selectedOption.dataset.walletType;
            const bankName = selectedOption.dataset.bankName;
            const userName = selectedOption.dataset.userName;

            if (walletId) {
                selectedWalletDetails.classList.remove('hidden');
                walletOwnerName.textContent = userName || 'N/A';
                walletTypeDisplay.textContent = walletType || 'N/A';
                bankNameDisplay.textContent = bankName || 'N/A';
                walletNameDisplay.textContent = walletName || 'N/A';
                walletNumberDisplay.textContent = walletNumber || 'N/A';

                // Update hidden field for form submission
                document.getElementById('selected_wallet_id').value = walletId;
            } else {
                selectedWalletDetails.classList.add('hidden');
                document.getElementById('selected_wallet_id').value = '';
            }
        });

        // Form validation
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const selectedWallet = document.getElementById('wallet_selection').value;
                if (!selectedWallet) {
                    e.preventDefault();
                    alert('Please select a wallet to send money to.');
                    return false;
                }
            });
        }

        // Make copyWalletNumber function global
        window.copyWalletNumber = function() {
            const walletNumber = document.getElementById('wallet_number_display').textContent;
            navigator.clipboard.writeText(walletNumber).then(function() {
                const copyButton = event.target.closest('button');
                const originalText = copyButton.innerHTML;
                copyButton.innerHTML = '<i class="fas fa-check mr-1"></i>Copied!';
                copyButton.classList.add('text-green-600');

                setTimeout(() => {
                    copyButton.innerHTML = originalText;
                    copyButton.classList.remove('text-green-600');
                }, 2000);
            }).catch(function(err) {
                console.error('Could not copy text: ', err);
                alert('Failed to copy wallet number to clipboard');
            });
        }
    });
</script>
<?= $this->endSection() ?>