<?php

namespace App\Modules\P4_GestionServiciosCitas\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Promocion — CU24
 *
 * Representa descuentos y promociones aplicables a servicios del salón.
 * Tiene relación muchos-a-muchos con Servicio vía tabla pivote.
 */
class Promocion extends Model
{
    use HasFactory;

    protected $table = 'promociones';

    protected $fillable = [
        'nombre',
        'descripcion',
        'porcentaje_descuento',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    protected $casts = [
        'fecha_inicio'         => 'date',
        'fecha_fin'            => 'date',
        'porcentaje_descuento' => 'decimal:2',
    ];

    /**
     * Servicios asociados a esta promoción.
     */
    public function servicios()
    {
        return $this->belongsToMany(
            Servicio::class,
            'promocion_servicio',
            'promocion_id',
            'servicio_id'
        );
    }

    /**
     * Verifica si la promoción está vigente (dentro de rango de fechas y activa).
     */
    public function getIsVigenteAttribute(): bool
    {
        return $this->estado === 'activa'
            && $this->fecha_inicio <= now()
            && $this->fecha_fin >= now();
    }

    /**
     * Scope: solo promociones activas.
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }

    /**
     * Scope: solo promociones vigentes (activas + dentro de rango de fechas).
     */
    public function scopeVigentes($query)
    {
        return $query->where('estado', 'activa')
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now());
    }
}
