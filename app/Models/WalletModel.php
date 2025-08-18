<?php

namespace App\Models;

use CodeIgniter\Model;

class WalletModel extends Model
{
    protected $table = 'wallets';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'user_id',
        'balance',
        'currency',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'user_id' => 'required|integer|greater_than[0]',
        'balance' => 'required|numeric|greater_than_equal_to[0]',
        'currency' => 'required|in_list[USD,PKR]',
        'status' => 'required|in_list[active,inactive,suspended]'
    ];

    // Get user's wallet
    public function getUserWallet($userId)
    {
        $wallet = $this->where('user_id', $userId)->first();
        
        if (!$wallet) {
            // Create wallet if it doesn't exist
            $wallet = $this->createWallet($userId);
        }
        
        return $wallet;
    }

    // Create a new wallet for user
    public function createWallet($userId)
    {
        $data = [
            'user_id' => $userId,
            'balance' => 0.00,
            'currency' => 'USD', // Default currency
            'status' => 'active'
        ];
        
        $this->insert($data);
        return $this->find($this->insertID);
    }

    // Add money to wallet (topup)
    public function addMoney($userId, $amount, $transactionType = 'topup')
    {
        $wallet = $this->getUserWallet($userId);
        
        if (!$wallet) {
            return false;
        }
        
        $newBalance = $wallet['balance'] + $amount;
        
        $this->update($wallet['id'], ['balance' => $newBalance]);
        
        // Record transaction
        $transactionModel = new WalletTransactionModel();
        $transactionModel->insert([
            'wallet_id' => $wallet['id'],
            'type' => $transactionType,
            'amount' => $amount,
            'balance_before' => $wallet['balance'],
            'balance_after' => $newBalance,
            'status' => 'completed',
            'description' => ucfirst($transactionType) . ' - $' . number_format($amount, 2)
        ]);
        
        return true;
    }

    // Deduct money from wallet
    public function deductMoney($userId, $amount, $transactionType = 'deduction')
    {
        $wallet = $this->getUserWallet($userId);
        
        if (!$wallet || $wallet['balance'] < $amount) {
            return false;
        }
        
        $newBalance = $wallet['balance'] - $amount;
        
        $this->update($wallet['id'], ['balance' => $newBalance]);
        
        // Record transaction
        $transactionModel = new WalletTransactionModel();
        $transactionModel->insert([
            'wallet_id' => $wallet['id'],
            'type' => $transactionType,
            'amount' => -$amount, // Negative for deductions
            'balance_before' => $wallet['balance'],
            'balance_after' => $newBalance,
            'status' => 'completed',
            'description' => ucfirst($transactionType) . ' - $' . number_format($amount, 2)
        ]);
        
        return true;
    }

    // Check if user has sufficient balance
    public function hasSufficientBalance($userId, $amount)
    {
        $wallet = $this->getUserWallet($userId);
        return $wallet && $wallet['balance'] >= $amount;
    }

    // Get wallet balance
    public function getBalance($userId)
    {
        $wallet = $this->getUserWallet($userId);
        return $wallet ? $wallet['balance'] : 0;
    }

    // Update wallet currency
    public function updateCurrency($userId, $currency)
    {
        $wallet = $this->getUserWallet($userId);
        if ($wallet) {
            return $this->update($wallet['id'], ['currency' => $currency]);
        }
        return false;
    }
}
