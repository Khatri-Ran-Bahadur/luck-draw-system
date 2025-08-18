<?php

namespace App\Models;

use CodeIgniter\Model;

class WinnerModel extends Model
{
    protected $table = 'winners';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'lucky_draw_id',
        'cash_draw_id',
        'product_draw_id',
        'draw_type',
        'user_id',
        'position',
        'prize_amount',
        'is_claimed',
        'claim_details',
        'claim_approved',
        'approved_at',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'user_id' => 'required|integer|greater_than[0]',
        'position' => 'required|integer|greater_than[0]',
        'prize_amount' => 'required|numeric|greater_than[0]',
        'draw_type' => 'required|in_list[cash,product]'
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID is required',
            'integer' => 'User ID must be a valid integer',
            'greater_than' => 'User ID must be greater than 0'
        ],
        'position' => [
            'required' => 'Position is required',
            'integer' => 'Position must be a valid integer',
            'greater_than' => 'Position must be greater than 0'
        ],
        'prize_amount' => [
            'required' => 'Prize amount is required',
            'numeric' => 'Prize amount must be a valid number',
            'greater_than' => 'Prize amount must be greater than 0'
        ],
        'draw_type' => [
            'required' => 'Draw type is required',
            'in_list' => 'Draw type must be either cash or product'
        ]
    ];

    /**
     * Custom validation to ensure proper draw ID is provided
     */
    public function validateDrawId($data)
    {
        if ($data['draw_type'] === 'cash') {
            if (empty($data['cash_draw_id'])) {
                return false;
            }
        } elseif ($data['draw_type'] === 'product') {
            if (empty($data['product_draw_id'])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Override insert method to add custom validation
     */
    public function insert($data = null, bool $returnID = true)
    {
        // Validate draw ID before insertion
        if (!$this->validateDrawId($data)) {
            return false;
        }

        return parent::insert($data, $returnID);
    }

    // Get winners for a specific lucky draw
    public function getWinnersByDraw($drawId)
    {
        return $this->where('lucky_draw_id', $drawId)
            ->orderBy('position', 'ASC')
            ->findAll();
    }

    // Get winners for a specific cash draw
    public function getCashDrawWinners($cashDrawId)
    {
        return $this->select('winners.*, users.username, users.full_name, users.email, cash_draws.title, cash_draws.prize_amount')
            ->join('users', 'users.id = winners.user_id')
            ->join('cash_draws', 'cash_draws.id = winners.cash_draw_id')
            ->where('winners.cash_draw_id', $cashDrawId)
            ->orderBy('winners.position', 'ASC')
            ->findAll();
    }

    // Get winners for a specific product draw
    public function getProductDrawWinners($productDrawId)
    {
        return $this->select('winners.*, users.username, users.full_name, users.email, product_draws.title, product_draws.product_name, product_draws.product_price')
            ->join('users', 'users.id = winners.user_id')
            ->join('product_draws', 'product_draws.id = winners.product_draw_id')
            ->where('winners.product_draw_id', $productDrawId)
            ->orderBy('winners.position', 'ASC')
            ->findAll();
    }

    // Get all winners for display (both cash and product)
    public function getAllWinners($limit = 20)
    {
        $cashWinners = $this->select('winners.*, users.username, users.full_name, cash_draws.title, cash_draws.prize_amount, "cash" as draw_type')
            ->join('users', 'users.id = winners.user_id')
            ->join('cash_draws', 'cash_draws.id = winners.cash_draw_id')
            ->where('winners.cash_draw_id IS NOT NULL')
            ->orderBy('winners.created_at', 'DESC')
            ->limit($limit)
            ->findAll();

        $productWinners = $this->select('winners.*, users.username, users.full_name, product_draws.title, product_draws.product_name, product_draws.product_price, "product" as draw_type')
            ->join('users', 'users.id = winners.user_id')
            ->join('product_draws', 'product_draws.id = winners.product_draw_id')
            ->where('winners.product_draw_id IS NOT NULL')
            ->orderBy('winners.created_at', 'DESC')
            ->limit($limit)
            ->findAll();

        // Merge and sort by creation date
        $allWinners = array_merge($cashWinners, $productWinners);
        usort($allWinners, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return array_slice($allWinners, 0, $limit);
    }

    // Get approved winners only (for admin display of approved claims)
    public function getApprovedWinners($limit = 20)
    {
        $cashWinners = $this->select('winners.*, users.username, users.full_name, cash_draws.title, cash_draws.prize_amount, "cash" as draw_type')
            ->join('users', 'users.id = winners.user_id')
            ->join('cash_draws', 'cash_draws.id = winners.cash_draw_id')
            ->where('winners.cash_draw_id IS NOT NULL')
            ->where('winners.claim_approved', true)
            ->orderBy('winners.created_at', 'DESC')
            ->limit($limit)
            ->findAll();

        $productWinners = $this->select('winners.*, users.username, users.full_name, product_draws.title, product_draws.product_name, product_draws.product_price, "product" as draw_type')
            ->join('users', 'users.id = winners.user_id')
            ->join('product_draws', 'product_draws.id = winners.product_draw_id')
            ->where('winners.product_draw_id IS NOT NULL')
            ->where('winners.claim_approved', true)
            ->orderBy('winners.created_at', 'DESC')
            ->limit($limit)
            ->findAll();

        // Merge and sort by creation date
        $allWinners = array_merge($cashWinners, $productWinners);
        usort($allWinners, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return array_slice($allWinners, 0, $limit);
    }

    // Get all winners for admin display (both claimed and unclaimed)
    public function getAllWinnersForAdmin()
    {
        // Get cash winners using separate queries to avoid join conflicts
        $cashWinners = $this->db->table('winners')
            ->select('winners.*, users.username, users.full_name, users.email')
            ->join('users', 'users.id = winners.user_id')
            ->where('winners.cash_draw_id IS NOT NULL')
            ->orderBy('winners.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Get product winners using separate queries to avoid join conflicts
        $productWinners = $this->db->table('winners')
            ->select('winners.*, users.username, users.full_name, users.email')
            ->join('users', 'users.id = winners.user_id')
            ->where('winners.product_draw_id IS NOT NULL')
            ->orderBy('winners.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Add draw details separately for cash winners
        foreach ($cashWinners as &$winner) {
            $draw = $this->db->table('cash_draws')
                ->select('title, prize_amount')
                ->where('id', $winner['cash_draw_id'])
                ->get()
                ->getRowArray();
            if ($draw) {
                $winner['draw_title'] = $draw['title'];
                $winner['prize_amount'] = $draw['prize_amount'];
            }
            $winner['draw_type'] = 'cash';
        }

        // Add draw details separately for product winners
        foreach ($productWinners as &$winner) {
            $draw = $this->db->table('product_draws')
                ->select('title, product_name, product_price')
                ->where('id', $winner['product_draw_id'])
                ->get()
                ->getRowArray();
            if ($draw) {
                $winner['draw_title'] = $draw['title'];
                $winner['prize_amount'] = $draw['product_price'];
            }
            $winner['draw_type'] = 'product';
        }

        // Merge and sort by creation date
        $allWinners = array_merge($cashWinners, $productWinners);
        usort($allWinners, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return $allWinners;
    }

    // Get winner with complete details for claim form
    public function getWinnerWithDetails($winnerId)
    {
        $winner = $this->find($winnerId);
        if (!$winner) {
            return null;
        }

        // Get user details
        $user = $this->select('users.username, users.full_name, users.email')
            ->join('users', 'users.id = winners.user_id')
            ->where('winners.id', $winnerId)
            ->first();

        if ($user) {
            $winner = array_merge($winner, $user);
        }

        // Get draw details based on type
        if ($winner['draw_type'] === 'cash' && $winner['cash_draw_id']) {
            $draw = $this->db->table('cash_draws')
                ->select('title, prize_amount')
                ->where('id', $winner['cash_draw_id'])
                ->get()
                ->getRowArray();

            if ($draw) {
                $winner['draw_title'] = $draw['title'];
                $winner['prize_amount'] = $draw['prize_amount'];
            }
        } elseif ($winner['draw_type'] === 'product' && $winner['product_draw_id']) {
            $draw = $this->db->table('product_draws')
                ->select('title, product_name, product_price')
                ->where('id', $winner['product_draw_id'])
                ->get()
                ->getRowArray();

            if ($draw) {
                $winner['draw_title'] = $draw['title'];
                $winner['product_name'] = $draw['product_name'];
                $winner['prize_amount'] = $draw['product_price'];
            }
        }

        return $winner;
    }

    // Get pending claims
    public function getPendingClaims()
    {
        // Get pending claims using separate queries to avoid join conflicts
        $pendingClaims = $this->db->table('winners')
            ->select('winners.*, users.username, users.email, users.full_name')
            ->join('users', 'users.id = winners.user_id')
            ->where('winners.is_claimed', true)
            ->where('winners.claim_approved', false)
            ->orderBy('winners.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Add draw details separately
        foreach ($pendingClaims as &$claim) {
            if ($claim['cash_draw_id']) {
                $draw = $this->db->table('cash_draws')
                    ->select('title, prize_amount')
                    ->where('id', $claim['cash_draw_id'])
                    ->get()
                    ->getRowArray();
                if ($draw) {
                    $claim['draw_title'] = $draw['title'];
                    $claim['prize_amount'] = $draw['prize_amount'];
                }
                $claim['draw_type'] = 'cash';
            } elseif ($claim['product_draw_id']) {
                $draw = $this->db->table('product_draws')
                    ->select('title, product_name, product_price')
                    ->where('id', $claim['product_draw_id'])
                    ->get()
                    ->getRowArray();
                if ($draw) {
                    $claim['draw_title'] = $draw['title'];
                    $claim['prize_amount'] = $draw['product_price'];
                }
                $claim['draw_type'] = 'product';
            }
        }

        return $pendingClaims;
    }

    // Get approved claims
    public function getApprovedClaims()
    {
        // Get approved claims using separate queries to avoid join conflicts
        $approvedClaims = $this->db->table('winners')
            ->select('winners.*, users.username, users.email, users.full_name')
            ->join('users', 'users.id = winners.user_id')
            ->where('winners.claim_approved', true)
            ->orderBy('winners.approved_at', 'DESC')
            ->get()
            ->getResultArray();

        // Add draw details separately
        foreach ($approvedClaims as &$claim) {
            if ($claim['cash_draw_id']) {
                $draw = $this->db->table('cash_draws')
                    ->select('title, prize_amount')
                    ->where('id', $claim['cash_draw_id'])
                    ->get()
                    ->getRowArray();
                if ($draw) {
                    $claim['draw_title'] = $draw['title'];
                    $claim['prize_amount'] = $draw['prize_amount'];
                }
                $claim['draw_type'] = 'cash';
            } elseif ($claim['product_draw_id']) {
                $draw = $this->db->table('product_draws')
                    ->select('title, product_name, product_price')
                    ->where('id', $claim['product_draw_id'])
                    ->get()
                    ->getRowArray();
                if ($draw) {
                    $claim['draw_title'] = $draw['title'];
                    $claim['prize_amount'] = $draw['product_price'];
                }
                $claim['draw_type'] = 'product';
            }
        }

        return $approvedClaims;
    }

    // Approve a claim
    public function approveClaim($winnerId)
    {
        return $this->update($winnerId, [
            'claim_approved' => true,
            'approved_at' => date('Y-m-d H:i:s')
        ]);
    }

    // Get user's winnings
    public function getUserWinnings($userId)
    {
        // Get cash draw winners
        $cashWinners = $this->db->table('winners')
            ->select('winners.*, cash_draws.title as draw_title, cash_draws.prize_amount')
            ->join('cash_draws', 'cash_draws.id = winners.cash_draw_id')
            ->where('winners.user_id', $userId)
            ->where('winners.cash_draw_id IS NOT NULL')
            ->orderBy('winners.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Get product draw winners
        $productWinners = $this->db->table('winners')
            ->select('winners.*, product_draws.title as draw_title, product_draws.product_name, product_draws.product_price as prize_amount')
            ->join('product_draws', 'product_draws.id = winners.product_draw_id')
            ->where('winners.user_id', $userId)
            ->where('winners.product_draw_id IS NOT NULL')
            ->orderBy('winners.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Add draw type to each winner
        foreach ($cashWinners as &$winner) {
            $winner['draw_type'] = 'cash';
        }
        foreach ($productWinners as &$winner) {
            $winner['draw_type'] = 'product';
        }

        // Merge and sort by creation date
        $allWinnings = array_merge($cashWinners, $productWinners);
        usort($allWinnings, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return $allWinnings;
    }

    // Get user's total winnings amount
    public function getUserTotalWinnings($userId)
    {
        $cashWinnings = $this->select('SUM(cash_draws.prize_amount) as total')
            ->join('cash_draws', 'cash_draws.id = winners.cash_draw_id')
            ->where('winners.user_id', $userId)
            ->where('winners.cash_draw_id IS NOT NULL')
            ->first();

        $productWinnings = $this->select('SUM(product_draws.product_price) as total')
            ->join('product_draws', 'product_draws.id = winners.product_draw_id')
            ->where('winners.user_id', $userId)
            ->where('winners.product_draw_id IS NOT NULL')
            ->first();

        $total = 0;
        if ($cashWinnings && $cashWinnings['total']) {
            $total += $cashWinnings['total'];
        }
        if ($productWinnings && $productWinnings['total']) {
            $total += $productWinnings['total'];
        }

        return $total;
    }

    // Clear existing winners for a cash draw
    public function clearCashDrawWinners($cashDrawId)
    {
        return $this->where('cash_draw_id', $cashDrawId)->delete();
    }

    // Clear existing winners for a product draw
    public function clearProductDrawWinners($productDrawId)
    {
        return $this->where('product_draw_id', $productDrawId)->delete();
    }
}
