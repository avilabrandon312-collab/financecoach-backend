<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Income;
use Carbon\Carbon;

class PsychAnalysisService
{
    /**
     * Palabras clave para clasificar categorías cuando no tienen 'naturaleza' asignada en BD.
     */
    protected array $palabrasDiscrecionales = [
        'ocio', 'entretenimiento', 'restaurante', 'domicilio', 'delivery', 'compras',
        'ropa', 'viaje', 'bar', 'fiesta', 'streaming', 'juegos', 'apuestas', 'antojo',
    ];

    protected array $palabrasEsenciales = [
        'vivienda', 'arriendo', 'servicios', 'salud', 'transporte', 'educacion',
        'educación', 'alimentacion', 'alimentación', 'mercado', 'seguro', 'deuda', 'credito', 'crédito',
    ];

    public function generarPerfil(int $userId, int $dias = 90): array
    {
        $desde = Carbon::today()->subDays($dias);

        $gastos = Expense::with('category')
            ->where('user_id', $userId)
            ->where('date', '>=', $desde)
            ->get();

        $ingresos = Income::where('user_id', $userId)
            ->where('date', '>=', $desde)
            ->sum('amount');

        if ($gastos->isEmpty()) {
            return [
                'perfil'          => 'sin_datos',
                'titulo'          => 'Aún no hay suficiente información',
                'resumen'         => 'Registra tus gastos durante algunos días para que podamos analizar tu comportamiento financiero.',
                'metricas'        => [],
                'recomendaciones' => ['Registra al menos una semana de gastos para obtener un análisis confiable.'],
            ];
        }

        $totalGastado = (float) $gastos->sum('amount');

        // Clasificar cada gasto como esencial / discrecional
        $totalEsencial = 0.0;
        $totalDiscrecional = 0.0;
        $porCategoria = [];

        foreach ($gastos as $gasto) {
            $nombreCategoria = optional($gasto->category)->name ?? 'Sin categoría';
            $naturaleza = optional($gasto->category)->naturaleza ?? $this->inferirNaturaleza($nombreCategoria);

            if ($naturaleza === 'esencial') {
                $totalEsencial += (float) $gasto->amount;
            } elseif ($naturaleza === 'discrecional') {
                $totalDiscrecional += (float) $gasto->amount;
            }

            $porCategoria[$nombreCategoria] = ($porCategoria[$nombreCategoria] ?? 0) + (float) $gasto->amount;
        }

        arsort($porCategoria);
        $categoriaTop = array_key_first($porCategoria) ?? 'N/A';

        // Gasto de fin de semana vs entre semana
        $gastoFinde = 0.0;
        $gastoEntreSemana = 0.0;
        $montos = [];

        foreach ($gastos as $gasto) {
            $fecha = Carbon::parse($gasto->date);
            $montos[] = (float) $gasto->amount;
            if (in_array($fecha->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                $gastoFinde += (float) $gasto->amount;
            } else {
                $gastoEntreSemana += (float) $gasto->amount;
            }
        }

        // Variabilidad del gasto (coeficiente de variación) como señal de impulsividad
        $promedio = count($montos) > 0 ? array_sum($montos) / count($montos) : 0;
        $varianza = count($montos) > 1
            ? array_sum(array_map(fn ($m) => ($m - $promedio) ** 2, $montos)) / (count($montos) - 1)
            : 0;
        $desviacion = sqrt($varianza);
        $coefVariacion = $promedio > 0 ? $desviacion / $promedio : 0;

        $porcentajeDiscrecional = $totalGastado > 0 ? ($totalDiscrecional / $totalGastado) * 100 : 0;
        $tasaAhorro = $ingresos > 0 ? (($ingresos - $totalGastado) / $ingresos) * 100 : null;
        $ratioFindeVsSemana = $gastoEntreSemana > 0 ? $gastoFinde / $gastoEntreSemana : ($gastoFinde > 0 ? 999 : 0);

        // --- Lógica de clasificación del perfil psicológico ---
        $perfil = 'equilibrado';
        $titulo = 'Gestor equilibrado';
        $resumen = 'Mantienes un balance razonable entre tus gastos esenciales y discrecionales, sin patrones bruscos de impulsividad.';

        if ($porcentajeDiscrecional >= 55 && $coefVariacion >= 0.8) {
            $perfil = 'impulsivo';
            $titulo = 'Gastador impulsivo / emocional';
            $resumen = "Una parte importante de tu dinero (" . round($porcentajeDiscrecional) . "%) se va en gastos discrecionales, con montos muy variables entre un día y otro. Esto suele indicar compras guiadas por el estado de ánimo más que por planeación.";
        } elseif ($tasaAhorro !== null && $tasaAhorro >= 20 && $porcentajeDiscrecional <= 30) {
            $perfil = 'planificador';
            $titulo = 'Planificador disciplinado';
            $resumen = 'Priorizas lo esencial, mantienes una tasa de ahorro saludable y tus gastos discrecionales están bajo control.';
        } elseif ($tasaAhorro !== null && $tasaAhorro < 0) {
            $perfil = 'en_alerta';
            $titulo = 'Gasto por encima de tus ingresos';
            $resumen = 'En el periodo analizado tus gastos superaron tus ingresos. Vale la pena revisar de cerca en qué se está yendo el dinero.';
        } elseif ($ratioFindeVsSemana >= 1.5) {
            $perfil = 'gastador_de_fin_de_semana';
            $titulo = 'Gastador de fin de semana';
            $resumen = 'Tu gasto se concentra notablemente los fines de semana, lo que sugiere que el ocio y los planes sociales son tu principal fuente de gasto discrecional.';
        }

        $recomendaciones = $this->recomendacionesPara($perfil, $categoriaTop);

        return [
            'perfil'   => $perfil,
            'titulo'   => $titulo,
            'resumen'  => $resumen,
            'metricas' => [
                'periodo_dias'                 => $dias,
                'total_gastado'                => round($totalGastado, 2),
                'total_ingresos'                => round((float) $ingresos, 2),
                'porcentaje_gasto_discrecional' => round($porcentajeDiscrecional, 1),
                'porcentaje_gasto_esencial'     => $totalGastado > 0 ? round(($totalEsencial / $totalGastado) * 100, 1) : 0,
                'tasa_ahorro'                   => $tasaAhorro !== null ? round($tasaAhorro, 1) : null,
                'categoria_principal'           => $categoriaTop,
                'gasto_fin_de_semana'           => round($gastoFinde, 2),
                'gasto_entre_semana'            => round($gastoEntreSemana, 2),
                'variabilidad_gasto'            => round($coefVariacion, 2),
            ],
            'recomendaciones' => $recomendaciones,
        ];
    }

    protected function inferirNaturaleza(string $nombreCategoria): string
    {
        $nombre = mb_strtolower($nombreCategoria);

        foreach ($this->palabrasDiscrecionales as $palabra) {
            if (str_contains($nombre, $palabra)) {
                return 'discrecional';
            }
        }

        foreach ($this->palabrasEsenciales as $palabra) {
            if (str_contains($nombre, $palabra)) {
                return 'esencial';
            }
        }

        return 'otro';
    }

    protected function recomendacionesPara(string $perfil, string $categoriaTop): array
    {
        return match ($perfil) {
            'impulsivo' => [
                "Antes de comprar en '{$categoriaTop}', espera 24 horas para confirmar si realmente lo necesitas.",
                'Define un límite mensual para gastos discrecionales y actívale una alerta.',
                'Prueba registrar cómo te sentías antes de cada gasto no esencial durante una semana.',
            ],
            'planificador' => [
                'Vas muy bien: considera destinar tu ahorro excedente a un fondo de emergencia o inversión de bajo riesgo.',
                'Revisa el módulo de educación financiera sobre inversión para hacer crecer tus ahorros.',
            ],
            'en_alerta' => [
                'Identifica los 3 gastos más grandes del periodo y evalúa cuáles se pueden reducir de inmediato.',
                'Considera pausar temporalmente los gastos discrecionales hasta equilibrar tu balance.',
            ],
            'gastador_de_fin_de_semana' => [
                'Define un presupuesto específico para el fin de semana antes de que empiece.',
                'Busca alternativas de ocio de bajo costo para algunos fines de semana al mes.',
            ],
            default => [
                'Sigue registrando tus movimientos para obtener recomendaciones cada vez más precisas.',
            ],
        };
    }
}
