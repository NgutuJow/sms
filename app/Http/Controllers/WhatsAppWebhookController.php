<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    /**
     * Webhook verification for Meta (GET request)
     */
    public function verify(Request $request)
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        // You should set this in your .env
        $verifyToken = env('WHATSAPP_WEBHOOK_VERIFY_TOKEN', 'my_secure_token_123');

        if ($mode && $token) {
            if ($mode === 'subscribe' && $token === $verifyToken) {
                return response($challenge, 200);
            }
        }

        return response('Forbidden', 403);
    }

    /**
     * Receive incoming messages from Meta (POST request)
     */
    public function handle(Request $request)
    {
        $data = $request->all();

        // Log the incoming data for debugging
        Log::info('WhatsApp Webhook Received:', $data);

        try {
            // Check if it's a message event
            if (isset($data['entry'][0]['changes'][0]['value']['messages'][0])) {
                $messageData = $data['entry'][0]['changes'][0]['value']['messages'][0];
                $senderPhone = $messageData['from']; // e.g. "255712345678"
                $text = $messageData['text']['body'] ?? '';

                // Try to find the student associated with this parent phone number
                // We'll look for 255... and also sanitize it just in case
                $student = Student::where('guardian_phone', 'LIKE', '%' . substr($senderPhone, -9))->first();

                if ($student) {
                    ChatMessage::create([
                        'sender_id' => null, // null for parent
                        'student_id' => $student->id,
                        'sender_role' => 'parent',
                        'recipient_phone' => $senderPhone,
                        'channel' => 'whatsapp',
                        'message' => $text,
                        'status' => 'received',
                        'sent_at' => now(),
                    ]);
                    
                    Log::info("Saved incoming WhatsApp message from parent of student ID: {$student->id}");
                } else {
                    Log::warning("Received WhatsApp message from unknown phone: {$senderPhone}");
                }
            }
            
            return response('EVENT_RECEIVED', 200);

        } catch (\Exception $e) {
            Log::error('WhatsApp Webhook Error: ' . $e->getMessage());
            return response('Internal Server Error', 500);
        }
    }
}
