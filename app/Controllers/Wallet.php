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
    protected $paymentConfig;
    protected $notificationService;
    protected $currencyService;

    public function __construct()
    {
        $this->walletModel = new \App\Models\WalletModel();
        $this->walletTransactionModel = new \App\Models\WalletTransactionModel();
        $this->userModel = new \App\Models\UserModel();
        $this->paymentConfig = config('Payment');
        $this->notificationService = new NotificationService();
        $this->currencyService = new CurrencyService();
    }

    public function index()
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        $userId = session()->get('user_id');

        // Get wallet information
        $wallet = $this->walletModel->getUserWallet($userId);

        // Get recent transactions
        $transactions = $this->walletTransactionModel->getUserTransactions($userId, 10);

        // Get pending topups
        $pendingTopups = $this->walletTransactionModel->getPendingTopups($userId);

        $data = [
            'wallet' => $wallet,
            'transactions' => $transactions,
            'pendingTopups' => $pendingTopups
        ];

        return view('wallet/index', $data);
    }

    public function topup()
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to('login')->with('error', 'Please login to access your wallet');
        }

        if ($this->request->getMethod() === 'POST') {
            $amount = $this->request->getPost('final_amount') ?: $this->request->getPost('amount');
            $paymentMethod = $this->request->getPost('payment_method');

            // Debug logging

            // Validate amount (PKR is primary currency)
            if (!$amount || $amount < 500) {
                return redirect()->back()->with('error', 'Please enter a valid amount (minimum Rs. 500)');
            }

            // Validate PayPal minimum
            if ($paymentMethod === 'paypal' && !$this->currencyService->meetsPayPalMinimum($amount)) {
                return redirect()->back()->with('error', 'PayPal requires minimum amount of Rs. 500');
            }

            // Validate payment method
            if (!$paymentMethod || !in_array($paymentMethod, ['paypal', 'easypaisa'])) {
                return redirect()->back()->with('error', 'Please select a valid payment method');
            }

            $userId = session()->get('user_id');

            try {
                $wallet = $this->walletModel->getUserWallet($userId);

                if (!$wallet) {
                    return redirect()->back()->with('error', 'Failed to access wallet. Please try again or contact support.');
                }

                // Create pending transaction (use PKR amount)
                $transactionId = $this->walletTransactionModel->createPendingTopup(
                    $wallet['id'],
                    $amount,
                    $paymentMethod
                );


                if (!$transactionId) {
                    return redirect()->back()->with('error', 'Failed to create transaction. Please try again.');
                }

                // Redirect to payment processing based on method
                if ($paymentMethod === 'paypal') {
                    return $this->processPayPalPayment($transactionId, $amount);
                } elseif ($paymentMethod === 'easypaisa') {
                    return $this->processEasypaisaPayment($transactionId, $amount);
                }
            } catch (\Exception $e) {
                log_message('error', 'Topup process failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'An error occurred while processing your request. Please try again.');
            }
        }

        // Show any error messages
        $data = [
            'error' => session()->get('error'),
            'success' => session()->get('success'),
            'currencyService' => $this->currencyService
        ];

        return view('wallet/topup', $data);
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

    public function withdraw()
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if ($this->request->getMethod() === 'POST') {
            $amount = $this->request->getPost('amount');
            $withdrawalMethod = $this->request->getPost('withdrawal_method');
            $accountDetails = $this->request->getPost('account_details');

            // Validate amount
            if (!$amount || $amount < 10) {
                return redirect()->back()->with('error', 'Minimum withdrawal amount is Rs. 10');
            }

            // Validate PayPal minimum for withdrawals
            if ($withdrawalMethod === 'paypal' && !$this->currencyService->meetsPayPalMinimum($amount)) {
                return redirect()->back()->with('error', 'PayPal withdrawal requires minimum amount of Rs. 280');
            }

            $userId = session()->get('user_id');

            // Check if user has sufficient balance
            if (!$this->walletModel->hasSufficientBalance($userId, $amount)) {
                return redirect()->back()->with('error', 'Insufficient wallet balance');
            }

            // Get wallet first
            $wallet = $this->walletModel->getUserWallet($userId);

            // Create withdrawal request (pending admin approval)
            $transactionId = $this->walletTransactionModel->insert([
                'wallet_id' => $wallet['id'],
                'type' => 'withdrawal',
                'amount' => -$amount,
                'balance_before' => $wallet['balance'],
                'balance_after' => $wallet['balance'], // No change until approved
                'status' => 'pending',
                'description' => 'Withdrawal request - Rs. ' . number_format($amount, 2),
                'payment_method' => $withdrawalMethod,
                'metadata' => json_encode([
                    'withdrawal_method' => $withdrawalMethod,
                    'account_details' => $accountDetails,
                    'requested_at' => time()
                ])
            ]);

            // Try to send notification to admin about withdrawal request
            try {
                $this->notificationService->notifyAdmin('user_withdraw', $userId, [
                    'amount' => $amount,
                    'withdrawal_method' => $withdrawalMethod,
                    'transaction_id' => $transactionId,
                    'account_details' => $accountDetails
                ]);
                log_message('info', 'Withdrawal: Admin notification sent successfully for transaction ' . $transactionId);
            } catch (\Exception $e) {
                // Log notification error but don't fail the withdrawal request
                log_message('error', 'Withdrawal: Failed to send admin notification - ' . $e->getMessage());
            }

            return redirect()->to('wallet')->with('success', 'Withdrawal request submitted successfully. It will be processed within 24-48 hours.');
        }

        // Get user's withdrawal history
        $userId = session()->get('user_id');
        $wallet = $this->walletModel->getUserWallet($userId);

        // Get recent withdrawal requests
        $withdrawalHistory = $this->walletTransactionModel
            ->select('wallet_transactions.*, wallets.balance as wallet_balance')
            ->join('wallets', 'wallets.id = wallet_transactions.wallet_id')
            ->where('wallets.user_id', $userId)
            ->where('wallet_transactions.type', 'withdrawal')
            ->orderBy('wallet_transactions.created_at', 'DESC')
            ->limit(10)
            ->findAll();

        $data = [
            'wallet' => $wallet,
            'withdrawal_history' => $withdrawalHistory
        ];

        return view('wallet/withdraw', $data);
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
