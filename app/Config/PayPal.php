<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class PayPal extends BaseConfig
{
    public $clientId = '';
    public $clientSecret = '';
    public $mode = 'sandbox'; // Change to 'live' for production
    public $returnUrl = '';
    public $cancelUrl = '';
}
