<?php
namespace App\Http\Controllers;

use App\Models\Timetable;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\User; // Tunachukulia walimu wapo kwenye Table ya Users
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    public function index()
{
    // 1. Pata sessions na madarasa (kama unavyofanya kwenye dashboard kuu)
    $sessions = \App\Models\Session::with('semesters')->get();
    $classes = \App\Models\Classes::with(['streams', 'subjects', 'branch'])->get();

    // 2. Pata walimu (Hakikisha column ni 'role' na value ni 'teacher')
    $teachers = \App\Models\User::where('role', 'teacher')->get(); 

    // 3. Pata data za Timetable na Syllabus kwa ajili ya zile Table za kwenye Tabs
    $timetables = Timetable::with(['stream', 'subject', 'teacher'])->get();
    $syllabuses = \App\Models\Syllabus::with('subject')->get();

    // MUHIMU: Hakikisha hapa unarudisha ile view yenye TABS zote (Full Code niliyokupa mwanzo)
    return view('pages.academic.index', compact(
        'sessions', 
        'classes', 
        'teachers', 
        'timetables', 
        'syllabuses'
    ));
}

   public function store(Request $request)
{
    $request->validate([
        'stream_id' => 'required|exists:streams,id',
        'timetable_name' => 'required|string|max:255', // Jina la ratiba mfano "Term 1 Routine"
        'timetable_pdf' => 'required|mimes:pdf|max:10240', // Max 10MB
    ]);

    if ($request->hasFile('timetable_pdf')) {
        $file = $request->file('timetable_pdf');
        $fileName = time() . '_timetable_' . $request->stream_id . '.pdf';
        $path = $file->storeAs('timetables', $fileName, 'public');

        \App\Models\Timetable::create([
            'stream_id' => $request->stream_id,
            'timetable_name' => $request->timetable_name, // Hakikisha una column hii au tumia nyingine
            'file_path' => $path,
            // Ikiwa unatumia mfumo wa zamani, weka default values kwenye column zingine au zifanye nullable
        ]);

        return back()->with('success', 'Ratiba ya darasa imepakiwa (uploaded) kikamilifu!');
    }
}
    // Marekebisho ya kipindi kilichopo (Update)
    public function update(Request $request, $id)
    {
        $timetable = Timetable::findOrFail($id);
        
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $timetable->update($request->except(['_token', '_method']));
        return back()->with('success', 'Ratiba imerekebishwa!');
    }

    public function destroy($id)
    {
        Timetable::destroy($id);
        return back()->with('success', 'Kipindi kimefutwa kwenye ratiba!');
    }
}