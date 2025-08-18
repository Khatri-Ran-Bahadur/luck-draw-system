<?php

namespace App\Controllers;

use App\Models\WinnerModel;
use App\Models\CashDrawModel;
use App\Models\ProductDrawModel;
use App\Models\EntryModel;
use App\Libraries\NotificationService;

class LuckyDraw extends BaseController
{
    protected $winnerModel;
    protected $cashDrawModel;
    protected $productDrawModel;
    protected $entryModel;
    protected $notificationService;

    public function __construct()
    {
        $this->winnerModel = new WinnerModel();
        $this->cashDrawModel = new CashDrawModel();
        $this->productDrawModel = new ProductDrawModel();
        $this->entryModel = new EntryModel();
        $this->notificationService = new NotificationService();
    }

    /**
     * View winner details and claim form
     */
    public function viewWinner($winnerId)
    {
        // Get winner details with draw information
        $winner = $this->winnerModel->getWinnerWithDetails($winnerId);

        if (!$winner) {
            return redirect()->to('dashboard')->with('error', 'Winner not found');
        }

        // Check if user is authorized to view this winner
        $userId = session()->get('user_id');
        if (!$userId || $winner['user_id'] != $userId) {
            return redirect()->to('dashboard')->with('error', 'You are not authorized to view this winner');
        }

        // Get draw details
        if ($winner['draw_type'] === 'cash') {
            $draw = $this->cashDrawModel->find($winner['cash_draw_id']);
            $winner['draw_title'] = $draw['title'] ?? 'Cash Draw';
        } else {
            $draw = $this->productDrawModel->find($winner['product_draw_id']);
            $winner['draw_title'] = $draw['title'] ?? 'Product Draw';
            $winner['product_name'] = $draw['product_name'] ?? 'Product';
        }

        // Parse claim details if they exist
        if ($winner['claim_details']) {
            $winner['claim_details'] = json_decode($winner['claim_details'], true);
        }

        return view('lucky_draw/claim', ['winner' => $winner]);
    }

    /**
     * Process prize claim
     */
    public function claim($winnerId)
    {
        // Get winner details
        $winner = $this->winnerModel->find($winnerId);

        if (!$winner) {
            return redirect()->to('dashboard')->with('error', 'Winner not found');
        }

        // Check if user is authorized to claim this prize
        $userId = session()->get('user_id');
        if (!$userId || $winner['user_id'] != $userId) {
            return redirect()->to('dashboard')->with('error', 'You are not authorized to claim this prize');
        }

        // Check if already claimed
        if ($winner['is_claimed']) {
            return redirect()->to('dashboard')->with('error', 'Prize has already been claimed');
        }

        // Validate form data
        $rules = [
            'whatsapp' => 'required|min_length[10]',
            'address' => 'required|min_length[10]',
            'terms_accepted' => 'required'
        ];

        $messages = [
            'whatsapp' => [
                'required' => 'WhatsApp number is required',
                'min_length' => 'WhatsApp number must be at least 10 characters'
            ],
            'address' => [
                'required' => 'Delivery address is required',
                'min_length' => 'Address must be at least 10 characters'
            ],
            'terms_accepted' => [
                'required' => 'You must accept the terms and conditions'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            $errors = $this->validator->getErrors();
            $errorMessage = 'Please fix the following errors: ';
            foreach ($errors as $field => $message) {
                $errorMessage .= ucfirst($field) . ': ' . $message . '. ';
            }
            return redirect()->back()->withInput()->with('error', trim($errorMessage));
        }

        try {
            // Prepare claim details
            $claimDetails = [
                'whatsapp' => $this->request->getPost('whatsapp'),
                'phone' => $this->request->getPost('phone') ?: '',
                'address' => $this->request->getPost('address'),
                'additional_info' => $this->request->getPost('additional_info') ?: '',
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent()->getAgentString()
            ];

            // Update winner record
            $success = $this->winnerModel->update($winnerId, [
                'is_claimed' => true,
                'claim_details' => json_encode($claimDetails),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if ($success) {
                // Send notification to admin
                $this->notificationService->sendAdminMessage(
                    null, // No specific admin, send to all
                    'Prize Claim Submitted',
                    'A new prize claim has been submitted for review.',
                    'medium',
                    [
                        'winner_id' => $winnerId,
                        'user_id' => $userId,
                        'draw_type' => $winner['draw_type'],
                        'prize_amount' => $winner['prize_amount']
                    ]
                );

                // Send confirmation to user
                $this->notificationService->notifyUser($userId, 'claim_submitted', [
                    'winner_id' => $winnerId,
                    'draw_type' => $winner['draw_type'],
                    'prize_amount' => $winner['prize_amount']
                ], $userId);

                return redirect()->to('dashboard')->with('success', 'Prize claim submitted successfully! Our team will review and contact you soon.');
            } else {
                return redirect()->back()->with('error', 'Failed to submit claim. Please try again.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error processing prize claim: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while processing your claim. Please try again.');
        }
    }

    /**
     * View all user's winnings
     */
    public function myWinnings()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('login');
        }

        // Get user's winnings
        $winnings = $this->winnerModel->getUserWinnings($userId);

        // Get total winnings amount
        $totalWinnings = $this->winnerModel->getUserTotalWinnings($userId);

        return view('lucky_draw/my_winnings', [
            'winnings' => $winnings,
            'total_winnings' => $totalWinnings
        ]);
    }

    /**
     * View specific draw details
     */
    public function viewDraw($drawType, $drawId)
    {
        if ($drawType === 'cash') {
            $draw = $this->cashDrawModel->find($drawId);
            $entries = $this->entryModel->getCashDrawEntries($drawId);
            $winners = $this->winnerModel->getCashDrawWinners($drawId);
        } else {
            $draw = $this->productDrawModel->find($drawId);
            $entries = $this->entryModel->getProductDrawEntries($drawId);
            $winners = $this->winnerModel->getProductDrawWinners($drawId);
        }

        if (!$draw) {
            return redirect()->to('dashboard')->with('error', 'Draw not found');
        }

        // Check if user has entered this draw
        $userEntry = null;
        $userId = session()->get('user_id');
        if ($userId) {
            if ($drawType === 'cash') {
                $userEntry = $this->entryModel->where('cash_draw_id', $drawId)
                    ->where('user_id', $userId)
                    ->where('payment_status', 'completed')
                    ->first();
            } else {
                $userEntry = $this->entryModel->where('product_draw_id', $drawId)
                    ->where('user_id', $userId)
                    ->where('payment_status', 'completed')
                    ->first();
            }
        }

        return view('lucky_draw/view', [
            'draw' => $draw,
            'draw_type' => $drawType,
            'entries' => $entries,
            'winners' => $winners,
            'user_entry' => $userEntry
        ]);
    }
}
