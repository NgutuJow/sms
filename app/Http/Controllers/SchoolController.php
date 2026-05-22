<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::latest()->get();
        return view('pages.school', compact('schools'));
    }

  public function create()
{
    return view('pages.createSchool');
}
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'code' => 'required|unique:schools',
        'school_type' => 'required',
        'email' => 'nullable|email',
        'phone' => 'required',
        'address' => 'required',
        'region' => 'required',
        'district' => 'required',
        'ward' => 'required',
    ]);

    School::create($request->all());

    // Badilisha hapa kwenda kwenye page ya index badala ya kubaki kwenye fomu
    return redirect()->route('school.index')->with('success', 'School created successfully');
}

    public function show($id)
    {
        //
    }

   public function edit($id)
        {
            $school = School::findOrFail($id);
            return view('pages.schoolEdit', compact('school'));
        }

   public function update(Request $request, $id)
{
    $school = School::findOrFail($id);

    $request->validate([
        'name' => 'required',
        'code' => 'required|unique:schools,code,' . $id,
        'school_type' => 'required',
        'email' => 'nullable|email',
        'phone' => 'required',
        'address' => 'required',
        'region' => 'required',
        'district' => 'required',
        'ward' => 'required',
    ]);

    $school->update($request->all());

    return redirect()->route('school.index')
        ->with('success', 'School updated successfully');
}

    public function destroy($id)
{
    $school = School::findOrFail($id);
    $school->delete();

    return redirect()->route('school.index')
        ->with('success', 'School deleted successfully');
}






}