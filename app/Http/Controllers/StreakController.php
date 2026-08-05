<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\StreakService;
use Illuminate\Support\Facades\Auth;

class StreakController extends Controller
{
    protected StreakService $streakService;

    public function __construct(StreakService $streakService)
    {
        $this->streakService = $streakService;
    }

    // GET /api/streak
    public function index()
    {
        $user = User::find(Auth::id() ?? 1);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        return response()->json($this->streakService->estado($user), 200);
    }
}
