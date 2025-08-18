<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductDrawModel extends Model
{
    protected $table = 'product_draws';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'title',
        'description',
        'product_name',
        'product_image',
        'product_price',
        'entry_fee',
        'draw_date',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'description' => 'required|min_length[10]',
        'product_name' => 'required|min_length[3]|max_length[255]',
        'product_price' => 'required|numeric|greater_than[0]',
        'entry_fee' => 'required|numeric|greater_than[0]',
        'draw_date' => 'required|valid_date',
        'status' => 'required|in_list[active,inactive,completed]'
    ];

    // Get all active product draws that haven't ended yet
    public function getActiveDraws()
    {
        return $this->where('status', 'active')
            ->where('draw_date >', date('Y-m-d H:i:s'))
            ->orderBy('draw_date', 'ASC')
            ->findAll();
    }

    // Get product draw by ID
    public function getDrawById($drawId)
    {
        return $this->find($drawId);
    }

    // Get all product draws for admin
    public function getDrawsForAdmin()
    {
        return $this->orderBy('created_at', 'DESC')->findAll();
    }

    // Get active draws with participant counts that haven't ended yet
    public function getActiveDrawsWithCounts()
    {
        $draws = $this->select('product_draws.*, COUNT(entries.id) as participant_count')
            ->join('entries', 'entries.product_draw_id = product_draws.id AND entries.payment_status = "completed"', 'left')
            ->where('product_draws.status', 'active')
            ->groupBy('product_draws.id')
            ->findAll();

        // Ensure participant_count is never null
        foreach ($draws as &$draw) {
            $draw['participant_count'] = (int)($draw['participant_count'] ?? 0);
        }

        return $draws;
    }

    // Get draw with participant count
    public function getDrawWithParticipants($drawId)
    {
        return $this->select('product_draws.*, 
                             (SELECT COUNT(*) FROM entries WHERE entries.product_draw_id = product_draws.id AND entries.payment_status = "completed") as participant_count')
            ->where('product_draws.id', $drawId)
            ->first();
    }

    // Get recent winners
    public function getRecentWinners($limit = 10)
    {
        return $this->select('product_draws.title, product_draws.product_name, product_draws.product_price, winners.position, winners.created_at, users.username, users.full_name')
            ->join('winners', 'winners.product_draw_id = product_draws.id')
            ->join('users', 'users.id = winners.user_id')
            ->where('product_draws.status', 'completed')
            ->where('winners.claim_approved', true)
            ->orderBy('winners.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    // Get completed draws with winners
    public function getCompletedDrawsWithWinners()
    {
        return $this->select('product_draws.*, 
                             (SELECT COUNT(*) FROM winners WHERE winners.product_draw_id = product_draws.id) as winner_count')
            ->where('status', 'completed')
            ->orderBy('draw_date', 'DESC')
            ->findAll();
    }

    // Auto-update status of draws that have ended
    public function updateExpiredDraws()
    {
        return $this->where('status', 'active')
            ->where('draw_date <=', date('Y-m-d H:i:s'))
            ->set('status', 'completed')
            ->update();
    }

    // Get upcoming draws (not started yet)
    public function getUpcomingDraws()
    {
        return $this->where('status', 'active')
            ->where('draw_date >', date('Y-m-d H:i:s'))
            ->where('draw_date <=', date('Y-m-d H:i:s', strtotime('+7 days')))
            ->orderBy('draw_date', 'ASC')
            ->findAll();
    }

    // Increment participant count when someone joins
    public function incrementParticipantCount($drawId)
    {
        $draw = $this->find($drawId);
        if ($draw) {
            $currentCount = (int)($draw['participant_count'] ?? 0);
            $this->update($drawId, [
                'participant_count' => $currentCount + 1,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    // Decrement participant count (if needed for refunds/cancellations)
    public function decrementParticipantCount($drawId)
    {
        $draw = $this->find($drawId);
        if ($draw) {
            $currentCount = (int)($draw['participant_count'] ?? 0);
            if ($currentCount > 0) {
                $this->update($drawId, [
                    'participant_count' => $currentCount - 1,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }
}
