<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\Branch;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = Budget::with('branch')->latest()->paginate(20);
        $totalAllocated = Budget::sum('allocated_amount');
        $totalSpent = Budget::sum('spent_amount');

        return view('pages.finance.budgets.index', compact('budgets', 'totalAllocated', 'totalSpent'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('pages.finance.budgets.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'allocated_amount' => 'required|numeric|min:0',
            'spent_amount' => 'nullable|numeric|min:0',
            'month' => 'required|string|max:20',
            'year' => 'required|string|max:10',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $validated['spent_amount'] = $validated['spent_amount'] ?? 0;
        $budget = Budget::create($validated);

        $this->logAudit('create', 'Budget', $budget->id, "Created budget for {$budget->category}");

        return redirect()->route('finance.budgets.index')->with('success', 'Budget created successfully.');
    }

    public function show(Budget $budget)
    {
        return view('pages.finance.budgets.show', compact('budget'));
    }

    public function edit(Budget $budget)
    {
        $branches = Branch::all();
        return view('pages.finance.budgets.edit', compact('budget', 'branches'));
    }

    public function update(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'allocated_amount' => 'required|numeric|min:0',
            'spent_amount' => 'nullable|numeric|min:0',
            'month' => 'required|string|max:20',
            'year' => 'required|string|max:10',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $validated['spent_amount'] = $validated['spent_amount'] ?? 0;
        $oldValues = $budget->getOriginal();
        $budget->update($validated);

        $this->logAudit('update', 'Budget', $budget->id, "Updated budget {$budget->category}", $oldValues, $validated);

        return redirect()->route('finance.budgets.show', $budget)->with('success', 'Budget updated successfully.');
    }

    public function destroy(Budget $budget)
    {
        $this->logAudit('delete', 'Budget', $budget->id, "Deleted budget {$budget->category}");
        $budget->delete();

        return redirect()->route('finance.budgets.index')->with('success', 'Budget deleted successfully.');
    }

    private function logAudit($action, $model, $modelId, $description, $oldValues = null, $newValues = null)
    {
        AuditLog::create([
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'user_id' => Auth::id(),
            'changes' => ['old' => $oldValues, 'new' => $newValues],
            'description' => $description,
        ]);
    }
}
