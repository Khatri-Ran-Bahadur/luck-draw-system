<?php

namespace App\Libraries;

use App\Config\Google as GoogleConfig;
use League\OAuth2\Client\Provider\Google;

class GoogleOAuth
{
    protected $config;
    protected $session;
    protected $provider;

    public function __construct()
    {
        $this->config = new GoogleConfig();
        $this->session = session();

        // Initialize the Google OAuth provider
        log_message('debug', 'Google OAuth clientId: ' . $this->config->clientId);
        log_message('debug', 'Google OAuth clientSecret: ' . $this->config->clientSecret);
        log_message('debug', 'Google OAuth redirectUri: ' . $this->config->redirectUri);
        $this->provider = new Google([
            'clientId'     => $this->config->clientId,
            'clientSecret' => $this->config->clientSecret,
            'redirectUri'  => $this->config->redirectUri,
        ]);
    }

    /**
     * Generate Google OAuth authorization URL
     */
    public function getAuthorizationUrl()
    {
        // Generate the authorization URL
        $authUrl = $this->provider->getAuthorizationUrl([
            'scope' => $this->config->scopes
        ]);

        // Store the state for validation
        $this->session->set('google_oauth_state', $this->provider->getState());

        return $authUrl;
    }

    /**
     * Exchange authorization code for access token
     */
    public function getAccessToken($code, $state = null)
    {
        // Get stored state from session
        $storedState = $this->session->get('google_oauth_state');

        // Verify state parameter for security
        if ($state && $storedState && $state !== $storedState) {
            throw new \Exception('Invalid state parameter. Expected: ' . $storedState . ', Got: ' . $state);
        }

        // Get access token using the provider
        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $code
        ]);

        return $token;
    }

    /**
     * Get user information using access token
     */
    public function getUserInfo($token)
    {
        // Get user details using the provider
        $user = $this->provider->getResourceOwner($token);

        // Get user data as array and extract needed fields
        $userData = $user->toArray();

        return [
            'id' => $user->getId(),
            'email' => $userData['email'] ?? '',
            'name' => $userData['name'] ?? '',
            'given_name' => $userData['given_name'] ?? '',
            'family_name' => $userData['family_name'] ?? '',
            'picture' => $userData['picture'] ?? null,
            'verified_email' => $userData['verified_email'] ?? true
        ];
    }

    /**
     * Complete OAuth flow - get user data from authorization code
     */
    public function handleCallback($code, $state = null)
    {
        try {
            // Get access token
            log_message('debug', 'going to get access token');
            $token = $this->getAccessToken($code, $state);

            log_message('debug', 'Google OAuth access token: ' . json_encode(['code' => $code, 'state' => $state, 'token' => $token]));

            // Get user information
            $userInfo = $this->getUserInfo($token);

            // Clean up session
            $this->session->remove('google_oauth_state');

            return $userInfo;
        } catch (\Exception $e) {
            // Clean up session on error
            $this->session->remove('google_oauth_state');
            throw $e;
        }
    }
}
