<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'username',
        'email',
        'password',
        'full_name',
        'phone',
        'profile_image',
        'is_admin',
        'status',
        'google_id',
        'login_type',
        'reset_token',
        'reset_token_expires',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,{id}]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'full_name' => 'required|min_length[2]|max_length[255]',
        'phone' => 'permit_empty|min_length[10]|max_length[20]',
    ];

    protected $validationMessages = [
        'username' => [
            'required' => 'Username is required',
            'min_length' => 'Username must be at least 3 characters long',
            'max_length' => 'Username cannot exceed 100 characters',
            'is_unique' => 'Username already exists'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email address',
            'is_unique' => 'Email already exists'
        ],
        'password' => [
            'required' => 'Password is required',
            'min_length' => 'Password must be at least 6 characters long'
        ],
        'full_name' => [
            'required' => 'Full name is required',
            'min_length' => 'Full name must be at least 2 characters long',
            'max_length' => 'Full name cannot exceed 255 characters'
        ],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public function findByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function findByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    public function getActiveUsers()
    {
        return $this->where('status', 'active')->findAll();
    }

    public function getAdmins()
    {
        return $this->where('is_admin', true)->findAll();
    }

    /**
     * Find or create user by Google ID
     */
    public function findOrCreateByGoogle($googleData)
    {
        $user = $this->where('google_id', $googleData['id'])->first();

        if (!$user) {
            // Check if email exists
            $user = $this->where('email', $googleData['email'])->first();
            if ($user) {
                // Update existing user with Google data and get fresh data
                $this->update($user['id'], [
                    'google_id' => $googleData['id'],
                    'login_type' => 'google'
                ]);
                // Get updated user data
                $user = $this->find($user['id']);
            } else {
                // Create new user
                $userId = $this->insert([
                    'email' => $googleData['email'],
                    'username' => $this->generateUsername($googleData['name']),
                    'full_name' => $googleData['name'],
                    'google_id' => $googleData['id'],
                    'password' => bin2hex(random_bytes(16)), // Random password
                    'status' => 'active',
                    'login_type' => 'google'
                ]);
                $user = $this->find($userId);
            }
        }

        return $user;
    }

    /**
     * Generate unique username from name
     */
    protected function generateUsername($name)
    {
        $baseUsername = strtolower(str_replace(' ', '', $name));
        $username = $baseUsername;
        $i = 1;

        while ($this->where('username', $username)->first()) {
            $username = $baseUsername . $i;
            $i++;
        }

        return $username;
    }

    /**
     * Update last login time
     */
    public function updateLastLogin($userId)
    {
        return $this->update($userId, ['last_login' => date('Y-m-d H:i:s')]);
    }

    // Get user's winnings
    public function getUserWinnings($userId)
    {
        $winnerModel = new \App\Models\WinnerModel();
        return $winnerModel->getUserWinnings($userId);
    }

    // Get user's total winnings amount
    public function getUserTotalWinnings($userId)
    {
        $winnerModel = new \App\Models\WinnerModel();
        return $winnerModel->getUserTotalWinnings($userId);
    }
}
