<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CashDrawModel;
use App\Models\ProductDrawModel;
use App\Models\WinnerModel;
use App\Models\WalletModel;
use App\Models\WalletTransactionModel;
use App\Models\EntryModel;
use App\Models\ReferralModel;
use App\Models\WalletTopupRequestModel;
use App\Models\UserTransferModel;
use App\Models\SettingModel;
use App\Models\ContactModel;
use App\Libraries\NotificationService;
use App\Libraries\CurrencyService;

class Admin extends BaseController
{
    protected $userModel;
    protected $cashDrawModel;
    protected $productDrawModel;
    protected $winnerModel;
    protected $walletModel;
    protected $walletTransactionModel;
    protected $entryModel;
    protected $referralModel;
    protected $walletTopupRequestModel;
    protected $userTransferModel;
    protected $settingModel;
    protected $contactModel;
    protected $notificationModel;
    protected $notificationService;
    protected $currencyService;
    protected $db;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->cashDrawModel = new CashDrawModel();
        $this->productDrawModel = new ProductDrawModel();
        $this->winnerModel = new WinnerModel();
        $this->walletModel = new WalletModel();
        $this->walletTransactionModel = new WalletTransactionModel();
        $this->entryModel = new EntryModel();
        $this->referralModel = new ReferralModel();
        $this->walletTopupRequestModel = new WalletTopupRequestModel();
        $this->userTransferModel = new UserTransferModel();
        $this->settingModel = new SettingModel();
        $this->contactModel = new ContactModel();
        $this->notificationModel = new \App\Models\NotificationModel();
        $this->notificationService = new NotificationService();
        $this->currencyService = new CurrencyService();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Get comprehensive dashboard statistics
        $data = $this->getDashboardData();

        // Add referral statistics to dashboard
        $data['referral_stats'] = $this->referralModel->getAdminReferralStats();

        return view('admin/dashboard', $data);
    }

    private function getDashboardData()
    {
        // Basic counts
        $totalUsers = $this->userModel->where('is_admin', false)->countAllResults();
        $totalAdmins = $this->userModel->where('is_admin', true)->countAllResults();
        $totalCashDraws = $this->cashDrawModel->countAllResults();
        $totalProductDraws = $this->productDrawModel->countAllResults();
        $activeCashDraws = $this->cashDrawModel->where('status', 'active')->countAllResults();
        $activeProductDraws = $this->productDrawModel->where('status', 'active')->countAllResults();
        $totalEntries = $this->entryModel->where('payment_status', 'completed')->countAllResults();
        $pendingClaims = $this->winnerModel->where('is_claimed', true)->where('claim_approved', false)->countAllResults();
        $totalTransactions = $this->walletTransactionModel->countAllResults();

        // Revenue calculations
        $totalRevenue = $this->calculateTotalRevenue();
        $monthlyRevenue = $this->calculateMonthlyRevenue();
        $revenueGrowth = $this->calculateRevenueGrowth();

        // Transaction statistics
        $transactionStats = $this->getTransactionStatistics();

        // Get recent data
        $recentTransactions = $this->getRecentTransactions(5);
        $recentUsers = $this->userModel->where('is_admin', false)->orderBy('created_at', 'DESC')->limit(5)->findAll();
        $lowStockProducts = $this->getLowStockProducts();

        // System health metrics
        $systemHealth = $this->getSystemHealthMetrics();

        // Growth percentages (comparing to last month)
        $userGrowth = $this->calculateUserGrowth();
        $drawGrowth = $this->calculateDrawGrowth();
        $transactionGrowth = $this->calculateTransactionGrowth();

        // Wallet and top-up statistics (only special user requests for admin dashboard)
        $topupStats = $this->walletTopupRequestModel->getSpecialUserTopupStats();
        $transferStats = $this->userTransferModel->getTransferStats();

        return [
            // Main statistics
            'total_users' => $totalUsers,
            'total_admins' => $totalAdmins,
            'total_cash_draws' => $totalCashDraws,
            'total_product_draws' => $totalProductDraws,
            'active_cash_draws' => $activeCashDraws,
            'active_product_draws' => $activeProductDraws,
            'total_entries' => $totalEntries,
            'pending_claims' => $pendingClaims,
            'total_transactions' => $totalTransactions,

            // Financial data
            'total_revenue' => $totalRevenue,
            'monthly_revenue' => $monthlyRevenue,
            'revenue_growth' => $revenueGrowth,

            // Transaction statistics
            'transaction_stats' => $transactionStats,

            // Recent data
            'recent_transactions' => $recentTransactions,
            'recent_users' => $recentUsers,
            'low_stock_products' => $lowStockProducts,

            // System health
            'system_health' => $systemHealth,

            // Growth metrics
            'user_growth' => $userGrowth,
            'draw_growth' => $drawGrowth,
            'transaction_growth' => $transactionGrowth,

            // Wallet statistics
            'topup_stats' => $topupStats,
            'transfer_stats' => $transferStats
        ];
    }

    private function calculateTotalRevenue()
    {
        // Calculate total revenue from completed transactions
        $result = $this->walletTransactionModel
            ->select('SUM(amount) as total')
            ->where('status', 'completed')
            ->whereIn('type', ['topup', 'draw_entry'])
            ->first();

        return $result['total'] ?? 0;
    }

    private function calculateMonthlyRevenue()
    {
        // Calculate current month revenue
        $result = $this->walletTransactionModel
            ->select('SUM(amount) as total')
            ->where('status', 'completed')
            ->whereIn('type', ['topup', 'draw_entry'])
            ->where('created_at >=', date('Y-m-01 00:00:00'))
            ->where('created_at <=', date('Y-m-t 23:59:59'))
            ->first();

        return $result['total'] ?? 0;
    }

    private function calculateRevenueGrowth()
    {
        // Calculate revenue growth compared to last month
        $currentMonth = $this->calculateMonthlyRevenue();

        $lastMonth = $this->walletTransactionModel
            ->select('SUM(amount) as total')
            ->where('status', 'completed')
            ->whereIn('type', ['topup', 'draw_entry'])
            ->where('created_at >=', date('Y-m-01 00:00:00', strtotime('-1 month')))
            ->where('created_at <=', date('Y-m-t 23:59:59', strtotime('-1 month')))
            ->first()['total'] ?? 0;

        if ($lastMonth > 0) {
            return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
        }

        return $currentMonth > 0 ? 100 : 0;
    }

    private function getTransactionStatistics()
    {
        $completed = $this->walletTransactionModel->where('status', 'completed')->countAllResults();
        $pending = $this->walletTransactionModel->where('status', 'pending')->countAllResults();
        $failed = $this->walletTransactionModel->where('status', 'failed')->countAllResults();
        $total = $completed + $pending + $failed;

        return [
            'completed' => $completed,
            'pending' => $pending,
            'failed' => $failed,
            'total' => $total,
            'completed_percentage' => $total > 0 ? round(($completed / $total) * 100, 1) : 0,
            'pending_percentage' => $total > 0 ? round(($pending / $total) * 100, 1) : 0,
            'failed_percentage' => $total > 0 ? round(($failed / $total) * 100, 1) : 0
        ];
    }

    private function getRecentTransactions($limit = 5)
    {
        return $this->walletTransactionModel
            ->select('wallet_transactions.*, users.username, users.email, users.full_name')
            ->join('wallets', 'wallets.id = wallet_transactions.wallet_id')
            ->join('users', 'users.id = wallets.user_id')
            ->orderBy('wallet_transactions.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    private function getLowStockProducts()
    {
        // Get product draws with low entry counts (less than 5 entries)
        return $this->productDrawModel
            ->select('product_draws.*, COUNT(entries.id) as entry_count')
            ->join('entries', 'entries.lucky_draw_id = product_draws.id', 'left')
            ->where('product_draws.status', 'active')
            ->groupBy('product_draws.id')
            ->having('entry_count < 5')
            ->orderBy('entry_count', 'ASC')
            ->limit(5)
            ->findAll();
    }

    private function getSystemHealthMetrics()
    {
        // Calculate system health based on various metrics
        $totalUsers = $this->userModel->countAllResults();
        $activeUsers = $this->userModel->where('status', 'active')->countAllResults();
        $failedTransactions = $this->walletTransactionModel->where('status', 'failed')->countAllResults();
        $totalTransactions = $this->walletTransactionModel->countAllResults();

        $userHealthScore = $totalUsers > 0 ? ($activeUsers / $totalUsers) * 100 : 100;
        $transactionHealthScore = $totalTransactions > 0 ? (($totalTransactions - $failedTransactions) / $totalTransactions) * 100 : 100;

        $overallHealth = ($userHealthScore + $transactionHealthScore) / 2;

        return [
            'overall_score' => round($overallHealth, 1),
            'user_health' => round($userHealthScore, 1),
            'transaction_health' => round($transactionHealthScore, 1),
            'uptime' => 99.9, // This would come from server monitoring
            'response_time' => rand(80, 150) // This would come from performance monitoring
        ];
    }

    private function calculateUserGrowth()
    {
        $currentMonth = $this->userModel
            ->where('is_admin', false)
            ->where('created_at >=', date('Y-m-01 00:00:00'))
            ->countAllResults();

        $lastMonth = $this->userModel
            ->where('is_admin', false)
            ->where('created_at >=', date('Y-m-01 00:00:00', strtotime('-1 month')))
            ->where('created_at <', date('Y-m-01 00:00:00'))
            ->countAllResults();

        if ($lastMonth > 0) {
            return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
        }

        return $currentMonth > 0 ? 100 : 0;
    }

    private function calculateDrawGrowth()
    {
        $currentMonthCash = $this->cashDrawModel
            ->where('created_at >=', date('Y-m-01 00:00:00'))
            ->countAllResults();

        $currentMonthProduct = $this->productDrawModel
            ->where('created_at >=', date('Y-m-01 00:00:00'))
            ->countAllResults();

        $currentMonth = $currentMonthCash + $currentMonthProduct;

        $lastMonthCash = $this->cashDrawModel
            ->where('created_at >=', date('Y-m-01 00:00:00', strtotime('-1 month')))
            ->where('created_at <', date('Y-m-01 00:00:00'))
            ->countAllResults();

        $lastMonthProduct = $this->productDrawModel
            ->where('created_at >=', date('Y-m-01 00:00:00', strtotime('-1 month')))
            ->where('created_at <', date('Y-m-01 00:00:00'))
            ->countAllResults();

        $lastMonth = $lastMonthCash + $lastMonthProduct;

        if ($lastMonth > 0) {
            return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
        }

        return $currentMonth > 0 ? 100 : 0;
    }

    private function calculateTransactionGrowth()
    {
        $currentMonth = $this->walletTransactionModel
            ->where('created_at >=', date('Y-m-01 00:00:00'))
            ->countAllResults();

        $lastMonth = $this->walletTransactionModel
            ->where('created_at >=', date('Y-m-01 00:00:00', strtotime('-1 month')))
            ->where('created_at <', date('Y-m-01 00:00:00'))
            ->countAllResults();

        if ($lastMonth > 0) {
            return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
        }

        return $currentMonth > 0 ? 100 : 0;
    }

    // Users Management
    public function users()
    {
        $data['users'] = $this->userModel->where('is_admin', false)->findAll();
        return view('admin/users', $data);
    }

    // Admin Management
    public function admins()
    {
        $data['admins'] = $this->userModel->where('is_admin', true)->findAll();
        return view('admin/admins', $data);
    }

    public function createAdmin()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = [
                'username' => $this->request->getPost('username'),
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'full_name' => $this->request->getPost('full_name'),
                'phone' => $this->request->getPost('phone'),
                'is_admin' => true,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Handle image upload
            $image = $this->request->getFile('profile_image');
            if ($image && $image->isValid() && !$image->hasMoved()) {
                $newName = $image->getRandomName();
                $image->move(ROOTPATH . 'public/uploads/profiles', $newName);
                $data['profile_image'] = 'uploads/profiles/' . $newName;
            }

            // Hash password
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            if ($this->userModel->insert($data)) {
                return redirect()->to(base_url('admin/admins'))->with('success', 'Admin created successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to create admin');
            }
        }

        return view('admin/create_admin');
    }

    public function editAdmin($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = [
                'username' => $this->request->getPost('username'),
                'email' => $this->request->getPost('email'),
                'full_name' => $this->request->getPost('full_name'),
                'phone' => $this->request->getPost('phone'),
                'status' => $this->request->getPost('status')
            ];

            // Handle password update if provided
            $newPassword = $this->request->getPost('new_password');
            if (!empty($newPassword)) {
                $data['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
            }

            // Handle image upload with better error handling
            $image = $this->request->getFile('profile_image');

            // Debug: Log file upload information
            log_message('info', 'File upload attempt - File object: ' . ($image ? 'exists' : 'null'));
            if ($image) {
                log_message('info', 'File details - Name: ' . $image->getName() . ', Size: ' . $image->getSize() . ', Valid: ' . ($image->isValid() ? 'yes' : 'no'));
            }

            if ($image && $image->isValid()) {
                // Check file size (5MB limit)
                if ($image->getSize() > 5 * 1024 * 1024) {
                    return redirect()->back()->with('error', 'Image file size must be less than 5MB');
                }

                // Check file type
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!in_array($image->getMimeType(), $allowedTypes)) {
                    return redirect()->back()->with('error', 'Only JPG, PNG, and GIF images are allowed');
                }

                try {
                    // Generate unique filename
                    $newName = $image->getRandomName();
                    $uploadPath = ROOTPATH . 'public/uploads/profiles/';

                    // Ensure upload directory exists
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }

                    // Move uploaded file
                    if ($image->move($uploadPath, $newName)) {
                        $data['profile_image'] = 'uploads/profiles/' . $newName;

                        // Delete old image if exists
                        $oldAdmin = $this->userModel->find($id);
                        if ($oldAdmin && $oldAdmin['profile_image'] && file_exists(ROOTPATH . 'public/' . $oldAdmin['profile_image'])) {
                            unlink(ROOTPATH . 'public/' . $oldAdmin['profile_image']);
                        }
                    } else {
                        return redirect()->back()->with('error', 'Failed to upload image. Please try again.');
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Image upload failed: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Image upload failed. Please try again.');
                }
            }

            // Skip validation to avoid password requirement issues
            $this->userModel->skipValidation(true);

            if ($this->userModel->update($id, $data)) {
                log_message('info', 'Admin update successful - Data: ' . json_encode($data));
                return redirect()->to(base_url('admin/admins'))->with('success', 'Admin updated successfully');
            } else {
                log_message('error', 'Admin update failed - Data: ' . json_encode($data));
                return redirect()->back()->with('error', 'Failed to update admin');
            }
        }

        $data['admin'] = $this->userModel->find($id);
        return view('admin/edit_admin', $data);
    }

    public function deleteAdmin($id)
    {
        // Prevent deleting own account
        if ($id == session()->get('user_id')) {
            return redirect()->back()->with('error', 'You cannot delete your own account');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to(base_url('admin/admins'))->with('success', 'Admin deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete admin');
        }
    }

    public function viewUser($id)
    {
        // Get user details
        $data['user'] = $this->userModel->find($id);
        if (!$data['user']) {
            return redirect()->to(base_url('admin/users'))->with('error', 'User not found');
        }

        // Get wallet information
        $data['wallet'] = $this->walletModel->getUserWallet($id);
        $walletBalance = $data['wallet'] ? $data['wallet']['balance'] : 0;

        // Get wallet transactions (paginated)
        $perPage = 10;
        $page = $this->request->getGet('transactions_page') ?? 1;
        $offset = ($page - 1) * $perPage;

        $totalTransactions = $this->walletTransactionModel
            ->where('wallet_id', $data['wallet']['id'] ?? 0)
            ->countAllResults();

        $data['transactions'] = $this->walletTransactionModel
            ->where('wallet_id', $data['wallet']['id'] ?? 0)
            ->orderBy('created_at', 'DESC')
            ->limit($perPage, $offset)
            ->findAll();

        // Transaction pagination
        $data['transaction_pagination'] = [
            'current_page' => (int)$page,
            'per_page' => $perPage,
            'total_transactions' => $totalTransactions,
            'total_pages' => ceil($totalTransactions / $perPage),
            'has_previous' => $page > 1,
            'has_next' => $page < ceil($totalTransactions / $perPage),
            'previous_page' => $page - 1,
            'next_page' => $page + 1,
            'start_transaction' => $offset + 1,
            'end_transaction' => min($offset + $perPage, $totalTransactions)
        ];

        // Get cash draw entries
        $data['cash_entries'] = $this->entryModel
            ->select('entries.*, cash_draws.title, cash_draws.entry_fee, cash_draws.draw_date, cash_draws.status')
            ->join('cash_draws', 'cash_draws.id = entries.cash_draw_id')
            ->where('entries.user_id', $id)
            ->where('entries.cash_draw_id IS NOT NULL')
            ->orderBy('entries.created_at', 'DESC')
            ->findAll();

        // Get product draw entries
        $data['product_entries'] = $this->entryModel
            ->select('entries.*, product_draws.title, product_draws.entry_fee, product_draws.draw_date, product_draws.status, product_draws.product_name')
            ->join('product_draws', 'product_draws.id = entries.product_draw_id')
            ->where('entries.user_id', $id)
            ->where('entries.product_draw_id IS NOT NULL')
            ->orderBy('entries.created_at', 'DESC')
            ->findAll();

        // Get user's winnings
        $data['cash_winnings'] = $this->winnerModel
            ->select('winners.*, cash_draws.title, cash_draws.draw_date')
            ->join('cash_draws', 'cash_draws.id = winners.cash_draw_id')
            ->where('winners.user_id', $id)
            ->where('winners.cash_draw_id IS NOT NULL')
            ->orderBy('winners.created_at', 'DESC')
            ->findAll();

        $data['product_winnings'] = $this->winnerModel
            ->select('winners.*, product_draws.title, product_draws.draw_date, product_draws.product_name')
            ->join('product_draws', 'product_draws.id = winners.product_draw_id')
            ->where('winners.user_id', $id)
            ->where('winners.product_draw_id IS NOT NULL')
            ->orderBy('winners.created_at', 'DESC')
            ->findAll();

        // Calculate statistics
        $data['stats'] = [
            'wallet_balance' => $walletBalance,
            'total_spent' => array_sum(array_column($data['cash_entries'], 'amount_paid')) + array_sum(array_column($data['product_entries'], 'amount_paid')),
            'total_entries' => count($data['cash_entries']) + count($data['product_entries']),
            'total_winnings' => count($data['cash_winnings']) + count($data['product_winnings']),
            'total_prize_won' => array_sum(array_column($data['cash_winnings'], 'prize_amount')),
            'pending_claims' => count(array_filter(array_merge($data['cash_winnings'], $data['product_winnings']), function ($w) {
                return !$w['claim_approved'] && $w['is_claimed'];
            })),
            'approved_claims' => count(array_filter(array_merge($data['cash_winnings'], $data['product_winnings']), function ($w) {
                return $w['claim_approved'];
            }))
        ];

        return view('admin/view_user', $data);
    }

    public function editUser($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = [
                'full_name' => $this->request->getPost('full_name'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'status' => $this->request->getPost('status')
            ];

            // Handle profile image upload
            $image = $this->request->getFile('profile_image');
            if ($image && $image->isValid()) {
                // Check file size (5MB limit)
                if ($image->getSize() > 5 * 1024 * 1024) {
                    return redirect()->back()->with('error', 'Image file size must be less than 5MB');
                }

                // Check file type
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!in_array($image->getMimeType(), $allowedTypes)) {
                    return redirect()->back()->with('error', 'Only JPG, PNG, and GIF images are allowed');
                }

                try {
                    // Generate unique filename
                    $newName = $image->getRandomName();
                    $uploadPath = ROOTPATH . 'public/uploads/profiles/';

                    // Ensure upload directory exists
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }

                    // Move uploaded file
                    if ($image->move($uploadPath, $newName)) {
                        $data['profile_image'] = 'uploads/profiles/' . $newName;

                        // Delete old image if exists
                        $oldUser = $this->userModel->find($id);
                        if ($oldUser && $oldUser['profile_image'] && file_exists(ROOTPATH . 'public/' . $oldUser['profile_image'])) {
                            unlink(ROOTPATH . 'public/' . $oldUser['profile_image']);
                        }
                    } else {
                        return redirect()->back()->with('error', 'Failed to upload image. Please try again.');
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Image upload failed: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Image upload failed. Please try again.');
                }
            }

            // Skip validation to avoid password requirement issues
            $this->userModel->skipValidation(true);

            if ($this->userModel->update($id, $data)) {
                log_message('info', 'User update successful - Data: ' . json_encode($data));
                return redirect()->to(base_url('admin/users'))->with('success', 'User updated successfully');
            } else {
                log_message('error', 'User update failed - Data: ' . json_encode($data));
                return redirect()->back()->with('error', 'Failed to update user');
            }
        }

        $data['user'] = $this->userModel->find($id);
        return view('admin/edit_user', $data);
    }

    public function deleteUser($id)
    {
        if ($this->userModel->delete($id)) {
            return redirect()->to(base_url('admin/users'))->with('success', 'User deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete user');
        }
    }

    // Cash Draws Management
    public function cashDraws()
    {
        $data['draws'] = $this->cashDrawModel->getDrawsForAdmin();
        return view('admin/cash_draws', $data);
    }

    public function createCashDraw()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'entry_fee' => $this->request->getPost('entry_fee'),
                'draw_date' => $this->request->getPost('draw_date'),
                'total_winners' => $this->request->getPost('total_winners'),
                'prize_amount' => $this->request->getPost('prize_amount'),
                'is_manual_selection' => $this->request->getPost('is_manual_selection') == '1' ? 1 : 0,
                'status' => 'active'
            ];

            if ($this->cashDrawModel->insert($data)) {
                return redirect()->to(base_url('admin/cash-draws'))->with('success', 'Cash lucky draw created successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to create cash lucky draw');
            }
        }

        return view('admin/create_cash_draw');
    }

    // Product Draws Management
    public function productDraws()
    {
        $data['draws'] = $this->productDrawModel->getDrawsForAdmin();
        return view('admin/product_draws', $data);
    }

    public function createProductDraw()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'product_name' => $this->request->getPost('product_name'),
                'product_price' => $this->request->getPost('product_price'),
                'entry_fee' => $this->request->getPost('entry_fee'),
                'draw_date' => $this->request->getPost('draw_date'),
                'status' => 'active'
            ];

            // Handle product image upload
            $image = $this->request->getFile('product_image');
            if ($image && $image->isValid() && !$image->hasMoved()) {
                $newName = $image->getRandomName();
                if (!is_dir(ROOTPATH . 'public/uploads/products')) {
                    mkdir(ROOTPATH . 'public/uploads/products', 0755, true);
                }
                $image->move(ROOTPATH . 'public/uploads/products', $newName);
                $data['product_image'] = 'uploads/products/' . $newName;
            }

            if ($this->productDrawModel->insert($data)) {
                return redirect()->to(base_url('admin/product-draws'))->with('success', 'Product draw created successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to create product draw')->withInput();
            }
        }

        return view('admin/create_product_draw');
    }

    // Edit methods for both types of draws
    public function editCashDraw($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'entry_fee' => $this->request->getPost('entry_fee'),
                'draw_date' => $this->request->getPost('draw_date'),
                'total_winners' => $this->request->getPost('total_winners'),
                'prize_amount' => $this->request->getPost('prize_amount'),
                'is_manual_selection' => $this->request->getPost('is_manual_selection') ? true : false,
                'status' => $this->request->getPost('status')
            ];

            if ($this->cashDrawModel->update($id, $data)) {
                return redirect()->to(base_url('admin/cash-draws'))->with('success', 'Cash lucky draw updated successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to update cash lucky draw');
            }
        }

        $data['draw'] = $this->cashDrawModel->find($id);
        if (!$data['draw']) {
            return redirect()->to(base_url('admin/cash-draws'))->with('error', 'Cash draw not found');
        }

        return view('admin/edit_cash_draw', $data);
    }

    public function editProductDraw($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'product_name' => $this->request->getPost('product_name'),
                'product_price' => $this->request->getPost('product_price'),
                'entry_fee' => $this->request->getPost('entry_fee'),
                'draw_date' => $this->request->getPost('draw_date'),
                'status' => $this->request->getPost('status')
            ];

            // Handle product image upload
            $image = $this->request->getFile('product_image');
            if ($image && $image->isValid() && !$image->hasMoved()) {
                $newName = $image->getRandomName();
                if (!is_dir(ROOTPATH . 'public/uploads/products')) {
                    mkdir(ROOTPATH . 'public/uploads/products', 0755, true);
                }
                $image->move(ROOTPATH . 'public/uploads/products', $newName);
                $data['product_image'] = 'uploads/products/' . $newName;
            }

            if ($this->productDrawModel->update($id, $data)) {
                return redirect()->to(base_url('admin/product-draws'))->with('success', 'Product lucky draw updated successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to update product lucky draw');
            }
        }

        $data['draw'] = $this->productDrawModel->find($id);
        if (!$data['draw']) {
            return redirect()->to(base_url('admin/product-draws'))->with('error', 'Product draw not found');
        }

        return view('admin/edit_product_draw', $data);
    }

    // View methods for both types of draws
    public function viewCashDraw($id)
    {
        $data['draw'] = $this->cashDrawModel->find($id);
        if (!$data['draw']) {
            return redirect()->to(base_url('admin/cash-draws'))->with('error', 'Cash draw not found');
        }

        return view('admin/view_cash_draw', $data);
    }

    public function viewProductDraw($id)
    {
        $data['draw'] = $this->productDrawModel->find($id);
        if (!$data['draw']) {
            return redirect()->to(base_url('admin/product-draws'))->with('error', 'Product draw not found');
        }

        return view('admin/view_product_draw', $data);
    }

    public function editLuckyDraw($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $draw = $this->cashDrawModel->find($id);
            if (!$draw) {
                return redirect()->back()->with('error', 'Lucky draw not found');
            }

            $data = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'entry_fee' => $this->request->getPost('entry_fee'),
                'status' => $this->request->getPost('status')
            ];

            if ($draw['draw_type'] === 'cash') {
                // Cash draw specific fields
                $data['total_winners'] = $this->request->getPost('total_winners');
                $data['draw_date'] = $this->request->getPost('draw_date');
                $data['is_manual_selection'] = $this->request->getPost('is_manual_selection') ? true : false;

                // Validate prize amounts
                $prizeAmounts = $this->request->getPost('prize_amounts');
                if (empty($prizeAmounts) || !is_array($prizeAmounts)) {
                    return redirect()->back()->with('error', 'Please configure prize amounts for winners');
                }

                $totalPrize = 0;
                foreach ($prizeAmounts as $amount) {
                    if ($amount && is_numeric($amount)) {
                        $totalPrize += floatval($amount);
                    }
                }

                if ($totalPrize <= 0) {
                    return redirect()->back()->with('error', 'Total prize amount must be greater than 0');
                }
            } else {
                // Product draw specific fields
                $data['draw_date'] = $this->request->getPost('product_draw_date');
                $data['product_details'] = $this->request->getPost('product_details');

                // Handle product image upload
                $image = $this->request->getFile('product_image');
                if ($image && $image->isValid() && !$image->hasMoved()) {
                    $newName = $image->getRandomName();
                    $image->move(ROOTPATH . 'public/uploads/products', $newName);
                    $data['product_image'] = 'uploads/products/' . $newName;
                }
            }

            if ($this->cashDrawModel->update($id, $data)) {
                // If it's a cash draw, update winner records with new prize amounts
                if ($draw['draw_type'] === 'cash') {
                    $winnerModel = new \App\Models\WinnerModel();
                    $prizeAmounts = $this->request->getPost('prize_amounts');

                    // Clear existing winners for this draw
                    $winnerModel->where('lucky_draw_id', $id)->delete();

                    // Create new winner records with updated prize amounts
                    for ($i = 0; $i < count($prizeAmounts); $i++) {
                        if ($prizeAmounts[$i] && is_numeric($prizeAmounts[$i])) {
                            $winnerData = [
                                'lucky_draw_id' => null, // Set to null for cash draws
                                'cash_draw_id' => $id,
                                'draw_type' => 'cash',
                                'user_id' => null, // Will be set when winners are selected
                                'position' => $i + 1,
                                'prize_amount' => floatval($prizeAmounts[$i]),
                                'is_claimed' => false,
                                'claim_approved' => false,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            ];

                            // Debug: Log the winner data being inserted
                            log_message('info', 'Attempting to insert winner: ' . json_encode($winnerData));

                            $winnerId = $this->winnerModel->insert($winnerData);
                            if (!$winnerId) {
                                // Get validation errors
                                $errors = $this->winnerModel->errors();
                                $errorMessage = 'Failed to create winner record for position ' . ($i + 1);
                                if (!empty($errors)) {
                                    $errorMessage .= '. Validation errors: ' . json_encode($errors);
                                }
                                throw new \Exception($errorMessage);
                            }

                            log_message('info', 'Winner created successfully with ID: ' . $winnerId);
                        }
                    }
                }

                $successMessage = $draw['draw_type'] === 'cash' ? 'Cash lucky draw updated successfully' : 'Product lucky draw updated successfully';
                return redirect()->to(base_url('admin/lucky-draws'))->with('success', $successMessage);
            } else {
                return redirect()->back()->with('error', 'Failed to update lucky draw');
            }
        }

        $data['draw'] = $this->cashDrawModel->find($id);

        // Get existing prize amounts for cash draws
        if ($data['draw']['draw_type'] === 'cash') {
            $winnerModel = new \App\Models\WinnerModel();
            $winners = $winnerModel->where('lucky_draw_id', $id)->orderBy('position', 'ASC')->findAll();
            $data['draw']['prize_amounts'] = array_column($winners, 'prize_amount');
        }

        return view('admin/edit_lucky_draw', $data);
    }

    public function deleteLuckyDraw($id)
    {
        // Delete related entries first
        $this->entryModel->where('lucky_draw_id', $id)->delete();

        // Delete related winners
        $this->winnerModel->where('lucky_draw_id', $id)->delete();

        // Delete the lucky draw
        if ($this->cashDrawModel->delete($id)) {
            return redirect()->to(base_url('admin/lucky-draws'))->with('success', 'Lucky draw deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete lucky draw');
        }
    }

    // Product Management
    public function products()
    {
        $data['products'] = $this->productDrawModel->findAll();
        return view('admin/products', $data);
    }

    public function createProduct()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'draw_type' => 'product',
                'entry_fee' => $this->request->getPost('entry_fee'),
                'total_winners' => 1, // Product draws have only 1 winner
                'draw_date' => $this->request->getPost('draw_date'),
                'is_manual_selection' => true, // Product draws are always manual selection
                'product_details' => $this->request->getPost('product_details'),
                'status' => 'active'
            ];

            // Handle image upload
            $image = $this->request->getFile('product_image');
            if ($image && $image->isValid() && !$image->hasMoved()) {
                $newName = $image->getRandomName();
                $image->move(ROOTPATH . 'public/uploads/products', $newName);
                $data['product_image'] = 'uploads/products/' . $newName;
            }

            if ($this->productDrawModel->insert($data)) {
                return redirect()->to(base_url('admin/products'))->with('success', 'Product created successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to create product');
            }
        }

        return view('admin/create_product');
    }

    public function editProduct($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'entry_fee' => $this->request->getPost('entry_fee'),
                'draw_date' => $this->request->getPost('draw_date'),
                'product_details' => $this->request->getPost('product_details')
            ];

            // Handle image upload
            $image = $this->request->getFile('product_image');
            if ($image && $image->isValid() && !$image->hasMoved()) {
                $newName = $image->getRandomName();
                $image->move(ROOTPATH . 'public/uploads/products', $newName);
                $data['product_image'] = 'uploads/products/' . $newName;
            }

            if ($this->productDrawModel->update($id, $data)) {
                return redirect()->to(base_url('admin/products'))->with('success', 'Product updated successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to update product');
            }
        }

        $data['product'] = $this->productDrawModel->find($id);
        return view('admin/edit_product', $data);
    }

    public function deleteProduct($id)
    {
        if ($this->productDrawModel->delete($id)) {
            return redirect()->to(base_url('admin/products'))->with('success', 'Product deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete product');
        }
    }

    // Transactions Management
    public function transactions()
    {
        $builder = $this->walletTransactionModel->select('wallet_transactions.*, users.username, users.email, users.full_name, wallets.balance as wallet_balance')
            ->join('wallets', 'wallets.id = wallet_transactions.wallet_id')
            ->join('users', 'users.id = wallets.user_id');

        // Filter by transaction type
        $filterType = $this->request->getGet('type');
        if ($filterType && in_array($filterType, ['deposit', 'withdrawal', 'draw_win', 'draw_entry'])) {
            $builder->where('wallet_transactions.type', $filterType);
        }

        // Filter by status
        $filterStatus = $this->request->getGet('status');
        if ($filterStatus && in_array($filterStatus, ['pending', 'completed', 'failed', 'cancelled'])) {
            $builder->where('wallet_transactions.status', $filterStatus);
        }

        // Search by user
        $search = $this->request->getGet('search');
        if ($search) {
            $builder->groupStart()
                ->like('users.username', $search)
                ->orLike('users.email', $search)
                ->orLike('users.full_name', $search)
                ->orLike('wallet_transactions.payment_reference', $search)
                ->groupEnd();
        }

        // Date range filter
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');
        if ($dateFrom) {
            $builder->where('wallet_transactions.created_at >=', $dateFrom . ' 00:00:00');
        }
        if ($dateTo) {
            $builder->where('wallet_transactions.created_at <=', $dateTo . ' 23:59:59');
        }

        $data['transactions'] = $builder->orderBy('wallet_transactions.created_at', 'DESC')
            ->limit(100) // Limit to 100 recent transactions for performance
            ->findAll();

        // Get summary statistics
        $data['summary'] = $this->getTransactionSummary();

        // Pass filter values to view
        $data['filters'] = [
            'type' => $filterType,
            'status' => $filterStatus,
            'search' => $search,
            'date_from' => $dateFrom,
            'date_to' => $dateTo
        ];

        return view('admin/transactions', $data);
    }

    private function getTransactionSummary()
    {
        $summary = [
            'total_deposits' => 0,
            'total_withdrawals' => 0,
            'total_winnings' => 0,
            'total_entries' => 0,
            'pending_withdrawals' => 0,
            'failed_transactions' => 0
        ];

        $transactions = $this->walletTransactionModel->findAll();

        foreach ($transactions as $transaction) {
            if ($transaction['status'] === 'completed') {
                switch ($transaction['type']) {
                    case 'deposit':
                        $summary['total_deposits'] += $transaction['amount'];
                        break;
                    case 'withdrawal':
                        $summary['total_withdrawals'] += $transaction['amount'];
                        break;
                    case 'draw_win':
                        $summary['total_winnings'] += $transaction['amount'];
                        break;
                    case 'draw_entry':
                        $summary['total_entries'] += $transaction['amount'];
                        break;
                }
            } elseif ($transaction['type'] === 'withdrawal' && $transaction['status'] === 'pending') {
                $summary['pending_withdrawals'] += $transaction['amount'];
            } elseif ($transaction['status'] === 'failed') {
                $summary['failed_transactions']++;
            }
        }

        return $summary;
    }

    public function notifications()
    {
        // Get pagination parameters
        $page = (int)($this->request->getGet('page') ?? 1);
        $perPage = (int)($this->request->getGet('per_page') ?? 10);
        $perPage = max(1, min($perPage, 100)); // Limit per_page between 1 and 100
        $offset = ($page - 1) * $perPage;

        // Get filter parameters
        $filter = $this->request->getGet('filter') ?? 'all';
        $dateFilter = $this->request->getGet('date') ?? '';

        // Build query based on filters - use a fresh query builder to avoid conflicts
        $db = \Config\Database::connect();
        $builder = $db->table('notifications')
            ->select('notifications.*, u.username, u.full_name, u.email')
            ->join('users u', 'u.id = notifications.user_id', 'left')
            ->where('notifications.admin_id IS NULL'); // Admin notifications

        // Apply filters
        if ($filter === 'unread') {
            $builder->where('notifications.is_read', false);
        } elseif ($filter !== 'all') {
            $builder->where('notifications.type', $filter);
        }

        // Apply date filter
        if ($dateFilter) {
            $builder->where('DATE(notifications.created_at)', $dateFilter);
        }

        // Get total count for pagination
        $totalNotifications = $builder->countAllResults(false);

        // Get notifications with pagination
        $notifications = $builder->orderBy('notifications.created_at', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResultArray();

        // Add styling information
        foreach ($notifications as &$notification) {
            $notification['style'] = $this->notificationService->getNotificationStyle($notification['type']);
            $notification['priority_style'] = $this->notificationService->getPriorityStyle($notification['priority']);
            $notification['time_ago'] = $this->timeAgo($notification['created_at']);
        }

        // Calculate pagination using helper
        $pager = \App\Helpers\PaginationHelper::generatePager($totalNotifications, $page, $perPage);

        // Get notification statistics
        $stats = $this->notificationModel->getStats();

        $data = [
            'title' => 'Notifications Center',
            'notifications' => $notifications,
            'pager' => $pager,
            'stats' => $stats,
            'current_filter' => $filter,
            'date_filter' => $dateFilter
        ];

        return view('admin/notifications', $data);
    }

    /**
     * Helper function to calculate time ago
     */
    private function timeAgo($datetime)
    {
        $time = time() - strtotime($datetime);

        if ($time < 60) return 'just now';
        if ($time < 3600) return floor($time / 60) . 'm ago';
        if ($time < 86400) return floor($time / 3600) . 'h ago';
        if ($time < 2592000) return floor($time / 86400) . 'd ago';
        if ($time < 31536000) return floor($time / 2592000) . 'mo ago';
        return floor($time / 31536000) . 'y ago';
    }

    public function transactionDetails($id)
    {
        $transaction = $this->walletTransactionModel->select('wallet_transactions.*, users.username, users.email, users.full_name')
            ->join('wallets', 'wallets.id = wallet_transactions.wallet_id')
            ->join('users', 'users.id = wallets.user_id')
            ->where('wallet_transactions.id', $id)
            ->first();

        if ($transaction) {
            return $this->response->setJSON([
                'success' => true,
                'transaction' => $transaction
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Transaction not found'
            ]);
        }
    }

    // Note: approveWithdrawal and rejectWithdrawal methods are now defined below in the withdrawRequests section

    // Winners Management
    public function winners()
    {
        // Get all winners (both claimed and unclaimed) for admin to see
        $data['all_winners'] = $this->winnerModel->getAllWinnersForAdmin();
        $data['pending_claims'] = $this->winnerModel->getPendingClaims();
        $data['approved_claims'] = $this->winnerModel->getApprovedClaims();
        return view('admin/winners', $data);
    }

    public function approveClaim($winnerId)
    {
        if ($this->winnerModel->approveClaim($winnerId)) {
            // Add money to winner's wallet if it's a cash draw
            $winner = $this->winnerModel->find($winnerId);

            if ($winner['draw_type'] === 'cash' && $winner['cash_draw_id']) {
                $draw = $this->cashDrawModel->find($winner['cash_draw_id']);

                if ($draw) {
                    $wallet = $this->walletModel->getUserWallet($winner['user_id']);
                    if ($wallet) {
                        $this->walletModel->updateBalance($wallet['id'], $winner['prize_amount'], 'add');

                        // Record transaction
                        $this->walletTransactionModel->insert([
                            'wallet_id' => $wallet['id'],
                            'type' => 'draw_win',
                            'amount' => $winner['prize_amount'],
                            'payment_method' => 'system',
                            'payment_reference' => 'Winner: ' . $draw['title'],
                            'status' => 'completed',
                            'created_at' => date('Y-m-d H:i:s')
                        ]);

                        // Send win notification to user
                        $this->notificationService->notifyUser($winner['user_id'], 'draw_win', [
                            'draw_title' => $draw['title'],
                            'prize_amount' => $winner['prize_amount'],
                            'draw_type' => $draw['draw_type'],
                            'winner_id' => $winnerId
                        ], session()->get('user_id'));
                    }
                }
            }

            return redirect()->to(base_url('admin/winners'))->with('success', 'Claim approved successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to approve claim');
        }
    }

    public function rejectClaim($winnerId)
    {
        if ($this->winnerModel->update($winnerId, ['is_claimed' => false])) {
            return redirect()->to(base_url('admin/winners'))->with('success', 'Claim rejected successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to reject claim');
        }
    }

    public function withdrawRequests()
    {
        // Get pending withdrawal transactions only
        $perPage = 20;
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $perPage;

        // Count total pending withdrawals
        $totalWithdrawals = $this->walletTransactionModel
            ->where('type', 'withdrawal')
            ->where('status', 'pending')
            ->countAllResults();

        // Get paginated pending withdrawal requests with user details
        $data['withdrawals'] = $this->walletTransactionModel
            ->select('wallet_transactions.*, users.full_name, users.username, users.email, wallets.user_id')
            ->join('wallets', 'wallets.id = wallet_transactions.wallet_id')
            ->join('users', 'users.id = wallets.user_id')
            ->where('wallet_transactions.type', 'withdrawal')
            ->where('wallet_transactions.status', 'pending')
            ->orderBy('wallet_transactions.created_at', 'DESC')
            ->limit($perPage, $offset)
            ->findAll();

        // Pagination data
        $data['pagination'] = [
            'current_page' => (int)$page,
            'per_page' => $perPage,
            'total_withdrawals' => $totalWithdrawals,
            'total_pages' => ceil($totalWithdrawals / $perPage),
            'has_previous' => $page > 1,
            'has_next' => $page < ceil($totalWithdrawals / $perPage),
            'previous_page' => $page - 1,
            'next_page' => $page + 1,
            'start_withdrawal' => $offset + 1,
            'end_withdrawal' => min($offset + $perPage, $totalWithdrawals)
        ];

        // Calculate statistics
        $data['stats'] = [
            'total_pending' => $totalWithdrawals,
            'total_amount' => abs($this->walletTransactionModel
                ->selectSum('amount')
                ->where('type', 'withdrawal')
                ->where('status', 'pending')
                ->get()
                ->getRow()
                ->amount ?? 0),
            'today_count' => $this->walletTransactionModel
                ->where('type', 'withdrawal')
                ->where('status', 'pending')
                ->where('DATE(created_at)', date('Y-m-d'))
                ->countAllResults(),
            'today_amount' => abs($this->walletTransactionModel
                ->selectSum('amount')
                ->where('type', 'withdrawal')
                ->where('status', 'pending')
                ->where('DATE(created_at)', date('Y-m-d'))
                ->get()
                ->getRow()
                ->amount ?? 0),
            'total_completed' => $this->walletTransactionModel
                ->where('type', 'withdrawal')
                ->where('status', 'completed')
                ->countAllResults()
        ];

        return view('admin/withdraw_requests', $data);
    }

    public function approveWithdrawal($id)
    {
        $transaction = $this->walletTransactionModel->find($id);

        if (!$transaction || $transaction['status'] !== 'pending' || $transaction['type'] !== 'withdrawal') {
            return redirect()->back()->with('error', 'Invalid withdrawal request');
        }

        try {
            // Start database transaction
            $this->db->transStart();

            // Get wallet and user info
            $walletModel = new WalletModel();
            $wallet = $walletModel->find($transaction['wallet_id']);
            if (!$wallet) {
                throw new \Exception('Wallet not found');
            }

            $user = $this->userModel->find($wallet['user_id']);
            if (!$user) {
                throw new \Exception('User not found');
            }

            // Process payment based on payment method
            $paymentResult = $this->processWithdrawalPayment($transaction, $user);

            if (!$paymentResult['success']) {
                throw new \Exception('Payment processing failed: ' . $paymentResult['message']);
            }

            // Update transaction status to completed
            $updateData = [
                'status' => 'completed',
                'payment_reference' => $paymentResult['reference'],
                'balance_after' => $wallet['balance'], // Balance remains the same since it was already deducted
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $success = $this->walletTransactionModel->update($id, $updateData);
            if (!$success) {
                throw new \Exception('Failed to update transaction status');
            }

            // Send notification to user (without admin_id to avoid foreign key constraint)
            $this->notificationService->sendSystemMessage(
                $wallet['user_id'],
                'Withdrawal Approved! 🎉',
                'Great news! Your withdrawal request of $' . number_format(abs($transaction['amount']), 2) . ' has been approved and processed. The funds should reach your account within 1-3 business days.',
                'high'
            );

            // Commit transaction
            $this->db->transCommit();

            return redirect()->back()->with('success', 'Withdrawal request approved and payment processed successfully');
        } catch (\Exception $e) {
            // Rollback transaction
            $this->db->transRollback();

            log_message('error', 'Withdrawal approval failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to approve withdrawal: ' . $e->getMessage());
        }
    }

    public function rejectWithdrawal($id)
    {
        $transaction = $this->walletTransactionModel->find($id);

        if (!$transaction || $transaction['status'] !== 'pending' || $transaction['type'] !== 'withdrawal') {
            return redirect()->back()->with('error', 'Invalid withdrawal request');
        }

        try {
            // Start database transaction
            $this->db->transStart();

            // Get wallet info
            $walletModel = new WalletModel();
            $wallet = $walletModel->find($transaction['wallet_id']);
            if (!$wallet) {
                throw new \Exception('Wallet not found');
            }

            // Refund the amount back to wallet (since it was deducted when withdrawal was requested)
            $refundAmount = abs($transaction['amount']); // Convert negative amount to positive for refund
            $newBalance = $wallet['balance'] + $refundAmount;
            $walletModel->update($wallet['id'], ['balance' => $newBalance]);

            // Update transaction status to failed
            $success = $this->walletTransactionModel->update($id, [
                'status' => 'failed',
                'payment_reference' => 'ADMIN_REJECTED_' . time(),
                'balance_after' => $newBalance,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if (!$success) {
                throw new \Exception('Failed to update transaction status');
            }

            // Send notification to user (without admin_id to avoid foreign key constraint)
            $this->notificationService->sendSystemMessage(
                $wallet['user_id'],
                'Withdrawal Request Update',
                'Your withdrawal request of $' . number_format(abs($transaction['amount']), 2) . ' has been reviewed and rejected. The amount has been refunded to your wallet.',
                'high'
            );

            // Commit transaction
            $this->db->transCommit();

            return redirect()->back()->with('success', 'Withdrawal request rejected and amount refunded');
        } catch (\Exception $e) {
            // Rollback transaction
            $this->db->transRollback();

            log_message('error', 'Withdrawal rejection failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to reject withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Process withdrawal payment based on payment method
     */
    private function processWithdrawalPayment($transaction, $user)
    {
        $paymentMethod = $transaction['payment_method'];
        $amount = abs($transaction['amount']);

        try {
            switch ($paymentMethod) {
                case 'paypal':
                    // For PayPal, we would typically call PayPal API to send money
                    // For now, we'll simulate success
                    $paypalResult = $this->processPayPalWithdrawal($amount, $user);
                    if ($paypalResult['success']) {
                        return [
                            'success' => true,
                            'reference' => 'PAYPAL_' . time() . '_' . $user['id'],
                            'message' => 'Payment sent via PayPal'
                        ];
                    } else {
                        throw new \Exception($paypalResult['message']);
                    }
                    break;

                case 'easypaisa':
                    // For Easypaisa, we would call their API
                    $easypaisaResult = $this->processEasypaisaWithdrawal($amount, $user);
                    if ($easypaisaResult['success']) {
                        return [
                            'success' => true,
                            'reference' => 'EASYPAISA_' . time() . '_' . $user['id'],
                            'message' => 'Payment sent via Easypaisa'
                        ];
                    } else {
                        throw new \Exception($easypaisaResult['message']);
                    }
                    break;

                default:
                    // For other methods, just mark as completed
                    return [
                        'success' => true,
                        'reference' => 'MANUAL_' . time() . '_' . $user['id'],
                        'message' => 'Payment processed manually'
                    ];
            }
        } catch (\Exception $e) {
            log_message('error', 'Withdrawal payment processing failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Process PayPal withdrawal
     */
    private function processPayPalWithdrawal($amount, $user)
    {
        // Check if PayPal credentials are configured
        $paypalClientId = getenv('PAYPAL_CLIENT_ID');
        $paypalClientSecret = getenv('PAYPAL_CLIENT_SECRET');

        if (empty($paypalClientId) || empty($paypalClientSecret)) {
            return [
                'success' => false,
                'message' => 'PayPal is not configured. Please process payment manually.'
            ];
        }

        // Here you would implement actual PayPal API call to send money
        // For now, we'll simulate success
        log_message('info', 'PayPal withdrawal processed for user ' . $user['id'] . ' amount: $' . $amount);

        return [
            'success' => true,
            'message' => 'PayPal payment processed successfully'
        ];
    }

    /**
     * Process Easypaisa withdrawal
     */
    private function processEasypaisaWithdrawal($amount, $user)
    {
        // Check if Easypaisa credentials are configured
        $easypaisaStoreId = getenv('EASYPAISA_STORE_ID');
        $easypaisaHashKey = getenv('EASYPAISA_HASH_KEY');

        if (empty($easypaisaStoreId) || empty($easypaisaHashKey)) {
            return [
                'success' => false,
                'message' => 'Easypaisa is not configured. Please process payment manually.'
            ];
        }

        // Here you would implement actual Easypaisa API call to send money
        // For now, we'll simulate success
        log_message('info', 'Easypaisa withdrawal processed for user ' . $user['id'] . ' amount: $' . $amount);

        return [
            'success' => true,
            'message' => 'Easypaisa payment processed successfully'
        ];
    }

    // Settings
    public function settings()
    {
        if ($this->request->getMethod() === 'POST') {
            $currentPassword = $this->request->getPost('current_password');
            $newPassword = $this->request->getPost('new_password');
            $confirmPassword = $this->request->getPost('confirm_password');

            // Verify current password
            $admin = $this->userModel->find(session()->get('user_id'));
            if (!$this->userModel->verifyPassword($currentPassword, $admin['password'])) {
                return redirect()->back()->with('error', 'Current password is incorrect');
            }

            if ($newPassword !== $confirmPassword) {
                return redirect()->back()->with('error', 'New passwords do not match');
            }

            if ($this->userModel->update($admin['id'], ['password' => $this->userModel->hashPassword($newPassword)])) {
                return redirect()->to(base_url('admin/settings'))->with('success', 'Password updated successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to update password');
            }
        }

        return view('admin/settings');
    }

    // Delete methods for draws
    public function deleteCashDraw($id)
    {
        if ($this->cashDrawModel->delete($id)) {
            return redirect()->to(base_url('admin/cash-draws'))->with('success', 'Cash draw deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete cash draw');
        }
    }

    public function deleteProductDraw($id)
    {
        if ($this->productDrawModel->delete($id)) {
            return redirect()->to(base_url('admin/product-draws'))->with('success', 'Product draw deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete product draw');
        }
    }

    // Manual Winner Selection
    public function selectWinners($drawId)
    {
        if ($this->request->getMethod() === 'POST') {
            $winners = $this->request->getPost('winners');
            $prizeAmounts = $this->request->getPost('prize_amounts');

            // Validate that we have the right number of winners
            $draw = $this->cashDrawModel->find($drawId);
            if (!$draw) {
                return redirect()->to(base_url('admin/cash-draws'))->with('error', 'Cash draw not found');
            }

            try {
                // Start database transaction
                $this->db->transStart();

                // Clear existing winners
                $this->winnerModel->clearCashDrawWinners($drawId);

                // Add new winners
                $totalPrizeDistributed = 0;
                foreach ($winners as $position => $userId) {
                    if ($userId && isset($prizeAmounts[$position]) && $prizeAmounts[$position] > 0) {
                        $winnerData = [
                            'lucky_draw_id' => null, // Set to null for cash draws
                            'cash_draw_id' => $drawId,
                            'draw_type' => 'cash',
                            'user_id' => $userId,
                            'position' => $position + 1,
                            'prize_amount' => $prizeAmounts[$position],
                            'is_claimed' => false,
                            'claim_approved' => false,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];

                        // Debug: Log the winner data being inserted
                        log_message('info', 'Attempting to insert winner: ' . json_encode($winnerData));

                        $winnerId = $this->winnerModel->insert($winnerData);
                        if (!$winnerId) {
                            // Get validation errors
                            $errors = $this->winnerModel->errors();
                            $errorMessage = 'Failed to create winner record for position ' . ($position + 1);
                            if (!empty($errors)) {
                                $errorMessage .= '. Validation errors: ' . json_encode($errors);
                            }
                            throw new \Exception($errorMessage);
                        }

                        log_message('info', 'Winner created successfully with ID: ' . $winnerId);
                        $totalPrizeDistributed += $prizeAmounts[$position];
                    }
                }

                // Update draw status to completed and set final participant count
                $entries = $this->entryModel->getCashDrawEntries($drawId);
                $this->cashDrawModel->update($drawId, [
                    'status' => 'completed',
                    'participant_count' => count($entries),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                // Complete transaction
                $this->db->transComplete();

                if ($this->db->transStatus() === false) {
                    throw new \Exception('Database transaction failed');
                }

                return redirect()->to(base_url('admin/cash-draws'))->with(
                    'success',
                    'Winners selected successfully! Total prize distributed: Rs. ' . number_format($totalPrizeDistributed, 2)
                );
            } catch (\Exception $e) {
                // Rollback transaction on error
                $this->db->transRollback();
                log_message('error', 'Error selecting winners manually: ' . $e->getMessage());

                return redirect()->to(base_url('admin/cash-draws'))->with(
                    'error',
                    'Failed to select winners: ' . $e->getMessage()
                );
            }
        }

        // Get draw details
        $data['draw'] = $this->cashDrawModel->find($drawId);
        if (!$data['draw']) {
            return redirect()->to(base_url('admin/cash-draws'))->with('error', 'Cash draw not found');
        }

        // Get entries for this cash draw
        $data['entries'] = $this->entryModel->getCashDrawEntries($drawId);

        // Get existing winners
        $existingWinners = $this->winnerModel->getCashDrawWinners($drawId);
        $data['existing_winners'] = [];
        foreach ($existingWinners as $winner) {
            $data['existing_winners'][$winner['position'] - 1] = $winner;
        }

        // Calculate suggested prize distribution
        $totalPrize = $data['draw']['prize_amount'];
        $totalWinners = $data['draw']['total_winners'];
        $data['suggested_prizes'] = $this->calculatePrizeDistribution($totalPrize, $totalWinners);

        return view('admin/select_winners', $data);
    }

    public function selectRandomWinners($drawId)
    {
        $draw = $this->cashDrawModel->find($drawId);
        if (!$draw) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cash draw not found']);
        }

        // Get all eligible entries
        $entries = $this->entryModel->getCashDrawEntries($drawId);

        if (count($entries) < $draw['total_winners']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Not enough entries to select ' . $draw['total_winners'] . ' winners'
            ]);
        }

        try {
            // Start database transaction
            $this->db->transStart();

            // Clear existing winners
            $this->winnerModel->clearCashDrawWinners($drawId);

            // Randomly select winners
            $selectedEntries = array_rand($entries, min($draw['total_winners'], count($entries)));
            if (!is_array($selectedEntries)) {
                $selectedEntries = [$selectedEntries];
            }

            // Calculate prize distribution
            $prizeDistribution = $this->calculatePrizeDistribution($draw['prize_amount'], $draw['total_winners']);

            // Insert winners
            $winners = [];
            foreach ($selectedEntries as $position => $entryIndex) {
                $entry = $entries[$entryIndex];
                $winnerData = [
                    'lucky_draw_id' => null, // Set to null for cash draws
                    'cash_draw_id' => $drawId,
                    'draw_type' => 'cash',
                    'user_id' => $entry['user_id'],
                    'position' => $position + 1,
                    'prize_amount' => $prizeDistribution[$position],
                    'is_claimed' => false,
                    'claim_approved' => false,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                // Debug: Log the winner data being inserted
                log_message('info', 'Attempting to insert random winner: ' . json_encode($winnerData));

                $winnerId = $this->winnerModel->insert($winnerData);
                if (!$winnerId) {
                    // Get validation errors
                    $errors = $this->winnerModel->errors();
                    $errorMessage = 'Failed to create winner record for position ' . ($position + 1);
                    if (!empty($errors)) {
                        $errorMessage .= '. Validation errors: ' . json_encode($errors);
                    }
                    throw new \Exception($errorMessage);
                }

                log_message('info', 'Random winner created successfully with ID: ' . $winnerId);

                $winners[] = [
                    'position' => $position + 1,
                    'user_id' => $entry['user_id'],
                    'full_name' => $entry['full_name'],
                    'username' => $entry['username'],
                    'prize_amount' => $prizeDistribution[$position]
                ];
            }

            // Update draw status to completed and set final participant count
            $this->cashDrawModel->update($drawId, [
                'status' => 'completed',
                'participant_count' => count($entries),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Complete transaction
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Database transaction failed');
            }

            // Send notifications to winners
            foreach ($winners as $winner) {
                try {
                    $this->notificationService->notifyUser($winner['user_id'], 'draw_win', [
                        'draw_type' => 'cash',
                        'draw_id' => $drawId,
                        'draw_title' => $draw['title'],
                        'prize_amount' => $winner['prize_amount'],
                        'position' => $winner['position']
                    ], session()->get('user_id'));
                } catch (\Exception $e) {
                    log_message('error', 'Failed to send winner notification: ' . $e->getMessage());
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Random winners selected successfully!',
                'winners' => $winners
            ]);
        } catch (\Exception $e) {
            // Rollback transaction on error
            $this->db->transRollback();
            log_message('error', 'Error selecting cash draw winners: ' . $e->getMessage());

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to select winners: ' . $e->getMessage()
            ]);
        }
    }

    private function calculatePrizeDistribution($totalPrize, $totalWinners)
    {
        $prizes = [];

        if ($totalWinners == 1) {
            $prizes[0] = $totalPrize;
        } else {
            // Distribute prizes with first place getting more
            $remaining = $totalPrize;
            for ($i = 0; $i < $totalWinners; $i++) {
                if ($i == $totalWinners - 1) {
                    // Last winner gets remaining amount
                    $prizes[$i] = $remaining;
                } else {
                    // Calculate percentage based on position (first gets more)
                    $percentage = (($totalWinners - $i) / array_sum(range(1, $totalWinners)));
                    $amount = round($totalPrize * $percentage, 2);
                    $prizes[$i] = $amount;
                    $remaining -= $amount;
                }
            }
        }

        return $prizes;
    }

    public function viewCashDrawDetails($drawId)
    {
        $data['draw'] = $this->cashDrawModel->find($drawId);
        if (!$data['draw']) {
            return redirect()->to(base_url('admin/cash-draws'))->with('error', 'Cash draw not found');
        }

        // Pagination setup
        $perPage = 20; // Show 20 participants per page
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $perPage;

        // Get total count of entries
        $totalEntries = $this->entryModel->where('cash_draw_id', $drawId)->countAllResults();

        // Get paginated entries for this cash draw with user details
        $data['entries'] = $this->entryModel->select('entries.*, users.username, users.full_name, users.email')
            ->join('users', 'users.id = entries.user_id')
            ->where('entries.cash_draw_id', $drawId)
            ->orderBy('entries.created_at', 'DESC')
            ->limit($perPage, $offset)
            ->findAll();

        // Get winners with user details
        $data['winners'] = $this->winnerModel->select('winners.*, users.username, users.full_name, users.email')
            ->join('users', 'users.id = winners.user_id')
            ->where('winners.cash_draw_id', $drawId)
            ->orderBy('winners.position', 'ASC')
            ->findAll();

        // Pagination data
        $data['pagination'] = [
            'current_page' => (int)$page,
            'per_page' => $perPage,
            'total_entries' => $totalEntries,
            'total_pages' => ceil($totalEntries / $perPage),
            'has_previous' => $page > 1,
            'has_next' => $page < ceil($totalEntries / $perPage),
            'previous_page' => $page - 1,
            'next_page' => $page + 1,
            'start_entry' => $offset + 1,
            'end_entry' => min($offset + $perPage, $totalEntries)
        ];

        // Calculate statistics
        $data['stats'] = [
            'total_entries' => $totalEntries,
            'total_prize_distributed' => array_sum(array_column($data['winners'], 'prize_amount')),
            'total_revenue' => $totalEntries * $data['draw']['entry_fee'],
            'profit' => ($totalEntries * $data['draw']['entry_fee']) - array_sum(array_column($data['winners'], 'prize_amount'))
        ];

        return view('admin/view_cash_draw_details', $data);
    }

    public function viewProductDrawDetails($drawId)
    {
        $data['draw'] = $this->productDrawModel->find($drawId);
        if (!$data['draw']) {
            return redirect()->to(base_url('admin/product-draws'))->with('error', 'Product draw not found');
        }

        // Pagination setup
        $perPage = 20; // Show 20 participants per page
        $page = $this->request->getGet('page') ?? 1;
        $offset = ($page - 1) * $perPage;

        // Get total count of entries
        $totalEntries = $this->entryModel->where('product_draw_id', $drawId)->countAllResults();

        // Get paginated entries for this product draw with user details
        $data['entries'] = $this->entryModel->select('entries.*, users.username, users.full_name, users.email')
            ->join('users', 'users.id = entries.user_id')
            ->where('entries.product_draw_id', $drawId)
            ->orderBy('entries.created_at', 'DESC')
            ->limit($perPage, $offset)
            ->findAll();

        // Get winners with user details
        $data['winners'] = $this->winnerModel->select('winners.*, users.username, users.full_name, users.email')
            ->join('users', 'users.id = winners.user_id')
            ->where('winners.product_draw_id', $drawId)
            ->orderBy('winners.position', 'ASC')
            ->findAll();

        // Pagination data
        $data['pagination'] = [
            'current_page' => (int)$page,
            'per_page' => $perPage,
            'total_entries' => $totalEntries,
            'total_pages' => ceil($totalEntries / $perPage),
            'has_previous' => $page > 1,
            'has_next' => $page < ceil($totalEntries / $perPage),
            'previous_page' => $page - 1,
            'next_page' => $page + 1,
            'start_entry' => $offset + 1,
            'end_entry' => min($offset + $perPage, $totalEntries)
        ];

        // Calculate statistics
        $data['stats'] = [
            'total_entries' => $totalEntries,
            'total_revenue' => $totalEntries * $data['draw']['entry_fee'],
            'product_value' => $data['draw']['product_price'] ?? 0
        ];

        return view('admin/view_product_draw_details', $data);
    }

    public function selectProductWinners($drawId)
    {
        if ($this->request->getMethod() === 'POST') {
            $winner = $this->request->getPost('winner');

            // Validate that we have a winner
            $draw = $this->productDrawModel->find($drawId);
            if (!$draw) {
                return redirect()->to(base_url('admin/product-draws'))->with('error', 'Product draw not found');
            }

            // Clear existing winners
            $this->winnerModel->clearProductDrawWinners($drawId);

            try {
                // Start database transaction
                $this->db->transStart();

                // Clear existing winners
                $this->winnerModel->clearProductDrawWinners($drawId);

                // Add the winner (product draws typically have 1 winner)
                if ($winner) {
                    $winnerData = [
                        'lucky_draw_id' => null, // Set to null for product draws
                        'product_draw_id' => $drawId,
                        'draw_type' => 'product',
                        'user_id' => $winner,
                        'position' => 1,
                        'prize_amount' => $draw['product_price'] ?? 0,
                        'is_claimed' => false,
                        'claim_approved' => false,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    // Debug: Log the winner data being inserted
                    log_message('info', 'Attempting to insert manual product draw winner: ' . json_encode($winnerData));

                    $winnerId = $this->winnerModel->insert($winnerData);
                    if (!$winnerId) {
                        // Get validation errors
                        $errors = $this->winnerModel->errors();
                        $errorMessage = 'Failed to create winner record';
                        if (!empty($errors)) {
                            $errorMessage .= '. Validation errors: ' . json_encode($errors);
                        }
                        throw new \Exception($errorMessage);
                    }

                    log_message('info', 'Manual product draw winner created successfully with ID: ' . $winnerId);
                }

                // Update draw status to completed and set final participant count
                $entries = $this->entryModel->getProductDrawEntries($drawId);
                $this->productDrawModel->update($drawId, [
                    'status' => 'completed',
                    'participant_count' => count($entries),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                // Complete transaction
                $this->db->transComplete();

                if ($this->db->transStatus() === false) {
                    throw new \Exception('Database transaction failed');
                }

                return redirect()->to(base_url('admin/product-draws'))->with(
                    'success',
                    'Winner selected successfully for product draw!'
                );
            } catch (\Exception $e) {
                // Rollback transaction on error
                $this->db->transRollback();
                log_message('error', 'Error selecting product draw winner manually: ' . $e->getMessage());

                return redirect()->to(base_url('admin/product-draws'))->with(
                    'error',
                    'Failed to select winner: ' . $e->getMessage()
                );
            }
        }

        // Get draw details
        $data['draw'] = $this->productDrawModel->find($drawId);
        if (!$data['draw']) {
            return redirect()->to(base_url('admin/product-draws'))->with('error', 'Product draw not found');
        }

        // Get entries for this product draw
        $data['entries'] = $this->entryModel->getProductDrawEntries($drawId);

        // Get existing winner
        $existingWinners = $this->winnerModel->getProductDrawWinners($drawId);
        $data['existing_winner'] = !empty($existingWinners) ? $existingWinners[0] : null;

        return view('admin/select_product_winners', $data);
    }

    public function selectRandomProductWinners($drawId)
    {
        $draw = $this->productDrawModel->find($drawId);
        if (!$draw) {
            return $this->response->setJSON(['success' => false, 'message' => 'Product draw not found']);
        }

        // Get all eligible entries
        $entries = $this->entryModel->getProductDrawEntries($drawId);

        if (empty($entries)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No entries found for this product draw'
            ]);
        }

        try {
            // Start database transaction
            $this->db->transStart();

            // Clear existing winners
            $this->winnerModel->clearProductDrawWinners($drawId);

            // Randomly select one winner
            $randomIndex = array_rand($entries);
            $selectedEntry = $entries[$randomIndex];

            // Insert winner
            $winnerData = [
                'lucky_draw_id' => null, // Set to null for product draws
                'product_draw_id' => $drawId,
                'draw_type' => 'product',
                'user_id' => $selectedEntry['user_id'],
                'position' => 1,
                'prize_amount' => $draw['product_price'] ?? 0,
                'is_claimed' => false,
                'claim_approved' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Debug: Log the winner data being inserted
            log_message('info', 'Attempting to insert product draw winner: ' . json_encode($winnerData));

            $winnerId = $this->winnerModel->insert($winnerData);
            if (!$winnerId) {
                // Get validation errors
                $errors = $this->winnerModel->errors();
                $errorMessage = 'Failed to create winner record';
                if (!empty($errors)) {
                    $errorMessage .= '. Validation errors: ' . json_encode($errors);
                }
                throw new \Exception($errorMessage);
            }

            log_message('info', 'Product draw winner created successfully with ID: ' . $winnerId);

            // Update draw status to completed and set final participant count
            $this->productDrawModel->update($drawId, [
                'status' => 'completed',
                'participant_count' => count($entries),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Complete transaction
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Database transaction failed');
            }

            // Send notification to winner
            try {
                $this->notificationService->notifyUser($selectedEntry['user_id'], 'draw_win', [
                    'draw_type' => 'product',
                    'draw_id' => $drawId,
                    'draw_title' => $draw['title'],
                    'product_name' => $draw['product_name'],
                    'product_value' => $draw['product_price'] ?? 0,
                    'position' => 1
                ], session()->get('user_id'));
            } catch (\Exception $e) {
                log_message('error', 'Failed to send winner notification: ' . $e->getMessage());
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Random winner selected successfully!',
                'winner' => [
                    'user_id' => $selectedEntry['user_id'],
                    'full_name' => $selectedEntry['full_name'],
                    'username' => $selectedEntry['username'],
                    'product_value' => $draw['product_price'] ?? 0
                ]
            ]);
        } catch (\Exception $e) {
            // Rollback transaction on error
            $this->db->transRollback();
            log_message('error', 'Error selecting product draw winners: ' . $e->getMessage());

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to select winners: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Test method to create a sample winner for testing
     */
    public function createTestWinner()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('admin/login')->with('error', 'Admin access required');
        }

        try {
            // Create a test winner for cash draw
            $winnerData = [
                'cash_draw_id' => 1, // Assuming cash draw ID 1 exists
                'draw_type' => 'cash',
                'user_id' => 1, // Assuming user ID 1 exists
                'position' => 1,
                'prize_amount' => 1000, // Rs. 1000
                'is_claimed' => false,
                'claim_approved' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $winnerId = $this->winnerModel->insert($winnerData);

            if ($winnerId) {
                return redirect()->to('admin/winners')->with('success', 'Test winner created successfully! ID: ' . $winnerId);
            } else {
                return redirect()->back()->with('error', 'Failed to create test winner');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error creating test winner: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error creating test winner: ' . $e->getMessage());
        }
    }

    /**
     * Fix participant counts for all draws (run once to fix existing data)
     */
    public function fixParticipantCounts()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('admin/login')->with('error', 'Admin access required');
        }

        try {
            $fixedCount = 0;

            // Fix cash draws
            $cashDraws = $this->cashDrawModel->findAll();
            foreach ($cashDraws as $draw) {
                $entryCount = $this->entryModel->where('cash_draw_id', $draw['id'])
                    ->where('payment_status', 'completed')
                    ->countAllResults();

                if ($entryCount != $draw['participant_count']) {
                    $this->cashDrawModel->update($draw['id'], [
                        'participant_count' => $entryCount,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    $fixedCount++;
                }
            }

            // Fix product draws
            $productDraws = $this->productDrawModel->findAll();
            foreach ($productDraws as $draw) {
                $entryCount = $this->entryModel->where('product_draw_id', $draw['id'])
                    ->where('payment_status', 'completed')
                    ->countAllResults();

                if ($entryCount != $draw['participant_count']) {
                    $this->productDrawModel->update($draw['id'], [
                        'participant_count' => $entryCount,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    $fixedCount++;
                }
            }

            return redirect()->to('admin/dashboard')->with('success', "Fixed participant counts for $fixedCount draws!");
        } catch (\Exception $e) {
            log_message('error', 'Error fixing participant counts: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error fixing participant counts: ' . $e->getMessage());
        }
    }

    /**
     * Debug method to check winners and draw status
     */
    public function debugWinners($drawId = null)
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('admin/login')->with('error', 'Admin access required');
        }

        try {
            $debugInfo = [];

            if ($drawId) {
                // Debug specific draw
                $cashDraw = $this->cashDrawModel->find($drawId);
                $productDraw = $this->productDrawModel->find($drawId);

                if ($cashDraw) {
                    $debugInfo['draw_type'] = 'cash';
                    $debugInfo['draw'] = $cashDraw;
                    $debugInfo['entries'] = $this->entryModel->getCashDrawEntries($drawId);
                    $debugInfo['winners'] = $this->winnerModel->getCashDrawWinners($drawId);
                } elseif ($productDraw) {
                    $debugInfo['draw_type'] = 'product';
                    $debugInfo['draw'] = $productDraw;
                    $debugInfo['entries'] = $this->entryModel->getProductDrawEntries($drawId);
                    $debugInfo['winners'] = $this->winnerModel->getProductDrawWinners($drawId);
                } else {
                    return redirect()->back()->with('error', 'Draw not found');
                }
            } else {
                // Debug all draws
                $debugInfo['cash_draws'] = $this->cashDrawModel->findAll();
                $debugInfo['product_draws'] = $this->productDrawModel->findAll();
                $debugInfo['all_winners'] = $this->winnerModel->findAll();
            }

            // Return debug info as JSON for easy inspection
            return $this->response->setJSON([
                'success' => true,
                'debug_info' => $debugInfo,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error debugging winners: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Test winner creation with detailed logging
     */
    public function testWinnerCreation()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('admin/login')->with('error', 'Admin access required');
        }

        try {
            $results = [];

            // Test 1: Check if we can connect to database
            $results['database_connection'] = 'OK';
            $results['db_object'] = get_class($this->db);

            // Test 2: Check if WinnerModel is working
            $results['winner_model'] = 'OK';
            $results['winner_model_class'] = get_class($this->winnerModel);

            // Test 3: Try to create a simple winner record
            $testWinnerData = [
                'cash_draw_id' => 1, // Assuming cash draw ID 1 exists
                'draw_type' => 'cash',
                'user_id' => 1, // Assuming user ID 1 exists
                'position' => 1,
                'prize_amount' => 1000,
                'is_claimed' => false,
                'claim_approved' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Start transaction
            $this->db->transStart();

            // Try to insert
            $winnerId = $this->winnerModel->insert($testWinnerData);

            if ($winnerId) {
                $results['winner_creation'] = 'SUCCESS';
                $results['winner_id'] = $winnerId;

                // Try to retrieve the winner
                $createdWinner = $this->winnerModel->find($winnerId);
                $results['winner_retrieval'] = 'SUCCESS';
                $results['created_winner'] = $createdWinner;

                // Clean up - delete the test winner
                $this->winnerModel->delete($winnerId);
                $results['cleanup'] = 'SUCCESS';
            } else {
                $results['winner_creation'] = 'FAILED';
                $results['error'] = 'Failed to insert winner';
            }

            // Complete transaction
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $results['transaction'] = 'FAILED';
            } else {
                $results['transaction'] = 'SUCCESS';
            }

            // Return results as JSON
            return $this->response->setJSON([
                'success' => true,
                'test_results' => $results,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error testing winner creation: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Test database connection and winners table structure
     */
    public function testDatabase()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('admin/login')->with('error', 'Admin access required');
        }

        try {
            $results = [];

            // Test 1: Check database connection
            $results['database_connection'] = 'OK';
            $results['db_object'] = get_class($this->db);

            // Test 2: Check if winners table exists and get its structure
            $query = $this->db->query("DESCRIBE winners");
            $tableStructure = $query->getResultArray();
            $results['winners_table_structure'] = $tableStructure;

            // Test 3: Try to insert a test winner record
            $testWinnerData = [
                'lucky_draw_id' => null,
                'cash_draw_id' => 1,
                'product_draw_id' => null,
                'draw_type' => 'cash',
                'user_id' => 1,
                'position' => 1,
                'prize_amount' => 1000,
                'is_claimed' => false,
                'claim_approved' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Start transaction
            $this->db->transStart();

            // Try to insert
            $winnerId = $this->winnerModel->insert($testWinnerData);

            if ($winnerId) {
                $results['winner_creation'] = 'SUCCESS';
                $results['winner_id'] = $winnerId;

                // Clean up - delete the test winner
                $this->winnerModel->delete($winnerId);
                $results['cleanup'] = 'SUCCESS';
            } else {
                $results['winner_creation'] = 'FAILED';
                $results['validation_errors'] = $this->winnerModel->errors();
            }

            // Complete transaction
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $results['transaction'] = 'FAILED';
            } else {
                $results['transaction'] = 'SUCCESS';
            }

            // Return results as JSON
            return $this->response->setJSON([
                'success' => true,
                'test_results' => $results,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error testing database: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Approve Claims Management Page
     */
    public function approveClaims()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('admin/login')->with('error', 'Admin access required');
        }

        try {
            // Get pending claims
            $pendingClaims = $this->winnerModel->getPendingClaims();

            // Get approved claims
            $approvedClaims = $this->winnerModel->getApprovedClaims();

            // Get all winners for overview
            $allWinners = $this->winnerModel->getAllWinnersForAdmin();

            // Calculate total pending value
            $totalPendingValue = 0;
            foreach ($pendingClaims as $claim) {
                $totalPendingValue += $claim['prize_amount'];
            }

            $data = [
                'pending_claims' => $pendingClaims,
                'approved_claims' => $approvedClaims,
                'all_winners' => $allWinners,
                'total_pending_value' => $totalPendingValue
            ];

            return view('admin/approve_claims', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error loading approve claims page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading claims data: ' . $e->getMessage());
        }
    }

    // Referral Management
    public function referrals()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 20;

        $data['referrals'] = $this->referralModel->getAllReferrals($perPage, ($page - 1) * $perPage);
        $data['pager'] = $this->referralModel->pager;
        $data['stats'] = $this->referralModel->getAdminReferralStats();

        return view('admin/referrals', $data);
    }

    public function referralSettings()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = [
                'referral_bonus_amount' => $this->request->getPost('referral_bonus_amount'),
                'referral_bonus_conditions' => $this->request->getPost('referral_bonus_conditions'),
                'referral_code_length' => $this->request->getPost('referral_code_length'),
                'max_referrals_per_user' => $this->request->getPost('max_referrals_per_user')
            ];

            foreach ($data as $key => $value) {
                $this->settingModel->setSetting($key, $value);
            }

            return redirect()->back()->with('success', 'Referral settings updated successfully');
        }

        $data['settings'] = [
            'referral_bonus_amount' => $this->settingModel->getReferralBonusAmount(),
            'referral_bonus_conditions' => $this->settingModel->getReferralBonusConditions(),
            'referral_code_length' => $this->settingModel->getReferralCodeLength(),
            'max_referrals_per_user' => $this->settingModel->getMaxReferralsPerUser()
        ];

        return view('admin/referral_settings', $data);
    }

    public function approveReferralBonus($referralId)
    {
        $referral = $this->referralModel->find($referralId);
        if (!$referral) {
            return redirect()->back()->with('error', 'Referral not found');
        }

        if ($this->referralModel->approveReferralBonus($referralId, session()->get('user_id'))) {
            return redirect()->back()->with('success', 'Referral bonus approved and paid successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to approve referral bonus');
        }
    }

    // Wallet Top-up Management
    public function topupRequests()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 20;

        // Only show requests from special users (who are requesting top-ups from admin)
        $data['requests'] = $this->walletTopupRequestModel->getPendingSpecialUserRequests($perPage);
        $data['stats'] = $this->walletTopupRequestModel->getSpecialUserTopupStats();

        return view('admin/topup_requests', $data);
    }

    public function approveTopupRequest($requestId)
    {
        if ($this->request->getMethod() === 'POST') {
            $notes = $this->request->getPost('admin_notes');

            if ($this->walletTopupRequestModel->approveRequest($requestId, session()->get('user_id'), $notes)) {
                return redirect()->back()->with('success', 'Top-up request approved successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to approve top-up request');
            }
        }

        $data['request'] = $this->walletTopupRequestModel->getRequestWithUser($requestId);
        return view('admin/approve_topup_request', $data);
    }

    public function rejectTopupRequest($requestId)
    {
        if ($this->request->getMethod() === 'POST') {
            $notes = $this->request->getPost('admin_notes');

            if ($this->walletTopupRequestModel->rejectRequest($requestId, session()->get('user_id'), $notes)) {
                return redirect()->back()->with('success', 'Top-up request rejected successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to reject top-up request');
            }
        }

        $data['request'] = $this->walletTopupRequestModel->getRequestWithUser($requestId);
        return view('admin/reject_topup_request', $data);
    }

    // User Transfer Management
    public function userTransfers()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 20;

        $data['transfers'] = $this->userTransferModel->getPendingTransfers($perPage);
        $data['stats'] = $this->userTransferModel->getTransferStats();

        return view('admin/user_transfers', $data);
    }

    public function processUserTransfer($transferId)
    {
        if ($this->request->getMethod() === 'POST') {
            $notes = $this->request->getPost('admin_notes');

            if ($this->userTransferModel->processTransfer($transferId, session()->get('user_id'), $notes)) {
                return redirect()->back()->with('success', 'Transfer processed successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to process transfer');
            }
        }

        $data['transfer'] = $this->userTransferModel->getTransferWithUsers($transferId);
        return view('admin/process_user_transfer', $data);
    }

    public function rejectUserTransfer($transferId)
    {
        if ($this->request->getMethod() === 'POST') {
            $notes = $this->request->getPost('admin_notes');

            if ($this->userTransferModel->rejectTransfer($transferId, session()->get('user_id'), $notes)) {
                return redirect()->back()->with('success', 'Transfer rejected successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to reject transfer');
            }
        }

        $data['transfer'] = $this->userTransferModel->getTransferWithUsers($transferId);
        return view('admin/reject_user_transfer', $data);
    }

    // Payment Method Settings
    public function paymentSettings()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = [
                'paypal_enabled' => $this->request->getPost('paypal_enabled') == '1',
                'easypaisa_enabled' => $this->request->getPost('easypaisa_enabled') == '1',
                'jazz_cash_enabled' => $this->request->getPost('jazz_cash_enabled') == '1',
                'bank_transfer_enabled' => $this->request->getPost('bank_transfer_enabled') == '1',
                'manual_topup_enabled' => $this->request->getPost('manual_topup_enabled') == '1',
                'min_topup_amount' => $this->request->getPost('min_topup_amount'),
                'max_topup_amount' => $this->request->getPost('max_topup_amount'),
                'topup_approval_required' => $this->request->getPost('topup_approval_required') == '1',
                'user_transfer_enabled' => $this->request->getPost('user_transfer_enabled') == '1',
                'transfer_fee_percentage' => $this->request->getPost('transfer_fee_percentage'),
                'random_wallet_display' => $this->request->getPost('random_wallet_display') == '1',
                'wallet_display_count' => $this->request->getPost('wallet_display_count')
            ];

            foreach ($data as $key => $value) {
                $this->settingModel->setSetting($key, $value);
            }

            return redirect()->back()->with('success', 'Payment settings updated successfully');
        }

        $data['settings'] = [
            'paypal_enabled' => $this->settingModel->getPayPalEnabled(),
            'easypaisa_enabled' => $this->settingModel->getEasypaisaEnabled(),
            'jazz_cash_enabled' => $this->settingModel->getJazzCashEnabled(),
            'bank_transfer_enabled' => $this->settingModel->getBankTransferEnabled(),
            'manual_topup_enabled' => $this->settingModel->getManualTopupEnabled(),
            'min_topup_amount' => $this->settingModel->getMinTopupAmount(),
            'max_topup_amount' => $this->settingModel->getMaxTopupAmount(),
            'topup_approval_required' => $this->settingModel->getTopupApprovalRequired(),
            'user_transfer_enabled' => $this->settingModel->getUserTransferEnabled(),
            'transfer_fee_percentage' => $this->settingModel->getTransferFeePercentage(),
            'random_wallet_display' => $this->settingModel->getRandomWalletDisplay(),
            'wallet_display_count' => $this->settingModel->getWalletDisplayCount()
        ];

        return view('admin/payment_settings', $data);
    }

    // User Wallet Management
    public function userWallets()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 20;

        $data['users'] = $this->userModel->getUsersWithWalletDetailsForAdmin($perPage);

        return view('admin/user_wallets', $data);
    }

    public function editUserWallet($userId)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = [
                'wallet_name' => $this->request->getPost('wallet_name'),
                'wallet_number' => $this->request->getPost('wallet_number'),
                'wallet_type' => $this->request->getPost('wallet_type')
            ];

            if ($this->userModel->updateWalletDetails($userId, $data['wallet_name'], $data['wallet_number'], $data['wallet_type'])) {
                return redirect()->back()->with('success', 'User wallet details updated successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to update wallet details');
            }
        }

        $data['user'] = $this->userModel->find($userId);
        $data['wallet'] = $this->walletModel->getUserWallet($userId);

        return view('admin/edit_user_wallet', $data);
    }

    /**
     * Manage special users (users who can display wallet information for topups)
     */
    public function specialUsers()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 20;

        $data['users'] = $this->userModel->getUsersWithWalletDetails($perPage, ($page - 1) * $perPage);
        $data['pager'] = $this->userModel->pager;
        $data['stats'] = [
            'total_users' => $this->userModel->countAllResults(),
            'users_with_wallets' => $this->userModel->countUsersWithWalletDetails(),
            'active_wallets' => $this->userModel->countActiveWalletUsers()
        ];

        return view('admin/special_users', $data);
    }

    /**
     * Edit special user wallet information
     */
    public function editSpecialUser($userId)
    {
        if ($this->request->getMethod() === 'POST') {
            $walletName = $this->request->getPost('wallet_name');
            $walletNumber = $this->request->getPost('wallet_number');
            $walletType = $this->request->getPost('wallet_type');
            $bankName = $this->request->getPost('bank_name');
            $walletActive = $this->request->getPost('is_active') ? true : false;
            $userStatus = $this->request->getPost('user_status') ?? 'active';

            if ($this->userModel->updateSpecialUserWallet($userId, $walletName, $walletNumber, $walletType, $bankName, $walletActive, $userStatus)) {
                return redirect()->back()->with('success', 'Special user information updated successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to update user information');
            }
        }

        $data['user'] = $this->userModel->find($userId);
        $data['wallet'] = $this->walletModel->getUserWallet($userId);

        return view('admin/edit_special_user', $data);
    }

    /**
     * Admin profile page
     */
    public function profile()
    {
        $adminId = session()->get('user_id');
        $admin = $this->userModel->find($adminId);

        $data = [
            'title' => 'Admin Profile',
            'admin' => $admin
        ];

        return view('admin/profile', $data);
    }

    /**
     * Update admin profile
     */
    public function updateProfile()
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $adminId = session()->get('user_id');
        $admin = $this->userModel->find($adminId);

        $fullName = $this->request->getPost('full_name');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');

        // Validate email uniqueness
        $existingUser = $this->userModel->where('email', $email)->where('id !=', $adminId)->first();
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

            $newName = $profileImage->getRandomName();
            $profileImage->move($uploadPath, $newName);

            $updateData['profile_image'] = $newName;
        }

        // Skip validation for profile updates since we're not updating password
        $this->userModel->skipValidation(true);

        if ($this->userModel->update($adminId, $updateData)) {
            return redirect()->back()->with('success', 'Profile updated successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to update profile');
        }
    }

    /**
     * Change admin password
     */
    public function changePassword()
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $adminId = session()->get('user_id');
        $admin = $this->userModel->find($adminId);

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Verify current password
        if (!password_verify($currentPassword, $admin['password'])) {
            return redirect()->back()->with('error', 'Current password is incorrect');
        }

        // Check if new passwords match
        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'New passwords do not match');
        }

        // Validate new password length
        if (strlen($newPassword) < 8) {
            return redirect()->back()->with('error', 'New password must be at least 8 characters long');
        }

        // Update password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        if ($this->userModel->update($adminId, ['password' => $hashedPassword])) {
            return redirect()->back()->with('success', 'Password changed successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to change password');
        }
    }

    /**
     * Application settings page
     */
    public function applicationSettings()
    {
        $settings = $this->settingModel->getAllSettings();

        $data = [
            'title' => 'Application Settings',
            'settings' => $settings
        ];

        return view('admin/settings', $data);
    }

    /**
     * Admin wallet information management
     */
    public function adminWalletInfo()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        $adminId = session()->get('user_id');
        $admin = $this->userModel->find($adminId);

        if ($this->request->getMethod() === 'POST') {
            $walletName = $this->request->getPost('wallet_name');
            $walletNumber = $this->request->getPost('wallet_number');
            $walletType = $this->request->getPost('wallet_type');
            $bankName = $this->request->getPost('bank_name');

            // Update admin wallet information
            $updateData = [
                'wallet_name' => $walletName,
                'wallet_number' => $walletNumber,
                'wallet_type' => $walletType,
                'bank_name' => $bankName,
                'wallet_active' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->userModel->update($adminId, $updateData)) {
                return redirect()->back()->with('success', 'Admin wallet information updated successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to update wallet information');
            }
        }

        $data = [
            'title' => 'Admin Wallet Information',
            'admin' => $admin
        ];

        return view('admin/admin_wallet_info', $data);
    }

    /**
     * Save application settings
     */
    public function saveSettings()
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        try {
            // Website Settings
            $this->settingModel->setSetting('website_name', $this->request->getPost('website_name'));
            $this->settingModel->setSetting('contact_email', $this->request->getPost('contact_email'));
            $this->settingModel->setSetting('contact_phone', $this->request->getPost('contact_phone'));
            
            // Additional Contact Settings
            $this->settingModel->setSetting('contact_address', $this->request->getPost('contact_address'));
            $this->settingModel->setSetting('contact_working_hours', $this->request->getPost('contact_working_hours'));
            
            // Social Media Settings
            $this->settingModel->setSetting('facebook_url', $this->request->getPost('facebook_url'));
            $this->settingModel->setSetting('twitter_url', $this->request->getPost('twitter_url'));
            $this->settingModel->setSetting('instagram_url', $this->request->getPost('instagram_url'));
            $this->settingModel->setSetting('linkedin_url', $this->request->getPost('linkedin_url'));
            $this->settingModel->setSetting('youtube_url', $this->request->getPost('youtube_url'));
            
            // Footer Settings
            $this->settingModel->setSetting('footer_description', $this->request->getPost('footer_description'));
            $this->settingModel->setSetting('footer_copyright', $this->request->getPost('footer_copyright'));

            // Referral System Settings
            $this->settingModel->setSetting('referral_bonus_amount', $this->request->getPost('referral_bonus_amount'));
            
            // Commission Settings
            $this->settingModel->setSetting('special_user_commission', $this->request->getPost('special_user_commission'));



            // Handle file uploads
            $this->handleSettingsFileUploads();

            return redirect()->back()->with('success', 'Settings saved successfully');
        } catch (\Exception $e) {
            log_message('error', 'Failed to save settings: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to save settings: ' . $e->getMessage());
        }
    }

    /**
     * Handle file uploads for settings
     */
    private function handleSettingsFileUploads()
    {
        $uploadPath = 'uploads/settings/';

        // Create directory if it doesn't exist
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Handle website logo upload
        $websiteLogo = $this->request->getFile('website_logo');
        if ($websiteLogo && $websiteLogo->isValid() && !$websiteLogo->hasMoved()) {
            $newName = 'logo_' . time() . '.' . $websiteLogo->getExtension();
            $websiteLogo->move($uploadPath, $newName);
            $this->settingModel->setSetting('website_logo', $newName);
        }

        // Handle favicon upload
        $favicon = $this->request->getFile('favicon');
        if ($favicon && $favicon->isValid() && !$favicon->hasMoved()) {
            $newName = 'favicon_' . time() . '.' . $favicon->getExtension();
            $favicon->move($uploadPath, $newName);
            $this->settingModel->setSetting('favicon', $newName);
        }
    }

    /**
     * Make a normal user a special user
     */
    public function makeSpecialUser($userId)
    {
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        if ($user['is_special_user']) {
            return redirect()->back()->with('error', 'User is already a special user');
        }

        // Use direct database query to bypass field protection
        $db = \Config\Database::connect();

        // Update user to be a special user with basic wallet information
        $updateData = [
            'is_special_user' => 1,
            'wallet_active' => 1,
            'wallet_name' => $user['full_name'],
            'wallet_number' => 'Pending',
            'wallet_type' => 'easypaisa',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $db->table('users')->where('id', $userId)->update($updateData);

        // Check if user already has a wallet record
        $existingWallet = $this->walletModel->getUserWallet($userId);

        if (!$existingWallet) {
            // Create a basic wallet record
            $this->walletModel->insert([
                'user_id' => $userId,
                'balance' => 0,
                'wallet_name' => $user['full_name'],
                'wallet_number' => 'Pending',
                'wallet_type' => 'easypaisa',
                'bank_name' => null,
                'wallet_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            // Update existing wallet to be active
            $this->walletModel->update($existingWallet['id'], [
                'wallet_active' => 1,
                'wallet_name' => $user['full_name'],
                'wallet_number' => 'Pending',
                'wallet_type' => 'easypaisa'
            ]);
        }

        return redirect()->back()->with('success', 'User has been made a special user successfully. They can now update their wallet details in their profile.');
    }

    /**
     * Remove special user status from a user
     */
    public function removeSpecialUser($userId)
    {
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        if (!$user['is_special_user']) {
            return redirect()->back()->with('error', 'User is not a special user');
        }

        // Use direct database query to bypass field protection
        $db = \Config\Database::connect();

        // Update user to remove special user status and clear wallet info
        $updateData = [
            'is_special_user' => 0,
            'wallet_active' => 0,
            'wallet_name' => null,
            'wallet_number' => null,
            'wallet_type' => null,
            'bank_name' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $db->table('users')->where('id', $userId)->update($updateData);

        // Deactivate their wallet
        $wallet = $this->walletModel->getUserWallet($userId);
        if ($wallet) {
            $this->walletModel->update($wallet['id'], [
                'wallet_active' => 0
            ]);
        }

        return redirect()->back()->with('success', 'Special user status has been removed successfully.');
    }

    /**
     * Get header notifications for dropdown (AJAX)
     */
    public function getHeaderNotifications()
    {
        if (!session()->get('is_admin')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $limit = (int)($this->request->getGet('limit') ?? 10);
        $unreadOnly = $this->request->getGet('unread_only') === 'true';

        // Get notifications directly from database
        $db = \Config\Database::connect();
        $builder = $db->table('notifications')
            ->select('notifications.*, u.username, u.full_name, u.email')
            ->join('users u', 'u.id = notifications.user_id', 'left')
            ->where('notifications.admin_id IS NULL'); // Admin notifications

        if ($unreadOnly) {
            $builder->where('notifications.is_read', false);
        }

        $notifications = $builder->orderBy('notifications.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        // Get unread count
        $unreadCount = $db->table('notifications')
            ->where('admin_id IS NULL')
            ->where('is_read', false)
            ->countAllResults();

        // Add styling information
        foreach ($notifications as &$notification) {
            $notification['style'] = $this->notificationService->getNotificationStyle($notification['type']);
            $notification['priority_style'] = $this->notificationService->getPriorityStyle($notification['priority']);
            $notification['time_ago'] = $this->timeAgo($notification['created_at']);
        }

        return $this->response->setJSON([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    // Contact Management Methods
    public function contactSubmissions()
    {
        $data = [
            'pending_submissions' => $this->contactModel->getPendingSubmissions(),
            'processed_submissions' => $this->contactModel->getProcessedSubmissions(),
            'stats' => $this->contactModel->getSubmissionStats()
        ];

        return view('admin/contact_submissions', $data);
    }

    public function viewContactSubmission($id)
    {
        $submission = $this->contactModel->find($id);

        if (!$submission) {
            return redirect()->back()->with('error', 'Contact submission not found');
        }

        return view('admin/view_contact_submission', ['submission' => $submission]);
    }

    public function updateContactSubmission()
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $adminNotes = $this->request->getPost('admin_notes');

        $submission = $this->contactModel->find($id);
        if (!$submission) {
            return redirect()->back()->with('error', 'Contact submission not found');
        }

        $updateData = [
            'status' => $status,
            'admin_notes' => $adminNotes
        ];

        if ($this->contactModel->update($id, $updateData)) {
            // Send notification to user if replied
            if ($status === 'replied') {
                try {
                    $this->notificationService->sendContactReplyNotification($submission, $adminNotes);
                } catch (\Exception $e) {
                    log_message('error', 'Failed to send contact reply notification: ' . $e->getMessage());
                }
            }

            return redirect()->back()->with('success', 'Contact submission updated successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to update contact submission');
        }
    }

    public function deleteContactSubmission($id)
    {
        $submission = $this->contactModel->find($id);

        if (!$submission) {
            return redirect()->back()->with('error', 'Contact submission not found');
        }

        if ($this->contactModel->delete($id)) {
            return redirect()->back()->with('success', 'Contact submission deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete contact submission');
        }
    }


}
