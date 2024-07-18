<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\User;

class TestController extends Controller
{
    /**
     * Display all tests available for admin.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showTestsForAdmin()
    {
        // Retrieve all tests
        $tests = Test::all();

        return response()->json(['tests' => $tests], 200);
    }

    /**
     * Display tests available for a specific teacher.
     *
     * @param  int  $teacherId
     * @return \Illuminate\Http\JsonResponse
     */
    public function showTestsForTeacher($teacherId)
    {
        // Find the teacher by ID
        $teacher = User::find($teacherId);

        if (!$teacher || $teacher->type !== 'teacher') {
            return response()->json(['error' => 'Teacher not found'], 404);
        }

        // Retrieve tests associated with this teacher
        $tests = $teacher->tests;

        return response()->json(['tests' => $tests], 200);
    }

    /**
     * Display tests available for a specific student.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function showTestsForStudent($userId)
    {
        // Find the student by ID
        $student = User::find($userId);

        if (!$student || $student->type !== 'user') {
            return response()->json(['error' => 'Student not found'], 404);
        }

        // Retrieve tests associated with this student
        $tests = $student->tests;

        return response()->json(['tests' => $tests], 200);
    }

    /**
     * Display test details for a specific test.
     *
     * @param  int  $testId
     * @return \Illuminate\Http\JsonResponse
     */
    public function showTestDetails($testId)
    {
        // Find the test by ID
        $test = Test::find($testId);

        if (!$test) {
            return response()->json(['error' => 'Test not found'], 404);
        }

        return response()->json(['test' => $test], 200);
    }
}
