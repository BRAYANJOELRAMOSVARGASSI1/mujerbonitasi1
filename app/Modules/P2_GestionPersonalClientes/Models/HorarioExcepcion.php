<?php

namespace App\Modules\P2_GestionPersonalClientes\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioExcepcion extends Model
{
    use HasFactory;

    protected $table = 'horario_excepciones';

    protected $fillable = [
        'estilista_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'tipo',
        'motivo',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    /**
     * Relación: La excepción pertenece a un estilista.
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
