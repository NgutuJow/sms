<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PesapalService
{
    protected $baseUrl;
    protected $key;
    protected $secret;

    public function __construct()
    {
        $this->key = config('services.pesapal.key');
        $this->secret = config('services.pesapal.secret');
        $this->baseUrl = rtrim(config('services.pesapal.url'), '/');
    }
    
    public function getAccessToken()
    {
        try {
            Log::info('Pesapal: Requesting Access Token...', ['url' => $this->baseUrl . '/Auth/RequestToken']);
            
            $response = Http::acceptJson()
                ->post($this->baseUrl . '/Auth/RequestToken', [
                    'consumer_key' => $this->key,
                    'consumer_secret' => $this->secret,
                ]);
            
            if ($response->successful()) {
                $token = $response->json('token');
                Log::info('Pesapal: Token retrieved successfully.');
                return $token;
            }

            Log::error('Pesapal Auth Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'url' => $this->baseUrl . '/Auth/RequestToken'
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('Pesapal Auth Exception: ' . $e->getMessage(), [
                'url' => $this->baseUrl . '/Auth/RequestToken'
            ]);
            return null;
        }
    }

    public function registerIPN($token)
    {
        try {
            // Force HTTPS for callback if using ngrok
            $ipnUrl = route('pesapal.callback');
            if (str_contains($ipnUrl, 'ngrok-free.app')) {
                $ipnUrl = str_replace('http://', 'https://', $ipnUrl);
            }

            Log::info('Pesapal: Registering IPN...', ['url' => $ipnUrl]);

            $response = Http::withToken($token)
                ->acceptJson()
                ->post($this->baseUrl . '/URLSetup/RegisterIPN', [
                    'url' => $ipnUrl,
                    'ipn_notification_type' => 'POST',
                ]);

            if ($response->successful()) {
                $ipnId = $response->json('ipn_id');
                Log::info('Pesapal: IPN Registered.', ['ipn_id' => $ipnId]);
                return $ipnId;
            }

            Log::error('Pesapal IPN Registration Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'url' => $this->baseUrl . '/URLSetup/RegisterIPN'
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('Pesapal IPN Exception: ' . $e->getMessage());
            return null;
        }
    }

    public function submitOrder($orderData, $token)
    {
        try {
            Log::info('Pesapal: Submitting Order...');
            
            $response = Http::withToken($token)
                ->acceptJson()
                ->post($this->baseUrl . '/Transactions/SubmitOrderRequest', $orderData);

            if ($response->successful()) {
                $res = $response->json();
                Log::info('Pesapal: Order Submitted Successfully.', ['response' => $res]);
                return $res;
            }

            Log::error('Pesapal Submit Order Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return $response->json() ?? ['error' => 'Failed to submit order'];
        } catch (\Exception $e) {
            Log::error('Pesapal Submit Order Exception: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    public function verify($trackingId)
    {
        $token = $this->getAccessToken();
        if (!$token) return null;

        try {
            Log::info('Pesapal: Verifying Transaction...', ['tracking_id' => $trackingId]);
            
            $url = $this->baseUrl . "/Transactions/GetTransactionStatus?orderTrackingId=" . $trackingId;
            $response = Http::withToken($token)
                ->acceptJson()
                ->get($url);

            if ($response->successful()) {
                $res = $response->json();
                Log::info('Pesapal: Verification Response Received.', ['response' => $res]);
                return $res;
            }

            Log::error('Pesapal Verification Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('Pesapal Verification Exception: ' . $e->getMessage());
            return null;
        }
    }
}
