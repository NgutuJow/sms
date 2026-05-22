<?php
namespace App\Http\Controllers;

use App\Models\Syllabus;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <--- ONGEZA MSTARI HUU HAPA
class SyllabusController extends Controller
{
    public function index()
    {
        $syllabuses = Syllabus::with('subject')->latest()->get();
        $subjects = Subject::all();
        return view('pages.academic.syllabus.index', compact('syllabuses', 'subjects'));
    }

public function store(Request $request)
{
    // 1. Validate data (hakikisha file lipo na ni PDF)
    $request->validate([
        'subject_id' => 'required|exists:subjects,id',
        'topic_name' => 'required|string|max:255',
        'file_path'  => 'required|mimes:pdf|max:10240', // Max 10MB
    ]);

    // 2. Shughulikia file upload
    if ($request->hasFile('file_path')) {
        // Hifadhi file kwenye folder la 'syllabuses' ndani ya storage/app/public
        $path = $request->file('file_path')->store('syllabuses', 'public');
    }

    // 3. Save kwenye Database
    Syllabus::create([
        'subject_id' => $request->subject_id,
        'topic_name' => $request->topic_name,
        'file_path'  => $path, // Hapa ndipo thamani inapoingia sasa
    ]);

    return redirect()->back()->with('success', 'Syllabus imepakiwa kikamilifu!');
}

    // Marekebisho ya mada (Update)
    public function update(Request $request, $id)
    {
        $syllabus = Syllabus::findOrFail($id);
        
        $data = $request->except(['_token', '_method']);

        // Mantiki ya Tarehe ya Kukamilisha
        if ($request->status == 'Completed') {
            $data['completion_date'] = now();
        } else {
            $data['completion_date'] = null; // Rudisha null kama mada bado haijaisha
        }

        $syllabus->update($data);
        return back()->with('success', 'Taarifa za mada zimesasishwa!');
    }

    public function destroy($id)
    {
        Syllabus::destroy($id);
        return back()->with('success', 'Mada imefutwa!');
    }
    // Hakikisha jina ni 'download' (herufi ndogo zote)
public function download($id)
{
    $syllabus = Syllabus::findOrFail($id);

    // Angalia kama file lipo kwenye storage
    if (Storage::disk('public')->exists($syllabus->file_path)) {
        return Storage::disk('public')->download($syllabus->file_path, $syllabus->topic_name . '.pdf');
    }

    return back()->with('error', 'File halikupatikana server.');
}
}