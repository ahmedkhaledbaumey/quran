<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Authentication routes
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']); 
    Route::put('/update-user/{id}', [AuthController::class, 'updateUser']);

});

// Test routes
Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'tests'
], function () {
    Route::get('/', [TestController::class, 'showTestsForAdmin']); // Display all tests for admin
    Route::get('/teacher/{teacherId}', [TestController::class, 'showTestsForTeacher']); // Display tests for a specific teacher
    Route::get('/student/{userId}', [TestController::class, 'showTestsForStudent']); // Display tests for a specific student
    Route::get('/{testId}', [TestController::class, 'showTestDetails']); // Display test details for a specific test
});

// Admin routes
Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'admin'
], function () {
    Route::post('/add-teacher/{id}', [AdminController::class, 'addTeacher']); // Add a teacher
    Route::post('/add-admin/{id}', [AdminController::class, 'addAdmin'])->middleware('admin'); // Add an admin
    Route::delete('/delete-user/{id}', [AdminController::class, 'deleteUser']); // Delete a user
    Route::get('/show-students', [AdminController::class, 'showStudents']); // Display all students
    Route::get('/show-teachers', [AdminController::class, 'showTeachers']); // Display all teachers
});

// Teacher routes
Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'teacher'
], function () {
    Route::post('/add-grade/{studentId}/{teacherId}/{testId}/{grade}', [TeacherController::class, 'addGrade']); // Set grade for a student in a test
});

// Student routes
Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'student'
], function () {
    Route::post('/register-test', [StudentController::class, 'registerInTest']); // Register for a test
    Route::post('/update-grade/{userId}/{testId}', [StudentController::class, 'updateStudentGradeInTest']); // Update student's grade in a test
});

