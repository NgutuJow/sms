<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Record a payment and update the associated invoice.
     *
     * @param array $data
     * @return Payment|null
     */
    public function recordPayment(array $data)
    {
        return DB::transaction(function () use ($data) {
            try {
                $payment = Payment::create([
                    'student_id'     => $data['student_id'],
                    'invoice_id'     => $data['invoice_id'] && $data['invoice_id'] != 0 ? $data['invoice_id'] : null,
                    'amount'         => $data['amount'],
                    'currency'       => $data['currency'] ?? 'TZS',
                    'payment_method' => $data['payment_method'],
                    'provider'       => $data['provider'] ?? 'system',
                    'provider_ref'   => $data['provider_ref'] ?? null,
                    'status'         => $data['status'] ?? 'completed',
                    'meta'           => $data['meta'] ?? null,
                ]);

                if ($payment->invoice_id) {
                    $this->updateInvoice($payment->invoice_id, $payment->amount);
                } else {
                    // If no specific invoice, maybe apply to the oldest outstanding invoice?
                    $this->applyPaymentToStudentInvoices($payment->student_id, $payment->amount);
                }

                return $payment;
            } catch (\Exception $e) {
                Log::error('Payment Recording Error: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Initiate a Pesapal payment.
     */
    public function initiatePesapalPayment(Student $student, $amount, $invoiceId, $billingData)
    {
        $pesapal = new PesapalService();
        
        $token = $pesapal->getAccessToken();
        if (!$token) throw new \Exception("Failed to get Pesapal token");

        $ipnId = $pesapal->registerIPN($token);
        if (!$ipnId) throw new \Exception("Failed to register IPN");

        $reference = 'SCH-' . time();
        $callbackUrl = route('pesapal.callback', [
            'invoice_id' => $invoiceId ?? 0,
            'student_id' => $student->id,
        ]);

        $orderData = [
            "id" => $reference,
            "currency" => "TZS",
            "amount" => number_format((float)$amount, 2, '.', ''),
            "description" => "School Fees for " . $student->first_name,
            "callback_url" => $callbackUrl,
            "notification_id" => $ipnId,
            "billing_address" => [
                "email_address" => $billingData['email_address'],
                "phone_number"  => $billingData['phone_number'],
                "first_name"    => $billingData['first_name'],
                "last_name"     => $billingData['last_name'],
                "line_1"        => $billingData['line_1'] ?? "N/A",
                "city"          => $billingData['city'] ?? "Arusha",
                "country_code"  => "TZ"
            ]
        ];

        return $pesapal->submitOrder($orderData, $token);
    }

    /**
     * Update invoice paid amount and balance.
     */
    protected function updateInvoice($invoiceId, $amount)
    {
        $invoice = Invoice::find($invoiceId);
        if ($invoice) {
            $invoice->paid_amount += $amount;
            $invoice->balance = max(0, $invoice->total_amount - $invoice->paid_amount);
            
            if ($invoice->balance <= 0) {
                $invoice->status = 'PAID';
            } elseif ($invoice->paid_amount > 0) {
                $invoice->status = 'PARTIALLY_PAID';
            }
            
            $invoice->save();
        }
    }

    /**
     * Apply payment to student's outstanding invoices.
     */
    protected function applyPaymentToStudentInvoices($studentId, $amount)
    {
        $invoices = Invoice::where('student_id', $studentId)
            ->where('balance', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        $remainingAmount = $amount;

        foreach ($invoices as $invoice) {
            if ($remainingAmount <= 0) break;

            $paymentToApply = min($remainingAmount, $invoice->balance);
            $invoice->paid_amount += $paymentToApply;
            $invoice->balance -= $paymentToApply;

            if ($invoice->balance <= 0) {
                $invoice->status = 'PAID';
            } else {
                $invoice->status = 'PARTIALLY_PAID';
            }

            $invoice->save();
            $remainingAmount -= $paymentToApply;
        }
    }
}
