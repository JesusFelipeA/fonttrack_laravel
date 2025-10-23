<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'tb_vehiculos';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'id_lugar',
        'eco',
        'placas',
        'marca',
        'anio',
        'kilometraje',
        'conductor_habitual',
        'modelo',
        'color',
        'estatus'
    ];

    protected $casts = [
        'kilometraje' => 'integer'
    ];

    // Relación con lugar
    public function lugar()
    {
        return $this->belongsTo(Lugar::class, 'id_lugar', 'id_lugar');
    }

    // Scopes para consultas
    public function scopeActivos($query)
    {
        return $query->where('estatus', 'activo');
    }

    public function scopeDelLugar($query, $idLugar)
    {
        return $query->where('id_lugar', $idLugar);
    }

    public function scopeBuscar($query, $termino)
    {
        return $query->where(function ($q) use ($termino) {
            $q->where('eco', 'like', "%{$termino}%")
                ->orWhere('placas', 'like', "%{$termino}%")
                ->orWhere('marca', 'like', "%{$termino}%")
                ->orWhere('modelo', 'like', "%{$termino}%")
                ->orWhere('conductor_habitual', 'like', "%{$termino}%");
        });
    }
}