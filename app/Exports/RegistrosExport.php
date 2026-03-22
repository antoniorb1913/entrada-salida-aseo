<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RegistrosExport implements FromCollection, WithHeadings, WithMapping
{
    protected $registros;

    // Recibimos los registros filtrados desde el controlador
    public function __construct($registros)
    {
        $this->registros = $registros;
    }

    public function collection()
    {
        return $this->registros;
    }

    // 1. Aquí definimos los títulos de la primera fila
    public function headings(): array
    {
        return [
            'Alumno/a',
            'Curso/Grupo',
            'Profesor/a',
            'Fecha',
            'Hora Salida',
            'Hora Entrada',
            'Estado'
        ];
    }

    // 2. Aquí definimos qué columna de la DB va en cada columna del Excel
    public function map($reg): array
    {
        return [
            ($reg->alumno->apellidos ?? '') . ', ' . ($reg->alumno->nombre ?? ''),
            ($reg->curso->etapas->value ?? '') . ' ' . ($reg->curso->nivel ?? '') . ' ' . ($reg->curso->letra ?? ''),
            ($reg->profesor->apellidos ?? 'N/A') . ', '.  ($reg->profesor->nombre ?? 'N/A'),
            \Carbon\Carbon::parse($reg->fecha_salida)->format('d/m/Y'),
            \Carbon\Carbon::parse($reg->fecha_salida)->format('H:i'),
            $reg->fecha_entrada ? \Carbon\Carbon::parse($reg->fecha_entrada)->format('H:i') : '--:--',
            $reg->estado->value ?? $reg->estado // Ajusta según si usas Enums o String
        ];
    }
}