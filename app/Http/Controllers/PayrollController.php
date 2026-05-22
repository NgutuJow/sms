<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PayrollRecord;
use App\Models\Teacher;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    public function index()
    {
        $payrollRecords = PayrollRecord::latest()->paginate(20);
        $totalSalaries = PayrollRecord::where('status', 'paid')->sum('net_salary');
        $pendingSalaries = PayrollRecord::where('status', 'approved')->sum('net_salary');

        return view('pages.finance.payroll.index', compact('payrollRecords', 'totalSalaries', 'pendingSalaries'));
    }

    public function create()
    {
        $teachers = Teacher::all();
        $currentPayPeriod = now()->format('Y-m');
        return view('pages.finance.payroll.create', compact('teachers', 'currentPayPeriod'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'base_salary' => 'required|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'pay_period' => 'required|string',
        ]);

        $validated['deductions'] = $validated['deductions'] ?? 0;
        $validated['allowances'] = $validated['allowances'] ?? 0;
        $validated['net_salary'] = $validated['base_salary'] + $validated['allowances'] - $validated['deductions'];
        $validated['status'] = 'draft';

        $payroll = PayrollRecord::create($validated);

        $this->logAudit('create', 'PayrollRecord', $payroll->id, "Created payroll for teacher {$validated['teacher_id']} - {$validated['pay_period']}");

        return redirect()->route('finance.payroll.index')->with('success', 'Payroll record created successfully.');
    }

    public function show(PayrollRecord $payrollRecord)
    {
        return view('pages.finance.payroll.show', compact('payrollRecord'));
    }

    public function edit(PayrollRecord $payrollRecord)
    {
        $teachers = Teacher::all();
        return view('pages.finance.payroll.edit', compact('payrollRecord', 'teachers'));
    }

    public function update(Request $request, PayrollRecord $payrollRecord)
    {
        $validated = $request->validate([
            'base_salary' => 'required|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,approved,paid',
            'payment_date' => 'nullable|date',
        ]);

        $validated['deductions'] = $validated['deductions'] ?? 0;
        $validated['allowances'] = $validated['allowances'] ?? 0;
        $validated['net_salary'] = $validated['base_salary'] + $validated['allowances'] - $validated['deductions'];

        $oldStatus = $payrollRecord->status;
        $payrollRecord->update($validated);

        if ($oldStatus !== $validated['status']) {
            $this->logAudit('update', 'PayrollRecord', $payrollRecord->id, "Status changed from {$oldStatus} to {$validated['status']}");
        }

        return redirect()->route('finance.payroll.show', $payrollRecord)->with('success', 'Payroll record updated successfully.');
    }

    public function destroy(PayrollRecord $payrollRecord)
    {
        $this->logAudit('delete', 'PayrollRecord', $payrollRecord->id, "Deleted payroll record for {$payrollRecord->pay_period}");
        $payrollRecord->delete();

        return redirect()->route('finance.payroll.index')->with('success', 'Payroll record deleted successfully.');
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
