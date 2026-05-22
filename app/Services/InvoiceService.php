<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Student;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class InvoiceService
{
    /**
     * Create a new invoice and notify the guardian.
     */
    public function createInvoice(array $data)
    {
        $invoice = Invoice::create($data);
        
        $this->notify($invoice);
        
        return $invoice;
    }

    /**
     * Generate an invoice for a student based on their class fee structure.
     */
    public function generateForStudent(Student $student)
    {
        $session = $student->academicSessionData;
        if (!$session) return null;

        // Check if invoice already exists for this student and session
        $existing = Invoice::where('student_id', $student->id)
            ->where('academic_year', $session->name)
            ->first();
        if ($existing) return $existing;

        $feeStructures = \App\Models\FeeStructure::where('class_id', $student->classes)
            ->where('academic_year', $session->name)
            ->get();

        if ($feeStructures->isEmpty()) return null;

        $totalAmount = $feeStructures->sum('amount');
        
        $invoice = Invoice::create([
            'student_id' => $student->id,
            'academic_year' => $session->name,
            'total_amount' => $totalAmount,
            'paid_amount' => 0,
            'balance' => $totalAmount,
            'status' => 'unpaid',
            'reference_no' => 'INV-' . time() . '-' . $student->id,
            'due_date' => now()->addDays(30),
        ]);

        foreach ($feeStructures as $fee) {
            $invoice->items()->create([
                'name' => $fee->fee_type,
                'amount' => $fee->amount,
            ]);
        }

        $this->notify($invoice);

        return $invoice;
    }

    /**
     * Notify guardian about the invoice via Email and WhatsApp.
     */
    public function notify(Invoice $invoice)
    {
        $student = $invoice->student;
        if (!$student) return;

        // 1. Send Email
        if ($student->guardian_email) {
            try {
                Mail::to($student->guardian_email)->send(new InvoiceMail($invoice));
            } catch (\Exception $e) {
                Log::error('Failed to send invoice email: ' . $e->getMessage());
            }
        }

        // 2. Send WhatsApp
        if ($student->guardian_phone) {
            try {
                $whatsAppService = new WhatsAppService();
                $message = "Habari, Invoice mpya ({$invoice->reference_no}) ya kiasi cha TZS " . number_format($invoice->total_amount, 2) . " imetengenezwa kwa ajili ya mwanafunzi {$student->first_name}. Tafadhali fanya malipo kupitia mfumo wetu. Ahsante.";
                $whatsAppService->sendMessage($student, $message);
            } catch (\Exception $e) {
                Log::error('Failed to send WhatsApp invoice notification: ' . $e->getMessage());
            }
        }
    }
}
