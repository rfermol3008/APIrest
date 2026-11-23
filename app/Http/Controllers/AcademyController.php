<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAcademyRequest;
use App\Http\Requests\UpdateAcademyRequest;
use App\Http\Resources\AcademyResource;
use App\Models\Academy;
use Illuminate\Http\Request;

class AcademyController extends Controller
{
    public function index()
    {
        return AcademyResource::collection(Academy::all());
    }

    public function store(StoreAcademyRequest $request)
    {
        $academy = $request->user()->academies()->create($request->validated());

        return (new AcademyResource($academy))->response()->setStatusCode(201);
    }

    public function show(Academy $academy)
    {
        return new AcademyResource($academy);
    }

    public function update(UpdateAcademyRequest $request, Academy $academy)
    {
        if ($request->user()->id !== $academy->user_id) {
            return response()->json(['message' => 'No tienes permiso para modificar esta academia.'], 403);
        }

        $academy->update($request->validated());

        return new AcademyResource($academy);
    }

    public function destroy(Request $request, Academy $academy)
    {
        if ($request->user()->id !== $academy->user_id) {
            return response()->json(['message' => 'No tienes permiso para eliminar esta academia.'], 403);
        }

        $academy->delete();

        return response()->noContent();
    }
}