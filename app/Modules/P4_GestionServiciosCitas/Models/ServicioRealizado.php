<?php

namespace App\Modules\P4_GestionServiciosCitas\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\P2_GestionPersonalClientes\Models\Cliente;
use App\Modules\P2_GestionPersonalClientes\Models\Estilista;

/**
 * Modelo ServicioRealizado — CU14
 *
 * Registra el detalle de un servicio completado: observaciones,
 * productos utilizados, duración real y cálculo de comisión.
 * Se crea al marcar un servicio/cita como "Realizado".
 */
class ServicioRealizado extends Model
{
    use HasFactory;

    protected $table = 'servicios_realizados';

    protected $fillable = [
        'cita_id',
        'estilista_id',
        'servicio_id',
        'cliente_id',
        'observaciones',
        'duracion_real_minutos',
        'productos_utilizados',
        'precio_cobrado',
        'comision_porcentaje',
        'comision_monto',
        'fecha_realizacion',
    ];

    protected $casts = [
        'precio_cobrado'       => 'decimal:2',
        'comision_porcentaje'  => 'decimal:2',
        'comision_monto'       => 'decimal:2',
        'fecha_realizacion'    => 'datetime',
    ];

    /**
     * Boot: calcular comisión automáticamente al crear.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registro) {
            if ($registro->precio_cobrado && $registro->comision_porcentaje) {
                $registro->comision_monto = round(
                    ($registro->precio_cobrado * $registro->comision_porcentaje) / 100,
                    2
                );
            }
        });
    }

    /**
     * Relación: pertenece a una cita.
     */
    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    /**
     * Relación: pertenece a una estilista.
     */
    public function estilista()
    {
        return $this->belongsTo(Estilista::class);
    }

    /**
     * Relación: pertenece a un servicio.
     */
    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    /**
     * Relación: pertenece a un cliente.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Duración real formateada.
     */
    public function getDuracionFormateadaAttribute(): string
    {
        if (!$this->duracion_real_minutos) {
            return 'N/A';
        }

        $horas   = intdiv($this->duracion_real_minutos, 60);
        $minutos = $this->duracion_real_minutos % 60;

        if ($horas > 0 && $minutos > 0) {
            return "{$horas}h {$minutos}min";
        }
        if ($horas > 0) {
            return "{$horas}h";
        }
        return "{$minutos}min";
    }
}
