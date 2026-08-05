<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\User;
use App\Services\StreakService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    protected StreakService $streakService;

    public function __construct(StreakService $streakService)
    {
        $this->streakService = $streakService;
    }

    public function index(Request $request)
    {
        $userId = Auth::id() ?? 1;
        $query = Income::where('user_id', $userId);

        if ($request->filled('context')) {
            $query->where('context', $request->query('context'));
        }
        if ($request->filled('business_id')) {
            $query->where('business_id', $request->query('business_id'));
        }

        return response()->json($query->get(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'monto'       => 'required|numeric',
            'descripcion' => 'required|string|max:255',
            'categoria'   => 'required|string',
            'fecha'       => 'required|date',
            'context'     => 'nullable|in:personal,business',
            'business_id' => 'nullable|integer|exists:businesses,id',
        ]);

        $userId = Auth::id() ?? 1;

        $income = Income::create([
            'user_id'     => $userId,
            'category_id' => 1, // Le asignamos el ID 1 por defecto para evitar el error de MySQL
            'amount'      => $request->monto,
            'description' => $request->descripcion,
            'date'        => $request->fecha,
            'context'     => $request->input('context', 'personal'),
            'business_id' => $request->input('business_id'),
        ]);

        $user = User::find($userId);
        if ($user) {
            $this->streakService->registrarActividad($user);
        }

        return response()->json($income, 201);
    }

    public function destroy($id)
    {
        $userId = Auth::id() ?? 1;
        $income = Income::where('user_id', $userId)->findOrFail($id);
        $income->delete();

        return response()->json(['message' => 'Ingreso eliminado exitosamente'], 200);
    }
}
