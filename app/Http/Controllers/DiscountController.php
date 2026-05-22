<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;
use App\Models\Student;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::with('student')->latest()->paginate(20);
        $totalDiscount = Discount::sum('amount');

        return view('pages.finance.discounts.index', compact('discounts', 'totalDiscount'));
    }

    public function create()
    {
        $students = Student::all();
        return view('pages.finance.discounts.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'discount_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'reason' => 'nullable|string',
            'valid_from' => 'required|date',
            'valid_to' => 'nullable|date|after_or_equal:valid_from',
        ]);

        $discount = Discount::create($validated);
        $this->logAudit('create', 'Discount', $discount->id, "Created discount for student {$discount->student_id}");

        return redirect()->route('finance.discounts.index')->with('success', 'Discount created successfully.');
    }

    public function show(Discount $discount)
    {
        return view('pages.finance.discounts.show', compact('discount'));
    }

    public function edit(Discount $discount)
    {
        $students = Student::all();
        return view('pages.finance.discounts.edit', compact('discount', 'students'));
    }

    public function update(Request $request, Discount $discount)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'discount_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'reason' => 'nullable|string',
            'valid_from' => 'required|date',
            'valid_to' => 'nullable|date|after_or_equal:valid_from',
        ]);

        $oldValues = $discount->getOriginal();
        $discount->update($validated);
        $this->logAudit('update', 'Discount', $discount->id, "Updated discount for student {$discount->student_id}", $oldValues, $validated);

        return redirect()->route('finance.discounts.show', $discount)->with('success', 'Discount updated successfully.');
    }

    public function destroy(Discount $discount)
    {
        $this->logAudit('delete', 'Discount', $discount->id, "Deleted discount for student {$discount->student_id}");
        $discount->delete();

        return redirect()->route('finance.discounts.index')->with('success', 'Discount deleted successfully.');
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
