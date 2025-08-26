<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['key', 'value', 'description'];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'key' => 'required|is_unique[settings.key,id,{id}]',
        'value' => 'required',
    ];

    protected $validationMessages = [
        'key' => [
            'required' => 'Setting key is required',
            'is_unique' => 'Setting key already exists'
        ],
        'value' => [
            'required' => 'Setting value is required'
        ],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getSetting($key, $default = null)
    {
        $setting = $this->where('key', $key)->first();
        return $setting ? $setting['value'] : $default;
    }

    public function setSetting($key, $value, $description = null)
    {
        $existing = $this->where('key', $key)->first();

        if ($existing) {
            return $this->update($existing['id'], [
                'value' => $value,
                'description' => $description ?? $existing['description']
            ]);
        } else {
            return $this->insert([
                'key' => $key,
                'value' => $value,
                'description' => $description
            ]);
        }
    }

    public function getDrawFrequency()
    {
        return (int) $this->getSetting('draw_frequency', 7);
    }

    public function setDrawFrequency($days)
    {
        return $this->setSetting('draw_frequency', $days, 'Lucky draw frequency in days');
    }

    public function getEntryFee()
    {
        return (float) $this->getSetting('entry_fee', 10.00);
    }

    public function setEntryFee($amount)
    {
        return $this->setSetting('entry_fee', $amount, 'Default entry fee for lucky draws');
    }

    public function getMaxEntries()
    {
        return (int) $this->getSetting('max_entries', 100);
    }

    public function setMaxEntries($count)
    {
        return $this->setSetting('max_entries', $count, 'Maximum entries per lucky draw');
    }

    public function getSiteSettings()
    {
        $settings = $this->findAll();
        $result = [];

        foreach ($settings as $setting) {
            $result[$setting['key']] = $setting['value'];
        }

        return $result;
    }

    /**
     * Get all settings as an associative array
     */
    public function getAllSettings()
    {
        $settings = $this->findAll();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['key']] = $setting['value'];
        }
        
        return $result;
    }

    // Referral System Settings
    public function getReferralBonusAmount()
    {
        return (float) $this->getSetting('referral_bonus_amount', 100.00);
    }

    public function setReferralBonusAmount($amount)
    {
        return $this->setSetting('referral_bonus_amount', $amount, 'Referral bonus amount in PKR for new user registration');
    }

    public function getReferralBonusConditions()
    {
        return $this->getSetting('referral_bonus_conditions', 'registration');
    }

    public function setReferralBonusConditions($conditions)
    {
        return $this->setSetting('referral_bonus_conditions', $conditions, 'When to give referral bonus: registration, first_purchase, etc.');
    }

    public function getReferralCodeLength()
    {
        return (int) $this->getSetting('referral_code_length', 8);
    }

    public function setReferralCodeLength($length)
    {
        return $this->setSetting('referral_code_length', $length, 'Length of referral codes generated for users');
    }

    public function getMaxReferralsPerUser()
    {
        return (int) $this->getSetting('max_referrals_per_user', 0);
    }

    public function setMaxReferralsPerUser($count)
    {
        return $this->setSetting('max_referrals_per_user', $count, 'Maximum referrals allowed per user (0 = unlimited)');
    }

    // Payment method settings
    public function getPayPalEnabled()
    {
        return env('ENABLE_PAYPAL', 'false') === 'true';
    }

    public function setPayPalEnabled($enabled)
    {
        return $this->updateSetting('paypal_enabled', $enabled ? 'true' : 'false');
    }

    public function getEasypaisaEnabled()
    {
        return env('ENABLE_EASYPAISA', 'false') === 'true';
    }

    public function setEasypaisaEnabled($enabled)
    {
        return $this->updateSetting('easypaisa_enabled', $enabled ? 'true' : 'false');
    }

    public function getJazzCashEnabled()
    {
        return env('ENABLE_JAZZ_CASH', 'true') === 'true';
    }

    public function setJazzCashEnabled($enabled)
    {
        return $this->updateSetting('jazz_cash_enabled', $enabled ? 'true' : 'false');
    }

    public function getBankTransferEnabled()
    {
        return env('ENABLE_BANK_TRANSFER', 'true') === 'true';
    }

    public function setBankTransferEnabled($enabled)
    {
        return $this->updateSetting('bank_transfer_enabled', $enabled ? 'true' : 'false');
    }

    public function getManualTopupEnabled()
    {
        return env('ENABLE_MANUAL_TOPUP', 'true') === 'true';
    }

    public function setManualTopupEnabled($enabled)
    {
        return $this->updateSetting('manual_topup_enabled', $enabled ? 'true' : 'false');
    }

    // Top-up amount settings
    public function getMinTopupAmount()
    {
        return (float) env('MANUAL_TOPUP_MIN_AMOUNT', 500.00);
    }

    public function setMinTopupAmount($amount)
    {
        return $this->updateSetting('min_topup_amount', $amount);
    }

    public function getMaxTopupAmount()
    {
        return (float) env('MANUAL_TOPUP_MAX_AMOUNT', 50000.00);
    }

    public function setMaxTopupAmount($amount)
    {
        return $this->updateSetting('max_topup_amount', $amount);
    }

    public function getTopupApprovalRequired()
    {
        return env('MANUAL_TOPUP_APPROVAL_REQUIRED', 'true') === 'true';
    }

    public function setTopupApprovalRequired($required)
    {
        return $this->updateSetting('topup_approval_required', $required ? 'true' : 'false');
    }

    // User transfer settings
    public function getUserTransferEnabled()
    {
        return env('USER_TRANSFER_ENABLED', 'true') === 'true';
    }

    public function setUserTransferEnabled($enabled)
    {
        return $this->updateSetting('user_transfer_enabled', $enabled ? 'true' : 'false');
    }

    public function getTransferFeePercentage()
    {
        return (float) env('USER_TRANSFER_FEE_PERCENTAGE', 0.00);
    }

    public function setTransferFeePercentage($percentage)
    {
        return $this->updateSetting('transfer_fee_percentage', $percentage);
    }

    // Random wallet display settings
    public function getRandomWalletDisplay()
    {
        return env('RANDOM_WALLET_DISPLAY', 'true') === 'true';
    }

    public function setRandomWalletDisplay($enabled)
    {
        return $this->updateSetting('random_wallet_display', $enabled ? 'true' : 'false');
    }

    public function getWalletDisplayCount()
    {
        return (int) env('WALLET_DISPLAY_COUNT', 3);
    }

    public function setWalletDisplayCount($count)
    {
        return $this->updateSetting('wallet_display_count', $count);
    }

    /**
     * Get minimum withdrawal amount
     */
    public function getMinWithdrawAmount()
    {
        $setting = $this->where('key', 'min_withdraw_amount')->first();
        return $setting ? (float) $setting['value'] : 1000;
    }
}
