<?php

namespace App\Config;

use CodeIgniter\Config\BaseConfig;

class Google extends BaseConfig
{
    /**
     * Google OAuth Configuration
     * 
     * Get these credentials from Google Cloud Console:
     * 1. Go to https://console.cloud.google.com/
     * 2. Create a new project or select existing one
     * 3. Enable Google+ API
     * 4. Go to Credentials > Create Credentials > OAuth 2.0 Client IDs
     * 5. Set Authorized redirect URIs to: http://yourdomain.com/auth/google/callback
     */

    public $clientId;
    public $clientSecret;
    public $redirectUri;

    // Scopes for Google API
    public $scopes = [
        'openid',
        'profile',
        'email'
    ];

    // Google OAuth URLs
    public $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth';
    public $tokenUrl = 'https://oauth2.googleapis.com/token';
    public $userInfoUrl = 'https://www.googleapis.com/oauth2/v2/userinfo';

    public function __construct()
    {
        parent::__construct();

        // Load configuration from environment variables
        $this->clientId = env('GOOGLE_CLIENT_ID', '');
        $this->clientSecret = env('GOOGLE_CLIENT_SECRET', '');
        $this->redirectUri = env('GOOGLE_REDIRECT_URI', base_url('google/callback'));
    }
}
