<?php

namespace App\Models;

use CodeIgniter\Model;

class UserTransferModel extends Model
{
    protected $table = 'user_transfers';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'from_user_id',
        'to_user_id',
        'amount',
        'status',
        'admin_notes',
        'processed_by',
        'processed_at',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'from_user_id' => 'required|integer|greater_than[0]',
        'to_user_id' => 'required|integer|greater_than[0]',
        'amount' => 'required|numeric|greater_than[0]',
        'status' => 'required|in_list[pending,completed,failed,cancelled]'
    ];

    /**
     * Get pending transfers for admin review
     */
    public function getPendingTransfers($limit = null)
    {
        $builder = $this->db->table('user_transfers ut')
            ->select('ut.*, 
                                   fu.username as from_username, fu.email as from_email,
                                   tu.username as to_username, tu.email as to_email')
            ->join('users fu', 'fu.id = ut.from_user_id')
            ->join('users tu', 'tu.id = ut.to_user_id')
            ->where('ut.status', 'pending')
            ->orderBy('ut.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get transfers for a specific user
     */
    public function getUserTransfers($userId, $limit = null)
    {
        $builder = $this->db->table('user_transfers ut')
            ->select('ut.*, 
                                   fu.username as from_username, fu.email as from_email,
                                   tu.username as to_username, tu.email as to_email')
            ->join('users fu', 'fu.id = ut.from_user_id')
            ->join('users tu', 'tu.id = ut.to_user_id')
            ->where('ut.from_user_id', $userId)
            ->orWhere('ut.to_user_id', $userId)
            ->orderBy('ut.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->get()->getResultArray();
    }

    // Get transfer with user details
    public function getTransferWithUsers($transferId)
    {
        return $this->select('user_transfers.*, 
                from_user.username as from_username, from_user.full_name as from_full_name, from_user.email as from_email,
                to_user.username as to_username, to_user.full_name as to_full_name, to_user.email as to_email')
            ->join('users as from_user', 'from_user.id = user_transfers.from_user_id')
            ->join('users as to_user', 'to_user.id = user_transfers.to_user_id')
            ->where('user_transfers.id', $transferId)
            ->first();
    }

    // Process transfer (approve and execute)
    public function processTransfer($transferId, $adminId, $notes = null)
    {
        $transfer = $this->find($transferId);
        if (!$transfer || $transfer['status'] !== 'pending') {
            return false;
        }

        // Check if sender has sufficient balance
        $walletModel = new WalletModel();
        $senderWallet = $walletModel->getUserWallet($transfer['from_user_id']);
        if ($senderWallet['balance'] < $transfer['amount']) {
            return false;
        }

        // Calculate transfer fee
        $settingModel = new SettingModel();
        $transferFeePercentage = (float) $settingModel->getSetting('transfer_fee_percentage', 0);
        $transferFee = ($transfer['amount'] * $transferFeePercentage) / 100;
        $netAmount = $transfer['amount'] - $transferFee;

        // Deduct money from sender
        if (!$walletModel->deductMoney($transfer['from_user_id'], $transfer['amount'], 'transfer_sent')) {
            return false;
        }

        // Add money to receiver
        if (!$walletModel->addMoney($transfer['to_user_id'], $netAmount, 'transfer_received')) {
            // Rollback sender deduction if receiver addition fails
            $walletModel->addMoney($transfer['from_user_id'], $transfer['amount'], 'transfer_rollback');
            return false;
        }

        // Update transfer status
        $this->update($transferId, [
            'status' => 'completed',
            'admin_notes' => $notes,
            'processed_by' => $adminId,
            'processed_at' => date('Y-m-d H:i:s')
        ]);

        // Create wallet transactions for both users
        $transactionModel = new WalletTransactionModel();

        // Sender transaction
        $senderWallet = $walletModel->getUserWallet($transfer['from_user_id']);
        $transactionModel->insert([
            'wallet_id' => $senderWallet['id'],
            'type' => 'deduction',
            'amount' => -$transfer['amount'],
            'balance_before' => $senderWallet['balance'] + $transfer['amount'],
            'balance_after' => $senderWallet['balance'],
            'status' => 'completed',
            'description' => 'Transfer to ' . $transfer['to_username'] . ' - Rs. ' . number_format($transfer['amount'], 2),
            'payment_method' => 'wallet',
            'payment_reference' => 'TRANSFER-' . $transferId,
            'metadata' => json_encode([
                'transfer_id' => $transferId,
                'to_user_id' => $transfer['to_user_id'],
                'transfer_fee' => $transferFee
            ])
        ]);

        // Receiver transaction
        $receiverWallet = $walletModel->getUserWallet($transfer['to_user_id']);
        $transactionModel->insert([
            'wallet_id' => $receiverWallet['id'],
            'type' => 'topup',
            'amount' => $netAmount,
            'balance_before' => $receiverWallet['balance'] - $netAmount,
            'balance_after' => $receiverWallet['balance'],
            'status' => 'completed',
            'description' => 'Transfer from ' . $transfer['from_username'] . ' - Rs. ' . number_format($netAmount, 2),
            'payment_method' => 'wallet',
            'payment_reference' => 'TRANSFER-' . $transferId,
            'metadata' => json_encode([
                'transfer_id' => $transferId,
                'from_user_id' => $transfer['from_user_id'],
                'transfer_fee' => $transferFee
            ])
        ]);

        return true;
    }

    // Reject transfer
    public function rejectTransfer($transferId, $adminId, $notes = null)
    {
        $transfer = $this->find($transferId);
        if (!$transfer || $transfer['status'] !== 'pending') {
            return false;
        }

        return $this->update($transferId, [
            'status' => 'rejected',
            'admin_notes' => $notes,
            'processed_by' => $adminId,
            'processed_at' => date('Y-m-d H:i:s')
        ]);
    }

    // Get transfer statistics
    public function getTransferStats()
    {
        $stats = [
            'total_transfers' => $this->countAllResults(),
            'pending_transfers' => $this->where('status', 'pending')->countAllResults(),
            'completed_transfers' => $this->where('status', 'completed')->countAllResults(),
            'failed_transfers' => $this->where('status', 'failed')->countAllResults(),
            'total_amount_transferred' => $this->select('SUM(amount) as total')->where('status', 'completed')->first()['total'] ?? 0
        ];

        return $stats;
    }

    // Get recent transfers for admin dashboard
    public function getRecentTransfers($limit = 5)
    {
        return $this->select('user_transfers.*, 
                from_user.username as from_username, to_user.username as to_username')
            ->join('users as from_user', 'from_user.id = user_transfers.from_user_id')
            ->join('users as to_user', 'to_user.id = user_transfers.to_user_id')
            ->orderBy('user_transfers.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
