<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\User;
use App\Models\Test_User;

class TeacherController extends Controller
{
    /**
     * Set grade for a student in a test.
     *
     * @param  int  $userId
     * @param  int  $testId
     * @param  int  $grade
     * @return \Illuminate\Http\JsonResponse
     */
    public function addGrade($studentId,$teacherId, $testId, $grade)
    {
        // Get the current authenticated user (teacher)
        $teacher = auth()->user();
        
        // Check if the current user is a teacher
        if (!$teacher || $teacher->type !== 'teacher') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Find the user
        $teacher = User::find($teacherId);
        $student = User::find($studentId);
        if (!$teacher) {
            return response()->json(['error' => 'User not found'], 404);
        }
        if (!$student) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Find the test
        $test = Test::find($testId);
        if (!$test) {
            return response()->json(['error' => 'Test not found'], 404);
        }

        // Check if the user is registered for the test
        if (!$student->tests()->where('test_id', $testId)->exists()) {
            return response()->json(['error' => 'User is not registered for this test'], 400);
        }
      

        // Update the grade and teacher name for the user in the test
        $testUser = Test_User::where('user_id', $studentId)->where('test_id', $testId)->first();
        if (!$testUser) {
            return response()->json(['error' => 'Test user relationship not found'], 500);
        }

        // Update the grade and set the teacher name
        $testUser->update([
            'grade' => $grade,
            'teacher_id' => $teacherId, // Set the teacher name from the current user
        ]);

        return response()->json(['message' => 'Grade updated successfully'], 200);
    }

    /**
     * Update a student's grade in a test.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @param  int  $testId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStudentGradeInTest(Request $request, $userId, $testId)
    {
        try {
            // Find the user and test by IDs
            $user = User::findOrFail($userId);
            $test = Test::findOrFail($testId);

            // Update the student's grade for the test
            $user->tests()->updateExistingPivot($test->id, ['grade' => $request->input('grade')]);

            return response()->json(['message' => 'Student grade updated successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions or errors
            return response()->json(['error' => 'Failed to update student grade'], 500);
        }
    }
}
