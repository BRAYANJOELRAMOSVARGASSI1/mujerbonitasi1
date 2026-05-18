<?php

namespace App\Modules\P5_PagosFacturacion\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\P2_GestionPersonalClientes\Models\Estilista;

/**
 * Modelo Comision — CU25
 *
 * Resumen de comisiones calculadas para un estilista en un período dado.
 * Se genera desde los registros de servicios_realizados agrupados por período.
 */
class Comision extends Model
{
    use HasFactory;

    protected $table = 'comisiones';

    protected $fillable = [
        'estilista_id',
        'periodo_inicio',
        'periodo_fin',
        'total_servicios',
        'total_comision',
        'cantidad_servicios',
        'estado',
        'notas',
    ];

    protected $casts = [
        'periodo_inicio'   => 'date',
        'periodo_fin'      => 'date',
        'total_servicios'  => 'decimal:2',
        'total_comision'   => 'decimal:2',
    ];

    /**
     * Relación: pertenece a una estilista.
     */
    public function estilista()
    {
        return $this->belongsTo(Estilista::class);
    }

    /**
     * Scope: comisiones pendientes.
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope: comisiones aprobadas.
     */
    public function scopeAprobadas($query)
    {
        return $query->where('estado', 'aprobada');
    }
}
