<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restringe una ruta según tb_users.tipo_usuario.
 * Uso: ->middleware('role:admin')   (tipo_usuario = 1)
 *      ->middleware('role:usuario') (tipo_usuario = 2)
 *
 * Existía la ruta /dashboard con 'role:admin', pero el alias no estaba
 * registrado; este middleware lo implementa sin cambiar la base de datos.
 */
class CheckRole
{
    private const TIPOS = [
        'admin' => 1,
        'usuario' => 2,
    ];

    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $usuario = $request->user();

        if (! $usuario) {
            return redirect()->route('login.view');
        }

        $permitidos = array_map(fn ($rol) => self::TIPOS[$rol] ?? null, $roles);

        if (! in_array((int) $usuario->tipo_usuario, $permitidos, true)) {
            // Un usuario sin permiso vuelve a su pantalla de inicio.
            return redirect()->route('materiales.index');
        }

        return $next($request);
    }
}
