<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    // GET /api/reports/balance?context=personal|business
    public function getBalance(Request $request)
    {
        $userId = Auth::id() ?? 1;

        $incomeQuery = Income::where('user_id', $userId);
        $expenseQuery = Expense::where('user_id', $userId);

        if ($request->filled('context')) {
            $incomeQuery->where('context', $request->query('context'));
            $expenseQuery->where('context', $request->query('context'));
        }

        $totalIncomes = (float) $incomeQuery->sum('amount');
        $totalExpenses = (float) $expenseQuery->sum('amount');

        $balance = $totalIncomes - $totalExpenses;

        return response()->json([
            'total_income'  => $totalIncomes,
            'total_expense' => $totalExpenses,
            'balance'       => $balance
        ], 200);
    }
}
