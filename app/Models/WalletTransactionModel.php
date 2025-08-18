<?php

namespace App\Models;

use CodeIgniter\Model;

class WalletTransactionModel extends Model
{
    protected $table = 'wallet_transactions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'wallet_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'status',
        'description',
        'payment_method',
        'payment_reference',
        'metadata',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'wallet_id' => 'required|integer|greater_than[0]',
        'type' => 'required|in_list[topup,deduction,draw_entry,draw_win,withdrawal]',
        'amount' => 'required|numeric',
        'balance_before' => 'required|numeric',
        'balance_after' => 'required|numeric',
        'status' => 'required|in_list[pending,completed,failed,cancelled]',
        'payment_method' => 'permit_empty|in_list[paypal,easypaisa,wallet]'
    ];

    // Get user's transaction history
    public function getUserTransactions($userId, $limit = 20)
    {
        return $this->select('wallet_transactions.*, wallets.user_id')
            ->join('wallets', 'wallets.id = wallet_transactions.wallet_id')
            ->where('wallets.user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    // Get pending topup transactions
    public function getPendingTopups($userId)
    {
        return $this->select('wallet_transactions.*, wallets.user_id')
            ->join('wallets', 'wallets.id = wallet_transactions.wallet_id')
            ->where('wallets.user_id', $userId)
            ->where('wallet_transactions.type', 'topup')
            ->where('wallet_transactions.status', 'pending')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    // Create a pending topup transaction
    public function createPendingTopup($walletId, $amount, $paymentMethod, $paymentReference = null)
    {
        $walletModel = new WalletModel();
        $wallet = $walletModel->find($walletId);

        if (!$wallet) {
            return false;
        }

        $data = [
            'wallet_id' => $walletId,
            'type' => 'topup',
            'amount' => $amount,
            'balance_before' => $wallet['balance'],
            'balance_after' => $wallet['balance'], // No change until completed
            'status' => 'pending',
            'description' => 'Wallet topup - Rs. ' . number_format($amount, 2),
            'payment_method' => $paymentMethod,
            'payment_reference' => $paymentReference,
            'metadata' => json_encode([
                'payment_method' => $paymentMethod,
                'amount' => $amount,
                'timestamp' => time()
            ])
        ];

        return $this->insert($data);
    }

    // Complete a topup transaction
    public function completeTopup($transactionId, $paymentReference = null)
    {
        $transaction = $this->find($transactionId);

        if (!$transaction || $transaction['status'] !== 'pending') {
            return false;
        }

        $walletModel = new WalletModel();
        $wallet = $walletModel->find($transaction['wallet_id']);

        if (!$wallet) {
            return false;
        }

        // Update transaction status
        $this->update($transactionId, [
            'status' => 'completed',
            'payment_reference' => $paymentReference,
            'balance_after' => $wallet['balance'] + $transaction['amount']
        ]);

        // Add money to wallet
        return $walletModel->addMoney($wallet['user_id'], $transaction['amount'], 'topup');
    }

    // Get transaction statistics for admin
    public function getTransactionStats()
    {
        $stats = [
            'total_transactions' => $this->countAllResults(),
            'total_volume' => $this->select('SUM(ABS(amount)) as total')->where('status', 'completed')->first()['total'] ?? 0,
            'pending_topups' => $this->where('type', 'topup')->where('status', 'pending')->countAllResults(),
            'completed_topups' => $this->where('type', 'topup')->where('status', 'completed')->countAllResults(),
            'failed_transactions' => $this->where('status', 'failed')->countAllResults()
        ];

        return $stats;
    }

    // Get recent transactions for admin dashboard
    public function getRecentTransactions($limit = 10)
    {
        return $this->select('wallet_transactions.*, wallets.user_id, users.username, users.email')
            ->join('wallets', 'wallets.id = wallet_transactions.wallet_id')
            ->join('users', 'users.id = wallets.user_id')
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    // Record lucky draw entry deduction
    public function recordDrawEntry($walletId, $amount, $drawId)
    {
        $walletModel = new WalletModel();
        $wallet = $walletModel->find($walletId);

        if (!$wallet) {
            return false;
        }

        $data = [
            'wallet_id' => $walletId,
            'type' => 'draw_entry',
            'amount' => -$amount, // Negative for deduction
            'balance_before' => $wallet['balance'],
            'balance_after' => $wallet['balance'] - $amount,
            'status' => 'completed',
            'description' => 'Lucky draw entry fee - Rs. ' . number_format($amount, 2),
            'payment_method' => 'wallet',
            'metadata' => json_encode([
                'draw_id' => $drawId,
                'entry_type' => 'deduction'
            ])
        ];

        return $this->insert($data);
    }

    // Record lucky draw winnings
    public function recordDrawWinning($walletId, $amount, $drawId)
    {
        $walletModel = new WalletModel();
        $wallet = $walletModel->find($walletId);

        if (!$wallet) {
            return false;
        }

        $data = [
            'wallet_id' => $walletId,
            'type' => 'draw_win',
            'amount' => $amount, // Positive for winnings
            'balance_before' => $wallet['balance'],
            'balance_after' => $wallet['balance'] + $amount,
            'status' => 'completed',
            'description' => 'Lucky draw winnings - Rs. ' . number_format($amount, 2),
            'payment_method' => 'wallet',
            'metadata' => json_encode([
                'draw_id' => $drawId,
                'win_type' => 'prize'
            ])
        ];

        return $this->insert($data);
    }
}
