<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\User;
use App\Services\StreakService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    protected StreakService $streakService;

    public function __construct(StreakService $streakService)
    {
        $this->streakService = $streakService;
    }

    public function index(Request $request)
    {
        $userId = Auth::id() ?? 1;
        $query = Expense::where('user_id', $userId);

        // Filtro opcional por contexto: personal | business
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

        $expense = Expense::create([
            'user_id'     => $userId,
            'category_id' => 1, // Le asignamos el ID 1 por defecto para evitar el error de MySQL
            'amount'      => $request->monto,
            'description' => $request->descripcion,
            'date'        => $request->fecha,
            'context'     => $request->input('context', 'personal'),
            'business_id' => $request->input('business_id'),
        ]);

        // Registrar actividad para el sistema de racha
        $user = User::find($userId);
        if ($user) {
            $this->streakService->registrarActividad($user);
        }

        return response()->json($expense, 201);
    }

    public function destroy($id)
    {
        $userId = Auth::id() ?? 1;
        $expense = Expense::where('user_id', $userId)->findOrFail($id);
        $expense->delete();

        return response()->json(['message' => 'Gasto eliminado exitosamente'], 200);
    }
}
