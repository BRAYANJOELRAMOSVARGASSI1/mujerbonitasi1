<?php

namespace App\Modules\P6_ReportesComunicaciones\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Modules\P4_GestionServiciosCitas\Models\Promocion;
use App\Modules\P5_PagosFacturacion\Models\Comision;

class PromocionesExport implements WithMultipleSheets
{
    public function __construct(
        protected Carbon $inicio,
        protected Carbon $fin
    ) {}

    public function sheets(): array
    {
        return [
            new PromocionesSheet(),
            new ComisionesSheet($this->inicio, $this->fin),
        ];
    }
}

// ── Hoja 1: Promociones ───────────────────────────────────

class PromocionesSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    public function title(): string { return 'Promociones'; }

    public function collection()
    {
        return Promocion::with('servicios')->orderByDesc('fecha_inicio')->get();
    }

    public function headings(): array
    {
        return ['ID', 'Nombre', 'Descripción', 'Descuento %', 'Fecha Inicio', 'Fecha Fin', 'Estado', 'Servicios Asociados'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->nombre,
            $row->descripcion ?? '',
            $row->porcentaje_descuento . '%',
            $row->fecha_inicio?->format('d/m/Y'),
            $row->fecha_fin?->format('d/m/Y'),
            ucfirst($row->estado),
            $row->servicios->pluck('nombre')->join(', '),
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

// ── Hoja 2: Comisiones ────────────────────────────────────

class ComisionesSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    public function __construct(
        protected Carbon $inicio,
        protected Carbon $fin
    ) {}

    public function title(): string { return 'Comisiones'; }

    public function collection()
    {
        return Comision::with('estilista')
            ->where(function ($q) {
                $q->whereBetween('periodo_inicio', [$this->inicio, $this->fin])
                  ->orWhereBetween('periodo_fin', [$this->inicio, $this->fin]);
            })
            ->orderByDesc('periodo_fin')
            ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Estilista', 'Período Inicio', 'Período Fin', 'Total Servicios (₡)', 'Comisión (₡)', 'Cantidad Servicios', 'Estado'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->estilista?->nombre_completo ?? 'N/A',
            $row->periodo_inicio?->format('d/m/Y'),
            $row->periodo_fin?->format('d/m/Y'),
            number_format($row->total_servicios, 2),
            number_format($row->total_comision, 2),
            $row->cantidad_servicios,
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
