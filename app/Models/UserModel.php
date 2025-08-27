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
        'referral_code',
        'referred_by',
        'referral_bonus_earned',
        'wallet_name',
        'wallet_number',
        'wallet_type',
        'is_special_user',
        'wallet_active',
        'bank_name',
        'last_login',
        'wallet_id',
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
     * Generate a unique referral code for a user
     */
    public function generateReferralCode($length = 8)
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length));
        } while ($this->where('referral_code', $code)->first());

        return $code;
    }

    /**
     * Generate a unique wallet ID for a user
     */
    public function generateWalletId($length = 12)
    {
        do {
            $id = 'W' . strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length - 1));
        } while ($this->where('wallet_id', $id)->first());

        return $id;
    }

    /**
     * Get user by referral code
     */
    public function findByReferralCode($referralCode)
    {
        return $this->where('referral_code', $referralCode)
            ->where('status', 'active')
            ->first();
    }

    /**
     * Get user's referral statistics
     */
    public function getReferralStats($userId)
    {
        $referralModel = new ReferralModel();
        return $referralModel->getUserReferralStats($userId);
    }

    /**
     * Get users referred by a specific user
     */
    public function getReferredUsers($userId)
    {
        $referralModel = new ReferralModel();
        return $referralModel->getReferredUsers($userId);
    }

    /**
     * Check if user can refer more people
     */
    public function canReferMore($userId)
    {
        $settingModel = new SettingModel();
        $maxReferrals = $settingModel->getMaxReferralsPerUser();

        if ($maxReferrals == 0) {
            return true; // Unlimited referrals
        }

        $referralCount = $this->where('referred_by', $userId)->countAllResults();
        return $referralCount < $maxReferrals;
    }

    /**
     * Update user's referral bonus earned
     */
    public function addReferralBonus($userId, $amount)
    {
        $user = $this->find($userId);
        if (!$user) {
            return false;
        }

        $currentBonus = (float) ($user['referral_bonus_earned'] ?? 0);
        $newBonus = $currentBonus + $amount;

        return $this->update($userId, ['referral_bonus_earned' => $newBonus]);
    }

    // Wallet Management Methods
    public function updateWalletDetails($userId, $walletName, $walletNumber, $walletType)
    {
        return $this->update($userId, [
            'wallet_name' => $walletName,
            'wallet_number' => $walletNumber,
            'wallet_type' => $walletType
        ]);
    }

    public function getWalletDetails($userId)
    {
        $user = $this->find($userId);
        if (!$user) {
            return null;
        }

        return [
            'wallet_name' => $user['wallet_name'] ?? '',
            'wallet_number' => $user['wallet_number'] ?? '',
            'wallet_type' => $user['wallet_type'] ?? 'easypaisa'
        ];
    }

    // Get random wallets for top-up display
    public function getRandomWalletsForTopup($count = 3, $excludeUserId = null)
    {
        $query = $this->select('id, username, full_name, wallet_name, wallet_number, wallet_type')
            ->where('wallet_name IS NOT NULL')
            ->where('wallet_number IS NOT NULL')
            ->where('wallet_name !=', '')
            ->where('wallet_number !=', '')
            ->where('status', 'active');

        if ($excludeUserId) {
            $query->where('id !=', $excludeUserId);
        }

        $wallets = $query->findAll();

        if (count($wallets) <= $count) {
            return $wallets;
        }

        // Shuffle and return random wallets
        shuffle($wallets);
        return array_slice($wallets, 0, $count);
    }

    // Get users with wallet details for admin
    public function getUsersWithWalletDetailsForAdmin($limit = null)
    {
        $query = $this->select('users.*, wallets.balance, wallets.currency')
            ->join('wallets', 'wallets.user_id = users.id', 'left')
            ->where('users.is_admin', false)
            ->orderBy('users.created_at', 'DESC');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->findAll();
    }

    /**
     * Get users with wallet details for special users display
     */
    public function getUsersWithWalletDetails($limit = 10, $offset = 0)
    {
        return $this->select('users.*, COALESCE(wallets.balance, 0) as balance')
            ->join('wallets', 'wallets.user_id = users.id', 'left')
            ->where('users.is_special_user', true)
            ->orderBy('RAND()')
            ->limit($limit, $offset)
            ->findAll();
    }

    /**
     * Count users with wallet details
     */
    public function countUsersWithWalletDetails()
    {
        return $this->where('is_special_user', true)->countAllResults();
    }

    /**
     * Count active wallet users
     */
    public function countActiveWalletUsers()
    {
        return $this->where('wallet_active', true)
            ->where('is_special_user', true)
            ->countAllResults();
    }

    /**
     * Update special user wallet information
     */
    public function updateSpecialUserWallet($userId, $walletName, $walletNumber, $walletType, $bankName = null, $walletActive = true, $userStatus = 'active')
    {
        $data = [
            'wallet_name' => $walletName,
            'wallet_number' => $walletNumber,
            'wallet_type' => $walletType,
            'wallet_active' => $walletActive,
            'status' => $userStatus,
            'is_special_user' => true
        ];

        if ($walletType === 'bank' && $bankName) {
            $data['bank_name'] = $bankName;
        }

        return $this->update($userId, $data);
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

    /**
     * Get users with complete wallet details for topup display
     */
    public function getUsersWithCompleteWalletDetails($limit = 10, $offset = 0)
    {
        // Use Query Builder directly for better control and debugging
        $builder = $this->db->table('users')
            ->select('users.*, COALESCE(wallets.balance, 0) as balance')
            ->join('wallets', 'wallets.user_id = users.id', 'left')
            ->where('users.wallet_name IS NOT NULL')
            ->where('users.wallet_name !=', '')
            ->where('users.wallet_name !=', 'N/A')
            ->where('users.wallet_name !=', 'Pending')
            ->where('users.wallet_name !=', 'pending')
            ->where('LENGTH(TRIM(users.wallet_name)) >', 2)
            ->where('users.wallet_number IS NOT NULL')
            ->where('users.wallet_number !=', '')
            ->where('users.wallet_number !=', 'N/A')
            ->where('users.wallet_number !=', 'Pending')
            ->where('users.wallet_number !=', 'pending')
            ->where('LENGTH(TRIM(users.wallet_number)) >', 2)
            ->where('users.wallet_type IS NOT NULL')
            ->where('users.wallet_type !=', '')
            ->where('users.wallet_type !=', 'N/A')
            ->where('users.wallet_type !=', 'Pending')
            ->where('users.wallet_type !=', 'pending')
            ->whereIn('users.wallet_type', ['easypaisa', 'jazz_cash', 'bank', 'hbl', 'ubank', 'abank', 'nbp', 'sbank', 'citi', 'hsbc', 'payoneer', 'skrill', 'neteller', 'other'])
            ->where('users.is_special_user', true)
            ->where('users.wallet_active', true)
            ->where('users.status', 'active')
            ->orderBy('RAND()')
            ->limit($limit, $offset);

        // Debug: Log the SQL query
        log_message('info', 'Special users query: ' . $builder->getCompiledSelect());

        $result = $builder->get()->getResultArray();

        // Debug: Log the result
        log_message('info', 'Special users result count: ' . count($result));
        log_message('info', 'Special users result: ' . json_encode($result));

        return $result;
    }

    /**
     * Count users with complete wallet details
     */
    public function countUsersWithCompleteWalletDetails()
    {
        return $this->where('wallet_name IS NOT NULL')
            ->where('wallet_name !=', '')
            ->where('wallet_name !=', 'N/A')
            ->where('wallet_name !=', 'Pending')
            ->where('wallet_name !=', 'pending')
            ->where('LENGTH(TRIM(wallet_name)) >', 2)
            ->where('users.wallet_number IS NOT NULL')
            ->where('users.wallet_number !=', '')
            ->where('users.wallet_number !=', 'N/A')
            ->where('users.wallet_number !=', 'Pending')
            ->where('users.wallet_number !=', 'pending')
            ->where('LENGTH(TRIM(users.wallet_number)) >', 2)
            ->where('users.wallet_type IS NOT NULL')
            ->where('users.wallet_type !=', '')
            ->where('users.wallet_type !=', 'N/A')
            ->where('users.wallet_type !=', 'Pending')
            ->where('users.wallet_type !=', 'pending')
            ->whereIn('users.wallet_type', ['easypaisa', 'jazz_cash', 'bank', 'hbl', 'ubank', 'abank', 'nbp', 'sbank', 'citi', 'hsbc', 'payoneer', 'skrill', 'neteller', 'other'])
            ->where('is_special_user', true)
            ->where('wallet_active', true)
            ->where('status', 'active')
            ->countAllResults();
    }

    /**
     * Ensure user has a wallet ID, generate if missing
     */
    public function ensureWalletId($userId)
    {
        $user = $this->find($userId);
        if (!$user) {
            return false;
        }

        if (empty($user['wallet_id'])) {
            $walletId = $this->generateWalletId();
            $this->update($userId, ['wallet_id' => $walletId]);
            return $walletId;
        }

        return $user['wallet_id'];
    }

    /**
     * Get user by wallet ID
     */
    public function findByWalletId($walletId)
    {
        return $this->where('wallet_id', $walletId)->first();
    }
}
