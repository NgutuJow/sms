<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::latest()->paginate(20);
        $totalExpenses = Expense::sum('amount');
        $monthlyExpenses = Expense::whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->sum('amount');

        return view('pages.finance.expenses', compact('expenses', 'totalExpenses', 'monthlyExpenses'));
    }

    public function create()
    {
        return view('pages.finance.expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
        ]);

        $validated['reference_no'] = 'EXP-' . time();
        $expense = Expense::create($validated);

        // Update budget if exists
        try {
            $budget = \App\Models\Budget::where('category', $expense->category)
                ->where('month', $expense->expense_date->format('F'))
                ->where('year', $expense->expense_date->format('Y'))
                ->first();
            
            if ($budget) {
                $budget->increment('spent_amount', $expense->amount);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to update budget for expense {$expense->id}: " . $e->getMessage());
        }

        $this->logAudit('create', 'Expense', $expense->id, "Created expense: {$validated['reference_no']}");

        return redirect()->route('finance.expenses.index')->with('success', 'Expense created successfully.');
    }

    public function show(Expense $expense)
    {
        return view('pages.finance.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        return view('pages.finance.expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $oldStatus = $expense->status;
        $expense->update($validated);

        if ($oldStatus !== $validated['status']) {
            $this->logAudit('update', 'Expense', $expense->id, "Status changed from {$oldStatus} to {$validated['status']}");
        }

        return redirect()->route('finance.expenses.show', $expense)->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $this->logAudit('delete', 'Expense', $expense->id, "Deleted expense: {$expense->reference_no}");
        $expense->delete();

        return redirect()->route('finance.expenses.index')->with('success', 'Expense deleted successfully.');
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
