<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Payment extends BaseConfig
{
    // PayPal Configuration
    public $paypal = [
        'client_id' => null, // Set in .env file
        'client_secret' => null, // Set in .env file
        'mode' => 'sandbox', // sandbox or live
        'webhook_id' => null, // Set in .env file
        'currency' => 'USD',
        'return_url' => null, // Set in .env file
        'cancel_url' => null, // Set in .env file
    ];

    // Easypaisa Configuration
    public $easypaisa = [
        'store_id' => null, // Set in .env file
        'hash_key' => null, // Set in .env file
        'environment' => 'sandbox', // sandbox or live
        'currency' => 'PKR',
        'return_url' => null, // Set in .env file
        'cancel_url' => null, // Set in .env file
    ];

    // Demo Mode Configuration
    public $demo_mode = true; // Set to false in production

    public function __construct()
    {
        parent::__construct();

        // Load from environment variables
        $this->paypal['client_id'] = getenv('PAYPAL_CLIENT_ID') ?: 'AeyJHilUB19rba6Dxlh2OhI4z8K2tEFGAyIzBw28gLY4fX61LdoTEU4P517iL01EVbdBOoPjwmneZWnZ';
        $this->paypal['client_secret'] = getenv('PAYPAL_CLIENT_SECRET') ?: 'EIoSw6RH8b7sTSbtu_1IcWKHbDLZM0LxopS-fmnJcvB5a_GHbiK08LCaSPIYRdJR44N47DBtMtaG1W3K';
        $this->paypal['mode'] = getenv('PAYPAL_MODE') ?: 'sandbox';
        $this->paypal['webhook_id'] = getenv('PAYPAL_WEBHOOK_ID') ?: null;
        $this->paypal['return_url'] = getenv('PAYPAL_RETURN_URL') ?: base_url('wallet/paypal/success');
        $this->paypal['cancel_url'] = getenv('PAYPAL_CANCEL_URL') ?: base_url('wallet/paypal/cancel');

        $this->easypaisa['store_id'] = getenv('EASYPAISA_STORE_ID') ?: null;
        $this->easypaisa['hash_key'] = getenv('EASYPAISA_HASH_KEY') ?: null;
        $this->easypaisa['environment'] = getenv('EASYPAISA_ENV') ?: 'sandbox';
        $this->easypaisa['return_url'] = getenv('EASYPAISA_RETURN_URL') ?: base_url('wallet/easypaisa/success');
        $this->easypaisa['cancel_url'] = getenv('EASYPAISA_CANCEL_URL') ?: base_url('wallet/easypaisa/cancel');

        $this->demo_mode = getenv('PAYMENT_DEMO_MODE') === 'true' ? true : false;
    }

    // Check if PayPal is configured
    public function isPayPalConfigured()
    {
        return !empty($this->paypal['client_id']) && !empty($this->paypal['client_secret']);
    }

    // Check if Easypaisa is configured
    public function isEasypaisaConfigured()
    {
        return !empty($this->easypaisa['store_id']) && !empty($this->easypaisa['hash_key']);
    }

    // Get payment method status
    public function getPaymentMethodStatus()
    {
        return [
            'paypal' => [
                'configured' => $this->isPayPalConfigured(),
                'mode' => $this->paypal['mode'],
                'demo' => $this->demo_mode || !$this->isPayPalConfigured()
            ],
            'easypaisa' => [
                'configured' => $this->isEasypaisaConfigured(),
                'mode' => $this->easypaisa['environment'],
                'demo' => $this->demo_mode || !$this->isEasypaisaConfigured()
            ]
        ];
    }
}
