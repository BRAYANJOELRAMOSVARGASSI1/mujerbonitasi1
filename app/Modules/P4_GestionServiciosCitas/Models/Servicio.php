<?php

namespace App\Modules\P4_GestionServiciosCitas\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';

    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria',
        'duracion_minutos',
        'precio',
        'estado',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
    ];

    /**
     * Categorías de servicios del salón MUJER BONITA.
     */
    public const CATEGORIAS = [
        'Cejas',
        'Pestañas',
        'Cabello',
        'Uñas - Manicure',
        'Uñas - Pedicure',
        'Maquillaje',
        'Paquetes Especiales',
    ];

    /**
     * Duración formateada (ej: "1h 30min").
     */
    public function getDuracionFormateadaAttribute(): string
    {
        $horas   = intdiv($this->duracion_minutos, 60);
        $minutos = $this->duracion_minutos % 60;

        if ($horas > 0 && $minutos > 0) {
            return "{$horas}h {$minutos}min";
        }
        if ($horas > 0) {
            return "{$horas}h";
        }
        return "{$minutos}min";
    }

    /**
     * Scope para filtrar solo activos.
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Relación: Servicios tiene muchas citas.
     */
    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    /**
     * Relación: Servicio pertenece a muchas promociones.
     */
    public function promociones()
    {
        return $this->belongsToMany(
            Promocion::class,
            'promocion_servicio',
            'servicio_id',
            'promocion_id'
        );
    }

    /**
     * Obtiene la mejor promoción vigente para este servicio.
     */
    public function getMejorPromocionAttribute()
    {
        return $this->promociones()
            ->where('estado', 'activa')
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->orderByDesc('porcentaje_descuento')
            ->first();
    }
}
