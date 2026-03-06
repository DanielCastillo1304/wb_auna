<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JournalistExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $journalists;

    public function __construct($journalists)
    {
        $this->journalists = $journalists;
    }

    public function collection()
    {
        return $this->journalists;
    }

    public function headings(): array
    {
        return [
            'Código',
            'Nombre Completo',
            'Documento',
            'Código Afiliación',
            'Fecha Afiliación',
            'Estado',
            'Medios',
            'Filial'
        ];
    }

    public function map($journalist): array
    {
        $statusMap = [
            'active' => 'Activo',
            'inactive' => 'Inactivo',
            'suspended' => 'Suspendido'
        ];

        return [
            $journalist->codjournalist,
            $journalist->person ? 
                ($journalist->person->first_name . ' ' . $journalist->person->last_name) : 
                'N/A',
            $journalist->person->document_number ?? 'N/A',
            $journalist->affiliation_code ?? 'N/A',
            $journalist->affiliation_date ? 
                \Carbon\Carbon::parse($journalist->affiliation_date)->format('d/m/Y') : 
                'N/A',
            $statusMap[$journalist->affiliation_status] ?? 'Desconocido',
            $journalist->medios->isNotEmpty() ? 
                $journalist->medios->pluck('name')->implode(', ') : 
                'Sin medios',
            $journalist->codfilial ?? 'N/A'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}