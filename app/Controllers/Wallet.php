<?php

namespace App\Controllers;

use App\Models\WalletModel;
use App\Models\WalletTransactionModel;
use App\Models\UserModel;
use App\Libraries\NotificationService;
use App\Libraries\CurrencyService;

class Wallet extends BaseController
{
    protected $walletModel;
    protected $walletTransactionModel;
    protected $userModel;
    protected $settingModel;
    protected $walletTopupRequestModel;
    protected $userTransferModel;
    protected $paymentConfig;
    protected $notificationService;
    protected $currencyService;

    public function __construct()
    {
        $this->walletModel = new \App\Models\WalletModel();
        $this->walletTransactionModel = new \App\Models\WalletTransactionModel();
        $this->userModel = new \App\Models\UserModel();
        $this->settingModel = new \App\Models\SettingModel();
        $this->walletTopupRequestModel = new \App\Models\WalletTopupRequestModel();
        $this->userTransferModel = new \App\Models\UserTransferModel();
        $this->paymentConfig = config('Payment');
        $this->notificationService = new NotificationService();
        $this->currencyService = new CurrencyService();
    }

    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $wallet = $this->walletModel->getUserWallet($userId);
        $recentTransactions = $this->walletTransactionModel->getUserTransactions($userId, 5);
        $pendingTopups = $this->walletTopupRequestModel->getUserTopupRequests($userId, 5);
        $user = $this->userModel->find($userId);

        // Ensure user has a wallet ID
        if (empty($user['wallet_id'])) {
            $walletId = $this->userModel->ensureWalletId($userId);
            $user['wallet_id'] = $walletId;
        }

        $data = [
            'title' => 'My Wallet',
            'wallet' => $wallet,
            'recentTransactions' => $recentTransactions,
            'pendingTopups' => $pendingTopups,
            'user' => $user
        ];

        return view('wallet/index', $data);
    }

    public function topup()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        // Ensure user has a wallet ID
        if (empty($user['wallet_id'])) {
            $walletId = $this->userModel->ensureWalletId($userId);
            $user['wallet_id'] = $walletId;
        }

        // Get users with wallet details for display
        $walletUsers = $this->userModel->getUsersWithCompleteWalletDetails(3);

        $data = [
            'title' => 'Wallet Top-up',
            'user' => $user,
            'wallet' => $this->walletModel->getUserWallet($userId),
            'walletUsers' => $walletUsers
        ];

        return view('wallet/topup', $data);
    }

    /**
     * Get enabled payment methods based on environment variables
     */
    private function getEnabledPaymentMethods()
    {
        $methods = [];

        // Manual top-up is always the primary method
        if ($this->settingModel->getManualTopupEnabled()) {
            $methods['manual'] = [
                'name' => 'Manual Top-up (Slip/Proof)',
                'description' => 'Upload payment slip or proof for admin approval',
                'icon' => 'fas fa-upload',
                'primary' => true
            ];
        }

        // Only show other methods if enabled in environment
        if ($this->settingModel->getPayPalEnabled()) {
            $methods['paypal'] = [
                'name' => 'PayPal',
                'description' => 'Secure payment via PayPal',
                'icon' => 'fab fa-paypal'
            ];
        }

        if ($this->settingModel->getEasypaisaEnabled()) {
            $methods['easypaisa'] = [
                'name' => 'Easypaisa',
                'description' => 'Mobile payment via Easypaisa',
                'icon' => 'fas fa-mobile-alt'
            ];
        }

        if ($this->settingModel->getJazzCashEnabled()) {
            $methods['jazz_cash'] = [
                'name' => 'Jazz Cash',
                'description' => 'Mobile payment via Jazz Cash',
                'icon' => 'fas fa-mobile-alt'
            ];
        }

        if ($this->settingModel->getBankTransferEnabled()) {
            $methods['bank'] = [
                'name' => 'Bank Transfer',
                'description' => 'Direct bank transfer',
                'icon' => 'fas fa-university'
            ];
        }

        return $methods;
    }

    // Manual top-up request
    public function manualTopup()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login')->with('error', 'Please login to access your wallet');
        }

        if ($this->request->getMethod() === 'POST') {
            $amount = $this->request->getPost('amount');
            $paymentMethod = $this->request->getPost('payment_method');
            $paymentProof = $this->request->getFile('payment_proof');

            // Validate amount
            $minAmount = $this->settingModel->getMinTopupAmount();
            $maxAmount = $this->settingModel->getMaxTopupAmount();

            if (!$amount || $amount < $minAmount || $amount > $maxAmount) {
                return redirect()->back()->with('error', "Please enter a valid amount (Rs. {$minAmount} - Rs. {$maxAmount})");
            }

            // Validate payment method
            if (!$paymentMethod || !in_array($paymentMethod, ['easypaisa', 'jazz_cash', 'bank', 'manual'])) {
                return redirect()->back()->with('error', 'Please select a valid payment method');
            }

            $userId = session()->get('user_id');
            $wallet = $this->walletModel->getUserWallet($userId);

            // Handle file upload if provided
            $proofPath = null;
            if ($paymentProof && $paymentProof->isValid() && !$paymentProof->hasMoved()) {
                $newName = $paymentProof->getRandomName();
                $paymentProof->move(ROOTPATH . 'public/uploads/topup_proofs', $newName);
                $proofPath = 'uploads/topup_proofs/' . $newName;
            }

            // Create top-up request
            $requestId = $this->walletTopupRequestModel->insert([
                'user_id' => $userId,
                'amount' => $amount,
                'payment_method' => $paymentMethod,
                'payment_proof' => $proofPath,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if ($requestId) {
                // Send notification to admin
                $this->notificationService->notifyAdmin('topup_request', $userId, [
                    'amount' => $amount,
                    'payment_method' => $paymentMethod,
                    'request_id' => $requestId
                ]);

                return redirect()->to('wallet')->with('success', 'Top-up request submitted successfully. Please wait for admin approval.');
            } else {
                return redirect()->back()->with('error', 'Failed to submit top-up request. Please try again.');
            }
        }

        $data = [
            'min_amount' => $this->settingModel->getMinTopupAmount(),
            'max_amount' => $this->settingModel->getMaxTopupAmount(),
            'random_wallets' => $this->userModel->getRandomWalletsForTopup(
                $this->settingModel->getWalletDisplayCount(),
                session()->get('user_id')
            )
        ];

        return view('wallet/manual_topup', $data);
    }

    /**
     * Submit manual topup request via AJAX
     */
    public function submitManualTopup()
    {
        if (!session()->get('user_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not logged in']);
        }

        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        // Validate input
        $amount = $this->request->getPost('amount');
        $paymentMethod = $this->request->getPost('payment_method');
        $notes = $this->request->getPost('notes');
        $paymentProof = $this->request->getFile('payment_proof');

        if (!$amount || $amount < 500 || $amount > 50000) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid amount']);
        }

        if (!$paymentMethod) {
            return $this->response->setJSON(['success' => false, 'message' => 'Payment method required']);
        }

        if (!$paymentProof || !$paymentProof->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Payment proof required']);
        }

        try {
            $userId = session()->get('user_id');

            // Handle file upload
            $uploadPath = 'uploads/payment_proofs/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $fileName = 'topup_' . $userId . '_' . time() . '.' . $paymentProof->getExtension();
            $paymentProof->move($uploadPath, $fileName);
            $filePath = $uploadPath . $fileName;

            // Create topup request
            $requestData = [
                'user_id' => $userId,
                'amount' => $amount,
                'payment_method' => $paymentMethod,
                'payment_proof' => $filePath,
                'notes' => $notes,
                'status' => 'pending'
            ];

            $requestId = $this->walletTopupRequestModel->insert($requestData);

            if ($requestId) {
                // Send notification to admin
                $this->notificationService->notifyAdmin('topup_request', $userId, [
                    'amount' => $amount,
                    'payment_method' => $paymentMethod,
                    'request_id' => $requestId
                ]);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Top-up request submitted successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create top-up request'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Manual topup submission failed: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while processing your request'
            ]);
        }
    }

    /**
     * User-to-user money transfer (only for special users)
     */
    public function transfer()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        // Only special users can transfer money
        if (!($user['is_special_user'] ?? false)) {
            return redirect()->to('wallet')->with('error', 'Only special users can transfer money to other users');
        }

        if ($this->request->getMethod() === 'POST') {
            $toUsername = $this->request->getPost('to_username');
            $amount = (float) $this->request->getPost('amount');
            $notes = $this->request->getPost('notes');

            // Validate input
            if (!$toUsername || !$amount || $amount <= 0) {
                return redirect()->back()->with('error', 'Please provide valid recipient username and amount');
            }

            // Check if recipient exists
            $recipient = $this->userModel->where('username', $toUsername)->first();
            if (!$recipient) {
                return redirect()->back()->with('error', 'Recipient user not found');
            }

            if ($recipient['id'] == $userId) {
                return redirect()->back()->with('error', 'You cannot transfer money to yourself');
            }

            // Check if sender has sufficient balance
            $senderWallet = $this->walletModel->getUserWallet($userId);
            if (!$senderWallet || $senderWallet['balance'] < $amount) {
                return redirect()->back()->with('error', 'Insufficient balance for transfer');
            }

            try {
                // Create transfer request
                $transferData = [
                    'from_user_id' => $userId,
                    'to_user_id' => $recipient['id'],
                    'amount' => $amount,
                    'status' => 'pending',
                    'admin_notes' => $notes,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $transferId = $this->userTransferModel->insert($transferData);
                if ($transferId) {
                    // Notify admin about the transfer request
                    $this->notificationService->notifyAdmin('transfer_request', $userId, [
                        'amount' => $amount,
                        'recipient' => $toUsername,
                        'transfer_id' => $transferId
                    ]);

                    return redirect()->back()->with('success', 'Transfer request submitted successfully. Waiting for admin approval.');
                } else {
                    return redirect()->back()->with('error', 'Failed to submit transfer request');
                }
            } catch (\Exception $e) {
                log_message('error', 'Transfer request failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'An error occurred while processing your transfer request');
            }
        }

        // Get user's recent transfers
        $recentTransfers = $this->userTransferModel->getUserTransfers($userId, 10);

        // Get user's wallet balance
        $wallet = $this->walletModel->getUserWallet($userId);

        $data = [
            'title' => 'Send Money',
            'user' => $user,
            'wallet' => $wallet,
            'recentTransfers' => $recentTransfers
        ];

        return view('wallet/transfer', $data);
    }

    /**
     * Withdraw money from wallet (only for special users)
     */
    public function withdraw()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        // Ensure user has a wallet ID
        if (empty($user['wallet_id'])) {
            $walletId = $this->userModel->ensureWalletId($userId);
            $user['wallet_id'] = $walletId;
        }

        // All users can request withdrawals, but special users get priority
        // Note: Special users can withdraw directly, normal users request withdrawals

        if ($this->request->getMethod() === 'POST') {
            $amount = (float) $this->request->getPost('amount');
            $withdrawMethod = $this->request->getPost('withdraw_method');
            $accountDetails = $this->request->getPost('account_details');
            $notes = $this->request->getPost('notes');

            // Validate input
            if (!$amount || $amount <= 0) {
                return redirect()->back()->with('error', 'Please provide a valid withdrawal amount');
            }

            if (!$withdrawMethod) {
                return redirect()->back()->with('error', 'Please select a withdrawal method');
            }

            if (!$accountDetails) {
                return redirect()->back()->with('error', 'Please provide account details for withdrawal');
            }

            // Check if user has sufficient balance
            $wallet = $this->walletModel->getUserWallet($userId);
            if (!$wallet || $wallet['balance'] < $amount) {
                return redirect()->back()->with('error', 'Insufficient balance for withdrawal');
            }

            // Check minimum withdrawal amount
            $minWithdraw = $this->settingModel->getMinWithdrawAmount() ?? 1000;
            if ($amount < $minWithdraw) {
                return redirect()->back()->with('error', "Minimum withdrawal amount is Rs. {$minWithdraw}");
            }

            try {
                // Create withdrawal request
                $withdrawData = [
                    'user_id' => $userId,
                    'amount' => $amount,
                    'withdraw_method' => $withdrawMethod,
                    'account_details' => $accountDetails,
                    'notes' => $notes,
                    'status' => 'pending',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                // Get user's wallet ID for the transaction
                $wallet = $this->walletModel->getUserWallet($userId);
                if (!$wallet) {
                    return redirect()->back()->with('error', 'Wallet not found');
                }

                // Create withdrawal transaction
                $transactionData = [
                    'wallet_id' => $wallet['id'],
                    'type' => 'withdrawal',
                    'amount' => -$amount, // Negative amount for withdrawal
                    'balance_before' => $wallet['balance'],
                    'balance_after' => $wallet['balance'], // No change until completed
                    'status' => 'pending',
                    'description' => "Withdrawal request via {$withdrawMethod}",
                    'payment_method' => $withdrawMethod,
                    'payment_reference' => 'WITHDRAW_' . time(),
                    'metadata' => json_encode([
                        'withdraw_method' => $withdrawMethod,
                        'account_details' => $accountDetails,
                        'notes' => $notes,
                        'timestamp' => time()
                    ])
                ];

                $transactionId = $this->walletTransactionModel->insert($transactionData);
                if ($transactionId) {
                    // Notify admin about the withdrawal request
                    $this->notificationService->notifyAdmin('withdrawal_request', $userId, [
                        'amount' => $amount,
                        'method' => $withdrawMethod,
                        'account_details' => $accountDetails,
                        'transaction_id' => $transactionId
                    ]);

                    return redirect()->back()->with('success', 'Withdrawal request submitted successfully. Waiting for admin approval.');
                } else {
                    return redirect()->back()->with('error', 'Failed to submit withdrawal request');
                }
            } catch (\Exception $e) {
                log_message('error', 'Withdrawal request failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'An error occurred while processing your withdrawal request');
            }
        }

        // Get user's wallet balance
        $wallet = $this->walletModel->getUserWallet($userId);

        // Get recent withdrawal requests
        $recentWithdrawals = $this->walletTransactionModel->getUserTransactions($userId, 10);
        // Filter for withdrawals only
        $recentWithdrawals = array_filter($recentWithdrawals, function ($transaction) {
            return $transaction['type'] === 'withdrawal';
        });

        $data = [
            'title' => 'Withdraw Money',
            'user' => $user,
            'wallet' => $wallet,
            'recentWithdrawals' => $recentWithdrawals
        ];

        return view('wallet/withdraw', $data);
    }

    /**
     * Show user requests list (only for special users)
     */
    public function userRequests()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        // Only special users can view user requests
        if (!($user['is_special_user'] ?? false)) {
            return redirect()->to('wallet')->with('error', 'Only special users can view user requests');
        }

        // Get pending topup requests
        $pendingTopups = $this->walletTopupRequestModel->getPendingRequests(20);

        // Get pending transfer requests
        $pendingTransfers = $this->userTransferModel->getPendingTransfers(20);

        $data = [
            'title' => 'User Requests',
            'user' => $user,
            'pendingTopups' => $pendingTopups,
            'pendingTransfers' => $pendingTransfers
        ];

        return view('wallet/user_requests', $data);
    }

    // Wallet profile management
    public function profile()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login')->with('error', 'Please login to access your wallet');
        }

        if ($this->request->getMethod() === 'POST') {
            $walletName = $this->request->getPost('wallet_name');
            $walletNumber = $this->request->getPost('wallet_number');
            $walletType = $this->request->getPost('wallet_type');

            if (!$walletName || !$walletNumber || !$walletType) {
                return redirect()->back()->with('error', 'Please fill in all wallet details');
            }

            $userId = session()->get('user_id');

            if ($this->userModel->updateWalletDetails($userId, $walletName, $walletNumber, $walletType)) {
                return redirect()->back()->with('success', 'Wallet details updated successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to update wallet details');
            }
        }

        $userId = session()->get('user_id');
        $data = [
            'wallet_details' => $this->userModel->getWalletDetails($userId),
            'wallet' => $this->walletModel->getUserWallet($userId)
        ];

        return view('wallet/profile', $data);
    }

    /**
     * Update user profile information
     */
    public function updateProfile()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $fullName = $this->request->getPost('full_name');
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');

        // Validate input
        if (!$fullName || !$username || !$email) {
            return redirect()->back()->with('error', 'Please fill in all required fields');
        }

        // Check if username is already taken by another user
        $existingUser = $this->userModel->where('username', $username)->where('id !=', $userId)->first();
        if ($existingUser) {
            return redirect()->back()->with('error', 'Username is already taken');
        }

        // Check if email is already taken by another user
        $existingUser = $this->userModel->where('email', $email)->where('id !=', $userId)->first();
        if ($existingUser) {
            return redirect()->back()->with('error', 'Email is already taken');
        }

        try {
            $updateData = [
                'full_name' => $fullName,
                'username' => $username,
                'email' => $email,
                'phone' => $phone
            ];

            // Handle profile image upload
            $profileImage = $this->request->getFile('profile_image');
            if ($profileImage && $profileImage->isValid() && !$profileImage->hasMoved()) {
                $uploadPath = 'uploads/profiles/';

                // Create directory if it doesn't exist
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $newName = $profileImage->getRandomName();
                $profileImage->move($uploadPath, $newName);

                $updateData['profile_image'] = $newName;
            }

            if ($this->userModel->update($userId, $updateData)) {
                return redirect()->back()->with('success', 'Profile updated successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to update profile');
            }
        } catch (\Exception $e) {
            log_message('error', 'Profile update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating profile');
        }
    }

    /**
     * Update wallet information
     */
    public function updateWallet()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $walletName = $this->request->getPost('wallet_name');
        $walletNumber = $this->request->getPost('wallet_number');
        $walletType = $this->request->getPost('wallet_type');
        $bankName = $this->request->getPost('bank_name');

        // Validate input
        if (!$walletName || !$walletNumber || !$walletType) {
            return redirect()->back()->with('error', 'Please fill in all required fields');
        }

        try {
            $updateData = [
                'wallet_name' => $walletName,
                'wallet_number' => $walletNumber,
                'wallet_type' => $walletType
            ];

            // Add bank name if it's a bank type
            if (in_array($walletType, ['bank', 'hbl', 'ubank', 'mcb', 'abank', 'nbp', 'sbank', 'citi', 'hsbc']) && $bankName) {
                $updateData['bank_name'] = $bankName;
            }

            if ($this->userModel->update($userId, $updateData)) {
                return redirect()->back()->with('success', 'Wallet information updated successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to update wallet information');
            }
        } catch (\Exception $e) {
            log_message('error', 'Wallet update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating wallet information');
        }
    }

    private function processPayPalPayment($transactionId, $amount)
    {
        try {
            // Try environment variables first, fallback to config file
            $paypalConfig = [
                'client_id' => getenv('PAYPAL_CLIENT_ID') ?: config('PayPal')->clientId,
                'client_secret' => getenv('PAYPAL_CLIENT_SECRET') ?: config('PayPal')->clientSecret,
                'mode' => getenv('PAYPAL_MODE') ?: config('PayPal')->mode
            ];

            // Debug logging to check what's being loaded
            log_message('info', 'PayPal Config Debug: ' . json_encode($paypalConfig));
            log_message('info', 'PAYPAL_MODE from env: ' . getenv('PAYPAL_MODE'));
            log_message('info', 'PAYPAL_MODE from ENV array: ' . (isset($_ENV['PAYPAL_MODE']) ? $_ENV['PAYPAL_MODE'] : 'Not set'));

            // Check if PayPal is properly configured
            if (empty($paypalConfig['client_id']) || empty($paypalConfig['client_secret'])) {
                return redirect()->back()->with('error', 'PayPal payment is not configured. Please contact support.');
            }

            // Store payment data in session for success handling
            session()->set('paypal_payment', [
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'payment_method' => 'paypal',
                'config' => $paypalConfig
            ]);

            // Create PayPal order
            $paypalOrder = $this->createPayPalOrder($amount, $transactionId, $paypalConfig);

            if ($paypalOrder && isset($paypalOrder['approval_url'])) {

                return redirect()->to($paypalOrder['approval_url']);
            } else {

                return redirect()->back()->with('error', 'Failed to create PayPal payment. Please try again.');
            }
        } catch (\Exception $e) {
            log_message('error', 'PayPal payment setup failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to setup PayPal payment: ' . $e->getMessage());
        }
    }

    private function processEasypaisaPayment($transactionId, $amount)
    {
        try {
            // Redirect to Easypaisa checkout
            $easypaisaService = new \App\Libraries\EasypaisaService();

            if (!$easypaisaService->isConfigured()) {
                return redirect()->back()->with('error', 'Easypaisa payment is not configured. Please contact support.');
            }

            // Store payment data in session for success handling
            session()->set('easypaisa_payment', [
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'payment_method' => 'easypaisa'
            ]);

            // Redirect to Easypaisa checkout
            return redirect()->to("easypaisa/checkout?transaction_id=$transactionId&amount=$amount&operation_type=topup");
        } catch (\Exception $e) {
            log_message('error', 'Easypaisa payment setup failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to setup Easypaisa payment: ' . $e->getMessage());
        }
    }

    public function paypalProcess()
    {
        $paymentData = session()->get('paypal_payment');

        if (!$paymentData) {
            return redirect()->to('wallet')->with('error', 'Invalid payment session');
        }

        $data = [
            'amount' => $paymentData['amount'],
            'transaction_id' => $paymentData['transaction_id']
        ];

        return view('wallet/paypal_process', $data);
    }

    public function paypalSuccess()
    {
        try {
            $paymentData = session()->get('paypal_payment');

            if (!$paymentData) {
                return redirect()->to('wallet')->with('error', 'Invalid payment session');
            }

            // Simulate successful PayPal payment
            $transactionId = $paymentData['transaction_id'];
            $paymentReference = 'PAYPAL_' . time() . '_' . rand(1000, 9999);

            // Complete the topup
            if ($this->walletTransactionModel->completeTopup($transactionId, $paymentReference)) {
                session()->remove('paypal_payment');

                // Try to send notification to admin about user topup
                try {
                    $userId = session()->get('user_id');
                    $this->notificationService->notifyAdmin('user_topup', $userId, [
                        'amount' => $paymentData['amount'],
                        'payment_method' => 'PayPal',
                        'transaction_id' => $transactionId,
                        'payment_reference' => $paymentReference
                    ]);
                } catch (\Exception $e) {
                    // Log notification error but don't fail the payment
                    log_message('error', 'PayPal success: Failed to send admin notification - ' . $e->getMessage());
                }

                // Pass data to success view
                $data = [
                    'amount' => $paymentData['amount'],
                    'payment_method' => 'paypal',
                    'transaction_id' => $transactionId
                ];

                return view('wallet/payment_success', $data);
            } else {
                log_message('error', 'PayPal success: Failed to complete topup for transaction ' . $transactionId);
                return redirect()->to('wallet')->with('error', 'Failed to complete topup');
            }
        } catch (\Exception $e) {
            log_message('error', 'PayPal success: Unexpected error - ' . $e->getMessage());
            return redirect()->to('wallet')->with('error', 'An error occurred while processing your payment. Please contact support if the amount was deducted.');
        }
    }

    public function paypalCancel()
    {
        session()->remove('paypal_payment');
        return redirect()->to('wallet')->with('error', 'PayPal payment was cancelled');
    }

    public function easypaisaProcess()
    {
        $paymentData = session()->get('easypaisa_payment');

        if (!$paymentData) {
            return redirect()->to('wallet')->with('error', 'Invalid payment session');
        }

        $data = [
            'amount' => $paymentData['amount'],
            'transaction_id' => $paymentData['transaction_id']
        ];

        return view('wallet/easypaisa_process', $data);
    }

    public function easypaisaSuccess()
    {
        try {
            $paymentData = session()->get('easypaisa_payment');

            if (!$paymentData) {
                log_message('error', 'EasyPaisa success: Invalid payment session');
                return redirect()->to('wallet')->with('error', 'Invalid payment session');
            }

            // Simulate successful Easypaisa payment
            $transactionId = $paymentData['transaction_id'];
            $paymentReference = 'EASYPAISA_' . time() . '_' . rand(1000, 9999);

            log_message('info', 'EasyPaisa success: Processing transaction ' . $transactionId . ' with reference ' . $paymentReference);

            // Complete the topup
            if ($this->walletTransactionModel->completeTopup($transactionId, $paymentReference)) {
                session()->remove('easypaisa_payment');

                // Try to send notification to admin about user topup
                try {
                    $userId = session()->get('user_id');
                    $this->notificationService->notifyAdmin('user_topup', $userId, [
                        'amount' => $paymentData['amount'],
                        'payment_method' => 'EasyPaisa',
                        'transaction_id' => $transactionId,
                        'payment_reference' => $paymentReference
                    ]);
                    log_message('info', 'EasyPaisa success: Admin notification sent successfully');
                } catch (\Exception $e) {
                    // Log notification error but don't fail the payment
                    log_message('error', 'EasyPaisa success: Failed to send admin notification - ' . $e->getMessage());
                }

                // Pass data to success view
                $data = [
                    'amount' => $paymentData['amount'],
                    'payment_method' => 'easypaisa',
                    'transaction_id' => $transactionId
                ];

                log_message('info', 'EasyPaisa success: Payment completed successfully for transaction ' . $transactionId);
                return view('wallet/payment_success', $data);
            } else {
                log_message('error', 'EasyPaisa success: Failed to complete topup for transaction ' . $transactionId);
                return redirect()->to('wallet')->with('error', 'Failed to complete topup');
            }
        } catch (\Exception $e) {
            log_message('error', 'EasyPaisa success: Unexpected error - ' . $e->getMessage());
            return redirect()->to('wallet')->with('error', 'An error occurred while processing your payment. Please contact support if the amount was deducted.');
        }
    }

    public function easypaisaCancel()
    {
        session()->remove('easypaisa_payment');
        return redirect()->to('wallet')->with('error', 'Easypaisa payment was cancelled');
    }

    public function transactions()
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        $userId = session()->get('user_id');
        $page = $this->request->getGet('page') ?: 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Get paginated transactions
        $transactions = $this->walletTransactionModel->getUserTransactions($userId, $limit);
        $totalTransactions = $this->walletTransactionModel->select('COUNT(*) as total')
            ->join('wallets', 'wallets.id = wallet_transactions.wallet_id')
            ->where('wallets.user_id', $userId)
            ->first()['total'];

        $data = [
            'transactions' => $transactions,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil($totalTransactions / $limit),
                'total_records' => $totalTransactions
            ]
        ];

        return view('wallet/transactions', $data);
    }

    private function createPayPalOrder($amount, $transactionId, $config)
    {
        try {
            // PayPal API endpoint
            $baseUrl = $config['mode'] === 'live'
                ? 'https://api.paypal.com'
                : 'https://api.sandbox.paypal.com';

            // Debug logging

            // Get access token
            $accessToken = $this->getPayPalAccessToken($config);
            if (!$accessToken) {
                throw new \Exception('Failed to get PayPal access token');
            }

            // Convert PKR amount to USD for PayPal API (internal use only)
            $usdAmount = $this->currencyService->pkrToUsd($amount);

            // Create order payload - Convert PKR to USD for PayPal (internal conversion)
            $orderData = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => 'TRANSACTION_' . $transactionId,
                        'amount' => [
                            'currency_code' => 'USD', // PayPal expects USD (internal)
                            'value' => number_format($usdAmount, 2, '.', '')
                        ],
                        'description' => 'Wallet Topup - Lucky Draw System (Rs. ' . number_format($amount, 2) . ' PKR)'
                    ]
                ],
                'application_context' => [
                    'return_url' => getenv('PAYPAL_RETURN_URL') ?: base_url('wallet/paypal/success'),
                    'cancel_url' => getenv('PAYPAL_CANCEL_URL') ?: base_url('wallet/paypal/cancel'),
                    'brand_name' => 'Lucky Draw System',
                    'landing_page' => 'BILLING',
                    'user_action' => 'PAY_NOW'
                ]
            ];

            log_message('info', 'PayPal Order Data: ' . json_encode($orderData));

            // Make API call to create order
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $baseUrl . '/v2/checkout/orders');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken,
                'PayPal-Request-Id: ' . uniqid()
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 201) {
                log_message('error', 'PayPal order creation failed. HTTP Code: ' . $httpCode . ', Response: ' . $response);
                return false;
            }

            $orderResponse = json_decode($response, true);

            // Find approval URL
            $approvalUrl = null;
            if (isset($orderResponse['links'])) {
                foreach ($orderResponse['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        $approvalUrl = $link['href'];
                        break;
                    }
                }
            }

            // Store PayPal order ID in session
            session()->set('paypal_order_id', $orderResponse['id']);

            return [
                'order_id' => $orderResponse['id'],
                'approval_url' => $approvalUrl,
                'status' => $orderResponse['status']
            ];
        } catch (\Exception $e) {
            log_message('error', 'PayPal order creation error: ' . $e->getMessage());
            return false;
        }
    }

    private function getPayPalAccessToken($config)
    {
        try {
            $baseUrl = $config['mode'] === 'live'
                ? 'https://api.paypal.com'
                : 'https://api.sandbox.paypal.com';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $baseUrl . '/v1/oauth2/token');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, $config['client_id'] . ':' . $config['client_secret']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/json',
                'Accept-Language: en_US'
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                log_message('error', 'PayPal access token request failed. HTTP Code: ' . $httpCode . ', Response: ' . $response);
                return false;
            }

            $tokenData = json_decode($response, true);
            return $tokenData['access_token'] ?? false;
        } catch (\Exception $e) {
            log_message('error', 'PayPal access token error: ' . $e->getMessage());
            return false;
        }
    }

    public function test()
    {
        try {
            // Test database connection
            $db = \Config\Database::connect();
            $result = $db->query('SELECT 1 as test')->getRow();

            // Test wallet model
            $walletCount = $this->walletModel->countAllResults();

            // Test wallet transaction model
            $transactionCount = $this->walletTransactionModel->countAllResults();

            echo "Database connection: OK<br>";
            echo "Wallet table count: " . $walletCount . "<br>";
            echo "Wallet transactions table count: " . $transactionCount . "<br>";

            // Test user session
            $userId = session()->get('user_id');
            echo "User ID from session: " . ($userId ?: 'NOT SET') . "<br>";

            if ($userId) {
                $wallet = $this->walletModel->getUserWallet($userId);
                echo "User wallet: " . json_encode($wallet) . "<br>";
            }
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function debug()
    {
        echo "<h2>Wallet Debug Information</h2>";

        // Check session
        echo "<h3>Session Data:</h3>";
        echo "<pre>" . print_r(session()->get(), true) . "</pre>";

        // Check user ID
        $userId = session()->get('user_id');
        echo "<h3>User ID: " . ($userId ?: 'NOT SET') . "</h3>";

        if ($userId) {
            // Check user exists
            $user = $this->userModel->find($userId);
            echo "<h3>User Data:</h3>";
            echo "<pre>" . print_r($user, true) . "</pre>";

            // Check wallet
            $wallet = $this->walletModel->getUserWallet($userId);
            echo "<h3>Wallet Data:</h3>";
            echo "<pre>" . print_r($wallet, true) . "</pre>";

            // Test wallet transaction creation
            if ($wallet) {
                echo "<h3>Testing Transaction Creation:</h3>";
                $transactionId = $this->walletTransactionModel->createPendingTopup(
                    $wallet['id'],
                    10,
                    'paypal'
                );
                echo "Transaction ID: " . ($transactionId ?: 'FAILED') . "<br>";

                if ($transactionId) {
                    echo "Transaction created successfully!<br>";
                    // Clean up test transaction
                    $this->walletTransactionModel->delete($transactionId);
                    echo "Test transaction cleaned up.<br>";
                }
            }
        }

        echo "<br><a href='" . base_url('wallet') . "'>Back to Wallet</a>";
    }

    public function paymentStatus()
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to('login')->with('error', 'Please login to access this page');
        }

        $data = [
            'paymentStatus' => $this->paymentConfig->getPaymentMethodStatus(),
            'config' => $this->paymentConfig
        ];

        return view('wallet/payment_status', $data);
    }
}
