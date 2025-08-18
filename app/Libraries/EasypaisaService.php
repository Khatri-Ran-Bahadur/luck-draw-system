<?php

namespace App\Libraries;

use Config\Easypaisa as EasypaisaConfig;

class EasypaisaService
{
    protected $config;
    protected $baseUrl;

    public function __construct()
    {
        $this->config = new EasypaisaConfig();
        $this->baseUrl = base_url();
    }

    /**
     * Generate payment request data for Easypaisa
     */
    public function generatePaymentRequest($amount, $orderRefNum, $paymentMethod = null)
    {
        // Set postback URL for wallet topup completion
        $this->config->postBackUrl1 = $this->baseUrl . 'easypaisa/complete';

        // Generate expiry date (current time + expiry hours)
        $expiryDateTime = new \DateTime();
        $expiryDateTime->modify('+' . $this->config->expiryHours . ' hours');
        $expiryDate = $expiryDateTime->format('Ymd His');

        // Prepare post data
        $postData = [
            'storeId' => $this->config->storeId,
            'amount' => $amount,
            'postBackURL' => $this->config->postBackUrl1,
            'orderRefNum' => $orderRefNum,
            'expiryDate' => $expiryDate,
            'merchantHashedReq' => '', // Will be set after hash generation
            'autoRedirect' => $this->config->autoRedirect,
            'paymentMethod' => $paymentMethod ?: $this->config->defaultPaymentMethod
        ];

        // Generate hash
        $postData['merchantHashedReq'] = $this->generateHash($postData);

        return $postData;
    }

    /**
     * Generate hash for Easypaisa request
     */
    private function generateHash($postData)
    {
        // Sort array alphabetically and skip empty fields
        $sortedArray = $postData;
        ksort($sortedArray);

        $sortedString = '';
        $i = 1;

        foreach ($sortedArray as $key => $value) {
            if (!empty($value) && $key !== 'merchantHashedReq') {
                if ($i == 1) {
                    $sortedString = $key . '=' . $value;
                } else {
                    $sortedString = $sortedString . '&' . $key . '=' . $value;
                }
                $i++;
            }
        }

        // Encrypt using AES/ECB/PKCS5Padding algorithm
        $cipher = "aes-128-ecb";
        $crypttext = openssl_encrypt($sortedString, $cipher, $this->config->hashKey, OPENSSL_RAW_DATA);

        return base64_encode($crypttext);
    }

    /**
     * Verify hash from Easypaisa response
     */
    public function verifyHash($responseData)
    {
        if (empty($responseData['merchantHashedReq'])) {
            return false;
        }

        // Sort response data alphabetically
        $sortedArray = $responseData;
        ksort($sortedArray);

        $sortedString = '';
        $i = 1;

        foreach ($sortedArray as $key => $value) {
            if (!empty($value) && $key !== 'merchantHashedReq') {
                if ($i == 1) {
                    $sortedString = $key . '=' . $value;
                } else {
                    $sortedString = $sortedString . '&' . $key . '=' . $value;
                }
                $i++;
            }
        }

        // Generate hash from sorted string
        $cipher = "aes-128-ecb";
        $crypttext = openssl_encrypt($sortedString, $cipher, $this->config->hashKey, OPENSSL_RAW_DATA);
        $generatedHash = base64_encode($crypttext);

        // Compare with received hash
        return $generatedHash === $responseData['merchantHashedReq'];
    }

    /**
     * Get transaction post URL based on environment
     */
    public function getTransactionPostUrl()
    {
        return $this->config->urls[$this->config->environment]['transaction_post'];
    }

    /**
     * Check if Easypaisa is configured
     */
    public function isConfigured()
    {
        return !empty($this->config->storeId) && !empty($this->config->hashKey);
    }

    /**
     * Get configuration status
     */
    public function getConfigStatus()
    {
        return [
            'configured' => $this->isConfigured(),
            'store_id' => !empty($this->config->storeId),
            'hash_key' => !empty($this->config->hashKey),
            'environment' => $this->config->environment
        ];
    }
}
