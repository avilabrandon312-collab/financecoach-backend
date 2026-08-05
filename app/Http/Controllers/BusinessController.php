<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessController extends Controller
{
    // GET /api/businesses
    public function index()
    {
        $userId = Auth::id() ?? 1;
        return response()->json(Business::where('user_id', $userId)->get(), 200);
    }

    // POST /api/businesses
    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'nit'    => 'nullable|string|max:100',
            'sector' => 'nullable|string|max:100',
        ]);

        $business = Business::create([
            'user_id'   => Auth::id() ?? 1,
            'name'      => $request->name,
            'nit'       => $request->nit,
            'sector'    => $request->sector,
            'is_active' => true,
        ]);

        return response()->json($business, 201);
    }

    // PUT /api/businesses/{id}
    public function update(Request $request, $id)
    {
        $userId = Auth::id() ?? 1;
        $business = Business::where('user_id', $userId)->findOrFail($id);

        $request->validate([
            'name'      => 'sometimes|string|max:255',
            'nit'       => 'nullable|string|max:100',
            'sector'    => 'nullable|string|max:100',
            'is_active' => 'sometimes|boolean',
        ]);

        $business->update($request->only(['name', 'nit', 'sector', 'is_active']));

        return response()->json($business, 200);
    }

    // DELETE /api/businesses/{id}
    public function destroy($id)
    {
        $userId = Auth::id() ?? 1;
        $business = Business::where('user_id', $userId)->findOrFail($id);
        $business->delete();

        return response()->json(['message' => 'Negocio eliminado exitosamente'], 200);
    }

    // GET /api/businesses/{id}/report
    public function reporte($id)
    {
        $userId = Auth::id() ?? 1;
        $business = Business::where('user_id', $userId)->findOrFail($id);

        $totalIngresos = (float) Income::where('business_id', $business->id)->sum('amount');
        $totalGastos = (float) Expense::where('business_id', $business->id)->sum('amount');

        return response()->json([
            'business'      => $business,
            'total_income'  => $totalIngresos,
            'total_expense' => $totalGastos,
            'balance'       => $totalIngresos - $totalGastos,
        ], 200);
    }
}
