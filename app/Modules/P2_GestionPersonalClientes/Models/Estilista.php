<?php

namespace App\Modules\P2_GestionPersonalClientes\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estilista extends Model
{
    use HasFactory;

    protected $table = 'estilistas';

    protected $fillable = [
        'user_id',
        'nombre',
        'apellido',
        'telefono',
        'email',
        'especialidad',
        'porcentaje_comision',
        'fecha_contratacion',
        'estado',
    ];

    protected $casts = [
        'fecha_contratacion'  => 'date',
        'porcentaje_comision' => 'decimal:2',
    ];

    /**
     * Especialidades disponibles en el salón.
     */
    public const ESPECIALIDADES = [
        'Cejas',
        'Pestañas',
        'Cabello',
        'Uñas',
        'Maquillaje',
    ];

    /**
     * Nombre completo de la estilista.
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    /**
     * Relación: Estilista pertenece a un User (opcional).
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Relación: Estilista tiene muchos horarios.
     */
    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    /**
     * Relación: Estilista tiene muchas citas.
     */
    public function citas()
    {
        return $this->hasMany(\App\Modules\P4_GestionServiciosCitas\Models\Cita::class);
    }

    /**
     * Relación: Estilista tiene muchos servicios realizados.
     */
    public function serviciosRealizados()
    {
        return $this->hasMany(\App\Modules\P4_GestionServiciosCitas\Models\ServicioRealizado::class);
    }

    /**
     * Relación: Estilista tiene muchas comisiones.
     */
    public function comisiones()
    {
        return $this->hasMany(\App\Modules\P5_PagosFacturacion\Models\Comision::class);
    }

    /**
     * Scope para filtrar solo activos.
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }
}
