<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fine;
use App\Models\Invoice;
use App\Models\Student;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class FineController extends Controller
{
    public function index()
    {
        $fines = Fine::latest()->paginate(20);
        $totalFines = Fine::where('status', 'pending')->sum('fine_amount');
        $paidFines = Fine::where('status', 'paid')->sum('fine_amount');

        return view('pages.finance.fines.index', compact('fines', 'totalFines', 'paidFines'));
    }

    public function create()
    {
        $invoices = Invoice::where('balance', '>', 0)->get();
        $students = Student::all();
        return view('pages.finance.fines.create', compact('invoices', 'students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'student_id' => 'required|exists:students,id',
            'percentage' => 'required|numeric|min:0|max:100',
            'reason' => 'nullable|string',
            'due_date' => 'required|date',
        ]);

        $invoice = Invoice::find($validated['invoice_id']);
        $validated['fine_amount'] = ($invoice->balance * $validated['percentage']) / 100;
        $validated['applied_date'] = now();
        $validated['status'] = 'pending';

        $fine = Fine::create($validated);

        $this->logAudit('create', 'Fine', $fine->id, "Created fine: {$validated['percentage']}% on invoice {$invoice->reference_no}");

        return redirect()->route('finance.fines.index')->with('success', 'Fine created successfully.');
    }

    public function show(Fine $fine)
    {
        return view('pages.finance.fines.show', compact('fine'));
    }

    public function edit(Fine $fine)
    {
        $invoices = Invoice::all();
        return view('pages.finance.fines.edit', compact('fine', 'invoices'));
    }

    public function update(Request $request, Fine $fine)
    {
        $validated = $request->validate([
            'percentage' => 'required|numeric|min:0|max:100',
            'reason' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,waived,paid',
        ]);

        $oldStatus = $fine->status;
        $fine->update($validated);

        if ($oldStatus !== $validated['status']) {
            $this->logAudit('update', 'Fine', $fine->id, "Status changed from {$oldStatus} to {$validated['status']}");
        }

        return redirect()->route('finance.fines.show', $fine)->with('success', 'Fine updated successfully.');
    }

    public function destroy(Fine $fine)
    {
        $this->logAudit('delete', 'Fine', $fine->id, "Deleted fine for student {$fine->student_id}");
        $fine->delete();

        return redirect()->route('finance.fines.index')->with('success', 'Fine deleted successfully.');
    }

    private function logAudit($action, $model, $modelId, $description)
    {
        AuditLog::create([
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'user_id' => Auth::id(),
            'description' => $description,
        ]);
    }
}
