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
     * Get pending topup requests for a specific special user to approve
     */
    public function getRequestsForSpecialUser($specialUserId, $limit = null)
    {
        $builder = $this->db->table('wallet_topup_requests wtr')
            ->select('wtr.*, u.username, u.email, u.full_name')
            ->join('users u', 'u.id = wtr.user_id')
            ->where('wtr.special_user_id', $specialUserId)
            ->where('wtr.status', 'pending')
            ->orderBy('wtr.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get pending topup requests for admin review
     */
    public function getPendingRequests($limit = null)
    {
        $builder = $this->db->table('wallet_topup_requests wtr')
            ->select('wtr.*, u.username, u.email, u.full_name')
            ->join('users u', 'u.id = wtr.user_id')
            ->where('wtr.status', 'pending')
            ->orderBy('wtr.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get pending top-up requests from special users only (for admin)
     */
    public function getPendingSpecialUserRequests($limit = null)
    {
        $builder = $this->db->table('wallet_topup_requests wtr')
            ->select('wtr.*, u.username, u.email, u.full_name')
            ->join('users u', 'u.id = wtr.user_id')
            ->where('wtr.status', 'pending')
            ->where('u.is_special_user', true)
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

    /**
     * Get topup statistics for special users only
     */
    public function getSpecialUserTopupStats()
    {
        $totalRequests = $this->db->table('wallet_topup_requests wtr')
            ->join('users u', 'u.id = wtr.user_id')
            ->where('u.is_special_user', true)
            ->countAllResults();
        
        $pendingRequests = $this->db->table('wallet_topup_requests wtr')
            ->join('users u', 'u.id = wtr.user_id')
            ->where('wtr.status', 'pending')
            ->where('u.is_special_user', true)
            ->countAllResults();
        
        $approvedRequests = $this->db->table('wallet_topup_requests wtr')
            ->join('users u', 'u.id = wtr.user_id')
            ->where('wtr.status', 'approved')
            ->where('u.is_special_user', true)
            ->countAllResults();
        
        $rejectedRequests = $this->db->table('wallet_topup_requests wtr')
            ->join('users u', 'u.id = wtr.user_id')
            ->where('wtr.status', 'rejected')
            ->where('u.is_special_user', true)
            ->countAllResults();

        $totalAmountPending = $this->db->table('wallet_topup_requests wtr')
            ->select('COALESCE(SUM(wtr.amount), 0) as total')
            ->join('users u', 'u.id = wtr.user_id')
            ->where('wtr.status', 'pending')
            ->where('u.is_special_user', true)
            ->get()
            ->getRow()
            ->total;

        return [
            'total_requests' => $totalRequests,
            'pending_requests' => $pendingRequests,
            'approved_requests' => $approvedRequests,
            'rejected_requests' => $rejectedRequests,
            'total_amount_pending' => $totalAmountPending
        ];
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

    /**
     * Get pending topup requests for a specific special user
     */
    public function getPendingRequestsForSpecialUser($specialUserId, $limit = null)
    {
        $builder = $this->db->table('wallet_topup_requests wtr')
            ->select('wtr.*, u.username, u.email, u.full_name')
            ->join('users u', 'u.id = wtr.user_id')
            ->where('wtr.special_user_id', $specialUserId)
            ->where('wtr.status', 'pending')
            ->orderBy('wtr.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Special user approves topup request after confirming payment
     */
    public function approveBySpecialUser($requestId, $specialUserId, $notes = null)
    {
        $request = $this->find($requestId);
        if (!$request || $request['status'] !== 'pending' || $request['special_user_id'] != $specialUserId) {
            return false;
        }

        // Get commission percentage from settings (default 5%)
        $settingModel = new \App\Models\SettingModel();
        $commissionPercentage = $settingModel->getSpecialUserCommission() ?? 5.0;
        $commissionAmount = ($request['amount'] * $commissionPercentage) / 100;
        $netAmount = $request['amount'] - $commissionAmount;

        // Update request status
        $this->update($requestId, [
            'status' => 'approved',
            'special_user_notes' => $notes,
            'special_user_processed_at' => date('Y-m-d H:i:s')
        ]);

        // Add money to normal user's wallet
        $walletModel = new WalletModel();
        $walletModel->addMoney($request['user_id'], $request['amount'], 'topup_approved_by_special_user');

        // Get current wallet balances for accurate transaction records
        $specialUserWallet = $walletModel->getUserWallet($specialUserId);
        $normalUserWallet = $walletModel->getUserWallet($request['user_id']);
        
        if (!$specialUserWallet || !$normalUserWallet) {
            return false;
        }

        // Store original balances for transaction records
        $specialUserBalanceBefore = $specialUserWallet['balance'];
        $normalUserBalanceBefore = $normalUserWallet['balance'];

        // Add money to normal user's wallet
        $walletModel->addMoney($request['user_id'], $request['amount'], 'topup_approved_by_special_user');

        // Deduct money from special user's wallet (net amount after commission)
        $walletModel->deductMoney($specialUserId, $netAmount, 'topup_payment_to_normal_user');

        // Get updated balances after wallet operations
        $specialUserWallet = $walletModel->getUserWallet($specialUserId);
        $normalUserWallet = $walletModel->getUserWallet($request['user_id']);

        // Create wallet transaction for normal user
        $transactionModel = new WalletTransactionModel();
        $transactionModel->insert([
            'wallet_id' => $normalUserWallet['id'],
            'type' => 'topup',
            'amount' => $request['amount'],
            'balance_before' => $normalUserBalanceBefore,
            'balance_after' => $normalUserWallet['balance'],
            'status' => 'completed',
            'description' => 'Top-up approved by special user - Rs. ' . number_format($request['amount'], 2),
            'payment_method' => $request['payment_method'],
            'payment_reference' => 'TOPUP-SPECIAL-' . $requestId,
            'metadata' => json_encode([
                'topup_request_id' => $requestId,
                'approved_by_special_user' => $specialUserId,
                'notes' => $notes
            ])
        ]);

        // Create wallet transaction for special user (deduction - NEGATIVE amount)
        $transactionModel->insert([
            'wallet_id' => $specialUserWallet['id'],
            'type' => 'deduction',
            'amount' => -$netAmount, // NEGATIVE amount for deduction
            'balance_before' => $specialUserBalanceBefore,
            'balance_after' => $specialUserWallet['balance'],
            'status' => 'completed',
            'description' => 'Payment to normal user topup - Rs. ' . number_format($netAmount, 2),
            'payment_method' => 'wallet_transfer',
            'payment_reference' => 'PAYMENT-' . $requestId,
            'metadata' => json_encode([
                'topup_request_id' => $requestId,
                'normal_user_id' => $request['user_id'],
                'commission_earned' => $commissionAmount,
                'notes' => $notes
            ])
        ]);

        // Add commission to special user's wallet
        if ($commissionAmount > 0) {
            $walletModel->addMoney($specialUserId, $commissionAmount, 'commission_earned');
            
            // Get final balance after commission
            $finalSpecialUserWallet = $walletModel->getUserWallet($specialUserId);
            
            // Create commission transaction
            $transactionModel->insert([
                'wallet_id' => $specialUserWallet['id'],
                'type' => 'commission',
                'amount' => $commissionAmount,
                'balance_before' => $specialUserWallet['balance'],
                'balance_after' => $finalSpecialUserWallet['balance'],
                'status' => 'completed',
                'description' => 'Commission earned from topup approval - Rs. ' . number_format($commissionAmount, 2),
                'payment_method' => 'commission',
                'payment_reference' => 'COMMISSION-' . $requestId,
                'metadata' => json_encode([
                    'topup_request_id' => $requestId,
                    'normal_user_id' => $request['user_id'],
                    'commission_percentage' => $commissionPercentage,
                    'notes' => $notes
                ])
            ]);
        }

        return true;
    }

    /**
     * Special user rejects topup request
     */
    public function rejectBySpecialUser($requestId, $specialUserId, $notes = null)
    {
        $request = $this->find($requestId);
        if (!$request || $request['status'] !== 'pending' || $request['special_user_id'] != $specialUserId) {
            return false;
        }

        return $this->update($requestId, [
            'status' => 'rejected',
            'special_user_notes' => $notes,
            'special_user_processed_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Create topup request for special user self-topup (admin approval required)
     */
    public function createSpecialUserTopupRequest($specialUserId, $amount, $paymentMethod, $paymentProof, $notes = null)
    {
        $data = [
            'user_id' => $specialUserId,
            'special_user_id' => null, // No special user for self-topup
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'payment_proof' => $paymentProof,
            'status' => 'pending',
            'admin_notes' => $notes,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        return $this->insert($data);
    }
}
