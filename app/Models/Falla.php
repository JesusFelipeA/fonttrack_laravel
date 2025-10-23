<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Falla extends Model
{
    use HasFactory;

    protected $table = 'tb_fallas'; // Nombre de la tabla
    protected $primaryKey = 'id'; // Clave primaria

    protected $fillable = [
        'id_lugar',
        'usuario_reporta_id',        // ✅ AGREGADO
        'nombre_usuario_reporta',    // ✅ AGREGADO  
        'correo_usuario_reporta',    // ✅ AGREGADO
        'usuario_revisa_id',         // ✅ NUEVO - ID del admin que revisa
        'nombre_usuario_revisa',     // ✅ NUEVO - Nombre del admin que revisa
        'correo_usuario_revisa',     // ✅ NUEVO - Correo del admin que revisa
        'eco',
        'placas',
        'marca',
        'anio',              // Almacenamos 'ano' en la columna 'anio'
        'km',
        'fecha',
        'nombre_conductor',
        'descripcion',
        'observaciones',
        'autorizado_por',
        'reviso_por',
        'correo_destino',
        'material',          // Resumen de materiales (opcional)
        'cantidad',          // Total de cantidad descontada
        'materials'          // JSON original de materiales
    ];

    // ✅ MEJORA OPCIONAL: Casts para mejor manejo de datos
    protected $casts = [
        'fecha' => 'date',           // Convierte automáticamente a Carbon
        'materials' => 'array',      // Convierte JSON a array automáticamente
        'cantidad' => 'integer',     // Asegura que sea entero
    ];

    // ✅ RELACIÓN EXISTENTE: con la tabla Lugar
    public function lugar()
    {
        return $this->belongsTo(Lugar::class, 'id_lugar', 'id_lugar');
    }

    // ✅ RELACIÓN: Usuario que reporta
    public function usuarioReporta()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_reporta_id', 'id_usuario');
    }

    // ✅ NUEVA RELACIÓN: Usuario que revisa (ADMIN)
    public function usuarioRevisa()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_revisa_id', 'id_usuario');
    }

    // ✅ CORREGIDO: Accessor para obtener materiales formateados (SOLUCIONA EL PROBLEMA)
    public function getMaterialesFormateadosAttribute()
    {
        // Si hay array de materials, procesarlo
        if (!empty($this->materials) && is_array($this->materials)) {
            return collect($this->materials)->map(function ($material) {
                // Buscar ID del material en diferentes claves posibles
                $materialId = $material['id'] ?? $material['id_material'] ?? $material['material_id'] ?? null;
                
                // ✅ CLAVE: Priorizar descripción del array, luego buscar en BD
                $descripcion = 'Material no especificado';
                
                if (!empty($material['descripcion'])) {
                    // Si ya viene la descripción en el array, usarla
                    $descripcion = $material['descripcion'];
                } elseif ($materialId) {
                    // Si tenemos ID, buscar en BD
                    $materialModel = \App\Models\Material::find($materialId);
                    if ($materialModel && $materialModel->descripcion) {
                        $descripcion = $materialModel->descripcion;
                    }
                } elseif (!empty($material['nombre'])) {
                    // Buscar en otras claves posibles
                    $descripcion = $material['nombre'];
                } elseif (!empty($material['name'])) {
                    $descripcion = $material['name'];
                }
                
                // Buscar cantidad en diferentes claves posibles
                $cantidad = $material['cantidad'] ?? $material['qty'] ?? $material['quantity'] ?? 0;
                
                // Obtener modelo del material para datos adicionales
                $materialModel = $materialId ? \App\Models\Material::find($materialId) : null;
                
                return [
                    'id' => $materialId,
                    'descripcion' => $descripcion,
                    'cantidad' => $cantidad,
                    'clave' => $materialModel?->clave_material ?? 'N/A',
                    'costo_unitario' => $materialModel?->costo_promedio ?? 0,
                    'costo_total' => ($materialModel?->costo_promedio ?? 0) * $cantidad
                ];
            })->toArray();
        }
        
        // Fallback a campos legacy
        if (!empty($this->material)) {
            return [
                [
                    'id' => null,
                    'descripcion' => $this->material,
                    'cantidad' => $this->cantidad ?? 0,
                    'clave' => 'N/A',
                    'costo_unitario' => 0,
                    'costo_total' => 0
                ]
            ];
        }
        
        return [];
    }

    // ✅ NUEVO: Verifica si la falla tiene materiales
    public function tieneMateriales()
    {
        return !empty($this->materiales_formateados) || !empty($this->material);
    }

    // ✅ NUEVO: Obtiene el nombre del primer material (para mostrar en tabla)
    public function getNombrePrimerMaterialAttribute()
    {
        $materiales = $this->materiales_formateados;
        
        if (empty($materiales)) {
            return $this->material ?? 'Sin material';
        }
        
        $primerMaterial = $materiales[0];
        $nombre = $primerMaterial['descripcion'];
        
        // Si hay más de un material, agregar indicador
        if (count($materiales) > 1) {
            $nombre .= ' (+' . (count($materiales) - 1) . ' más)';
        }
        
        return $nombre;
    }

    // ✅ NUEVO: Obtiene todos los nombres de materiales separados por coma
    public function getNombresMaterialesAttribute()
    {
        $materiales = $this->materiales_formateados;
        
        if (empty($materiales)) {
            return $this->material ?? 'Sin materiales';
        }
        
        return collect($materiales)
            ->pluck('descripcion')
            ->implode(', ');
    }

    // ✅ NUEVO: Obtiene la cantidad total de materiales
    public function getCantidadTotalMaterialesAttribute()
    {
        $materiales = $this->materiales_formateados;
        
        if (empty($materiales)) {
            return $this->cantidad ?? 0;
        }
        
        return collect($materiales)->sum('cantidad');
    }

    // ✅ NUEVO: Calcula el costo total de todos los materiales
    public function getCostoTotalMaterialesAttribute()
    {
        $materiales = $this->materiales_formateados;
        
        return collect($materiales)->sum('costo_total');
    }

    // ✅ MEJORA ADICIONAL: Accessor para obtener resumen del reporte
    public function getResumenAttribute()
    {
        $descripcion = $this->descripcion ?? 'Sin descripción';
        $vehiculo = $this->eco ? "ECO: {$this->eco}" : ($this->placas ? "Placas: {$this->placas}" : 'Vehículo no especificado');

        return "Falla - {$vehiculo} - " . substr($descripcion, 0, 50) . (strlen($descripcion) > 50 ? '...' : '');
    }

    // ✅ CORREGIDO: Accessor para obtener conteo de materiales
    public function getMaterialesCountAttribute()
    {
        return count($this->materiales_formateados);
    }

    // ✅ MEJORA ADICIONAL: Accessor para obtener información del vehículo
    public function getVehiculoInfoAttribute()
    {
        $info = [];

        if ($this->eco)
            $info[] = "ECO: {$this->eco}";
        if ($this->placas)
            $info[] = "Placas: {$this->placas}";
        if ($this->marca)
            $info[] = "Marca: {$this->marca}";
        if ($this->anio)
            $info[] = "Año: {$this->anio}";

        return !empty($info) ? implode(' | ', $info) : 'No especificado';
    }

    // ✅ MEJORA ADICIONAL: Accessor para obtener tiempo transcurrido
    public function getTiempoTranscurridoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // ✅ MEJORA ADICIONAL: Accessor para obtener fecha formateada
    public function getFechaCreacionAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    // ✅ MEJORA ADICIONAL: Scope para filtrar por lugar
    public function scopePorLugar($query, $idLugar)
    {
        return $query->where('id_lugar', $idLugar);
    }

    // ✅ MEJORA ADICIONAL: Scope para búsqueda general
    public function scopeBuscar($query, $termino)
    {
        return $query->where(function ($q) use ($termino) {
            $q->where('descripcion', 'like', "%{$termino}%")
                ->orWhere('observaciones', 'like', "%{$termino}%")
                ->orWhere('eco', 'like', "%{$termino}%")
                ->orWhere('placas', 'like', "%{$termino}%")
                ->orWhere('marca', 'like', "%{$termino}%")
                ->orWhere('nombre_conductor', 'like', "%{$termino}%")
                ->orWhere('material', 'like', "%{$termino}%")
                ->orWhere('autorizado_por', 'like', "%{$termino}%")
                ->orWhere('nombre_usuario_reporta', 'like', "%{$termino}%");
        });
    }
}