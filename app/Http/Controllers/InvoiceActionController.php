<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Receipt;
use App\Services\WhatsAppService;
use App\Mail\InvoiceMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceActionController extends Controller
{
    public function notify(Request $request, $id)
    {
        $invoice = Invoice::with('student')->findOrFail($id);
        
        try {
            (new \App\Services\InvoiceService())->notify($invoice);
            return back()->with('success', 'Notifications sent successfully via Email and WhatsApp.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send notifications: ' . $e->getMessage());
        }
    }

    public function download($id)
    {
        $invoice = Invoice::with(['student.classData', 'student.branchData'])->findOrFail($id);
        
        if (!$invoice->student) {
            return back()->with('error', 'Hushiriki: Mwanafunzi wa ankara hii hakuweza kupatikana kwenye mfumo.');
        }

        $pdf = Pdf::loadView('pages.pdf.invoice', compact('invoice'));
        return $pdf->download("Invoice_{$invoice->reference_no}.pdf");
    }

    public function downloadReceipt($paymentId)
    {
        $payment = Payment::with(['student', 'invoice'])->findOrFail($paymentId);
        
        if (!$payment->student) {
            return back()->with('error', 'Hushiriki: Mwanafunzi wa risiti hii hakuweza kupatikana kwenye mfumo.');
        }

        $pdf = Pdf::loadView('pages.pdf.receipt', compact('payment'));
        return $pdf->download("Receipt_{$payment->provider_ref}.pdf");
    }
}
