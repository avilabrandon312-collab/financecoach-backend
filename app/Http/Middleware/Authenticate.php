<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Este backend es solo una API (sin vistas Blade), así que nunca
        // redirigimos a una página de login: siempre respondemos con JSON 401.
        return null;
    }
}
