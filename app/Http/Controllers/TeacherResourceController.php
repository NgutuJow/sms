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
        // Unaweza kutumia hii kuonyesha file moja moja kama unahitaji tracking
        $syllabus = Syllabus::findOrFail($id);
        $path = storage_path('app/public/' . $syllabus->file_path);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    // Nimeacha mbinu nyingine (store, update, destroy) zikiwa tupu 
    // mpaka utakapohitaji mwalimu aweze ku-upload mwenyewe.
}