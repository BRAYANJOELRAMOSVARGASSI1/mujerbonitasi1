<?php

namespace App\Modules\P6_ReportesComunicaciones\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Modules\P3_GestionInventarioHerramientas\Models\Producto;

class InventarioExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    public function title(): string
    {
        return 'Inventario';
    }

    public function query()
    {
        return Producto::orderBy('categoria')->orderBy('nombre');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Categoría',
            'Marca',
            'Ubicación',
            'Stock Actual',
            'Stock Mínimo',
            'Stock Máximo',
            'Unidad',
            'Precio Compra (₡)',
            'Precio Venta (₡)',
            'Estado Stock',
            'Estado',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->nombre,
            $row->categoria,
            $row->marca    ?? 'N/A',
            $row->ubicacion ?? 'N/A',
            $row->stock_actual,
            $row->stock_minimo,
            $row->stock_maximo ?? 'N/A',
            $row->unidad_medida,
            number_format($row->precio_compra, 2),
            number_format($row->precio_venta, 2),
            ucfirst($row->nivel_alerta),
            ucfirst($row->estado),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                  'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF662A5B']]],
        ];
    }
}
