<?php

namespace App\Modules\P5_PagosFacturacion\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\P4_GestionServiciosCitas\Models\Cita;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $fillable = [
        'cita_id',
        'monto',
        'estado_pago',
        'metodo',
        'transaccion_id',
    ];

    /**
     * Relación: Un pago pertenece a una cita.
     */
    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }
}
