<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Student;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Allowed roles for WhatsApp chat.
     *
     * @var array<int, string>
     */
    private $allowedRoles = ['admin', 'teacher', 'accountant'];

    public function index()
    {
        $this->authorizeRole();

        $students = Student::with(['classData'])
            ->whereNotNull('guardian_phone')
            ->orderBy('first_name')
            ->get();

        foreach ($students as $student) {
            $student->whatsapp_status = $this->whatsappService->isNumberOnWhatsApp($student->guardian_phone);
        }

        return view('pages.chat.index', compact('students'));
    }

    public function show($studentId)
    {
        $this->authorizeRole();

        $student = Student::with(['classData'])->findOrFail($studentId);
        $messages = ChatMessage::where('student_id', $student->id)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        $whatsappStatus = $this->whatsappService->isNumberOnWhatsApp($student->guardian_phone);

        return view('pages.chat.show', compact('student', 'messages', 'whatsappStatus'));
    }

    public function send(Request $request, $studentId)
    {
        $this->authorizeRole();

        $student = Student::findOrFail($studentId);
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $result = $this->whatsappService->sendMessage(
            $student,
            $request->message,
            Auth::user()->role,
            Auth::id()
        );

        if (!$result['success']) {
            return back()->with('error', $result['message'])
                        ->with('error_details', $result['error_details'] ?? null);
        }

        $redirect = redirect()->route('chat.show', $student->id)
            ->with('success', $result['message']);

        if (isset($result['whatsapp_url'])) {
            $redirect->with('whatsapp_url', $result['whatsapp_url']);
        }

        return $redirect;
    }

    public function destroy($id)
    {
        $this->authorizeRole();
        $message = ChatMessage::findOrFail($id);
        $studentId = $message->student_id;
        $message->delete();

        return redirect()->route('chat.show', $studentId)->with('success', 'Message removed from log.');
    }

    public function clearLogs($studentId)
    {
        $this->authorizeRole();
        ChatMessage::where('student_id', $studentId)->delete();

        return redirect()->route('chat.show', $studentId)->with('success', 'All conversation logs for this student have been cleared.');
    }

    private function authorizeRole()
    {
        $user = Auth::user();
        if (! $user || ! in_array(strtolower($user->role ?? ''), $this->allowedRoles, true)) {
            abort(403);
        }
    }
}
