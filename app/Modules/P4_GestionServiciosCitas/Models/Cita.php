<?php

namespace App\Modules\P4_GestionServiciosCitas\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\P2_GestionPersonalClientes\Models\Cliente;
use App\Modules\P2_GestionPersonalClientes\Models\Estilista;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'cliente_id',
        'estilista_id',
        'servicio_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'precio_total',
        'estado',
        'notas',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    /**
     * Relación: La cita pertenece a un cliente.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación: La cita pertenece a una estilista.
     */
    public function estilista()
    {
        return $this->belongsTo(Estilista::class);
    }

    /**
     * Relación: La cita pertenece a un servicio.
     */
    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    /**
     * Scope para filtrar citas activas (no completadas ni canceladas).
     */
    public function scopeActivas($query)
    {
        return $query->whereIn('estado', ['pendiente', 'en_curso']);
    }
}
