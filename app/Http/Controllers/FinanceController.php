<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\FeeStructure;
use App\Models\Receipt;
use App\Models\Expense;
use App\Models\Payroll;
use App\Models\Discount;
use App\Models\Fine;
use App\Models\Budget;
use App\Models\AuditLog;
use App\Services\PesapalService;
use App\Services\PaymentService;
use App\Services\ReceiptService;

class FinanceController extends Controller
{
    public function index()
    {
        $totalFeesCollected = Payment::where('status', 'completed')->sum('amount');
        $totalInvoices = Invoice::count();
        $pendingFees = Invoice::where('balance', '>', 0)->sum('balance');
        $todayCollections = Payment::where('status', 'completed')->whereDate('created_at', now())->sum('amount');

        $topDefaulters = Invoice::where('balance', '>', 0)
            ->with('student')
            ->orderByDesc('balance')
            ->take(5)
            ->get();

        $recentPayments = Payment::latest()
            ->with(['invoice.student'])
            ->take(6)
            ->get();

        $feeStructureCount = FeeStructure::count();
        $receiptCount = Receipt::count();

        return view('pages.finance.index', compact(
            'totalFeesCollected',
            'totalInvoices',
            'pendingFees',
            'todayCollections',
            'topDefaulters',
            'recentPayments',
            'feeStructureCount',
            'receiptCount'
        ));
    }

    public function invoices()
    {
        $invoices = Invoice::latest()->with('student')->get();
        return view('pages.finance.invoices', compact('invoices'));
    }

    public function studentFees()
    {
        $studentInvoices = Invoice::with('student')->latest()->take(30)->get();
        return view('pages.finance.student-fees', compact('studentInvoices'));
    }

    public function pay($id)
    {
        $invoice = Invoice::with('student')->findOrFail($id);
        $student = $invoice->student;

        try {
            $paymentService = new PaymentService();
            $result = $paymentService->initiatePesapalPayment($student, $invoice->balance, $invoice->id, [
                'email_address' => $student->guardian_email ?? 'n/a@example.com',
                'phone_number'  => $student->guardian_phone ?? '0000000000',
                'first_name'    => $student->first_name,
                'last_name'     => $student->last_name,
            ]);

            if (!empty($result['redirect_url'])) {
                return redirect($result['redirect_url']);
            }

            return back()->with('error', 'Pesapal Error: ' . ($result['message'] ?? 'Failed to initiate payment'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function callback(Request $request)
    {
        $trackingId = $request->get('OrderTrackingId') ?? $request->get('tracking_id');
        if (!$trackingId) return redirect()->route('finance.invoices')->with('error', 'No tracking ID');

        $pesapal = new PesapalService();
        $response = $pesapal->verify($trackingId);

        if (isset($response['status_code']) && (string)$response['status_code'] === '1') {
            $paymentService = new PaymentService();
            $payment = $paymentService->recordPayment([
                'student_id'     => $request->student_id ?? 1,
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

            return redirect()->route('finance.invoices')->with('success', 'Payment recorded successfully.');
        }

        return redirect()->route('finance.invoices')->with('error', 'Payment failed or pending.');
    }

    // Expense Management
    public function expenses()
    {
        $expenses = Expense::with('branch')->latest()->get();
        return view('pages.finance.expenses', compact('expenses'));
    }

    public function createExpense()
    {
        $branches = \App\Models\Branch::all();
        return view('pages.finance.expense-create', compact('branches'));
    }

    public function storeExpense(Request $request)
    {
        $request->validate([
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string',
            'expense_date' => 'required|date',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        Expense::create($request->all());
        return redirect()->route('finance.expenses.index')->with('success', 'Expense created successfully.');
    }

    // Audit Logs
    public function auditLogs()
    {
        $auditLogs = AuditLog::with('user')->latest()->get();
        return view('pages.finance.audit-logs', compact('auditLogs'));
    }
}
