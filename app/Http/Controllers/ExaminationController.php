<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ExaminationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $examinations = Examination::with(['exam', 'class', 'subject', 'teacher'])
            ->latest()
            ->paginate(15);

        return view('pages.examinations.index', compact('examinations'));
    }

    public function create()
    {
        $exams = Exam::all();
        $classes = SchoolClass::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();

        return view('pages.examinations.create', compact('exams', 'classes', 'subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'question_count' => 'required|integer|min:1',
            'duration_minutes' => 'required|integer|min:15',
            'instructions' => 'nullable|string',
            'is_published' => 'boolean'
        ]);

        $examination = Examination::create(array_merge($validated, [
            'is_published' => $request->boolean('is_published', false),
            'published_date' => $request->boolean('is_published') ? Carbon::now() : null,
            'status' => $request->boolean('is_published') ? 'PUBLISHED' : 'DRAFT'
        ]));

        return redirect()->route('examinations.show', $examination->id)
            ->with('success', 'Examination created successfully');
    }

    public function show($id)
    {
        $examination = Examination::with(['exam', 'class', 'subject', 'teacher', 'marks'])
            ->findOrFail($id);

        return view('pages.examinations.show', compact('examination'));
    }

    public function edit($id)
    {
        $examination = Examination::findOrFail($id);
        $exams = Exam::all();
        $classes = SchoolClass::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();

        return view('pages.examinations.edit', compact('examination', 'exams', 'classes', 'subjects', 'teachers'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'question_count' => 'required|integer|min:1',
            'duration_minutes' => 'required|integer|min:15',
            'instructions' => 'nullable|string',
            'is_published' => 'boolean'
        ]);

        $examination = Examination::findOrFail($id);
        $examination->update(array_merge($validated, [
            'is_published' => $request->boolean('is_published', $examination->is_published),
            'published_date' => $request->boolean('is_published') ? Carbon::now() : $examination->published_date,
            'status' => $request->boolean('is_published') ? 'PUBLISHED' : 'DRAFT'
        ]));

        return redirect()->route('examinations.show', $examination->id)
            ->with('success', 'Examination updated successfully');
    }

    public function destroy($id)
    {
        $examination = Examination::findOrFail($id);
        $examination->marks()->delete();
        $examination->delete();

        return redirect()->route('examinations.index')
            ->with('success', 'Examination deleted successfully');
    }

    public function publish($id)
    {
        $examination = Examination::findOrFail($id);
        $examination->update([
            'is_published' => true,
            'published_date' => Carbon::now(),
            'status' => 'PUBLISHED'
        ]);

        return redirect()->back()->with('success', 'Examination published successfully');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'action' => 'required|string|in:publish,delete'
        ]);

        $examinations = Examination::whereIn('id', $request->ids)->get();

        if ($request->action === 'publish') {
            foreach ($examinations as $examination) {
                $examination->update([
                    'is_published' => true,
                    'published_date' => Carbon::now(),
                    'status' => 'PUBLISHED'
                ]);
            }
        } else {
            foreach ($examinations as $examination) {
                $examination->marks()->delete();
                $examination->delete();
            }
        }

        return redirect()->back()->with('success', 'Bulk action completed successfully');
    }
}
