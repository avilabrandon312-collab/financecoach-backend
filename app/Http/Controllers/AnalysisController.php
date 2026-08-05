<?php

namespace App\Http\Controllers;

use App\Services\PsychAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalysisController extends Controller
{
    protected PsychAnalysisService $analysisService;

    public function __construct(PsychAnalysisService $analysisService)
    {
        $this->analysisService = $analysisService;
    }

    // GET /api/analysis/psychological?days=90
    public function perfilPsicologico(Request $request)
    {
        $userId = Auth::id() ?? 1;
        $dias = (int) $request->query('days', 90);
        $dias = $dias > 0 ? $dias : 90;

        $perfil = $this->analysisService->generarPerfil($userId, $dias);

        return response()->json($perfil, 200);
    }
}
