<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeccionRequest;
use App\Models\Leccion;
use App\Models\LeccionProgreso;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeccionController extends Controller
{
    /**
     * Lista todas las lecciones de educación financiera, marcando cuáles
     * ya completó el usuario autenticado.
     */
    public function index(): JsonResponse
    {
        $userId = Auth::id() ?? 1;

        $lecciones = Leccion::orderBy('categoria')->get();
        $progresos = LeccionProgreso::where('user_id', $userId)
            ->pluck('completada', 'leccion_id');

        $data = $lecciones->map(function ($leccion) use ($progresos) {
            $leccion->completada = (bool) ($progresos[$leccion->id] ?? false);
            return $leccion;
        });

        return response()->json($data, 200);
    }

    /**
     * Muestra el detalle de una lección puntual.
     */
    public function show($id): JsonResponse
    {
        $leccion = Leccion::findOrFail($id);
        return response()->json($leccion, 200);
    }

    /**
     * Almacena una nueva lección de educación financiera.
     */
    public function store(StoreLeccionRequest $request): JsonResponse
    {
        $leccion = Leccion::create($request->validated());

        return response()->json([
            'message' => 'Lección registrada exitosamente',
            'data'    => $leccion,
        ], 201);
    }

    /**
     * Marca (o desmarca) una lección como completada para el usuario actual.
     */
    public function marcarCompletada(Request $request, $id): JsonResponse
    {
        $userId = Auth::id() ?? 1;
        Leccion::findOrFail($id); // valida que exista

        $completada = $request->boolean('completada', true);

        $progreso = LeccionProgreso::updateOrCreate(
            ['user_id' => $userId, 'leccion_id' => $id],
            [
                'completada'    => $completada,
                'completada_en' => $completada ? now() : null,
            ]
        );

        return response()->json($progreso, 200);
    }

    /**
     * Resumen del progreso del usuario en el módulo educativo (para gamificación/racha).
     */
    public function miProgreso(): JsonResponse
    {
        $userId = Auth::id() ?? 1;

        $totalLecciones = Leccion::count();
        $completadas = LeccionProgreso::where('user_id', $userId)
            ->where('completada', true)
            ->count();

        return response()->json([
            'total_lecciones'     => $totalLecciones,
            'lecciones_completadas' => $completadas,
            'porcentaje'          => $totalLecciones > 0 ? round(($completadas / $totalLecciones) * 100, 1) : 0,
        ], 200);
    }
}
