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
use App\Modules\P4_GestionServiciosCitas\Models\Cita;

class ServiciosExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    public function __construct(
        protected Carbon $inicio,
        protected Carbon $fin
    ) {}

    public function title(): string
    {
        return 'Citas y Servicios';
    }

    public function query()
    {
        return Cita::with(['cliente', 'estilista', 'servicio'])
            ->whereBetween('fecha', [$this->inicio->toDateString(), $this->fin->toDateString()])
            ->orderByDesc('fecha');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Fecha',
            'Hora Inicio',
            'Hora Fin',
            'Cliente',
            'Estilista',
            'Servicio',
            'Precio (₡)',
            'Estado',
            'Notas',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->fecha?->format('d/m/Y'),
            $row->hora_inicio,
            $row->hora_fin,
            $row->cliente?->nombre_completo  ?? 'N/A',
            $row->estilista?->nombre_completo ?? 'N/A',
            $row->servicio?->nombre          ?? 'N/A',
            number_format($row->precio_total ?? 0, 2),
            ucfirst($row->estado),
            $row->notas ?? '',
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
