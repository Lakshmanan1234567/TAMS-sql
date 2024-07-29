<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseApiController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'C_Name' => 'required|string|max:255',
            'C_Documents' => 'required|string|max:255',
            'C_Slot' => 'required|string|min:8',
            'Duration' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $course = Course::create([
            'C_Name' => $request->Course,
            'C_Documents' => $request->Subject,
            'C_Slot' => $request->Slot,
            'C_Duration' => $request->Duration,
        ]);

        return response()->json(['message' => 'Course created successfully', 'course' => $course], 201);
    }

    public function index()
    {
        $course = Course::all();
        return response()->json($course);
    }

    public function show($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        return response()->json($course);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'Course' => 'sometimes|string|max:255',
            'Subject' => 'sometimes|string|max:255',
            'Slot' => 'sometimes|string|min:8',
            'Duration' => 'sometimes|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $course = Course::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $course->update($request->only(['Course', 'Subject', 'Slot', 'Duration']));

        return response()->json(['message' => 'Course updated successfully', 'course' => $course]);
    }

    public function delete($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $course->delete();

        return response()->json(['message' => 'Course deleted successfully']);
    }
}
