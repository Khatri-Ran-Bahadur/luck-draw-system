<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Libraries\GoogleOAuth;
use CodeIgniter\HTTP\RedirectResponse;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (session()->get('user_id')) {
            return redirect()->to(base_url('dashboard'));
        }
        return redirect()->to(base_url('login'));
    }

    public function login()
    {

        if ($this->request->getMethod() == 'POST') {
            // Validate required fields

            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $errors = [];



            if (empty($email)) {
                $errors['email'] = 'Email is required';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Please enter a valid email address';
            }

            if (empty($password)) {
                $errors['password'] = 'Password is required';
            }

            if (!empty($errors)) {
                // Store old input in session for form repopulation
                session()->setFlashdata('old_input', ['email' => $email]);
                return view('auth/login', ['errors' => $errors]);
            }

            $user = $this->userModel->findByEmail($email);

            if ($user && $this->userModel->verifyPassword($password, $user['password'])) {

                if ($user['status'] === 'suspended') {
                    session()->setFlashdata('error', 'Your account has been suspended. Please contact administrator for assistance.');
                    return redirect()->back()->withInput();
                }

                if ($user['status'] === 'inactive') {
                    session()->setFlashdata('error', 'Your account is inactive. Please contact administrator to activate your account.');
                    return redirect()->back()->withInput();
                }

                session()->set([
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'full_name' => $user['full_name'],
                    'is_admin' => $user['is_admin']
                ]);

                session()->setFlashdata('success', 'Welcome back, ' . $user['full_name'] . '!');

                if ($user['is_admin']) {
                    return redirect()->to(base_url('admin/dashboard'));
                }

                return redirect()->to(base_url('dashboard'));
            } else {
                $errors = ['email' => 'Invalid email or password'];
                // Store old input in session for form repopulation
                session()->setFlashdata('old_input', ['email' => $email]);
                return view('auth/login', ['errors' => $errors]);
            }
        }

        return view('auth/login');
    }

    public function register()
    {
        if ($this->request->getMethod() == 'POST') {
            // Get form data
            $formData = [
                'username' => $this->request->getPost('username'),
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'confirm_password' => $this->request->getPost('confirm_password'),
                'full_name' => $this->request->getPost('full_name'),
                'phone' => $this->request->getPost('phone'),
                'referral_code' => $this->request->getPost('referral_code')
            ];

            // Check if passwords match
            if ($formData['password'] !== $formData['confirm_password']) {
                $errors = ['confirm_password' => 'Passwords do not match'];
                // Store old input in session for form repopulation
                session()->setFlashdata('old_input', $formData);
                return view('auth/register', ['errors' => $errors]);
            }

            // Handle referral code
            $referrerId = null;
            $referralCode = null;

            if (!empty($formData['referral_code'])) {
                $referrer = $this->userModel->findByReferralCode($formData['referral_code']);
                if ($referrer && $referrer['id'] != session()->get('user_id')) {
                    $referrerId = $referrer['id'];
                    $referralCode = $formData['referral_code'];
                }
            }

            $data = [
                'username' => $formData['username'],
                'email' => $formData['email'],
                'password' => $formData['password'],
                'full_name' => $formData['full_name'],
                'phone' => $formData['phone'],
                'is_admin' => false,
                'status' => 'active',
                'referred_by' => $referrerId,
                'referral_code' => $this->userModel->generateReferralCode()
            ];

            if (!$this->userModel->validate($data)) {
                $errors = $this->userModel->errors();
                // Store old input in session for form repopulation
                session()->setFlashdata('old_input', $formData);
                return view('auth/register', ['errors' => $errors]);
            }

            $data['password'] = $this->userModel->hashPassword($data['password']);

            $userId = $this->userModel->insert($data);
            if ($userId) {
                // Get the newly created user
                $user = $this->userModel->find($userId);

                // Handle referral bonus if user was referred
                if ($referrerId && $referralCode) {
                    $this->processReferralBonus($referrerId, $userId, $referralCode);
                }

                // Automatically log in the user
                session()->set([
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'full_name' => $user['full_name'],
                    'is_admin' => $user['is_admin']
                ]);

                session()->setFlashdata('success', 'Welcome to Lucky Draw System, ' . $user['full_name'] . '! Your account has been created successfully.');

                // Redirect based on user role
                if ($user['is_admin']) {
                    return redirect()->to(base_url('admin/dashboard'));
                }

                return redirect()->to(base_url('dashboard'));
            } else {
                session()->setFlashdata('error', 'Registration failed. Please try again.');
                // Store old input in session for form repopulation
                session()->setFlashdata('old_input', $formData);
                return view('auth/register', ['errors' => ['general' => 'Registration failed. Please try again.']]);
            }
        }

        return view('auth/register');
    }

    /**
     * Process referral bonus for new user registration
     */
    private function processReferralBonus($referrerId, $referredId, $referralCode)
    {
        try {
            $settingModel = new \App\Models\SettingModel();
            $referralModel = new \App\Models\ReferralModel();
            $walletModel = new \App\Models\WalletModel();
            $walletTransactionModel = new \App\Models\WalletTransactionModel();

            // Get referral bonus amount from settings
            $bonusAmount = $settingModel->getReferralBonusAmount();

            // Create referral record
            $referralId = $referralModel->createReferral($referrerId, $referredId, $referralCode, $bonusAmount);

            if ($referralId) {
                // Update referral status to active
                $referralModel->updateReferralStatus($referralId, 'active');

                // Get referrer's wallet
                $referrerWallet = $walletModel->getUserWallet($referrerId);

                if ($referrerWallet) {
                    // Add bonus to referrer's wallet
                    $walletModel->updateBalance($referrerWallet['id'], $bonusAmount, 'add');

                    // Record wallet transaction
                    $walletTransactionModel->insert([
                        'wallet_id' => $referrerWallet['id'],
                        'type' => 'referral_bonus',
                        'amount' => $bonusAmount,
                        'payment_method' => 'system',
                        'payment_reference' => 'Referral bonus for ' . $referralCode,
                        'status' => 'completed',
                        'balance_before' => $referrerWallet['balance'],
                        'balance_after' => $referrerWallet['balance'] + $bonusAmount,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    // Mark referral bonus as paid
                    $referralModel->markBonusAsPaid($referralId);

                    // Update user's referral bonus earned
                    $this->userModel->addReferralBonus($referrerId, $bonusAmount);

                    // Send notification to referrer
                    $notificationService = new \App\Libraries\NotificationService();
                    $notificationService->sendSystemMessage(
                        $referrerId,
                        'Referral Bonus Earned! 🎉',
                        'Congratulations! You earned Rs. ' . number_format($bonusAmount, 2) . ' for referring a new user with code: ' . $referralCode,
                        'high'
                    );
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error processing referral bonus: ' . $e->getMessage());
        }
    }

    /**
     * Handle referral link visits
     */
    public function referral($referralCode)
    {
        // Store referral code in session for registration
        session()->set('referral_code', $referralCode);

        // Redirect to registration page
        return redirect()->to(base_url('register'));
    }

    /**
     * Show user's referral statistics
     */
    public function referralStats()
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'));
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        $referralStats = $this->userModel->getReferralStats($userId);
        $referredUsers = $this->userModel->getReferredUsers($userId);

        $data = [
            'user' => $user,
            'referral_stats' => $referralStats,
            'referred_users' => $referredUsers
        ];

        return view('auth/referral_stats', $data);
    }

    /**
     * Show user's referrals
     */
    public function myReferrals()
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'));
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        $referralStats = $this->userModel->getReferralStats($userId);
        $referredUsers = $this->userModel->getReferredUsers($userId);

        $data = [
            'user' => $user,
            'referral_stats' => $referralStats,
            'referred_users' => $referredUsers
        ];

        return view('auth/my_referrals', $data);
    }

    public function logout()
    {
        session()->destroy();
        session()->setFlashdata('success', 'You have been logged out successfully.');
        return redirect()->to(base_url('login'));
    }

    // Profile functionality moved to Home controller
    // Use /profile route for profile management

    public function changePassword()
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url('login'));
        }

        if ($this->request->getMethod() === 'POST') {
            $userId = session()->get('user_id');
            $currentPassword = $this->request->getPost('current_password');
            $newPassword = $this->request->getPost('new_password');
            $confirmPassword = $this->request->getPost('confirm_password');

            $user = $this->userModel->find($userId);

            if (!$this->userModel->verifyPassword($currentPassword, $user['password'])) {
                session()->setFlashdata('error', 'Current password is incorrect.');
                return redirect()->back();
            }

            if ($newPassword !== $confirmPassword) {
                session()->setFlashdata('error', 'New passwords do not match.');
                return redirect()->back();
            }

            if (strlen($newPassword) < 6) {
                session()->setFlashdata('error', 'New password must be at least 6 characters long.');
                return redirect()->back();
            }

            $hashedPassword = $this->userModel->hashPassword($newPassword);

            if ($this->userModel->update($userId, ['password' => $hashedPassword])) {
                session()->setFlashdata('success', 'Password changed successfully!');
                return redirect()->to(base_url('profile'));
            } else {
                session()->setFlashdata('error', 'Failed to change password. Please try again.');
            }
        }

        return view('auth/change_password');
    }

    public function forgotPassword()
    {
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'email' => 'required|valid_email'
            ];

            if (!$this->validate($rules)) {
                return view('auth/forgot_password', [
                    'validation' => $this->validator
                ]);
            }

            $email = $this->request->getPost('email');
            $user = $this->userModel->where('email', $email)->first();

            if (!$user) {
                session()->setFlashdata('error', 'No account found with that email address.');
                return redirect()->back()->withInput();
            }

            // Generate reset token
            $token = bin2hex(random_bytes(32));
            $this->userModel->update($user['id'], [
                'reset_token' => $token,
                'reset_token_expires' => date('Y-m-d H:i:s', strtotime('+1 hour'))
            ]);

            // Send reset email
            try {
                $emailService = \Config\Services::email();

                // Set from email
                $emailService->setFrom('ranbdrkc201@gmail.com', 'Lucky Draw System');
                $emailService->setTo($user['email']);
                $emailService->setSubject('🔐 Reset Your Password - Lucky Draw System');

                // Generate beautiful HTML email
                $emailMessage = view('emails/reset_password', [
                    'token' => $token,
                    'name' => $user['full_name']
                ]);

                $emailService->setMessage($emailMessage);

                if ($emailService->send()) {
                    log_message('info', 'Password reset email sent to: ' . $user['email']);
                    session()->setFlashdata('success', 'Password reset instructions have been sent to your email address. Please check your inbox and spam folder.');
                    return redirect()->to(current_url());
                } else {
                    log_message('error', 'Failed to send password reset email to: ' . $user['email']);
                    log_message('error', 'Email error: ' . $emailService->printDebugger(['headers']));
                    session()->setFlashdata('error', 'Failed to send reset instructions. Please try again later or contact support.');
                    return redirect()->back()->withInput();
                }
            } catch (\Exception $e) {
                log_message('error', 'Email exception: ' . $e->getMessage());
                log_message('error', 'Email trace: ' . $e->getTraceAsString());
                session()->setFlashdata('error', 'Email service is currently unavailable. Please try again later.');
                return redirect()->back()->withInput();
            }
        }

        return view('auth/forgot_password');
    }

    public function resetPassword($token = null)
    {
        if (!$token) {
            return redirect()->to(base_url('login'));
        }

        $user = $this->userModel->where('reset_token', $token)
            ->where('reset_token_expires >', date('Y-m-d H:i:s'))
            ->first();

        if (!$user) {
            session()->setFlashdata('error', 'Invalid or expired reset token.');
            return redirect()->to(base_url('forgot-password'));
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'password' => 'required|min_length[6]',
                'confirm_password' => 'required|matches[password]'
            ];

            if (!$this->validate($rules)) {
                return view('auth/reset_password', [
                    'validation' => $this->validator,
                    'token' => $token
                ]);
            }

            // Update password
            $this->userModel->update($user['id'], [
                'password' => $this->userModel->hashPassword($this->request->getPost('password')),
                'reset_token' => null,
                'reset_token_expires' => null
            ]);

            session()->setFlashdata('success', 'Password has been reset successfully. Please login with your new password.');
            return redirect()->to(base_url('login'));
        }

        return view('auth/reset_password', [
            'token' => $token
        ]);
    }

    /**
     * Redirect to Google OAuth
     */
    public function googleLogin()
    {
        try {
            $googleOAuth = new GoogleOAuth();
            $authUrl = $googleOAuth->getAuthorizationUrl();
            return redirect()->to($authUrl);
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Failed to initialize Google login: ' . $e->getMessage());
            return redirect()->to(base_url('login'));
        }
    }

    /**
     * Handle Google OAuth callback
     */
    public function googleCallback()
    {
        $code = $this->request->getGet('code');
        $state = $this->request->getGet('state');
        $error = $this->request->getGet('error');


        // Check for OAuth errors
        if ($error) {
            session()->setFlashdata('error', 'Google login was cancelled or failed.');
            return redirect()->to(base_url('login'));
        }

        if (!$code) {
            session()->setFlashdata('error', 'Authorization code not received from Google.');
            return redirect()->to(base_url('login'));
        }

        try {
            $googleOAuth = new GoogleOAuth();
            log_message('debug', 'Google callback - Code: ' . $code);
            log_message('debug', 'Google callback - State: ' . $state);
            $googleUser = $googleOAuth->handleCallback($code, $state);

            // Find or create user
            $referralCode = session()->get('referral_code');
            $user = $this->userModel->findOrCreateByGoogle($googleUser, $referralCode);

            // Clear referral code from session after processing
            if ($referralCode) {
                session()->remove('referral_code');
            }

            // Debug logging

            if (!$user) {
                session()->setFlashdata('error', 'Failed to create or find user account.');
                return redirect()->to(base_url('login'));
            }

            // Check if user is active
            if ($user['status'] !== 'active') {
                session()->setFlashdata('error', 'Your account is not active. Please contact administrator.');
                return redirect()->to(base_url('login'));
            }

            // Set session data
            session()->set([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'full_name' => $user['full_name'],
                'is_admin' => $user['is_admin']
            ]);

            session()->setFlashdata('success', 'Welcome back, ' . $user['full_name'] . '!');

            // Redirect based on user role
            if ($user['is_admin']) {
                return redirect()->to(base_url('admin/dashboard'));
            }

            return redirect()->to(base_url('dashboard'));
        } catch (\Exception $e) {

            // More specific error messages for debugging
            if (strpos($e->getMessage(), 'redirect_uri_mismatch') !== false) {
                session()->setFlashdata('error', 'Google OAuth redirect URI mismatch. Please check your Google Console settings.');
            } elseif (strpos($e->getMessage(), 'invalid_client') !== false) {
                session()->setFlashdata('error', 'Invalid Google OAuth client credentials.');
            } else {
                session()->setFlashdata('error', 'Google login failed: ' . $e->getMessage());
            }

            return redirect()->to(base_url('login'));
        }
    }
}
