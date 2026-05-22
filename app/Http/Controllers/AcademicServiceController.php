<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Semester;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\Branch;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicServiceController extends Controller
{
    /**
     * Display all academic services in one dashboard using tabs.
     */
 public function index()
{
    $data = [
        'sessions'       => AcademicSession::with('semesters')->latest()->get(),
        'activeSemester' => Semester::where('is_current', 1)->first(),
        'classes'        => SchoolClass::with(['branch', 'streams.teacher', 'subjects'])->latest()->get(),
        'branches'       => Branch::where('status', 1)->get(),
        'teachers'       => Teacher::where('status', 1)->get(),
        
        // REKEBISHA HAPA: Hakikisha unapata 'teacher' na 'subject'
        'timetables'     => \App\Models\Timetable::with(['stream', 'subject', 'teacher'])->latest()->get(),
        'syllabuses'     => \App\Models\Syllabus::with(['subject', 'teacher'])->latest()->get(),
    ];

    return view('pages.academic.index', $data);
}

    public function destroySession($id)
    {
        $session = AcademicSession::findOrFail($id);

        if ($session->is_current) {
            $fallback = AcademicSession::where('id', '!=', $id)->latest()->first();
            if ($fallback) {
                $fallback->update(['is_current' => 1]);
            }
        }

        $session->delete();

        return back()->with('success', 'Academic year removed successfully.');
    }

    public function setSemesterActive($id)
    {
        DB::transaction(function () use ($id) {
            Semester::where('is_current', 1)->update(['is_current' => 0]);

            $semester = Semester::findOrFail($id);
            $semester->update(['is_current' => 1]);

            AcademicSession::where('is_current', 1)->update(['is_current' => 0]);
            AcademicSession::where('id', $semester->academic_session_id)->update(['is_current' => 1]);
        });

        return back()->with('success', 'Active semester has been updated!');
    }

    public function destroySemester($id)
    {
        $semester = Semester::findOrFail($id);
        $semester->delete();

        return back()->with('success', 'Semester removed successfully.');
    }

    public function storeSession(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:academic_sessions,name',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        AcademicSession::create($request->all());
        return back()->with('success', 'Academic Session created successfully!');
    }

    public function storeSemester(Request $request)
    {
        $request->validate([
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'semester_name' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $data = array_merge($request->except('_token'), [
            'is_current' => 0,
        ]);

        Semester::create($data);

        return redirect()->route('academic.index')->with('success', 'Semester added!');
    }

    // --- CLASSES & STREAMS ---

    public function storeClass(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'class_name' => 'required|string|max:255',
        ]);

        SchoolClass::create($request->all());
        return back()->with('success', 'New Class (Grade) established!');
    }

    public function destroyClass($id)
    {
        $class = SchoolClass::findOrFail($id);
        $class->delete();

        return back()->with('success', 'Class removed successfully.');
    }

    public function storeStream(Request $request)
    {
        $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'stream_name' => 'required|string',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        Stream::create($request->all());
        return back()->with('success', 'Stream/Section assigned to class!');
    }

    public function destroyStream($id)
    {
        $stream = Stream::findOrFail($id);
        $stream->delete();

        return back()->with('success', 'Stream removed successfully.');
    }

    // --- SUBJECTS ---

    public function storeSubject(Request $request)
    {
        $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'subject_name' => 'required|string',
            'subject_code' => 'nullable|string',
            'type' => 'required|in:Theory,Practical,Both',
        ]);

        Subject::create($request->all());
        return back()->with('success', 'Subject linked to the class successfully!');
    }

    // --- STATUS UPDATES ---

    public function setSessionActive($id)
    {
        DB::transaction(function () use ($id) {
            AcademicSession::where('is_current', 1)->update(['is_current' => 0]);
            AcademicSession::where('id', $id)->update(['is_current' => 1]);
        });

        return back()->with('success', 'Active academic year has been updated!');
    }

    // --- AJAX METHODS ---

    public function getStreamsByClass($classId)
    {
        $streams = Stream::where('school_class_id', $classId)
            ->get(['id', 'stream_name as name']);

        return response()->json($streams);
    }
}