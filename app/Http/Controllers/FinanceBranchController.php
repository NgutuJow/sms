<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\School;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class FinanceBranchController extends Controller
{
    public function index()
    {
        $branches = Branch::with('school')->latest()->paginate(20);
        return view('pages.finance.branches.index', compact('branches'));
    }

    public function create()
    {
        $schools = School::all();
        return view('pages.finance.branches.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'branch_name' => 'required|string|max:255',
            'branch_code' => 'required|string|max:50|unique:branches,branch_code',
            'branch_type' => 'nullable|string|max:255',
            'education_level' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'region' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'street' => 'nullable|string|max:255',
            'physical_address' => 'nullable|string|max:255',
            'postal_address' => 'nullable|string|max:255',
        ]);

        $branch = Branch::create(array_merge($validated, ['status' => 1]));
        $this->logAudit('create', 'Branch', $branch->id, "Created branch {$branch->branch_name}");

        return redirect()->route('finance.branches.index')->with('success', 'Branch created successfully.');
    }

    public function show(Branch $branch)
    {
        return view('pages.finance.branches.show', compact('branch'));
    }

    public function edit(Branch $branch)
    {
        $schools = School::all();
        return view('pages.finance.branches.edit', compact('branch', 'schools'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'branch_name' => 'required|string|max:255',
            'branch_code' => 'required|string|max:50|unique:branches,branch_code,' . $branch->id,
            'branch_type' => 'nullable|string|max:255',
            'education_level' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'region' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'street' => 'nullable|string|max:255',
            'physical_address' => 'nullable|string|max:255',
            'postal_address' => 'nullable|string|max:255',
        ]);

        $oldValues = $branch->getOriginal();
        $branch->update($validated);
        $this->logAudit('update', 'Branch', $branch->id, "Updated branch {$branch->branch_name}", $oldValues, $validated);

        return redirect()->route('finance.branches.show', $branch)->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        $this->logAudit('delete', 'Branch', $branch->id, "Deleted branch {$branch->branch_name}");
        $branch->delete();

        return redirect()->route('finance.branches.index')->with('success', 'Branch deleted successfully.');
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
