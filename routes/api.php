<?php

use App\Http\Controllers\AcademyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;

Route::apiResource('academies', AcademyController::class);
Route::apiResource('courses', CourseController::class);

Route::get('/academy/{academy}/courses', [AcademyController::class, 'courses']);
Route::get('/course/{course}/students', [CourseController::class, 'students']);
Route::post('/courses/{course}/enroll/{student}', [CourseController::class, 'enroll']);
Route::delete('/courses/{course}/unenroll/{student}', [CourseController::class, 'unenroll']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('academies', AcademyController::class);