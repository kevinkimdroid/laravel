<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class DarajaService
{
    protected $consumerKey;
    protected $consumerSecret;
    protected $shortCode;
    protected $passkey;
    protected $callbackUrl;
    protected $baseUrl;
    protected $environment;

    public function __construct()
    {
        $this->consumerKey = config('daraja.consumer_key');
        $this->consumerSecret = config('daraja.consumer_secret');
        $this->shortCode = config('daraja.short_code');
        $this->passkey = config('daraja.passkey');
        $this->callbackUrl = config('daraja.callback_url');
        $this->environment = config('daraja.environment', 'sandbox');
        $this->baseUrl = $this->environment === 'production' 
            ? 'https://api.safaricom.co.ke' 
            : 'https://sandbox.safaricom.co.ke';
        
        // Validate required credentials
        if (empty($this->consumerKey) || empty($this->consumerSecret) || empty($this->shortCode) || empty($this->passkey)) {
            \Log::warning('Daraja API credentials not fully configured. Please check your .env file.');
        }
    }

    /**
     * Get OAuth access token
     */
    public function getAccessToken()
    {
        // Validate credentials first
        if (empty($this->consumerKey) || empty($this->consumerSecret)) {
            Log::error('Daraja: Missing credentials', [
                'has_consumer_key' => !empty($this->consumerKey),
                'has_consumer_secret' => !empty($this->consumerSecret),
                'environment' => $this->environment
            ]);
            return null;
        }

        // Check cache first
        $cacheKey = 'daraja_access_token';
        $token = Cache::get($cacheKey);
        
        if ($token) {
            return $token;
        }

        try {
            $url = $this->baseUrl . '/oauth/v1/generate?grant_type=client_credentials';
            
            Log::info('Daraja: Requesting access token', [
                'url' => $url,
                'environment' => $this->environment
            ]);
            
            $response = Http::timeout(30)
                ->withBasicAuth($this->consumerKey, $this->consumerSecret)
                ->get($url);

            $statusCode = $response->status();
            $responseBody = $response->body();
            $responseData = $response->json();

            if ($response->successful()) {
                $token = $responseData['access_token'] ?? null;
                
                if ($token) {
                    // Cache token for 55 minutes (tokens expire in 1 hour)
                    Cache::put($cacheKey, $token, now()->addMinutes(55));
                    Log::info('Daraja: Access token obtained successfully');
                    return $token;
                } else {
                    Log::error('Daraja: Access token not found in response', [
                        'response' => $responseData
                    ]);
                }
            } else {
                $errorMessage = $responseData['errorMessage'] ?? $responseData['error'] ?? 'Unknown error';
                Log::error('Daraja: Failed to get access token', [
                    'status' => $statusCode,
                    'error' => $errorMessage,
                    'response' => $responseBody,
                    'environment' => $this->environment,
                    'consumer_key_prefix' => substr($this->consumerKey, 0, 10) . '...'
                ]);
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Daraja: Exception getting access token', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'environment' => $this->environment
            ]);
            return null;
        }
    }

    /**
     * Initiate STK Push
     */
    public function initiateSTKPush($phoneNumber, $amount, $accountReference, $transactionDesc)
    {
        // Check if credentials are configured
        if (empty($this->consumerKey) || empty($this->consumerSecret) || empty($this->shortCode) || empty($this->passkey)) {
            return [
                'success' => false,
                'message' => 'M-Pesa API credentials are not configured. Please contact the administrator.',
                'details' => 'Missing: ' . implode(', ', array_filter([
                    empty($this->consumerKey) ? 'Consumer Key' : null,
                    empty($this->consumerSecret) ? 'Consumer Secret' : null,
                    empty($this->shortCode) ? 'Short Code' : null,
                    empty($this->passkey) ? 'Passkey' : null,
                ]))
            ];
        }

        $accessToken = $this->getAccessToken();
        
        if (!$accessToken) {
            $errorDetails = 'Please check your Daraja API credentials in the .env file. ';
            $errorDetails .= 'Environment: ' . $this->environment . '. ';
            $errorDetails .= 'Check the logs for more details.';
            
            return [
                'success' => false,
                'message' => 'Failed to authenticate with M-Pesa API. ' . $errorDetails
            ];
        }

        // Format phone number (remove + and ensure it starts with 254)
        $phoneNumber = $this->formatPhoneNumber($phoneNumber);
        
        if (!$phoneNumber) {
            return [
                'success' => false,
                'message' => 'Invalid phone number format'
            ];
        }

        // Generate timestamp and password
        $timestamp = date('YmdHis');
        $password = base64_encode($this->shortCode . $this->passkey . $timestamp);

        $url = $this->baseUrl . '/mpesa/stkpush/v1/processrequest';

        $payload = [
            'BusinessShortCode' => $this->shortCode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => (int) $amount,
            'PartyA' => $phoneNumber,
            'PartyB' => $this->shortCode,
            'PhoneNumber' => $phoneNumber,
            'CallBackURL' => $this->callbackUrl,
            'AccountReference' => $accountReference,
            'TransactionDesc' => $transactionDesc,
        ];

        try {
            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->post($url, $payload);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['ResponseCode']) && $responseData['ResponseCode'] == '0') {
                Log::info('Daraja: STK Push initiated successfully', [
                    'checkout_request_id' => $responseData['CheckoutRequestID'] ?? null,
                    'merchant_request_id' => $responseData['MerchantRequestID'] ?? null,
                    'account_reference' => $accountReference
                ]);

                return [
                    'success' => true,
                    'checkout_request_id' => $responseData['CheckoutRequestID'] ?? null,
                    'merchant_request_id' => $responseData['MerchantRequestID'] ?? null,
                    'customer_message' => $responseData['CustomerMessage'] ?? 'STK Push sent to your phone. Please enter your M-Pesa PIN to complete the payment.',
                    'response_code' => $responseData['ResponseCode'] ?? null
                ];
            } else {
                $errorMessage = $responseData['errorMessage'] ?? $responseData['errorMessage'] ?? 'Failed to initiate payment';
                
                Log::error('Daraja: STK Push failed', [
                    'response' => $responseData,
                    'status' => $response->status()
                ]);

                return [
                    'success' => false,
                    'message' => $errorMessage,
                    'response_code' => $responseData['ResponseCode'] ?? null
                ];
            }
        } catch (\Exception $e) {
            Log::error('Daraja: Exception initiating STK Push', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred while initiating payment. Please try again.'
            ];
        }
    }

    /**
     * Query STK Push status
     */
    public function querySTKPushStatus($checkoutRequestId)
    {
        $accessToken = $this->getAccessToken();
        
        if (!$accessToken) {
            return null;
        }

        $timestamp = date('YmdHis');
        $password = base64_encode($this->shortCode . $this->passkey . $timestamp);

        $url = $this->baseUrl . '/mpesa/stkpushquery/v1/query';

        $payload = [
            'BusinessShortCode' => $this->shortCode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'CheckoutRequestID' => $checkoutRequestId,
        ];

        try {
            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->post($url, $payload);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Daraja: Exception querying STK Push status', [
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Format phone number to 254XXXXXXXXX format
     */
    protected function formatPhoneNumber($phoneNumber)
    {
        // Remove all non-numeric characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // If starts with 0, replace with 254
        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = '254' . substr($phoneNumber, 1);
        }
        
        // If starts with +254, remove +
        if (substr($phoneNumber, 0, 4) === '254') {
            // Already correct format
        } elseif (strlen($phoneNumber) === 9) {
            // Assume it's missing 254 prefix
            $phoneNumber = '254' . $phoneNumber;
        }
        
        // Validate: should be 12 digits starting with 254
        if (strlen($phoneNumber) === 12 && substr($phoneNumber, 0, 3) === '254') {
            return $phoneNumber;
        }
        
        return null;
    }
}

