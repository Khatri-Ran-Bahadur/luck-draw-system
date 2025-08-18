<?php

namespace App\Models;

use CodeIgniter\Model;

class LuckyDrawModel extends Model
{
    protected $table = 'lucky_draws';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'title',
        'description',
        'draw_type',
        'entry_fee',
        'total_winners',
        'draw_date',
        'is_manual_selection',
        'product_image',
        'product_details',
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
        'draw_type' => 'required|in_list[cash,product]',
        'entry_fee' => 'required|numeric|greater_than[0]',
        'total_winners' => 'required|integer|greater_than[0]',
        'draw_date' => 'required|valid_date',
        'status' => 'required|in_list[active,inactive,completed]'
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Title is required',
            'min_length' => 'Title must be at least 3 characters long',
            'max_length' => 'Title cannot exceed 255 characters'
        ],
        'description' => [
            'required' => 'Description is required',
            'min_length' => 'Description must be at least 10 characters long'
        ],
        'entry_fee' => [
            'required' => 'Entry fee is required',
            'numeric' => 'Entry fee must be a number',
            'greater_than' => 'Entry fee must be greater than 0'
        ],
        'total_winners' => [
            'required' => 'Total winners is required',
            'integer' => 'Total winners must be a whole number',
            'greater_than' => 'Total winners must be greater than 0'
        ],
        'draw_date' => [
            'required' => 'Draw date is required',
            'valid_date' => 'Please enter a valid date'
        ]
    ];

    // Get all active draws that haven't ended yet
    public function getActiveDraws()
    {
        return $this->where('status', 'active')
                   ->where('draw_date >', date('Y-m-d H:i:s'))
                   ->orderBy('draw_date', 'ASC')
                   ->findAll();
    }

    // Get draws by type
    public function getDrawsByType($type)
    {
        return $this->where('draw_type', $type)
                   ->where('status', 'active')
                   ->findAll();
    }

    // Get cash draws - redirect to CashDrawModel
    public function getCashDraws()
    {
        $cashDrawModel = new \App\Models\CashDrawModel();
        return $cashDrawModel->getActiveDraws();
    }

    // Get product draws - redirect to ProductDrawModel
    public function getProductDraws()
    {
        $productDrawModel = new \App\Models\ProductDrawModel();
        return $productDrawModel->getActiveDraws();
    }

    // Get draw with entries count
    public function getDrawWithEntries($drawId)
    {
        return $this->select('lucky_draws.*, COUNT(entries.id) as total_entries')
                   ->join('entries', 'entries.lucky_draw_id = lucky_draws.id', 'left')
                   ->where('lucky_draws.id', $drawId)
                   ->groupBy('lucky_draws.id')
                   ->first();
    }

    // Get all draws for admin with entry counts
    public function getDrawsForAdmin()
    {
        return $this->select('lucky_draws.*, COUNT(entries.id) as total_entries')
                   ->join('entries', 'entries.lucky_draw_id = lucky_draws.id', 'left')
                   ->groupBy('lucky_draws.id')
                   ->orderBy('lucky_draws.created_at', 'DESC')
                   ->findAll();
    }

    // Get draw by ID with all related data
    public function getDrawById($id)
    {
        return $this->find($id);
    }
}
