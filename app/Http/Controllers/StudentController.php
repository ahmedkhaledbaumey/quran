<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Test;
use App\Models\User;

class StudentController extends Controller
{
  


    public function registerInTest(Request $request)
    {
        $validator = Validator::make($request->all(), [     
            'name' => 'required|string|between:2,100',
            'ssn' => 'required|integer|max:100|unique:users',
            'test_id' => 'required|integer|exists:tests,id', // Validate that test_id exists in the tests table
            'user_id' => 'required|integer|exists:users,id', // Validate that user_id exists in the users table
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
    
        $user = User::find($request->user_id);
        $test = Test::find($request->test_id);
    
        // Check if the user exists
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        // Check if the test exists
        if (!$test) {
            return response()->json(['error' => 'Test not found'], 404);
        }
    
        // Check if the user is already registered for this test
        if ($user->tests->contains($test)) {
            return response()->json(['message' => 'User already registered for this test'], 400);
        }
    
        // Attach the test to the user
        $user->tests()->attach($test);
    
        return response()->json(['message' => 'Test registered successfully'], 200);
    }
    

}
