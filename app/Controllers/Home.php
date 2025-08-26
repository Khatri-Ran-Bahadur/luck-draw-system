<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CashDrawModel;
use App\Models\ProductDrawModel;
use App\Models\WinnerModel;

class Home extends BaseController
{
    protected $userModel;
    protected $cashDrawModel;
    protected $productDrawModel;
    protected $winnerModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->cashDrawModel = new CashDrawModel();
        $this->productDrawModel = new ProductDrawModel();
        $this->winnerModel = new WinnerModel();
    }

    public function index()
    {
        // Get active product draws
        $productDrawModel = new \App\Models\ProductDrawModel();
        $productDraws = $productDrawModel->where('status', 'active')
            ->where('draw_date >', date('Y-m-d H:i:s'))
            ->orderBy('draw_date', 'ASC')
            ->limit(6)
            ->findAll();

        // Get active cash draws
        $cashDrawModel = new \App\Models\CashDrawModel();
        $cashDraws = $cashDrawModel->where('status', 'active')
            ->orderBy('created_at', 'DESC')
            ->limit(6)
            ->findAll();

        // Check user entries if logged in
        $userProductEntries = [];
        $userCashEntries = [];

        if (session()->get('user_id')) {
            $userId = session()->get('user_id');
            $entryModel = new \App\Models\EntryModel();

            // Check which product draws the user has entered
            foreach ($productDraws as $draw) {
                $userEntry = $entryModel->checkUserProductDrawEntry($userId, $draw['id']);
                $userProductEntries[$draw['id']] = $userEntry;
            }

            // Check which cash draws the user has entered
            foreach ($cashDraws as $draw) {
                $userEntry = $entryModel->checkUserCashDrawEntry($userId, $draw['id']);
                $userCashEntries[$draw['id']] = $userEntry;
            }
        }

        $data = [
            'productDraws' => $productDraws,
            'cashDraws' => $cashDraws,
            'userProductEntries' => $userProductEntries,
            'userCashEntries' => $userCashEntries,
            'totalUsers' => $this->userModel->where('is_admin', false)->countAllResults(),
            'totalDraws' => count($this->cashDrawModel->getActiveDraws()) + count($this->productDrawModel->getActiveDraws()),
            'totalWinners' => $this->winnerModel->where('claim_approved', true)->countAllResults(),
            'currentDraw' => $this->cashDrawModel->getActiveDraws()[0] ?? null,
            'cash_draws' => $this->cashDrawModel->getActiveDraws(),
            'product_draws' => $this->productDrawModel->getActiveDraws(),
            'upcoming_cash_draws' => $this->cashDrawModel->getUpcomingDraws(),
            'upcoming_product_draws' => $this->productDrawModel->getUpcomingDraws()
        ];
        return view('home/index', $data);
    }

    public function dashboard()
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        $userId = session()->get('user_id');

        // Get user's wallet balance
        $walletModel = new \App\Models\WalletModel();
        $walletBalance = $walletModel->getBalance($userId);

        // Auto-update expired draws
        $this->cashDrawModel->updateExpiredDraws();
        $this->productDrawModel->updateExpiredDraws();

        // Get user's active draws
        $cashDraws = $this->cashDrawModel->getActiveDraws();
        $productDraws = $this->productDrawModel->getActiveDraws();

        // Get user's winnings
        $winnings = $this->winnerModel->getUserWinnings($userId);

        // Get user's recent wallet transactions
        $recentTransactions = [];
        if ($walletBalance) {
            $walletTransactionModel = new \App\Models\WalletTransactionModel();
            $recentTransactions = $walletTransactionModel->getUserTransactions($userId, 10);
        }

        // Get user's entries for active draws
        $entryModel = new \App\Models\EntryModel();
        $userEntries = [];

        // Check cash draw entries
        foreach ($cashDraws as $draw) {
            $entry = $entryModel->where('cash_draw_id', $draw['id'])
                ->where('user_id', $userId)
                ->where('payment_status', 'completed')
                ->first();
            if ($entry) {
                $userEntries[] = $entry;
            }
        }

        // Check product draw entries
        foreach ($productDraws as $draw) {
            $entry = $entryModel->where('product_draw_id', $draw['id'])
                ->where('user_id', $userId)
                ->where('payment_status', 'completed')
                ->first();
            if ($entry) {
                $userEntries[] = $entry;
            }
        }

        $data = [
            'walletBalance' => $walletBalance,
            'cashDraws' => $cashDraws,
            'productDraws' => $productDraws,
            'winnings' => $winnings,
            'userEntries' => $userEntries,
            'userWinnings' => $winnings, // Alias for compatibility
            'recent_transactions' => $recentTransactions
        ];

        return view('home/dashboard', $data);
    }

    /**
     * User profile page
     */
    public function profile()
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        // Ensure user has a referral code
        if (empty($user['referral_code'])) {
            $referralCode = $this->userModel->generateReferralCode();
            $this->userModel->update($userId, ['referral_code' => $referralCode]);
            $user['referral_code'] = $referralCode;
        }

        // Ensure user has a wallet ID
        if (empty($user['wallet_id'])) {
            $walletId = $this->userModel->ensureWalletId($userId);
            $user['wallet_id'] = $walletId;
        }

        // Get user's wallet information
        $walletModel = new \App\Models\WalletModel();
        $wallet = $walletModel->getUserWallet($userId);

        // Get referral statistics
        $referralStats = [];
        if (!empty($user['referral_code'])) {
            try {
                $referralModel = new \App\Models\ReferralModel();
                $referralStats = $referralModel->getUserReferralStats($userId);
            } catch (\Exception $e) {
                // If referral model doesn't exist, use default stats
                $referralStats = [
                    'total_referrals' => 0,
                    'active_referrals' => 0,
                    'total_bonus_earned' => $user['referral_bonus_earned'] ?? 0
                ];
            }
        }

        $data = [
            'user' => $user,
            'wallet' => $wallet,
            'referralStats' => $referralStats
        ];

        return view('home/profile', $data);
    }

    /**
     * Update user profile
     */
    public function updateProfile()
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        $fullName = $this->request->getPost('full_name');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');

        // Validate email uniqueness
        $existingUser = $this->userModel->where('email', $email)->where('id !=', $userId)->first();
        if ($existingUser) {
            return redirect()->back()->with('error', 'Email address is already in use by another user');
        }

        // Handle profile image upload
        $profileImage = $this->request->getFile('profile_image');
        $updateData = [
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone
        ];

        if ($profileImage && $profileImage->isValid() && !$profileImage->hasMoved()) {
            $uploadPath = 'uploads/profiles/';

            // Create directory if it doesn't exist
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $newName = 'profile_' . $userId . '_' . time() . '.' . $profileImage->getExtension();
            $profileImage->move($uploadPath, $newName);
            $updateData['profile_image'] = $uploadPath . $newName;

            // Delete old profile image if exists
            if ($user['profile_image'] && file_exists($user['profile_image'])) {
                unlink($user['profile_image']);
            }
        }

        if ($this->userModel->update($userId, $updateData)) {
            return redirect()->back()->with('success', 'Profile updated successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to update profile');
        }
    }

    /**
     * Update user wallet information (for special users)
     */
    public function updateWallet()
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        // Check if user is a special user
        if (!$user['is_special_user']) {
            return redirect()->back()->with('error', 'Only special users can update wallet information');
        }

        $walletName = $this->request->getPost('wallet_name');
        $walletNumber = $this->request->getPost('wallet_number');
        $walletType = $this->request->getPost('wallet_type');
        $bankName = $this->request->getPost('bank_name');

        // Validate required fields
        if (empty($walletName) || empty($walletNumber) || empty($walletType)) {
            return redirect()->back()->with('error', 'Wallet name, number, and type are required');
        }

        // Update user's wallet information
        $updateData = [
            'wallet_name' => $walletName,
            'wallet_number' => $walletNumber,
            'wallet_type' => $walletType,
            'wallet_active' => true
        ];

        // Add bank name if wallet type is bank
        if ($walletType === 'bank' && !empty($bankName)) {
            $updateData['bank_name'] = $bankName;
        }

        if ($this->userModel->update($userId, $updateData)) {
            // Also update the wallet table if it exists
            $walletModel = new \App\Models\WalletModel();
            $existingWallet = $walletModel->getUserWallet($userId);

            if ($existingWallet) {
                $walletModel->update($existingWallet['id'], [
                    'wallet_name' => $walletName,
                    'wallet_number' => $walletNumber,
                    'wallet_type' => $walletType,
                    'bank_name' => $bankName,
                    'wallet_active' => true
                ]);
            }

            return redirect()->back()->with('success', 'Wallet information updated successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to update wallet information');
        }
    }

    public function cashDraws()
    {
        // Auto-update expired draws
        $this->cashDrawModel->updateExpiredDraws();

        $data = [
            'cash_draws' => $this->cashDrawModel->getActiveDrawsWithCounts(),
            'recent_winners' => $this->cashDrawModel->getRecentWinners(5),
            'completed_draws' => $this->cashDrawModel->getCompletedDrawsWithWinners(),
            'upcoming_draws' => $this->cashDrawModel->getUpcomingDraws()
        ];
        return view('home/cash_draws', $data);
    }

    public function productDraws()
    {
        // Auto-update expired draws
        $this->productDrawModel->updateExpiredDraws();

        // Get product draws
        $productDraws = $this->productDrawModel->getActiveDrawsWithCounts();

        // Check user entries if logged in
        $userProductEntries = [];

        if (session()->get('user_id')) {
            $userId = session()->get('user_id');
            $entryModel = new \App\Models\EntryModel();

            // Check which product draws the user has entered
            foreach ($productDraws as $draw) {
                $userEntry = $entryModel->checkUserProductDrawEntry($userId, $draw['id']);
                $userProductEntries[$draw['id']] = $userEntry;
            }
        }

        $data = [
            'product_draws' => $productDraws,
            'user_product_entries' => $userProductEntries,
            'recent_winners' => $this->productDrawModel->getRecentWinners(5),
            'completed_draws' => $this->productDrawModel->getCompletedDrawsWithWinners(),
            'upcoming_draws' => $this->productDrawModel->getUpcomingDraws()
        ];
        return view('home/product_draws', $data);
    }

    public function winners()
    {
        // Get all winners using the new method
        $allWinners = $this->winnerModel->getAllWinners(20);

        // Separate cash and product winners for display
        $cashWinners = array_filter($allWinners, function ($winner) {
            return $winner['draw_type'] === 'cash';
        });

        $productWinners = array_filter($allWinners, function ($winner) {
            return $winner['draw_type'] === 'product';
        });

        $data = [
            'winners' => $allWinners,
            'cash_winners' => array_values($cashWinners),
            'product_winners' => array_values($productWinners)
        ];

        return view('home/winners', $data);
    }

    public function about()
    {
        return view('home/about');
    }

    public function contact()
    {
        return view('home/contact');
    }

    public function terms()
    {
        return view('home/terms');
    }

    public function privacy()
    {
        return view('home/privacy');
    }

    public function faq()
    {
        return view('home/faq');
    }

    public function search()
    {
        return view('home/search');
    }

    public function maintenance()
    {
        return view('home/maintenance');
    }
}
