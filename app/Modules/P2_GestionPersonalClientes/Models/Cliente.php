<?php

namespace App\Modules\P2_GestionPersonalClientes\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'apellido',
        'telefono',
        'email',
        'direccion',
        'fecha_nacimiento',
        'notas',
        'estado',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    /**
     * Nombre completo del cliente.
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    /**
     * Scope para búsqueda por nombre, apellido, teléfono o email.
     */
    public function scopeBuscar($query, ?string $termino)
    {
        if (!$termino) {
            return $query;
        }

        return $query->where(function ($q) use ($termino) {
            $q->where('nombre', 'like', "%{$termino}%")
              ->orWhere('apellido', 'like', "%{$termino}%")
              ->orWhere('telefono', 'like', "%{$termino}%")
              ->orWhere('email', 'like', "%{$termino}%");
        });
    }

    /**
     * Scope para filtrar solo activos.
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Citas del cliente.
     */
    public function citas()
    {
        return $this->hasMany(\App\Modules\P4_GestionServiciosCitas\Models\Cita::class, 'cliente_id');
    }
}
