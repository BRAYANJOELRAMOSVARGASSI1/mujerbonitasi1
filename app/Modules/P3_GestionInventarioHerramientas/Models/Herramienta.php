<?php

namespace App\Modules\P3_GestionInventarioHerramientas\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Herramienta extends Model
{
    use HasFactory;

    protected $table = 'herramientas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria',
        'numero_serie',
        'area_asignada',
        'estado',
        'fecha_adquisicion',
        'costo',
    ];

    protected $casts = [
        'fecha_adquisicion' => 'date',
        'costo'             => 'decimal:2',
    ];

    /**
     * Categorías de herramientas del salón.
     */
    public const CATEGORIAS = [
        'Cabello',
        'Uñas',
        'Cejas y Pestañas',
        'Maquillaje',
        'General',
    ];

    /**
     * Áreas del salón donde se asignan herramientas.
     */
    public const AREAS = [
        'Cejas',
        'Pestañas',
        'Cabello',
        'Uñas',
        'Maquillaje',
        'Recepción',
        'General',
    ];

    /**
     * Estados posibles de la herramienta.
     */
    public const ESTADOS = [
        'disponible'    => 'Disponible',
        'en_uso'        => 'En Uso',
        'mantenimiento' => 'Mantenimiento',
        'baja'          => 'Baja',
    ];

    /**
     * Scope para filtrar solo activos.
     */
    public function scopeDisponibles($query)
    {
        return $query->where('estado', 'disponible');
    }
}
