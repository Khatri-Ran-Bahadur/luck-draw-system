<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Easypaisa extends BaseConfig
{
    // Telenor Easypaisa Configuration
    public $storeId = '';
    public $hashKey = '';

    // Environment (sandbox or live)
    public $environment = 'sandbox';

    // URLs for different environments
    public $urls = [
        'sandbox' => [
            'transaction_post' => 'https://easypaystg.easypaisa.com.pk/easypay/Index.jsf',
            'confirm' => 'https://easypaystg.easypaisa.com.pk/easypay/Confirm.jsf'
        ],
        'live' => [
            'transaction_post' => 'https://easypay.easypaisa.com.pk/easypay/Index.jsf',
            'confirm' => 'https://easypay.easypaisa.com.pk/easypay/Confirm.jsf'
        ]
    ];

    // Payment methods
    public $paymentMethods = [
        'OTC_PAYMENT_METHOD' => 'Over the Counter',
        'MA_PAYMENT_METHOD' => 'Mobile Account',
        'CC_PAYMENT_METHOD' => 'Credit Card'
    ];

    // Default payment method
    public $defaultPaymentMethod = 'MA_PAYMENT_METHOD';

    // Transaction expiry time (in hours)
    public $expiryHours = 1;

    // Auto redirect
    public $autoRedirect = '1';

    // Postback URLs (will be set dynamically)
    public $postBackUrl1 = '';
    public $postBackUrl2 = '';
}
