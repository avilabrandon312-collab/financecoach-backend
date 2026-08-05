<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    // API pública y gratuita, no requiere API key
    protected string $baseUrl = 'https://open.er-api.com/v6/latest/';

    // Monedas más relevantes para mostrar de forma rápida en el dashboard
    protected array $monedasPopulares = ['USD', 'EUR', 'COP', 'MXN', 'ARS', 'PEN', 'BRL', 'GBP'];

    /**
     * Devuelve todas las tasas de cambio para una moneda base, con caché de 10 minutos
     * para no exceder los límites de la API gratuita.
     */
    public function obtenerTasas(string $base = 'USD'): array
    {
        $base = strtoupper($base);
        $cacheKey = "currency_rates_{$base}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($base) {
            try {
                $respuesta = Http::timeout(8)->get($this->baseUrl . $base);

                if (!$respuesta->successful()) {
                    return $this->respuestaError('No se pudo consultar el proveedor de divisas.');
                }

                $data = $respuesta->json();

                if (($data['result'] ?? null) !== 'success') {
                    return $this->respuestaError('El proveedor de divisas devolvió un error.');
                }

                return [
                    'success'        => true,
                    'base'           => $data['base_code'] ?? $base,
                    'rates'          => $data['rates'] ?? [],
                    'last_update'    => $data['time_last_update_utc'] ?? null,
                    'next_update'    => $data['time_next_update_utc'] ?? null,
                ];
            } catch (\Throwable $e) {
                Log::warning('CurrencyService error: ' . $e->getMessage());
                return $this->respuestaError('No fue posible conectar con el servicio de divisas en este momento.');
            }
        });
    }

    /**
     * Devuelve solo las tasas de las monedas más usadas, respecto a una base.
     */
    public function tasasPopulares(string $base = 'USD'): array
    {
        $todas = $this->obtenerTasas($base);

        if (!($todas['success'] ?? false)) {
            return $todas;
        }

        $filtradas = [];
        foreach ($this->monedasPopulares as $moneda) {
            if (isset($todas['rates'][$moneda])) {
                $filtradas[$moneda] = $todas['rates'][$moneda];
            }
        }

        return [
            'success'     => true,
            'base'        => $todas['base'],
            'rates'       => $filtradas,
            'last_update' => $todas['last_update'],
        ];
    }

    /**
     * Convierte un monto de una moneda a otra usando tasas en tiempo real (cacheadas).
     */
    public function convertir(string $from, string $to, float $amount): array
    {
        $from = strtoupper($from);
        $to = strtoupper($to);

        $tasas = $this->obtenerTasas($from);

        if (!($tasas['success'] ?? false)) {
            return $tasas;
        }

        if (!isset($tasas['rates'][$to])) {
            return $this->respuestaError("No se encontró la tasa de cambio para {$to}.");
        }

        $tasa = $tasas['rates'][$to];
        $resultado = $amount * $tasa;

        return [
            'success'      => true,
            'from'         => $from,
            'to'           => $to,
            'amount'       => $amount,
            'rate'         => $tasa,
            'result'       => round($resultado, 4),
            'last_update'  => $tasas['last_update'],
        ];
    }

    protected function respuestaError(string $mensaje): array
    {
        return [
            'success' => false,
            'message' => $mensaje,
        ];
    }
}
