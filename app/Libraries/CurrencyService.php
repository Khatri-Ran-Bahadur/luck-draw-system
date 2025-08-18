<?php

namespace App\Libraries;

class CurrencyService
{
    protected $paypalExchangeRate;

    public function __construct()
    {
        $this->refreshExchangeRate();
    }

    /**
     * Refresh exchange rate from environment (useful for testing)
     */
    public function refreshExchangeRate()
    {
        // Get exchange rate from environment variable, default to 280 if not set
        $this->paypalExchangeRate = getenv('PAYPAL_EXCHANGE_RATE');

        // If not set, try config file
        if (!$this->paypalExchangeRate) {
            $config = config('Currency');
            $this->paypalExchangeRate = $config->paypalExchangeRate ?? 280;
        }

        // Convert to float to ensure proper calculation
        $this->paypalExchangeRate = (float) $this->paypalExchangeRate;

        // Log for debugging
        log_message('info', 'PayPal Exchange Rate loaded: ' . $this->paypalExchangeRate);
    }

    /**
     * Convert PKR amount to USD (only for PayPal API - internal use)
     */
    public function pkrToUsd($pkrAmount)
    {
        return round($pkrAmount / $this->paypalExchangeRate, 2);
    }

    /**
     * Convert USD amount to PKR (only for PayPal responses - internal use)
     */
    public function usdToPkr($usdAmount)
    {
        return round($usdAmount * $this->paypalExchangeRate, 2);
    }

    /**
     * Check if amount meets PayPal minimum (Rs. 500 minimum)
     */
    public function meetsPayPalMinimum($pkrAmount)
    {
        // Minimum Rs. 500 for PayPal
        return $pkrAmount >= 500;
    }

    /**
     * Format amount in PKR
     */
    public function formatPkr($amount)
    {
        return 'Rs. ' . number_format($amount, 2);
    }

    /**
     * Get PayPal minimum amount in PKR
     */
    public function getPayPalMinimumAmount()
    {
        return 500;
    }

    /**
     * Get PayPal exchange rate
     */
    public function getPayPalExchangeRate()
    {
        return $this->paypalExchangeRate;
    }

    /**
     * Force refresh exchange rate (for testing)
     */
    public function forceRefreshExchangeRate()
    {
        $this->refreshExchangeRate();
        return $this->paypalExchangeRate;
    }
}
