<?php

namespace App\Controllers;

use App\Libraries\EasypaisaService;
use App\Models\WalletTransactionModel;
use App\Models\WalletModel;
use App\Libraries\NotificationService;
use App\Libraries\CurrencyService;

class Easypaisa extends BaseController
{
    protected $easypaisaService;
    protected $walletTransactionModel;
    protected $walletModel;
    protected $notificationService;
    protected $currencyService;
    protected $db;

    public function __construct()
    {
        $this->easypaisaService = new EasypaisaService();
        $this->walletTransactionModel = new WalletTransactionModel();
        $this->walletModel = new WalletModel();
        $this->notificationService = new NotificationService();
        $this->currencyService = new CurrencyService();
        $this->db = \Config\Database::connect();
    }

    /**
     * Handle Easypaisa completion callback for wallet topup
     */
    public function complete()
    {
        $status = $this->request->getGet('status');
        $description = $this->request->getGet('desc');
        $orderRefNumber = $this->request->getGet('orderRefNumber');

        $paymentData = session()->get('easypaisa_payment');
        if (!$paymentData) {
            return redirect()->to('wallet')->with('error', 'Payment session expired');
        }

        try {
            // Start database transaction
            $this->db->transStart();

            if ($status === '000' && $description === 'completed') {
                // Payment successful
                $transactionId = $paymentData['transaction_id'];
                $amount = $paymentData['amount'];

                // Update transaction status
                $this->walletTransactionModel->update($transactionId, [
                    'status' => 'completed',
                    'payment_reference' => $orderRefNumber,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                // Update wallet balance
                $transaction = $this->walletTransactionModel->find($transactionId);
                $wallet = $this->walletModel->find($transaction['wallet_id']);

                if ($wallet) {
                    $newBalance = $wallet['balance'] + $amount;
                    $this->walletModel->update($wallet['id'], ['balance' => $newBalance]);
                }

                // Send success notification (amount is already in PKR)
                $this->notificationService->sendSystemMessage(
                    $wallet['user_id'],
                    'Topup Successful! 🎉',
                    "Your Easypaisa topup of Rs. " . number_format($amount, 2) . " has been processed successfully. Transaction ID: $orderRefNumber",
                    'high'
                );

                // Send admin notification
                try {
                    $this->notificationService->notifyAdmin('user_topup', $wallet['user_id'], [
                        'amount' => $amount,
                        'payment_method' => 'Easypaisa',
                        'transaction_id' => $transactionId,
                        'payment_reference' => $orderRefNumber
                    ]);
                } catch (\Exception $e) {
                    log_message('error', 'Failed to send admin notification: ' . $e->getMessage());
                }

                $this->db->transCommit();

                // Clear session
                session()->remove('easypaisa_payment');

                return redirect()->to('wallet')->with('success', 'Payment completed successfully! Your wallet has been credited.');
            } else {
                // Payment failed
                $transactionId = $paymentData['transaction_id'];

                // Update transaction status
                $this->walletTransactionModel->update($transactionId, [
                    'status' => 'failed',
                    'payment_reference' => $orderRefNumber,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                $this->db->transCommit();

                // Clear session
                session()->remove('easypaisa_payment');

                return redirect()->to('wallet')->with('error', 'Payment failed: ' . $description);
            }
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Easypaisa payment completion failed: ' . $e->getMessage());

            return redirect()->to('wallet')->with('error', 'Payment processing failed. Please contact support.');
        }
    }
}
