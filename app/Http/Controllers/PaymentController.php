<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PesapalService;
use App\Services\PaymentService;
use App\Services\ReceiptService;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $pesapal;

    public function __construct(PesapalService $pesapal)
    {
        $this->pesapal = $pesapal;
    }

    public function checkout($id)
    {
        $student = Student::findOrFail($id);
        $invoice = Invoice::where('student_id', $id)
                    ->where('balance', '>', 0)
                    ->orderBy('created_at', 'desc')
                    ->first();

        if (!$invoice) {
            $invoice = (object) [
                'id' => 0, 
                'balance' => $student->balance ?? 0,
                'total_amount' => $student->balance ?? 0,
                'reference_no' => 'N/A',
                'student' => $student
            ];
        }

        return view('pages.parent.fee_checkout', compact('student', 'invoice'));
    }

    public function initiate(Request $request)
    {
        $request->validate([
            'student_id'    => 'required',
            'amount'        => 'required|numeric|min:1000',
            'email_address' => 'required|email',
            'phone_number'  => 'required',
            'first_name'    => 'required',
            'last_name'     => 'required',
        ]);

        try {
            $student = Student::findOrFail($request->student_id);
            $invoice = Invoice::find($request->invoice_id);

            // Normalize Phone Number to 255...
            $phone = $request->phone_number;
            if (str_starts_with($phone, '0')) {
                $phone = '255' . substr($phone, 1);
            } elseif (!str_starts_with($phone, '255')) {
                $phone = '255' . $phone;
            }

            // Use PaymentService to initiate the payment
            $paymentService = new PaymentService();
            $result = $paymentService->initiatePesapalPayment($student, $request->amount, $request->invoice_id, [
                'email_address' => $request->email_address,
                'phone_number'  => $phone,
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'line_1'        => $request->line_1,
            ]);
            
            // Check for redirect_url (This is the URL that would be in the iframe)
            if (!empty($result['redirect_url'])) {
                // Return a professional view that embeds this URL in an iframe
                return view('pages.parent.pesapal_iframe', [
                    'redirect_url' => $result['redirect_url'],
                    'student' => $student,
                    'invoice' => $invoice,
                    'amount' => $request->amount
                ]);
            }

            Log::error('Pesapal Order Submission Failed', $result);
            return back()->withInput()->with('error', 'Pesapal Error: ' . ($result['message'] ?? 'Unknown Error'));

        } catch (\Exception $e) {
            Log::error('Payment Initiation Exception: ' . $e->getMessage());
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function callback(Request $request)
    {
        $trackingId = $request->get('OrderTrackingId') ?? $request->get('tracking_id') ?? $request->get('orderTrackingId');

        if (!$trackingId) {
            return view('pages.parent.pesapal_result', [
                'success' => false,
                'message' => 'No tracking ID received from Pesapal.',
                'invoiceId' => $request->invoice_id,
            ]);
        }

        $response = $this->pesapal->verify($trackingId);
        
        if (!$response) {
            return view('pages.parent.pesapal_result', [
                'success' => false,
                'message' => 'Failed to verify payment with Pesapal.',
                'invoiceId' => $request->invoice_id,
            ]);
        }

        // Status checks for V3
        // 1 = Completed/Success
        $statusCode = (string)($response['status_code'] ?? ''); 
        
        if ($statusCode === '1') {
            
            // Check if payment already recorded
            $existingPayment = Payment::where('provider_ref', $trackingId)->first();
            
            if (!$existingPayment) {
                $paymentService = new PaymentService();
                $payment = $paymentService->recordPayment([
                    'student_id'     => $request->student_id,
                    'invoice_id'     => $request->invoice_id,
                    'amount'         => $response['amount'] ?? 0,
                    'payment_method' => 'pesapal',
                    'provider'       => 'pesapal',
                    'provider_ref'   => $trackingId,
                    'status'         => 'completed'
                ]);

                if ($payment) {
                    (new ReceiptService())->generate($payment);
                }
            }

            return view('pages.parent.pesapal_result', [
                'success' => true,
                'message' => 'Payment successfully completed! A receipt has been sent to your email and WhatsApp.',
                'invoiceId' => $request->invoice_id,
            ]);
        }

        $statusDescription = $response['payment_status_description'] ?? 'Failed';
        return view('pages.parent.pesapal_result', [
            'success' => false,
            'message' => 'Payment was not successful. Status: ' . $statusDescription,
            'invoiceId' => $request->invoice_id,
        ]);
    }
}
