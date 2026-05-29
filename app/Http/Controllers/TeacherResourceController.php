<?php

namespace App\Http\Controllers;

use App\Models\Syllabus;
use App\Models\Timetable;
use App\Models\SchoolClass;
use App\Models\AcademicSession;
use Illuminate\Http\Request;

class TeacherResourceController extends Controller
{
    /**
     * Display a listing of the resources (Syllabus & Timetable).
     */
public function index(Request $request)
{
    $classes = SchoolClass::all();
    $academicYears = AcademicSession::all();

    // Tunapakia subject PAMOJA na schoolClass yake
    $syllabus = Syllabus::with(['subject.schoolClass'])
        ->when($request->class_id, function ($query) use ($request) {
            return $query->whereHas('subject', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        })
        ->latest()->get();

    // Tunapakia stream PAMOJA na session yake pamoja na class info ya stream
    $timetables = Timetable::with(['stream.schoolClass', 'session'])
        ->when($request->class_id, function ($query) use ($request) {
            return $query->where('stream_id', $request->class_id);
        })
        ->latest()->get();

    return view('pages.teacher.resources.index', compact('syllabus', 'timetables', 'classes', 'academicYears'));
}

    /**
     * Display the specific PDF file in a browser.
     */
    public function show($id)
    {
        $syllabus = Syllabus::findOrFail($id);
        
        if (!$syllabus->file_path) {
            return back()->with('error', 'Faili halipo.');
        }

        $path = ltrim($syllabus->file_path, '/');
        $absolutePath = storage_path('app/public/' . $path);

        if (file_exists($absolutePath)) {
            return response()->file($absolutePath);
        }

        return back()->with('error', 'Faili halikupatikana server.');
    }

    // Nimeacha mbinu nyingine (store, update, destroy) zikiwa tupu 
    // mpaka utakapohitaji mwalimu aweze ku-upload mwenyewe.
}