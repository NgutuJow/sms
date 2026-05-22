<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\School;

class BranchController extends Controller
{
    public function index($school_id)
    {
        $school = School::findOrFail($school_id);
        $branches = Branch::where('school_id', $school_id)->latest()->get();

        return view('pages.branches.index', compact('school', 'branches'));
    }

    public function create($school_id)
    {
        $school = School::findOrFail($school_id);

        return view('pages.branches.create', compact('school'));
    }

    public function store(Request $request, $school_id)
{
    $request->validate([
        'branch_name' => 'required|string|max:255',
        'branch_code' => 'required|unique:branches|max:50',
        'education_level' => 'required',
        'phone' => 'required|string',
        'email' => 'nullable|email',
        'region' => 'required',
        'district' => 'required',
        'ward' => 'required',
    ]);

    try {
        Branch::create(array_merge($request->all(), [
            'school_id' => $school_id,
            'status' => 1
        ]));

        return redirect()->route('school.branches', $school_id)
            ->with('success', 'Hongera! Branch mpya imesajiliwa kikamilifu.');
    } catch (\Exception $e) {
        return back()->withInput()->with('error', 'Imeshindikana kusajili branch. Jaribu tena.');
    }
}

    public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        return view('pages.branches.edit', compact('branch'));
    }

  public function update(Request $request, $id)
{
    $branch = Branch::findOrFail($id);

    $request->validate([
        'branch_name' => 'required|string|max:255',
        'branch_code' => 'required|unique:branches,branch_code,' . $id,
        'education_level' => 'required',
        'phone' => 'required',
        'email' => 'nullable|email',
        'region' => 'required',
        'district' => 'required',
        'ward' => 'required',
    ]);

    $branch->update($request->all());

    return redirect()->route('school.branches', $branch->school_id)
        ->with('success', 'Taarifa za branch zimefanyiwa marekebisho.');
}

public function toggleStatus($id)
{
    $branch = Branch::findOrFail($id);
    $branch->status = !$branch->status;
    $branch->save();

    return back()->with('success', 'Status updated successfully');
}

    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        $school_id = $branch->school_id;

        $branch->delete();

        return redirect()->route('school.branches', $school_id)
            ->with('success', 'Branch deleted successfully');
    }
}