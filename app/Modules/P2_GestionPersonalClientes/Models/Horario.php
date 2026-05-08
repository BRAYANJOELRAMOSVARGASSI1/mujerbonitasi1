<?php

namespace App\Modules\P2_GestionPersonalClientes\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios';

    protected $fillable = [
        'estilista_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'estado',
    ];

    /**
     * Días de la semana disponibles.
     */
    public const DIAS_SEMANA = [
        'lunes'     => 'Lunes',
        'martes'    => 'Martes',
        'miercoles' => 'Miércoles',
        'jueves'    => 'Jueves',
        'viernes'   => 'Viernes',
        'sabado'    => 'Sábado',
        'domingo'   => 'Domingo',
    ];

    /**
     * Relación: Horario pertenece a una Estilista.
     */
    public function estilista()
    {
        return $this->belongsTo(Estilista::class);
    }

    /**
     * Scope para filtrar solo activos.
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }
}
