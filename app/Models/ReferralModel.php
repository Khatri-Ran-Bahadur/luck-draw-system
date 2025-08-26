<?php

namespace App\Models;

use CodeIgniter\Model;

class ReferralModel extends Model
{
    protected $table = 'referrals';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'referrer_id',
        'referred_id',
        'referral_code',
        'bonus_amount',
        'bonus_paid',
        'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'referrer_id' => 'required|integer',
        'referred_id' => 'required|integer',
        'referral_code' => 'required|min_length[3]|max_length[20]',
        'bonus_amount' => 'required|decimal',
        'status' => 'required|in_list[pending,active,completed,cancelled]'
    ];

    protected $validationMessages = [
        'referrer_id' => [
            'required' => 'Referrer ID is required',
            'integer' => 'Referrer ID must be a valid integer'
        ],
        'referred_id' => [
            'required' => 'Referred ID is required',
            'integer' => 'Referred ID must be a valid integer'
        ],
        'referral_code' => [
            'required' => 'Referral code is required',
            'min_length' => 'Referral code must be at least 3 characters long',
            'max_length' => 'Referral code cannot exceed 20 characters'
        ],
        'bonus_amount' => [
            'required' => 'Bonus amount is required',
            'decimal' => 'Bonus amount must be a valid decimal number'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Status must be one of: pending, active, completed, cancelled'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get all referrals for a specific user (as referrer)
     */
    public function getUserReferrals($userId)
    {
        return $this->select('referrals.*, users.username, users.full_name, users.email')
            ->join('users', 'users.id = referrals.referred_id')
            ->where('referrals.referrer_id', $userId)
            ->orderBy('referrals.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get referral statistics for a user
     */
    public function getUserReferralStats($userId)
    {
        $totalReferrals = $this->where('referrer_id', $userId)->countAllResults();
        $activeReferrals = $this->where('referrer_id', $userId)->where('status', 'active')->countAllResults();
        $completedReferrals = $this->where('referrer_id', $userId)->where('status', 'completed')->countAllResults();
        $totalBonusEarned = $this->selectSum('bonus_amount')
            ->where('referrer_id', $userId)
            ->where('bonus_paid', true)
            ->get()
            ->getRow()
            ->bonus_amount ?? 0;

        return [
            'total_referrals' => $totalReferrals,
            'active_referrals' => $activeReferrals,
            'completed_referrals' => $completedReferrals,
            'total_bonus_earned' => $totalBonusEarned
        ];
    }

    /**
     * Check if a referral code exists and is valid
     */
    public function isValidReferralCode($referralCode)
    {
        $user = $this->db->table('users')
            ->where('referral_code', $referralCode)
            ->where('status', 'active')
            ->get()
            ->getRowArray();

        return $user ? $user : false;
    }

    /**
     * Create a new referral relationship
     */
    public function createReferral($referrerId, $referredId, $referralCode, $bonusAmount)
    {
        $data = [
            'referrer_id' => $referrerId,
            'referred_id' => $referredId,
            'referral_code' => $referralCode,
            'bonus_amount' => $bonusAmount,
            'bonus_paid' => false,
            'status' => 'pending'
        ];

        return $this->insert($data);
    }

    /**
     * Update referral status
     */
    public function updateReferralStatus($referralId, $status)
    {
        return $this->update($referralId, ['status' => $status]);
    }

    /**
     * Mark referral bonus as paid
     */
    public function markBonusAsPaid($referralId)
    {
        return $this->update($referralId, ['bonus_paid' => true]);
    }

    /**
     * Get pending referrals that need bonus payment
     */
    public function getPendingBonusReferrals()
    {
        return $this->select('referrals.*, users.username, users.full_name')
            ->join('users', 'users.id = referrals.referrer_id')
            ->where('referrals.status', 'active')
            ->where('referrals.bonus_paid', false)
            ->findAll();
    }

    /**
     * Get all referrals for admin view
     */
    public function getAllReferralsForAdmin($limit = 50, $offset = 0)
    {
        return $this->select('referrals.*, 
                referrer.username as referrer_username, 
                referrer.full_name as referrer_full_name,
                referred.username as referred_username, 
                referred.full_name as referred_full_name')
            ->join('users as referrer', 'referrer.id = referrals.referrer_id')
            ->join('users as referred', 'referred.id = referrals.referred_id')
            ->orderBy('referrals.created_at', 'DESC')
            ->limit($limit, $offset)
            ->findAll();
    }

    /**
     * Count total referrals for admin
     */
    public function countAllReferrals()
    {
        return $this->countAllResults();
    }

    /**
     * Get referral statistics for admin dashboard
     */
    public function getAdminReferralStats()
    {
        $totalReferrals = $this->countAllResults();
        $activeReferrals = $this->where('status', 'active')->countAllResults();
        $completedReferrals = $this->where('status', 'completed')->countAllResults();
        $pendingReferrals = $this->where('status', 'pending')->countAllResults();
        $totalBonusPaid = $this->selectSum('bonus_amount')
            ->where('bonus_paid', true)
            ->get()
            ->getRow()
            ->bonus_amount ?? 0;
        $totalBonusPending = $this->selectSum('bonus_amount')
            ->where('bonus_paid', false)
            ->where('status', 'active')
            ->get()
            ->getRow()
            ->bonus_amount ?? 0;

        return [
            'total_referrals' => $totalReferrals,
            'active_referrals' => $activeReferrals,
            'completed_referrals' => $completedReferrals,
            'pending_referrals' => $pendingReferrals,
            'total_bonus_paid' => $totalBonusPaid,
            'total_bonus_pending' => $totalBonusPending
        ];
    }
}
