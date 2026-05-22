<?php

namespace App\Http\Controllers;

use App\Models\SubjectAssignment;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectAssignmentController extends Controller
{
    public function index()
    {
        $assignments = SubjectAssignment::with(['classData', 'subject'])->get();
        return view('pages.subject_assignments.index', compact('assignments'));
    }

    public function create()
    {
        $classes = SchoolClass::all();
        $subjects = Subject::all();

        return view('pages.subject_assignments.create', compact('classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'subject_id' => 'required',
        ]);

        SubjectAssignment::create($request->all());

        return redirect()->route('subject-assignments.index')
            ->with('success', 'Subject assigned successfully');
    }
}