<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('branch')->latest()->get();
        return view('pages.teachers.index', compact('teachers'));
    }

    public function create()
    {
        $branches = Branch::where('status', 1)->get();
        return view('pages.teachers.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'teacher_id_number' => 'required|unique:teachers,teacher_id_number',
            'full_name' => 'required|string|max:255',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email',
            'gender' => 'required',
            'designation' => 'required',
            'qualification' => 'required',
            'joining_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {

            DB::beginTransaction();

            // 1. Create User Account
            $user = User::create([
                'name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make('teacher123'),
                'role' => 'teacher'
            ]);

            // 2. Prepare teacher data
            $data = $request->only([
                'branch_id',
                'teacher_id_number',
                'full_name',
                'phone',
                'email',
                'gender',
                'designation',
                'qualification',
                'joining_date'
            ]);

            $data['user_id'] = $user->id;

            // 3. Handle image upload
            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/teachers'), $imageName);
                $data['image'] = $imageName;
            }

            // 4. Create teacher
            Teacher::create($data);

            DB::commit();

            return redirect()->route('teachers.index')
                ->with('success', 'Teacher registered successfully!');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $teacher = Teacher::findOrFail($id);
        $branches = Branch::where('status', 1)->get();

        return view('pages.teachers.edit', compact('teacher', 'branches'));
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'teacher_id_number' => 'required|unique:teachers,teacher_id_number,' . $id,
            'full_name' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only([
            'branch_id',
            'teacher_id_number',
            'full_name',
            'phone'
        ]);

        if ($request->hasFile('image')) {

            if ($teacher->image && File::exists(public_path('uploads/teachers/' . $teacher->image))) {
                File::delete(public_path('uploads/teachers/' . $teacher->image));
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/teachers'), $imageName);

            $data['image'] = $imageName;
        }

        $teacher->update($data);

        if ($teacher->user) {
            $teacher->user->update([
                'name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
        }

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher information updated!');
    }

    public function toggleStatus($id)
    {
        $teacher = Teacher::findOrFail($id);

        $teacher->status = !$teacher->status;
        $teacher->save();

        return back()->with('success', 'Teacher status updated!');
    }

    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);

        if ($teacher->image && File::exists(public_path('uploads/teachers/' . $teacher->image))) {
            File::delete(public_path('uploads/teachers/' . $teacher->image));
        }

        $teacher->delete();

        return back()->with('success', 'Teacher deleted successfully!');
    }
}