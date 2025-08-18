<?php

namespace App\Models;

use CodeIgniter\Model;

class EntryModel extends Model
{
    protected $table = 'entries';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'lucky_draw_id',
        'cash_draw_id',
        'product_draw_id',
        'draw_type',
        'entry_number',
        'payment_status',
        'payment_method',
        'payment_reference',
        'amount_paid',
        'entry_date'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'user_id' => 'required|integer',
        'entry_number' => 'required|is_unique[entries.entry_number,id,{id}]',
        'payment_status' => 'required|in_list[pending,completed,failed,refunded]',
        'payment_method' => 'permit_empty|in_list[easypaisa,paypal,wallet]',
        'amount_paid' => 'required|decimal',
        // Remove required validation for draw IDs
        'lucky_draw_id' => 'permit_empty|integer',
        'cash_draw_id' => 'permit_empty|integer',
        'product_draw_id' => 'permit_empty|integer',
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID is required',
            'integer' => 'User ID must be a valid integer'
        ],
        'entry_number' => [
            'required' => 'Entry number is required',
            'is_unique' => 'Entry number already exists'
        ],
        'payment_status' => [
            'required' => 'Payment status is required',
            'in_list' => 'Invalid payment status'
        ],
        'payment_method' => [
            'in_list' => 'Invalid payment method'
        ],
        'amount_paid' => [
            'required' => 'Amount paid is required',
            'decimal' => 'Amount paid must be a valid decimal number'
        ],
    ];

    public function insert($data = null, bool $returnID = true)
    {
        // Add debug logging
        log_message('info', 'EntryModel insert called with data: ' . json_encode($data));

        // Ensure required fields are present
        if (empty($data['user_id'])) {
            throw new \Exception('User ID is required');
        }

        if (empty($data['entry_number'])) {
            throw new \Exception('Entry number is required');
        }

        if (empty($data['payment_status'])) {
            throw new \Exception('Payment status is required');
        }

        if (empty($data['amount_paid'])) {
            throw new \Exception('Amount paid is required');
        }

        // Check if at least one draw ID is present
        $hasDrawId = !empty($data['lucky_draw_id']) || !empty($data['cash_draw_id']) || !empty($data['product_draw_id']);
        if (!$hasDrawId) {
            throw new \Exception('At least one draw ID (lucky_draw_id, cash_draw_id, or product_draw_id) must be provided');
        }

        try {
            $result = parent::insert($data, $returnID);
            log_message('info', 'EntryModel insert successful, result: ' . json_encode($result));
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'EntryModel insert failed: ' . $e->getMessage());
            throw $e;
        }
    }

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function generateEntryNumber()
    {
        do {
            $entryNumber = 'ENT-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));
            $exists = $this->where('entry_number', $entryNumber)->first();
        } while ($exists);

        return $entryNumber;
    }

    public function getUserEntries($userId)
    {
        return $this->select('entries.*, lucky_draws.title, lucky_draws.draw_date')
            ->join('lucky_draws', 'lucky_draws.id = entries.lucky_draw_id')
            ->where('entries.user_id', $userId)
            ->orderBy('entries.entry_date', 'DESC')
            ->findAll();
    }

    public function getDrawEntries($drawId)
    {
        return $this->select('entries.*, users.username, users.full_name, users.email')
            ->join('users', 'users.id = entries.user_id')
            ->where('entries.lucky_draw_id', $drawId)
            ->where('entries.payment_status', 'completed')
            ->orderBy('entries.entry_date', 'ASC')
            ->findAll();
    }

    public function getCompletedEntries($drawId)
    {
        return $this->where('lucky_draw_id', $drawId)
            ->where('payment_status', 'completed')
            ->findAll();
    }

    public function updatePaymentStatus($entryId, $status, $reference = null)
    {
        $data = ['payment_status' => $status];
        if ($reference) {
            $data['payment_reference'] = $reference;
        }

        return $this->update($entryId, $data);
    }

    public function getUserActiveEntries($userId)
    {
        return $this->select('entries.*, lucky_draws.title, lucky_draws.draw_date, lucky_draws.status')
            ->join('lucky_draws', 'lucky_draws.id = entries.lucky_draw_id')
            ->where('entries.user_id', $userId)
            ->where('entries.payment_status', 'completed')
            ->whereIn('lucky_draws.status', ['upcoming', 'active'])
            ->orderBy('lucky_draws.draw_date', 'ASC')
            ->findAll();
    }

    public function checkUserEntry($userId, $drawId)
    {
        return $this->where('user_id', $userId)
            ->where('lucky_draw_id', $drawId)
            ->where('payment_status', 'completed')
            ->first();
    }

    public function getCashDrawEntries($cashDrawId)
    {
        return $this->select('entries.*, users.username, users.full_name, users.email')
            ->join('users', 'users.id = entries.user_id')
            ->where('entries.cash_draw_id', $cashDrawId)
            ->where('entries.payment_status', 'completed')
            ->orderBy('entries.created_at', 'ASC')
            ->findAll();
    }

    public function getProductDrawEntries($productDrawId)
    {
        return $this->select('entries.*, users.username, users.full_name, users.email')
            ->join('users', 'users.id = entries.user_id')
            ->where('entries.product_draw_id', $productDrawId)
            ->where('entries.payment_status', 'completed')
            ->orderBy('entries.created_at', 'ASC')
            ->findAll();
    }

    public function checkUserCashDrawEntry($userId, $cashDrawId)
    {
        return $this->where('user_id', $userId)
            ->where('cash_draw_id', $cashDrawId)
            ->where('payment_status', 'completed')
            ->first();
    }

    public function checkUserProductDrawEntry($userId, $productDrawId)
    {
        log_message('info', 'checkUserProductDrawEntry called with user ID: ' . $userId . ' and product draw ID: ' . $productDrawId);

        $result = $this->where('user_id', $userId)
            ->where('product_draw_id', $productDrawId)
            ->where('payment_status', 'completed')
            ->first();

        log_message('info', 'checkUserProductDrawEntry result: ' . json_encode($result));

        return $result;
    }
}
