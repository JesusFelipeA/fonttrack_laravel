<?php

namespace App\Http\Controllers;

use App\Models\Falla;
use App\Models\Lugar;
use App\Models\Material;
use App\Models\Notificacion;
use App\Models\Usuarios;
use App\Models\Vehiculo;

/**
 * Panel del administrador. Solo lee datos que ya existen en las tablas
 * tb_materiales, tb_vehiculos, tb_fallas, tb_notificaciones, tb_users y
 * tb_lugares; no agrega columnas ni escribe nada.
 */
class DashboardController extends Controller
{
    public function index()
    {
        $materiales = [
            'total' => Material::count(),
            'unidades' => (int) Material::sum('existencia'),
            'sin_existencia' => Material::where('existencia', '<=', 0)->count(),
            'valor' => (float) Material::selectRaw('COALESCE(SUM(existencia * costo_promedio), 0) as valor')->value('valor'),
        ];

        $vehiculosPorEstatus = Vehiculo::selectRaw('estatus, COUNT(*) as total')
            ->groupBy('estatus')
            ->pluck('total', 'estatus');

        $vehiculos = [
            'total' => (int) $vehiculosPorEstatus->sum(),
            'activo' => (int) ($vehiculosPorEstatus['activo'] ?? 0),
            'mantenimiento' => (int) ($vehiculosPorEstatus['mantenimiento'] ?? 0),
            'inactivo' => (int) ($vehiculosPorEstatus['inactivo'] ?? 0),
        ];

        $notificacionesPendientes = Notificacion::where('estado', 'pendiente')->count();

        $fallas = [
            'total' => Falla::count(),
            'ultimos_30' => Falla::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        $fallasRecientes = Falla::with('lugar')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        $materialesSinExistencia = Material::where('existencia', '<=', 0)
            ->orderBy('clave_material')
            ->limit(6)
            ->get();

        $usuarios = Usuarios::count();
        $lugares = Lugar::count();

        return view('dashboard', compact(
            'materiales',
            'vehiculos',
            'notificacionesPendientes',
            'fallas',
            'fallasRecientes',
            'materialesSinExistencia',
            'usuarios',
            'lugares'
        ));
    }
}
