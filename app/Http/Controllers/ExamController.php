<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Semester;
use App\Models\AcademicSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index()
    {
        // Tunavuta mitihani pamoja na session na semester ili kupunguza database queries (Eager Loading)
        $examinations = Exam::with(['academicSession', 'semester', 'createdBy'])->latest()->get();
        
        return view('pages.examinations.index', compact('examinations'));
    }

    public function create()
{
    // Hakikisha jina la variable hapa ni 'sessions'
    $sessions = AcademicSession::all(); 
    
    // Pia vuta semesters ili nazo zionekane
    $semesters = Semester::all(); 
    
    return view('pages.examinations.create', compact('sessions', 'semesters'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
        'name'                => 'required|string|max:255',
        'exam_type'           => 'required|string',
        'start_date'          => 'required|date', 
        // Tumia 'after_or_equal:start_date' na hakikisha field zote mbili zipo
        'end_date'            => 'required|date|after_or_equal:start_date',
        'total_marks'         => 'required|numeric',
        'passing_marks'       => 'required|numeric',
        'academic_session_id' => 'required|exists:academic_sessions,id',
        'semester_id'         => 'required|exists:semesters,id',
        'description'         => 'nullable|string',
    ]);

        // Ongeza aliyengiza (Auth User)
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'active';

        Exam::create($validated);

        return redirect()->route('exams.index')->with('success', 'Examination created successfully!');
    }
public function viewClasses($id)
{
    $exam = Exam::findOrFail($id);
    
    // Tumia SchoolClass badala ya StudentClass
    // Na tunahakikisha tunavuta subjects na teacher wao
    $examClasses = \App\Models\SchoolClass::with(['subjects.teacher'])->get();

    return view('pages.examinations.classes', compact('exam', 'examClasses'));
}

// Function ya Download
public function downloadSubject($exam_id, $subject_id)
{
    // Logic ya kutafuta file la mtihani huu na somo hili
    // return response()->download($filePath);
    return back()->with('success', 'Pakua mtihani umeanza...');
}
// Kuidhinisha mtihani
public function approve($id)
{
    $exam = Exam::findOrFail($id);
    $exam->update(['is_approved' => true]);
    return back()->with('success', 'Examination approved successfully!');
}

// Kukataa mtihani
public function deny($id)
{
    $exam = Exam::findOrFail($id);
    $exam->update(['is_approved' => false]); // Au unaweza kuufuta/kuuweka draft
    return back()->with('error', 'Examination has been denied.');
}
// Kuidhinisha somo maalum kwenye darasa fulani


// Kukataa somo maalum kwenye darasa fulani
public function destroy($id)
{
    $exam = Exam::findOrFail($id);
    $exam->delete();

    return redirect()->route('exams.index')->with('success', 'Examination deleted successfully!');
}

public function approveSubject($examId, $classId, $subjectId)
{
    \App\Models\ExamPaper::where([
        'exam_id' => $examId,
        'class_id' => $classId,
        'subject_id' => $subjectId
    ])->update(['status' => 'approved']);

    return back()->with('success', 'Examination paper approved successfully!');
}

public function denySubject($examId, $classId, $subjectId)
{
    \App\Models\ExamPaper::where([
        'exam_id' => $examId,
        'class_id' => $classId,
        'subject_id' => $subjectId
    ])->update(['status' => 'denied']);

    return back()->with('error', 'Examination paper has been denied.');
}
}