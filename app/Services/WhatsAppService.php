<?php

namespace App\Services;

use App\Models\ChatMessage;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a WhatsApp message.
     * 
     * @param Student $student
     * @param string $message
     * @param string $senderRole
     * @param int|null $senderId
     * @return array
     */
    public function sendMessage(Student $student, $message, $senderRole = 'system', $senderId = null)
    {
        $phone = $this->sanitizePhone($student->guardian_phone);
        
        if (!$phone) {
            return [
                'success' => false,
                'message' => 'Invalid or missing guardian phone number.'
            ];
        }

        // Create the message record
        $chatMessage = ChatMessage::create([
            'sender_id' => $senderId ?: (Auth::id() ?: 1), // Default to 1 if no user (system)
            'student_id' => $student->id,
            'sender_role' => $senderRole,
            'recipient_phone' => $phone,
            'channel' => 'whatsapp',
            'message' => $message,
            'status' => 'pending',
            'sent_at' => now(),
        ]);

        // If we have an API configured, we would send it here.
        // For now, we'll implement a "Click to Chat" URL generator as a fallback
        // and a placeholder for automated sending.
        
        $canSendAutomatically = $this->hasApiConfigured();

        if ($canSendAutomatically) {
            return $this->sendViaApi($chatMessage);
        }

        return [
            'success' => true,
            'message' => 'Message recorded. Use the WhatsApp link to send manually.',
            'whatsapp_url' => $this->buildWhatsAppUrl($phone, $message),
            'chat_message' => $chatMessage
        ];
    }

    /**
     * Check if a phone number is registered on WhatsApp.
     * 
     * @param string $phone
     * @return bool|null Null if check is not possible without API
     */
    public function isNumberOnWhatsApp($phone)
    {
        if (!$this->hasApiConfigured()) {
            return null; // Cannot check without an API
        }

        // Placeholder for API check (e.g., UltraMsg, Wati, or Twilio)
        try {
            // Example for UltraMsg:
            // $response = Http::get("https://api.ultramsg.com/instanceXXXX/contacts/check", [
            //     'token' => config('services.whatsapp.token'),
            //     'chatId' => $phone . '@c.us'
            // ]);
            // return $response->json()['status'] === 'exists';
            
            return true; // Mocking a positive result
        } catch (\Exception $e) {
            Log::error("WhatsApp check failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Sanitize phone number to digits only.
     */
    public function sanitizePhone($phone)
    {
        $digits = preg_replace('/[^0-9]/', '', $phone);
        return $digits ?: null;
    }

    /**
     * Build a WhatsApp Click to Chat URL.
     */
    public function buildWhatsAppUrl($phone, $message)
    {
        $text = urlencode($message);
        return "https://api.whatsapp.com/send?phone={$phone}&text={$text}";
    }

    /**
     * Check if any WhatsApp API is configured in config/services.php
     */
    private function hasApiConfigured()
    {
        return !empty(config('services.whatsapp.cloud_api_token')) && 
               !empty(config('services.whatsapp.phone_number_id')) && 
               config('services.whatsapp.phone_number_id') !== 'YOUR_PHONE_NUMBER_ID_HERE';
    }

    /**
     * Send message via Meta WhatsApp Cloud API.
     */
    private function sendViaApi(ChatMessage $chatMessage)
    {
        $token = config('services.whatsapp.cloud_api_token');
        $phoneId = config('services.whatsapp.phone_number_id');
        $url = "https://graph.facebook.com/v19.0/{$phoneId}/messages";

        try {
            $response = Http::withToken($token)->post($url, [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $chatMessage->recipient_phone,
                'type' => 'text',
                'text' => [
                    'preview_url' => false,
                    'body' => $chatMessage->message
                ]
            ]);

            if ($response->successful()) {
                $chatMessage->update(['status' => 'sent']);
                return [
                    'success' => true, 
                    'message' => 'Message sent automatically via WhatsApp Cloud API.',
                    'api_response' => $response->json()
                ];
            }

            $errorData = $response->json();
            Log::error("WhatsApp Cloud API Error (Status: {$response->status()}):", [
                'url' => $url,
                'response' => $errorData,
                'phone_id' => $phoneId,
                'token_prefix' => substr($token, 0, 10) . '...'
            ]);
            
            $chatMessage->update(['status' => 'failed']);
            return [
                'success' => false, 
                'message' => 'API send failed: ' . ($errorData['error']['message'] ?? 'Authentication or Permission error'),
                'error_details' => $errorData
            ];

        } catch (\Exception $e) {
            Log::error("WhatsApp API Exception: " . $e->getMessage());
            $chatMessage->update(['status' => 'failed']);
            return ['success' => false, 'message' => 'API connection failed: ' . $e->getMessage()];
        }
    }
}
