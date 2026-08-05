<?php

namespace App\Http\Controllers;

use App\Services\CurrencyService;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    // GET /api/currency/rates?base=USD
    public function rates(Request $request)
    {
        $base = $request->query('base', 'USD');
        return response()->json($this->currencyService->obtenerTasas($base), 200);
    }

    // GET /api/currency/popular?base=USD
    public function popular(Request $request)
    {
        $base = $request->query('base', 'USD');
        return response()->json($this->currencyService->tasasPopulares($base), 200);
    }

    // GET /api/currency/convert?from=USD&to=COP&amount=100
    public function convert(Request $request)
    {
        $request->validate([
            'from'   => 'required|string|size:3',
            'to'     => 'required|string|size:3',
            'amount' => 'required|numeric|min:0',
        ]);

        $resultado = $this->currencyService->convertir(
            $request->query('from'),
            $request->query('to'),
            (float) $request->query('amount')
        );

        return response()->json($resultado, 200);
    }
}
