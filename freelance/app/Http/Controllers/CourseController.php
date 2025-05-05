<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('gestioncours.gestioncour', ['courses' => $courses]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'contenu' => 'required|string',
            'status' => 'required|in:active,inactive',
            'files.*' => 'file|mimes:jpeg,png,jpg,gif,pdf,docx|max:2048',
        ]);

        $fileNames = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filePath = $file->store('course_files', 'public');
                $fileNames[] = $filePath;
            }
        }

        $validated['files'] = implode(',', $fileNames);
        echo($validated['contenu']);
        Course::create($validated);
        return redirect()->route('courses.index')->with('success', 'Course created successfully.');
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('gestioncours.edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'titre' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'contenu' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:active,inactive',
            'files.*' => 'file|mimes:jpeg,png,jpg,gif,pdf,docx|max:2048',
        ]);

        $course = Course::findOrFail($id);

        if ($request->hasFile('files')) {
            $fileNames = [];
            foreach ($request->file('files') as $file) {
                $filePath = $file->store('course_files', 'public');
                $fileNames[] = $filePath;
            }
        } else {
            $fileNames = []; // or keep the old ones if needed
        }
        

        $validated['files'] = implode(',', $fileNames);

        $course->update($validated);
        return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }

    public function show($id)
    {
        $course = Course::findOrFail($id);
        return view('gestioncours.show', compact('course'));
    }
}
