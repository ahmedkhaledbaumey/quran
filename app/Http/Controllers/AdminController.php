<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Test;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Update user type to "teacher".
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addTeacher($id)
    {
        try {
            // Find the user by ID
            $user = User::findOrFail($id);

            // Check if the user is already a teacher
            if ($user->type === 'teacher') {
                return response()->json(['error' => 'User is already a teacher'], 422);
            }

            // Update user type to "teacher"
            $user->update(['type' => 'teacher']);

            return response()->json(['message' => 'User type updated to teacher successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions or errors
            return response()->json(['error' => 'Failed to update user type'], 500);
        }
    }

    /**
     * Update user type to "admin".
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addAdmin($id)
    {
        try {
            // Find the user by ID
            $user = User::findOrFail($id);

            // Check if the user is already an admin
            if ($user->type === 'admin') {
                return response()->json(['error' => 'User is already an admin'], 422);
            }

            // Update user type to "admin"
            $user->update(['type' => 'admin']);

            return response()->json(['message' => 'User type updated to admin successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions or errors
            return response()->json(['error' => 'Failed to update user type'], 500);
        }
    }

    /**
     * Display all students (users of type 'user').
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showStudents()
    {
        // Retrieve all users of type 'user' (students)
        $students = User::where('type', 'user')->get();

        return response()->json(['students' => $students], 200);
    }

    /**
     * Display all teachers (users of type 'teacher').
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showTeachers()
    {
        // Retrieve all users of type 'teacher'
        $teachers = User::where('type', 'teacher')->get();

        return response()->json(['teachers' => $teachers], 200);
    }

    /**
     * Delete a user by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser($id)
    {
        try {
            // Find the user by ID
            $user = User::findOrFail($id);

            // Delete the user
            $user->delete();

            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions or errors
            return response()->json(['error' => 'Failed to delete user'], 500);
        }
    }

    /**
     * Add a new test.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addTest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:tests',
            'details' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $test = Test::create($validator->validated());

        return response()->json(['message' => 'Test added successfully', 'test' => $test], 201);
    }

    /**
     * Remove a student from a test.
     *
     * @param  int  $userId
     * @param  int  $testId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeStudentFromTest($userId, $testId)
    {
        try {
            // Find the user and test by IDs
            $user = User::findOrFail($userId);
            $test = Test::findOrFail($testId);

            // Detach the user from the test
            $user->tests()->detach($test);

            return response()->json(['message' => 'Student removed from test successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions or errors
            return response()->json(['error' => 'Failed to remove student from test'], 500);
        }
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
