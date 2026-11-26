<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\StudentResource;
use App\Models\Academy;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        return CourseResource::collection(Course::all());
    }

    public function store(StoreCourseRequest $request)
    {
        $academy = Academy::findOrFail($request->academy_id);

        if ($request->user()->id !== $academy->user_id) {
            return response()->json(['message' => 'No puedes crear cursos en una academia que no es tuya.'], 403);
        }

        $course = $academy->courses()->create($request->validated());

        return (new CourseResource($course))->response()->setStatusCode(201);
    }

    public function show(Course $course)
    {
        return new CourseResource($course);
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        if (! $this->ownsCourse($request, $course)) {
            return response()->json(['message' => 'No tienes permiso para modificar este curso.'], 403);
        }

        $course->update($request->validated());

        return new CourseResource($course);
    }

    public function destroy(Request $request, Course $course)
    {
        if (! $this->ownsCourse($request, $course)) {
            return response()->json(['message' => 'No tienes permiso para eliminar este curso.'], 403);
        }

        $course->delete();

        return response()->noContent();
    }

    public function students(Course $course)
    {
        return StudentResource::collection($course->students);
    }

    public function enroll(Request $request, Course $course, Student $student)
    {
        if (! $this->ownsCourse($request, $course)) {
            return response()->json(['message' => 'No tienes permiso para matricular en este curso.'], 403);
        }

        if ($course->students()->where('students.id', $student->id)->exists()) {
            return response()->json(['message' => 'El estudiante ya está matriculado en este curso.'], 409);
        }

        if ($course->students()->count() >= $course->capacity) {
            return response()->json(['message' => 'El curso ha alcanzado su capacidad máxima.'], 409);
        }

        $course->students()->attach($student->id);

        return response()->json(['message' => 'Estudiante matriculado correctamente.'], 201);
    }

    public function unenroll(Request $request, Course $course, Student $student)
    {
        if (! $this->ownsCourse($request, $course)) {
            return response()->json(['message' => 'No tienes permiso para desmatricular en este curso.'], 403);
        }

        if (! $course->students()->where('students.id', $student->id)->exists()) {
            return response()->json(['message' => 'El estudiante no está matriculado en este curso.'], 404);
        }

        $course->students()->detach($student->id);

        return response()->noContent();
    }

    private function ownsCourse(Request $request, Course $course): bool
    {
        return $request->user()->id === $course->academy->user_id;
    }
}