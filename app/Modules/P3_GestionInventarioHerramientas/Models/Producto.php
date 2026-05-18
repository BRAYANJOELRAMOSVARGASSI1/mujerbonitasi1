<?php

namespace App\Modules\P3_GestionInventarioHerramientas\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria',
        'marca',
        'ubicacion',
        'precio_compra',
        'precio_venta',
        'stock_actual',
        'stock_minimo',
        'stock_maximo', //  de atributo stock maximo
        'unidad_medida',
        'estado',
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
    ];

    /**
     * Categorías de productos del salón.
     */
    public const CATEGORIAS = [
        'Cuidado Capilar',
        'Coloración',
        'Uñas',
        'Cejas y Pestañas',
        'Maquillaje',
        'Pedicure',
        'Productos de Venta',
        'Insumos Generales',
    ];

    /**
     * Unidades de medida disponibles.
     */
    public const UNIDADES = [
        'unidad' => 'Unidad',
        'ml' => 'Mililitros (ml)',
        'gr' => 'Gramos (gr)',
        'litro' => 'Litro',
        'caja' => 'Caja',
        'paquete' => 'Paquete',
    ];

    /**
     * Determina si el stock está bajo.
     */
    public function getStockBajoAttribute(): bool
    {
        return $this->stock_actual <= $this->stock_minimo;
    }

    /**
     * Determina si el stock es crítico (0).
     */
    public function getStockCriticoAttribute(): bool
    {
        return $this->stock_actual === 0;
    }

    /**
     * Nivel de alerta: 'critico', 'bajo', 'normal'.
     */
    public function getNivelAlertaAttribute(): string
    {
        if ($this->stock_actual === 0) {
            return 'critico';
        }
        if ($this->stock_actual <= $this->stock_minimo) {
            return 'bajo';
        }
        return 'normal';
    }

    /**
     * Scope para filtrar solo activos.
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope para productos con stock bajo.
     */
    public function scopeStockBajo($query)
    {
        return $query->whereColumn('stock_actual', '<=', 'stock_minimo');
    }
}
