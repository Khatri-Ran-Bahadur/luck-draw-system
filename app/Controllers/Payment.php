<?php

namespace App\Controllers;

use App\Models\WalletTransactionModel;
use App\Models\WalletModel;

class Payment extends BaseController
{
    protected $transactionModel;
    protected $walletModel;

    public function __construct()
    {
        $this->transactionModel = new WalletTransactionModel();
        $this->walletModel = new WalletModel();
    }

    public function paypal($transactionId)
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'));
        }

        $transaction = $this->transactionModel->find($transactionId);
        if (!$transaction) {
            return redirect()->to(base_url('wallet'))->with('error', 'Transaction not found');
        }

        $wallet = $this->walletModel->find($transaction['wallet_id']);
        if (!$wallet || $wallet['user_id'] !== session()->get('user_id')) {
            return redirect()->to(base_url('wallet'))->with('error', 'Invalid transaction');
        }

        // Initialize PayPal
        $paypal = \Config\Services::paypal();

        // Create payment
        $payment = $paypal->payment()
            ->setIntent('sale')
            ->setPayer(['payment_method' => 'paypal'])
            ->setTransactions([
                [
                    'amount' => [
                        'total' => $transaction['amount'],
                        'currency' => $wallet['currency']
                    ],
                    'description' => $transaction['description']
                ]
            ])
            ->setRedirectUrls([
                'return_url' => base_url("payment/success/{$transactionId}"),
                'cancel_url' => base_url("payment/cancel/{$transactionId}")
            ]);

        try {
            $payment->create();
            $approvalUrl = $payment->getApprovalLink();
            return redirect()->to($approvalUrl);
        } catch (\Exception $e) {
            $this->transactionModel->updateStatus($transactionId, 'failed', $e->getMessage());
            return redirect()->to(base_url('wallet'))->with('error', 'Failed to initialize PayPal payment');
        }
    }

    public function easypaisa($transactionId)
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'));
        }

        $transaction = $this->transactionModel->find($transactionId);
        if (!$transaction) {
            return redirect()->to(base_url('wallet'))->with('error', 'Transaction not found');
        }

        $wallet = $this->walletModel->find($transaction['wallet_id']);
        if (!$wallet || $wallet['user_id'] !== session()->get('user_id')) {
            return redirect()->to(base_url('wallet'))->with('error', 'Invalid transaction');
        }

        // Initialize EasyPaisa
        $easypaisa = \Config\Services::easypaisa();

        // Create payment request
        $payment = $easypaisa->payment()
            ->setAmount($transaction['amount'])
            ->setCurrency($wallet['currency'])
            ->setOrderId($transactionId)
            ->setDescription($transaction['description'])
            ->setMobileAccount(session()->get('phone'))
            ->setReturnUrl(base_url("payment/success/{$transactionId}"))
            ->setCancelUrl(base_url("payment/cancel/{$transactionId}"));

        try {
            $payment->create();
            $paymentUrl = $payment->getPaymentUrl();
            return redirect()->to($paymentUrl);
        } catch (\Exception $e) {
            $this->transactionModel->updateStatus($transactionId, 'failed', $e->getMessage());
            return redirect()->to(base_url('wallet'))->with('error', 'Failed to initialize EasyPaisa payment');
        }
    }

    public function processPaypal()
    {
        $paymentId = $this->request->getPost('paymentId');
        $payerId = $this->request->getPost('PayerID');
        $transactionId = $this->request->getPost('transactionId');

        if (!$paymentId || !$payerId || !$transactionId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid payment data']);
        }

        $transaction = $this->transactionModel->find($transactionId);
        if (!$transaction) {
            return $this->response->setJSON(['success' => false, 'message' => 'Transaction not found']);
        }

        // Execute PayPal payment
        $paypal = \Config\Services::paypal();
        try {
            $payment = $paypal->payment()->get($paymentId);
            $execution = $paypal->payment()->execute($paymentId, $payerId);

            if ($execution->getState() === 'completed') {
                // Update transaction and wallet
                $this->transactionModel->updateStatus($transactionId, 'completed');
                $this->walletModel->updateBalance($transaction['wallet_id'], $transaction['amount'], 'add');
                return $this->response->setJSON(['success' => true]);
            } else {
                $this->transactionModel->updateStatus($transactionId, 'failed', 'Payment execution failed');
                return $this->response->setJSON(['success' => false, 'message' => 'Payment execution failed']);
            }
        } catch (\Exception $e) {
            $this->transactionModel->updateStatus($transactionId, 'failed', $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function processEasypaisa()
    {
        $orderId = $this->request->getPost('orderId');
        $status = $this->request->getPost('status');
        $transactionId = $orderId; // We used transaction ID as order ID

        if (!$orderId || !$status) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid payment data']);
        }

        $transaction = $this->transactionModel->find($transactionId);
        if (!$transaction) {
            return $this->response->setJSON(['success' => false, 'message' => 'Transaction not found']);
        }

        if ($status === 'PAID') {
            // Update transaction and wallet
            $this->transactionModel->updateStatus($transactionId, 'completed');
            $this->walletModel->updateBalance($transaction['wallet_id'], $transaction['amount'], 'add');
            return $this->response->setJSON(['success' => true]);
        } else {
            $this->transactionModel->updateStatus($transactionId, 'failed', 'Payment failed: ' . $status);
            return $this->response->setJSON(['success' => false, 'message' => 'Payment failed']);
        }
    }

    public function success($transactionId)
    {
        $transaction = $this->transactionModel->find($transactionId);
        if (!$transaction) {
            return redirect()->to(base_url('wallet'))->with('error', 'Transaction not found');
        }

        if ($transaction['payment_method'] === 'paypal') {
            $paymentId = $this->request->getGet('paymentId');
            $payerId = $this->request->getGet('PayerID');

            return view('payment/success', [
                'transaction' => $transaction,
                'paymentId' => $paymentId,
                'payerId' => $payerId
            ]);
        } else {
            // EasyPaisa success
            return view('payment/success', [
                'transaction' => $transaction
            ]);
        }
    }

    public function cancel($transactionId)
    {
        $this->transactionModel->updateStatus($transactionId, 'cancelled', 'Payment cancelled by user');
        return redirect()->to(base_url('wallet'))->with('info', 'Payment was cancelled');
    }

    public function webhook()
    {
        $payload = $this->request->getJSON();
        log_message('info', 'Payment webhook received: ' . json_encode($payload));

        // Verify webhook signature
        $signature = $this->request->getHeader('X-Payment-Signature');
        if (!$this->verifyWebhookSignature($payload, $signature)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid signature']);
        }

        // Process webhook
        try {
            if ($payload->type === 'payment.completed') {
                $transactionId = $payload->data->metadata->transaction_id;
                $transaction = $this->transactionModel->find($transactionId);

                if ($transaction && $transaction['status'] === 'pending') {
                    $this->transactionModel->updateStatus($transactionId, 'completed');
                    $this->walletModel->updateBalance($transaction['wallet_id'], $transaction['amount'], 'add');
                }
            }

            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            log_message('error', 'Webhook processing error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Internal server error']);
        }
    }

    protected function verifyWebhookSignature($payload, $signature)
    {
        // Implement signature verification based on payment gateway
        $secret = getenv('PAYMENT_WEBHOOK_SECRET');
        $computedSignature = hash_hmac('sha256', json_encode($payload), $secret);
        return hash_equals($signature, $computedSignature);
    }
}
