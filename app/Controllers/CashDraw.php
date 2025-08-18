<?php

namespace App\Controllers;

use App\Models\CashDrawModel;
use App\Models\EntryModel;
use App\Models\WalletModel;
use App\Models\WalletTransactionModel;
use App\Models\WinnerModel;
use App\Libraries\NotificationService;

class CashDraw extends BaseController
{
    protected $cashDrawModel;
    protected $entryModel;
    protected $walletModel;
    protected $walletTransactionModel;
    protected $winnerModel;
    protected $notificationService;
    protected $db;

    public function __construct()
    {
        $this->cashDrawModel = new CashDrawModel();
        $this->entryModel = new EntryModel();
        $this->walletModel = new WalletModel();
        $this->walletTransactionModel = new WalletTransactionModel();
        $this->winnerModel = new WinnerModel();
        $this->notificationService = new NotificationService();
        $this->db = \Config\Database::connect();
    }

    public function view($drawId)
    {
        $draw = $this->cashDrawModel->find($drawId);
        if (!$draw) {
            return redirect()->to('home')->with('error', 'Cash draw not found');
        }

        // Get winners if draw is completed
        $winners = [];
        if ($draw['status'] === 'completed') {
            $winners = $this->winnerModel->getCashDrawWinners($drawId);
        }

        // Get user entry if logged in
        $userEntry = null;
        $userWallet = null;
        if (session()->get('user_id')) {
            $userId = session()->get('user_id');
            $userEntry = $this->entryModel->checkUserCashDrawEntry($userId, $drawId);
            $userWallet = $this->walletModel->getUserWallet($userId);
        }

        // Get recent participants
        $participants = $this->entryModel->select('entries.*, users.username, users.full_name')
            ->join('users', 'users.id = entries.user_id')
            ->where('entries.cash_draw_id', $drawId)
            ->where('entries.payment_status', 'completed')
            ->orderBy('entries.created_at', 'DESC')
            ->limit(10)
            ->findAll();

        $data = [
            'draw' => $draw,
            'user_entry' => $userEntry,
            'user_wallet' => $userWallet,
            'winners' => $winners,
            'participants' => $participants,
            'draw_type' => 'cash'
        ];

        return view('draws/cash_draw_view', $data);
    }

    public function enter($drawId)
    {
        if (!session()->get('user_id')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Please login to enter the draw']);
            }
            return redirect()->to('login')->with('error', 'Please login to enter the draw');
        }

        $draw = $this->cashDrawModel->find($drawId);
        if (!$draw || $draw['status'] !== 'active') {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Cash draw not found or not active']);
            }
            return redirect()->back()->with('error', 'Cash draw not found or not active');
        }

        // Check if draw date has passed
        if (strtotime($draw['draw_date']) <= time()) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'This draw has already ended']);
            }
            return redirect()->back()->with('error', 'This draw has already ended');
        }

        $userId = session()->get('user_id');

        // Check if user already entered
        $existingEntry = $this->entryModel->checkUserCashDrawEntry($userId, $drawId);
        if ($existingEntry) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'You have already entered this cash draw']);
            }
            return redirect()->back()->with('error', 'You have already entered this cash draw');
        }

        // Get user wallet
        $wallet = $this->walletModel->getUserWallet($userId);
        if (!$wallet) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Please create a wallet first']);
            }
            return redirect()->to('wallet')->with('error', 'Please create a wallet first');
        }

        // Check wallet balance
        if ($wallet['balance'] < $draw['entry_fee']) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Insufficient wallet balance. Please add funds first.']);
            }
            return redirect()->to('wallet/topup')->with('error', 'Insufficient wallet balance. Please add funds first.');
        }

        try {
            // Start transaction
            $this->db->transStart();

            // Check if user wants to pay with Easypaisa
            $paymentMethod = $this->request->getPost('payment_method');

            if ($paymentMethod === 'easypaisa') {
                // Create pending entry record
                $entryNumber = $this->entryModel->generateEntryNumber();

                $entryData = [
                    'user_id' => $userId,
                    'cash_draw_id' => $drawId,
                    'draw_type' => 'cash',
                    'entry_number' => $entryNumber,
                    'payment_status' => 'pending',
                    'payment_method' => 'easypaisa',
                    'amount_paid' => $draw['entry_fee'],
                    'entry_date' => date('Y-m-d H:i:s')
                ];

                $entryId = $this->entryModel->insert($entryData);

                if (!$entryId) {
                    throw new \Exception('Failed to create entry record');
                }

                // Create pending wallet transaction
                $transactionData = [
                    'wallet_id' => $wallet['id'],
                    'type' => 'draw_entry',
                    'amount' => -$draw['entry_fee'],
                    'balance_before' => $wallet['balance'],
                    'balance_after' => $wallet['balance'], // No change until payment confirmed
                    'status' => 'pending',
                    'description' => 'Cash Draw Entry - ' . $draw['title'],
                    'payment_method' => 'easypaisa',
                    'payment_reference' => $entryNumber,
                    'metadata' => json_encode([
                        'draw_id' => $drawId,
                        'draw_type' => 'cash',
                        'entry_id' => $entryId
                    ])
                ];

                $transactionId = $this->walletTransactionModel->insert($transactionData);

                // Complete transaction
                $this->db->transComplete();

                if ($this->db->transStatus() === false) {
                    throw new \Exception('Transaction failed');
                }

                // Redirect to Easypaisa checkout
                return redirect()->to("easypaisa/checkout?transaction_id=$transactionId&amount=" . $draw['entry_fee'] . "&draw_id=$drawId&draw_type=cash");
            } else {
                // Wallet payment (existing logic)
                $entryNumber = $this->entryModel->generateEntryNumber();

                // Create entry record
                $entryData = [
                    'user_id' => $userId,
                    'cash_draw_id' => $drawId,
                    'draw_type' => 'cash',
                    'entry_number' => $entryNumber,
                    'payment_status' => 'completed',
                    'payment_method' => 'wallet',
                    'amount_paid' => $draw['entry_fee'],
                    'entry_date' => date('Y-m-d H:i:s')
                ];

                $entryId = $this->entryModel->insert($entryData);

                if (!$entryId) {
                    throw new \Exception('Failed to create entry record');
                }

                // Deduct entry fee from wallet
                $newBalance = $wallet['balance'] - $draw['entry_fee'];
                $this->walletModel->update($wallet['id'], ['balance' => $newBalance]);

                // Create wallet transaction record
                $transactionData = [
                    'wallet_id' => $wallet['id'],
                    'type' => 'draw_entry',
                    'amount' => -$draw['entry_fee'],
                    'balance_before' => $wallet['balance'],
                    'balance_after' => $newBalance,
                    'status' => 'completed',
                    'description' => 'Cash Draw Entry - ' . $draw['title'],
                    'payment_method' => 'wallet',
                    'payment_reference' => $entryNumber,
                    'metadata' => json_encode([
                        'draw_id' => $drawId,
                        'draw_type' => 'cash',
                        'entry_id' => $entryId
                    ])
                ];

                $this->walletTransactionModel->insert($transactionData);

                // Complete transaction
                $this->db->transComplete();

                if ($this->db->transStatus() === false) {
                    throw new \Exception('Transaction failed');
                }

                // Update participant count
                $this->cashDrawModel->incrementParticipantCount($drawId);

                // Send notification to admin
                try {
                    $this->notificationService->notifyAdmin('draw_participation', $userId, [
                        'draw_type' => 'cash',
                        'draw_id' => $drawId,
                        'draw_title' => $draw['title'],
                        'entry_fee' => $draw['entry_fee'],
                        'entry_number' => $entryNumber
                    ]);
                } catch (\Exception $e) {
                    log_message('error', 'Failed to send admin notification: ' . $e->getMessage());
                }

                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Successfully entered the cash draw! Your entry number is: ' . $entryNumber,
                        'entry_number' => $entryNumber
                    ]);
                }
                return redirect()->to('cash-draw/' . $drawId)->with('success', 'Successfully entered the cash draw! Your entry number is: ' . $entryNumber);
            }
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Cash draw entry failed: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to enter the draw. Please try again.']);
            }
            return redirect()->back()->with('error', 'Failed to enter the draw. Please try again.');
        }
    }

    public function selectWinner($drawId)
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('admin/login')->with('error', 'Admin access required');
        }

        try {
            $draw = $this->cashDrawModel->find($drawId);
            if (!$draw) {
                return $this->response->setJSON(['success' => false, 'message' => 'Draw not found']);
            }

            // Get all completed entries for this draw
            $entryModel = new \App\Models\EntryModel();
            $entries = $entryModel->where('cash_draw_id', $drawId)
                ->where('payment_status', 'completed')
                ->findAll();

            if (empty($entries)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No completed entries found']);
            }

            // Randomly select winner
            $winnerEntry = $entries[array_rand($entries)];

            // Create winner record
            $winnerModel = new \App\Models\WinnerModel();
            $winnerData = [
                'cash_draw_id' => $drawId,
                'draw_type' => 'cash',
                'user_id' => $winnerEntry['user_id'],
                'position' => 1,
                'prize_amount' => $draw['prize_amount'],
                'is_claimed' => false,
                'claim_approved' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $winnerId = $winnerModel->insert($winnerData);

            if (!$winnerId) {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to create winner record']);
            }

            // Update draw status to completed
            $this->cashDrawModel->update($drawId, [
                'status' => 'completed',
                'winner_id' => $winnerId,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Update participant count (don't remove entries, just mark as completed)
            $participantCount = count($entries);
            $this->cashDrawModel->update($drawId, [
                'participant_count' => $participantCount
            ]);

            // Send notification to winner
            $notificationService = new \App\Libraries\NotificationService();
            $notificationService->sendSystemMessage($winnerEntry['user_id'], 'Congratulations! You Won! 🎉', 'You have won Rs. ' . number_format($draw['prize_amount'], 2) . ' in the cash draw: ' . $draw['title'], 'high');

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Winner selected successfully',
                'winner' => [
                    'user_id' => $winnerEntry['user_id'],
                    'entry_number' => $winnerEntry['entry_number'],
                    'prize_amount' => $draw['prize_amount']
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error selecting winner: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error selecting winner: ' . $e->getMessage()]);
        }
    }
}
