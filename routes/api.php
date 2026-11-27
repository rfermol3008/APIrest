<?php

use App\Http\Controllers\AcademyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/academy/{academy}/courses', [AcademyController::class, 'courses']);

// Rutas protegidas (requieren token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('academies', AcademyController::class);
    Route::apiResource('courses', CourseController::class);

    Route::get('/course/{course}/students', [CourseController::class, 'students']);
    Route::post('/courses/{course}/enroll/{student}', [CourseController::class, 'enroll']);
    Route::delete('/courses/{course}/unenroll/{student}', [CourseController::class, 'unenroll']);
});