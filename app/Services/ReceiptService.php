<?php
namespace App\Services;

use App\Models\Receipt;
use App\Models\Payment;
use App\Mail\ReceiptMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ReceiptService
{
    public function generate(Payment $payment)
    {
        $receiptNo = 'RCPT-'.time();

        $receipt = Receipt::create([
            'payment_id' => $payment->id,
            'receipt_no' => $receiptNo,
            'issued_at' => now()
        ]);

        // Send Notifications
        $this->notify($payment, $receipt);

        return $receipt;
    }

    protected function notify(Payment $payment, Receipt $receipt)
    {
        $student = $payment->student;
        if (!$student) return;

        // 1. Send Email to Guardian
        if ($student->guardian_email) {
            try {
                Mail::to($student->guardian_email)->send(new ReceiptMail($payment, $receipt));
            } catch (\Exception $e) {
                Log::error('Failed to send receipt email: ' . $e->getMessage());
            }
        }

        // 2. Send WhatsApp to Guardian
        if ($student->guardian_phone) {
            try {
                $whatsAppService = new WhatsAppService();
                $message = "Habari, tumepokea malipo ya Ada ya {$student->first_name} kiasi cha " . number_format($payment->amount, 2) . " {$payment->currency}. Receipt No: {$receipt->receipt_no}. Ahsante.";
                $whatsAppService->sendMessage($student, $message);
            } catch (\Exception $e) {
                Log::error('Failed to send WhatsApp receipt: ' . $e->getMessage());
            }
        }
    }
}