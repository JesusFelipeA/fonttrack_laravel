<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Usuarios extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tb_users';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'correo',
        'password',
        'tipo_usuario',
        'foto_usuario',
        'id_lugar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ✅ ESTA ES LA LÍNEA QUE NECESITAS AGREGAR
    protected $appends = ['foto_usuario_url'];

    public function lugar()
    {
        return $this->belongsTo(Lugar::class, 'id_lugar', 'id_lugar');
    }

    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            if (Str::startsWith($value, '$2y$')) {
                $this->attributes['password'] = $value;
            } else {
                $this->attributes['password'] = bcrypt($value);
            }
        }
    }

    public function getFotoUsuarioUrlAttribute()
    {
        if ($this->foto_usuario && file_exists(public_path('img/' . $this->foto_usuario))) {
            return asset('img/' . $this->foto_usuario);
        }
        return asset('img/usuario_default.png');
    }
}