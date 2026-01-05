<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DarajaService;
use Illuminate\Support\Facades\Log;

class DarajaTestController extends Controller
{
    /**
     * Test Daraja API connection (Admin only)
     */
    public function test(Request $request)
    {
        // Only allow admins
        if (!$request->user() || !$request->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        $darajaService = new DarajaService();
        
        $results = [
            'environment' => config('daraja.environment'),
            'base_url' => config('daraja.environment') === 'production' 
                ? 'https://api.safaricom.co.ke' 
                : 'https://sandbox.safaricom.co.ke',
            'credentials' => [
                'consumer_key' => !empty(config('daraja.consumer_key')) ? substr(config('daraja.consumer_key'), 0, 10) . '...' : 'NOT SET',
                'consumer_secret' => !empty(config('daraja.consumer_secret')) ? '***SET***' : 'NOT SET',
                'short_code' => config('daraja.short_code') ?: 'NOT SET',
                'passkey' => !empty(config('daraja.passkey')) ? '***SET***' : 'NOT SET',
                'callback_url' => config('daraja.callback_url'),
            ],
            'token_test' => null,
            'error' => null,
        ];

        try {
            // Test getting access token
            $token = $darajaService->getAccessToken();
            
            if ($token) {
                $results['token_test'] = [
                    'status' => 'success',
                    'message' => 'Access token obtained successfully',
                    'token_preview' => substr($token, 0, 20) . '...'
                ];
            } else {
                $results['token_test'] = [
                    'status' => 'failed',
                    'message' => 'Failed to get access token. Check logs for details.'
                ];
            }
        } catch (\Exception $e) {
            $results['error'] = $e->getMessage();
            $results['token_test'] = [
                'status' => 'error',
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }

        return view('admin.daraja-test', compact('results'));
    }
}

