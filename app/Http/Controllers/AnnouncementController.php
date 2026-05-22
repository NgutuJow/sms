<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::where('is_active', true)
            ->with('creator')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.announcements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'audience' => 'required|in:staff,parent',
            'pdf' => 'nullable|mimes:pdf|max:10240', // Max 10MB
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'audience' => $request->audience,
            'created_by' => Auth::id(),
            'is_active' => true
        ];

        // Handle PDF upload
        if ($request->hasFile('pdf')) {
            $file = $request->file('pdf');
            $path = $file->store('announcements', 'public');
            $data['pdf_path'] = $path;
        }

        $announcement = Announcement::create($data);

        // Notify parents via WhatsApp if audience is 'parent'
        if ($request->audience === 'parent') {
            try {
                $whatsappService = app(\App\Services\WhatsAppService::class);
                $students = \App\Models\Student::whereNotNull('guardian_phone')->get();
                $msg = "TANGAZO JIPYA: {$request->title}. \n\n{$request->description}\n\nUnaweza kuona maelezo zaidi kwenye portal ya wazazi. Ahsante.";
                
                // For bulk, we might want a queue, but let's do it simply for now or log
                foreach ($students as $student) {
                    $whatsappService->sendMessage($student, $msg, 'system');
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Announcement WhatsApp notification failed: " . $e->getMessage());
            }
        }

        return redirect()->route('announcements.index')->with('success', 'Announcement created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcement $announcement)
    {
        return view('pages.admin.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        return view('pages.admin.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'audience' => 'required|in:staff,parent',
            'pdf' => 'nullable|mimes:pdf|max:10240',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'audience' => $request->audience,
        ];

        // Handle PDF upload
        if ($request->hasFile('pdf')) {
            // Delete old PDF if exists
            if ($announcement->pdf_path) {
                Storage::disk('public')->delete($announcement->pdf_path);
            }
            $file = $request->file('pdf');
            $path = $file->store('announcements', 'public');
            $data['pdf_path'] = $path;
        }

        $announcement->update($data);

        return redirect()->route('announcements.index')->with('success', 'Announcement updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        // Delete PDF file if exists
        if ($announcement->pdf_path) {
            Storage::disk('public')->delete($announcement->pdf_path);
        }

        $announcement->delete();

        return redirect()->route('announcements.index')->with('success', 'Announcement deleted successfully');
    }

    /**
     * Get announcements for teacher dashboard
     */
    public function getForDashboard($audience = 'staff')
    {
        return Announcement::where('is_active', true)
            ->where('audience', $audience)
            ->with('creator')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Download announcement PDF
     */
    public function downloadPdf(Announcement $announcement)
    {
        if (!$announcement->pdf_path || !Storage::disk('public')->exists($announcement->pdf_path)) {
            abort(404, 'PDF file not found');
        }

        return Storage::disk('public')->download($announcement->pdf_path);
    }
}
