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
use App\Modules\P2_GestionPersonalClientes\Models\Cliente;

class ClientesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    public function __construct(
        protected Carbon $inicio,
        protected Carbon $fin
    ) {}

    public function title(): string
    {
        return 'Clientes';
    }

    public function query()
    {
        return Cliente::withCount('citas')->orderBy('nombre');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Apellido',
            'Teléfono',
            'Email',
            'Fecha Nacimiento',
            'Estado',
            'Total Citas',
            'Fecha Registro',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->nombre,
            $row->apellido,
            $row->telefono ?? 'N/A',
            $row->email    ?? 'N/A',
            $row->fecha_nacimiento?->format('d/m/Y') ?? 'N/A',
            ucfirst($row->estado),
            $row->citas_count,
            $row->created_at->format('d/m/Y'),
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
