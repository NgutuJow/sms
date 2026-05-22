<?php

namespace App\Http\Controllers;

use App\Models\FeeStructure;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class FeeStructureController extends Controller
{
    public function index()
    {
        $feeStructures = FeeStructure::with('schoolClass')->latest()->paginate(20);
        return view('pages.finance.fee-structures', compact('feeStructures'));
    }

    public function create()
    {
        $classes = SchoolClass::all();
        return view('pages.finance.fee-structures.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'nullable|exists:school_classes,id',
            'academic_year' => 'required|string|max:255',
            'fee_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'allow_installments' => 'boolean',
            'number_of_installments' => 'required_if:allow_installments,1|integer|min:1|max:12',
            'installment_dates' => 'nullable|array',
        ]);

        $data = $request->only(['class_id', 'academic_year', 'fee_type', 'amount']);

        // Check if installment columns exist in the database
        $hasInstallmentColumns = \Schema::hasColumn('fee_structures', 'allow_installments');

        if ($hasInstallmentColumns) {
            $data['allow_installments'] = $request->boolean('allow_installments', false);

            if ($data['allow_installments']) {
                $data['number_of_installments'] = (int) $request->input('number_of_installments', 1);
            } else {
                $data['number_of_installments'] = 1;
            }

            if ($request->has('installment_dates')) {
                $data['installment_dates'] = json_encode($request->installment_dates);
            }
        }

        FeeStructure::create($data);

        return redirect()->route('finance.fee-structures.index')
            ->with('success', 'Fee structure created successfully.');
    }

    public function show(FeeStructure $feeStructure)
    {
        return view('pages.finance.fee-structures.show', compact('feeStructure'));
    }

    public function edit(FeeStructure $feeStructure)
    {
        $classes = SchoolClass::all();
        return view('pages.finance.fee-structures.edit', compact('feeStructure', 'classes'));
    }

    public function update(Request $request, FeeStructure $feeStructure)
    {
        $request->validate([
            'class_id' => 'nullable|exists:school_classes,id',
            'academic_year' => 'required|string|max:255',
            'fee_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'allow_installments' => 'boolean',
            'number_of_installments' => 'required_if:allow_installments,1|integer|min:1|max:12',
            'installment_dates' => 'nullable|array',
        ]);

        $data = $request->only(['class_id', 'academic_year', 'fee_type', 'amount']);

        // Check if installment columns exist in the database
        $hasInstallmentColumns = \Schema::hasColumn('fee_structures', 'allow_installments');

        if ($hasInstallmentColumns) {
            $data['allow_installments'] = $request->boolean('allow_installments', false);

            if ($data['allow_installments']) {
                $data['number_of_installments'] = (int) $request->input('number_of_installments', 1);
            } else {
                $data['number_of_installments'] = 1;
            }

            if ($request->has('installment_dates')) {
                $data['installment_dates'] = json_encode($request->installment_dates);
            }
        }

        $feeStructure->update($data);

        return redirect()->route('finance.fee-structures.index')
            ->with('success', 'Fee structure updated successfully.');
    }

    public function destroy(FeeStructure $feeStructure)
    {
        $feeStructure->delete();

        return redirect()->route('finance.fee-structures.index')
            ->with('success', 'Fee structure deleted successfully.');
    }
}
