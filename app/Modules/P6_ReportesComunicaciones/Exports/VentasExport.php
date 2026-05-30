<?php

namespace App\Modules\P6_ReportesComunicaciones\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Modules\P4_GestionServiciosCitas\Models\ServicioRealizado;

class VentasExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    public function __construct(
        protected Carbon $inicio,
        protected Carbon $fin
    ) {}

    public function title(): string
    {
        return 'Ventas ' . $this->inicio->format('d/m/Y') . ' - ' . $this->fin->format('d/m/Y');
    }

    public function query()
    {
        return ServicioRealizado::with(['estilista', 'servicio', 'cliente'])
            ->whereBetween('fecha_realizacion', [$this->inicio, $this->fin])
            ->orderByDesc('fecha_realizacion');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Fecha',
            'Cliente',
            'Estilista',
            'Servicio',
            'Precio Cobrado (₡)',
            'Comisión %',
            'Comisión (₡)',
            'Duración (min)',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            optional($row->fecha_realizacion)->format('d/m/Y H:i'),
            $row->cliente?->nombre_completo ?? 'N/A',
            $row->estilista?->nombre_completo ?? 'N/A',
            $row->servicio?->nombre ?? 'N/A',
            number_format($row->precio_cobrado, 2),
            $row->comision_porcentaje . '%',
            number_format($row->comision_monto, 2),
            $row->duracion_real_minutos ?? 'N/A',
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
