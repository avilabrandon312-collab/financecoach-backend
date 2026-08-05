<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class StreakService
{
    /**
     * Registra actividad financiera del usuario en el día de hoy y actualiza su racha.
     * Se debe llamar cada vez que el usuario registra un ingreso o un gasto.
     */
    public function registrarActividad(User $user): User
    {
        $hoy = Carbon::today();
        $ultima = $user->last_activity_date ? Carbon::parse($user->last_activity_date) : null;

        if ($ultima === null) {
            // Primera actividad registrada
            $user->current_streak = 1;
        } elseif ($ultima->isSameDay($hoy)) {
            // Ya registró actividad hoy, la racha no cambia
        } elseif ($ultima->isSameDay($hoy->copy()->subDay())) {
            // Registró ayer -> continúa la racha
            $user->current_streak = $user->current_streak + 1;
        } else {
            // Hubo un salto de días -> se rompe la racha y arranca de nuevo
            $user->current_streak = 1;
        }

        if ($user->current_streak > $user->longest_streak) {
            $user->longest_streak = $user->current_streak;
        }

        $user->last_activity_date = $hoy;
        $user->save();

        return $user;
    }

    /**
     * Devuelve el estado de racha del usuario, marcando si ya está "rota"
     * por inactividad (más de un día sin registrar movimientos).
     */
    public function estado(User $user): array
    {
        $hoy = Carbon::today();
        $ultima = $user->last_activity_date ? Carbon::parse($user->last_activity_date) : null;

        $activaHoy = $ultima && $ultima->isSameDay($hoy);
        $enRiesgo = $ultima && $ultima->isSameDay($hoy->copy()->subDay()) && !$activaHoy;

        // Si pasó más de un día sin actividad, la racha visualmente ya se rompió
        $rachaActual = $user->current_streak;
        if ($ultima && $ultima->lt($hoy->copy()->subDay()) ) {
            $rachaActual = 0;
        }

        return [
            'current_streak'      => $rachaActual,
            'longest_streak'      => $user->longest_streak,
            'last_activity_date'  => $user->last_activity_date,
            'active_today'        => $activaHoy,
            'at_risk'             => $enRiesgo, // registró ayer pero no hoy todavía
        ];
    }
}
