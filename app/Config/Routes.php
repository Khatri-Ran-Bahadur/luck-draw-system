<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Home routes
$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');
$routes->post('contact', 'Home::contact');
$routes->get('terms', 'Home::terms');
$routes->get('privacy', 'Home::privacy');
$routes->get('faq', 'Home::faq');
$routes->get('winners', 'Home::winners');
$routes->get('search', 'Home::search');
$routes->get('maintenance', 'Home::maintenance');

// Cash and Product Draw routes
$routes->get('cash-draws', 'Home::cashDraws');
$routes->get('product-draws', 'Home::productDraws');
$routes->get('cash-draw/(:num)', 'CashDraw::view/$1');
$routes->get('product-draw/(:num)', 'ProductDraw::view/$1');
$routes->post('cash-draw/enter/(:num)', 'CashDraw::enter/$1');
$routes->post('product-draw/enter/(:num)', 'ProductDraw::enter/$1');

// Auth routes
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::login');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::register');
$routes->get('logout', 'Auth::logout');
$routes->get('change-password', 'Auth::changePassword');
$routes->post('change-password', 'Auth::changePassword');
$routes->get('forgot-password', 'Auth::forgotPassword');
$routes->post('forgot-password', 'Auth::forgotPassword');
$routes->get('reset-password/(:any)', 'Auth::resetPassword/$1');
$routes->post('reset-password/(:any)', 'Auth::resetPassword/$1');

// Referral routes
$routes->get('referral/(:any)', 'Auth::referral/$1');
$routes->get('my-referrals', 'Auth::myReferrals');
$routes->get('referral-stats', 'Auth::referralStats');

// Google OAuth routes
$routes->get('auth/google', 'Auth::googleLogin');
$routes->get('google/callback', 'Auth::googleCallback');

// Notification routes
$routes->group('notifications', function ($routes) {
    $routes->get('user', 'Notifications::getUserNotifications');
    $routes->get('admin', 'Notifications::getAdminNotifications');
    $routes->post('mark-read/(:num)', 'Notifications::markAsRead/$1');
    $routes->post('mark-all-read', 'Notifications::markAllAsRead');
    $routes->delete('(:num)', 'Notifications::delete/$1');
    $routes->get('counts', 'Notifications::getCounts');
    $routes->post('send-admin-message', 'Notifications::sendAdminMessage');
    $routes->post('broadcast', 'Notifications::broadcast');
});

// Lucky Draw Routes
$routes->get('lucky-draw/(:num)', 'LuckyDraw::view/$1');
$routes->post('lucky-draw/enter/(:num)', 'LuckyDraw::enter/$1');

// Winner Claim Routes
$routes->get('winner/(:num)', 'LuckyDraw::viewWinner/$1');
$routes->post('lucky-draw/claim/(:num)', 'LuckyDraw::claim/$1');
$routes->get('my-winnings', 'LuckyDraw::myWinnings');
$routes->get('draw/(:any)/(:num)', 'LuckyDraw::viewDraw/$1/$2');

// Wallet routes
$routes->get('wallet', 'Wallet::index');
$routes->get('wallet/topup', 'Wallet::topup');
$routes->post('wallet/topup', 'Wallet::topup');

$routes->get('wallet/withdraw', 'Wallet::withdraw');
$routes->post('wallet/withdraw', 'Wallet::withdraw');

// Special user topup approval routes
$routes->get('wallet/approve-topup/(:num)', 'Wallet::approveTopupRequest/$1');
$routes->post('wallet/approve-topup/(:num)', 'Wallet::approveTopupRequest/$1');
$routes->get('wallet/reject-topup/(:num)', 'Wallet::rejectTopupRequest/$1');
$routes->post('wallet/reject-topup/(:num)', 'Wallet::rejectTopupRequest/$1');
$routes->get('wallet/profile', 'Wallet::profile');
$routes->post('wallet/update-profile', 'Wallet::updateProfile');
$routes->post('wallet/update-wallet', 'Wallet::updateWallet');

$routes->get('wallet/transactions', 'Wallet::transactions');
$routes->get('wallet/user-requests', 'Wallet::userRequests');

// PayPal payment routes
$routes->get('wallet/paypal/process', 'Wallet::paypalProcess');
$routes->get('wallet/paypal/success', 'Wallet::paypalSuccess');
$routes->get('wallet/paypal/cancel', 'Wallet::paypalCancel');

// Easypaisa payment routes
$routes->get('wallet/easypaisa/process', 'Wallet::easypaisaProcess');
$routes->get('wallet/easypaisa/success', 'Wallet::easypaisaSuccess');
$routes->get('wallet/easypaisa/cancel', 'Wallet::easypaisaCancel');

// Payment success page
$routes->get('wallet/payment/success', 'Wallet::paymentSuccess');

// Payment status route
$routes->get('wallet/payment-status', 'Wallet::paymentStatus');

// Payment routes
$routes->get('payment/paypal/(:num)', 'Payment::paypal/$1');
$routes->post('payment/process-paypal', 'Payment::processPaypal');
$routes->get('payment/success/(:num)', 'Payment::success/$1');
$routes->get('payment/cancel/(:num)', 'Payment::cancel/$1');
$routes->post('payment/webhook', 'Payment::webhook');
$routes->get('payment/verify/(:num)', 'Payment::verifyPayment/$1');

// Easypaisa Payment Gateway (Wallet Topup Only)
$routes->get('easypaisa/complete', 'Easypaisa::complete');

// Smart dashboard route - redirects based on user role
$routes->get('dashboard', 'Home::smartDashboard');

// User dashboard
$routes->get('user-dashboard', 'Home::dashboard');
$routes->get('profile', 'Home::profile');
$routes->post('update-profile', 'Home::updateProfile');
$routes->post('update-wallet', 'Home::updateWallet');

// Admin routes
$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    $routes->get('dashboard', 'Admin::index');
    $routes->get('profile', 'Admin::profile');
    $routes->post('update-profile', 'Admin::updateProfile');
    $routes->post('change-password', 'Admin::changePassword');

    // Admin wallet management
    $routes->get('admin-wallet-info', 'Admin::adminWalletInfo');
    $routes->post('admin-wallet-info', 'Admin::adminWalletInfo');
    $routes->get('application-settings', 'Admin::applicationSettings');
    $routes->post('save-application-settings', 'Admin::saveSettings');

    // Admin Management
    $routes->get('admins', 'Admin::admins');
    $routes->get('admins/create', 'Admin::createAdmin');
    $routes->post('admins/create', 'Admin::createAdmin');
    $routes->get('admins/edit/(:num)', 'Admin::editAdmin/$1');
    $routes->post('admins/edit/(:num)', 'Admin::editAdmin/$1');
    $routes->get('admins/delete/(:num)', 'Admin::deleteAdmin/$1');

    $routes->get('lucky-draws', 'Admin::luckyDraws');
    $routes->get('lucky-draws/create', 'Admin::createLuckyDraw');
    $routes->post('lucky-draws/create', 'Admin::createLuckyDraw');
    $routes->get('lucky-draws/edit/(:num)', 'Admin::editLuckyDraw/$1');
    $routes->post('lucky-draws/edit/(:num)', 'Admin::editLuckyDraw/$1');
    $routes->get('lucky-draws/delete/(:num)', 'Admin::deleteLuckyDraw/$1');
    $routes->get('lucky-draws/select-winners/(:num)', 'Admin::selectWinners/$1');
    $routes->post('lucky-draws/select-winners/(:num)', 'Admin::selectWinners/$1');

    $routes->get('products', 'Admin::products');
    $routes->get('products/create', 'Admin::createProduct');
    $routes->post('products/create', 'Admin::createProduct');
    $routes->get('products/edit/(:num)', 'Admin::editProduct/$1');
    $routes->post('products/edit/(:num)', 'Admin::editProduct/$1');
    $routes->get('products/delete/(:num)', 'Admin::deleteProduct/$1');

    $routes->get('transactions', 'Admin::transactions');
    $routes->get('transaction-details/(:num)', 'Admin::transactionDetails/$1');

    $routes->get('notifications', 'Admin::notifications');
    $routes->get('header-notifications', 'Admin::getHeaderNotifications');

    // Cash Draws Management
    $routes->get('cash-draws', 'Admin::cashDraws');
    $routes->get('cash-draws/create', 'Admin::createCashDraw');
    $routes->post('cash-draws/create', 'Admin::createCashDraw');
    $routes->get('cash-draws/edit/(:num)', 'Admin::editCashDraw/$1');
    $routes->post('cash-draws/edit/(:num)', 'Admin::editCashDraw/$1');
    $routes->get('cash-draws/view/(:num)', 'Admin::viewCashDrawDetails/$1');
    $routes->get('cash-draws/delete/(:num)', 'Admin::deleteCashDraw/$1');
    $routes->get('select-winners/(:num)', 'Admin::selectWinners/$1');
    $routes->post('select-winners/(:num)', 'Admin::selectWinners/$1');
    $routes->post('select-random-winners/(:num)', 'Admin::selectRandomWinners/$1');

    // Product Draws Management
    $routes->get('product-draws', 'Admin::productDraws');
    $routes->get('product-draws/create', 'Admin::createProductDraw');
    $routes->post('product-draws/create', 'Admin::createProductDraw');
    $routes->get('product-draws/edit/(:num)', 'Admin::editProductDraw/$1');
    $routes->post('product-draws/edit/(:num)', 'Admin::editProductDraw/$1');
    $routes->get('product-draws/view/(:num)', 'Admin::viewProductDrawDetails/$1');
    $routes->get('product-draws/delete/(:num)', 'Admin::deleteProductDraw/$1');
    $routes->get('select-product-winners/(:num)', 'Admin::selectProductWinners/$1');
    $routes->post('select-product-winners/(:num)', 'Admin::selectProductWinners/$1');
    $routes->post('select-random-product-winners/(:num)', 'Admin::selectRandomProductWinners/$1');

    $routes->get('winners', 'Admin::winners');
    $routes->get('winners/approve/(:num)', 'Admin::approveClaim/$1');
    $routes->get('winners/reject/(:num)', 'Admin::rejectClaim/$1');

    // Prize Claims Management
    $routes->get('approve-claims', 'Admin::approveClaims');

    // Finance Management
    $routes->get('withdraw-requests', 'Admin::withdrawRequests');
    $routes->post('approve-withdrawal/(:num)', 'Admin::approveWithdrawal/$1');
    $routes->post('reject-withdrawal/(:num)', 'Admin::rejectWithdrawal/$1');

    $routes->get('settings', 'Admin::applicationSettings');
    $routes->post('settings', 'Admin::applicationSettings');

    // Referral Management
    $routes->get('referrals', 'Admin::referrals');
    $routes->get('referral-settings', 'Admin::referralSettings');
    $routes->post('referral-settings', 'Admin::referralSettings');
    $routes->get('approve-referral-bonus/(:num)', 'Admin::approveReferralBonus/$1');
    $routes->post('approve-referral-bonus/(:num)', 'Admin::approveReferralBonus/$1');

    // Wallet top-up management
    $routes->get('topup-requests', 'Admin::topupRequests');
    $routes->get('approve-topup-request/(:num)', 'Admin::approveTopupRequest/$1');
    $routes->post('approve-topup-request/(:num)', 'Admin::approveTopupRequest/$1');
    $routes->get('reject-topup-request/(:num)', 'Admin::rejectTopupRequest/$1');
    $routes->post('reject-topup-request/(:num)', 'Admin::rejectTopupRequest/$1');

    // User transfer management
    $routes->get('user-transfers', 'Admin::userTransfers');
    $routes->get('process-user-transfer/(:num)', 'Admin::processUserTransfer/$1');
    $routes->post('process-user-transfer/(:num)', 'Admin::processUserTransfer/$1');
    $routes->get('reject-user-transfer/(:num)', 'Admin::rejectUserTransfer/$1');
    $routes->post('reject-user-transfer/(:num)', 'Admin::rejectUserTransfer/$1');

    // Payment settings
    $routes->get('payment-settings', 'Admin::paymentSettings');
    $routes->post('payment-settings', 'Admin::paymentSettings');

    // User Management
    $routes->get('users', 'Admin::users');
    $routes->get('users/view/(:num)', 'Admin::viewUser/$1');
    $routes->get('users/edit/(:num)', 'Admin::editUser/$1');
    $routes->post('users/edit/(:num)', 'Admin::editUser/$1');
    $routes->get('users/delete/(:num)', 'Admin::deleteUser/$1');

    // User wallet management
    $routes->get('user-wallets', 'Admin::userWallets');
    $routes->get('edit-user-wallet/(:num)', 'Admin::editUserWallet/$1');
    $routes->post('edit-user-wallet/(:num)', 'Admin::editUserWallet/$1');
    $routes->get('special-users', 'Admin::specialUsers');
    $routes->get('edit-special-user/(:num)', 'Admin::editSpecialUser/$1');
    $routes->post('edit-special-user/(:num)', 'Admin::editSpecialUser/$1');
    $routes->get('make-special-user/(:num)', 'Admin::makeSpecialUser/$1');
    $routes->get('remove-special-user/(:num)', 'Admin::removeSpecialUser/$1');

    // Lucky Draw admin routes (consolidated)
    $routes->get('draws', 'LuckyDraw::adminDraws');
    $routes->get('draws/create', 'LuckyDraw::createDraw');
    $routes->post('draws/create', 'LuckyDraw::createDraw');
    $routes->get('draws/edit/(:num)', 'LuckyDraw::editDraw/$1');
    $routes->post('draws/edit/(:num)', 'LuckyDraw::editDraw/$1');
    $routes->get('draws/delete/(:num)', 'LuckyDraw::deleteDraw/$1');
    $routes->get('draws/run/(:num)', 'LuckyDraw::runDraw/$1');
});

// API routes for AJAX calls
$routes->group('api', function ($routes) {
    $routes->post('lucky-draw/join', 'LuckyDraw::processPayment');
    $routes->get('lucky-draw/current', 'LuckyDraw::getCurrentDraw');
    $routes->get('payment/status/(:num)', 'Payment::verifyPayment/$1');
});

// Catch-all route for 404
$routes->set404Override('Home::index');
