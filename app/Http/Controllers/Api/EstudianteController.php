<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEstudianteRequest;
use App\Http\Requests\UpdateEstudianteRequest;
use App\Models\Estudiante;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    /**
     * Listar estudiantes (paginado).
     * GET /api/estudiantes
     */
    public function index(Request $request): JsonResponse
    {
        // Paginación simple (15 por página). Puedes cambiarla o permitir ?per_page=
        $perPage = $request->query('per_page', 15);
        $estudiantes = Estudiante::orderBy('id', 'desc')->paginate($perPage);

        // Retornamos la respuesta paginada (incluye 'data' con los items)
        return response()->json($estudiantes, 200);
    }

    /**
     * Crear nuevo estudiante.
     * POST /api/estudiantes
     */
    public function store(StoreEstudianteRequest $request): JsonResponse
    {
        $data = $request->validated();
        $estudiante = Estudiante::create($data);

        return response()->json([
            'message' => 'Estudiante creado',
            'data' => $estudiante
        ], 201);
    }

    /**
     * Mostrar un estudiante específico.
     * GET /api/estudiantes/{estudiante}
     */
    public function show(Estudiante $estudiante): JsonResponse
    {
        return response()->json(['data' => $estudiante], 200);
    }

    /**
     * Actualizar (PUT/PATCH) un estudiante.
     * PUT /api/estudiantes/{estudiante}
     * PATCH /api/estudiantes/{estudiante}
     */
    public function update(UpdateEstudianteRequest $request, Estudiante $estudiante): JsonResponse
    {
        $data = $request->validated();
        $estudiante->fill($data);
        $estudiante->save();

        return response()->json([
            'message' => 'Estudiante actualizado',
            'data' => $estudiante
        ], 200);
    }

    /**
     * Eliminar estudiante.
     * DELETE /api/estudiantes/{estudiante}
     */
    public function destroy(Estudiante $estudiante): JsonResponse
    {
        $estudiante->delete();

        return response()->json(['message' => 'Estudiante eliminado'], 200);
    }
}
