<?php

namespace App\Models;

use CodeIgniter\Model;

class WalletTopupRequestModel extends Model
{
    protected $table = 'wallet_topup_requests';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'user_id',
        'special_user_id',
        'amount',
        'payment_method',
        'payment_proof',
        'payment_proof_file',
        'status',
        'admin_notes',
        'special_user_notes',
        'processed_by',
        'processed_at',
        'special_user_processed_at',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'user_id' => 'required|integer|greater_than[0]',
        'amount' => 'required|numeric|greater_than[0]',
        'payment_method' => 'required|in_list[easypaisa,jazz_cash,bank,manual]',
        'status' => 'required|in_list[pending,approved,rejected]'
    ];

    /**
     * Get pending topup requests for admin review
     */
    public function getPendingRequests($limit = null)
    {
        $builder = $this->db->table('wallet_topup_requests wtr')
            ->select('wtr.*, u.username, u.email')
            ->join('users u', 'u.id = wtr.user_id')
            ->where('wtr.status', 'pending')
            ->orderBy('wtr.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->get()->getResultArray();
    }

    // Get user's top-up requests
    public function getUserRequests($userId, $limit = null)
    {
        $query = $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->findAll();
    }

    /**
     * Get topup requests for a specific user
     */
    public function getUserTopupRequests($userId, $limit = null)
    {
        $builder = $this->db->table('wallet_topup_requests')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->get()->getResultArray();
    }

    // Get top-up request with user details
    public function getRequestWithUser($requestId)
    {
        return $this->select('wallet_topup_requests.*, users.username, users.full_name, users.email, users.wallet_name, users.wallet_number, users.wallet_type')
            ->join('users', 'users.id = wallet_topup_requests.user_id')
            ->where('wallet_topup_requests.id', $requestId)
            ->first();
    }

    // Approve top-up request
    public function approveRequest($requestId, $adminId, $notes = null)
    {
        $request = $this->find($requestId);
        if (!$request || $request['status'] !== 'pending') {
            return false;
        }

        // Update request status
        $this->update($requestId, [
            'status' => 'approved',
            'admin_notes' => $notes,
            'processed_by' => $adminId,
            'processed_at' => date('Y-m-d H:i:s')
        ]);

        // Add money to user's wallet
        $walletModel = new WalletModel();
        $walletModel->addMoney($request['user_id'], $request['amount'], 'topup_approved');

        // Create wallet transaction
        $transactionModel = new WalletTransactionModel();
        $wallet = $walletModel->getUserWallet($request['user_id']);
        $transactionModel->insert([
            'wallet_id' => $wallet['id'],
            'type' => 'topup',
            'amount' => $request['amount'],
            'balance_before' => $wallet['balance'] - $request['amount'],
            'balance_after' => $wallet['balance'],
            'status' => 'completed',
            'description' => 'Top-up approved - Rs. ' . number_format($request['amount'], 2),
            'payment_method' => $request['payment_method'],
            'payment_reference' => 'TOPUP-' . $requestId,
            'metadata' => json_encode([
                'topup_request_id' => $requestId,
                'approved_by' => $adminId,
                'notes' => $notes
            ])
        ]);

        return true;
    }

    // Reject top-up request
    public function rejectRequest($requestId, $adminId, $notes = null)
    {
        $request = $this->find($requestId);
        if (!$request || $request['status'] !== 'pending') {
            return false;
        }

        return $this->update($requestId, [
            'status' => 'rejected',
            'admin_notes' => $notes,
            'processed_by' => $adminId,
            'processed_at' => date('Y-m-d H:i:s')
        ]);
    }

    // Get top-up statistics
    public function getTopupStats()
    {
        $stats = [
            'total_requests' => $this->countAllResults(),
            'pending_requests' => $this->where('status', 'pending')->countAllResults(),
            'approved_requests' => $this->where('status', 'approved')->countAllResults(),
            'rejected_requests' => $this->where('status', 'rejected')->countAllResults(),
            'total_amount_pending' => $this->select('SUM(amount) as total')->where('status', 'pending')->first()['total'] ?? 0,
            'total_amount_approved' => $this->select('SUM(amount) as total')->where('status', 'approved')->first()['total'] ?? 0
        ];

        return $stats;
    }

    // Get recent top-up requests for admin dashboard
    public function getRecentRequests($limit = 5)
    {
        return $this->select('wallet_topup_requests.*, users.username, users.full_name')
            ->join('users', 'users.id = wallet_topup_requests.user_id')
            ->orderBy('wallet_topup_requests.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
