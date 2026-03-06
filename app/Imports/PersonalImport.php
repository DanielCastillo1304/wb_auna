<?php

namespace App\Imports;

use App\Models\maintenance\Personal;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithUpserts;

class PersonalImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function model(array $row)
    {
        return new Personal([

            'dni' => $row['dni'] ?? null,
            'usr_sfsf' => $row['usr_sfsf'] ?? null,
            'ape_nom' => $row['ape_nom'] ?? null,
            'sexo' => $row['sexo'] ?? null,

            'correo' => isset($row['correo']) ? strtolower($row['correo']) : null,

            'telefono' => $row['telefono_personal'] ?? null,

            'fec_ing' => !empty($row['fec_ing'])
                ? Carbon::createFromFormat('d/m/Y', $row['fec_ing'])->format('Y-m-d')
                : null,

            'tipo_contrato' => $row['tipo_contrato'] ?? null,
            'exclusividad' => $row['exclusividad'] ?? null,

            'cod_sociedad' => $row['codigo_sociedad'] ?? null,
            'soc' => $row['soc'] ?? null,
            'alcance' => $row['alcance'] ?? null,
            'negocio_atendido' => $row['negocio_atendido'] ?? null,

            'cod_n1' => $row['codigo_n1'] ?? null,
            'n1' => $row['n1_negocioofi'] ?? null,

            'cod_n2' => $row['codigo_n2'] ?? null,
            'n2' => $row['n2_gerencia'] ?? null,

            'cod_n3' => $row['codigo_n3'] ?? null,
            'n3' => $row['n3_division'] ?? null,

            'cod_n4' => $row['codigo_n4'] ?? null,
            'area_n4' => $row['area_n4'] ?? null,

            'cod_n5' => $row['codigo_n5'] ?? null,
            'n5' => $row['n5_departamento'] ?? null,

            'cargo' => $row['cargo'] ?? null,
            'cod_funcion' => $row['codigo_funcion'] ?? null,
            'cat_ocup' => $row['categoria_ocupacional'] ?? null,

            'ccosto' => $row['centro_de_coste'] ?? null,
            'desc_ccosto' => $row['descripcion_de_centro_de_coste'] ?? null,

            'cod_sede' => $row['codigo_sede'] ?? null,
            'sede' => $row['sede'] ?? null,

            'posicion_jefe' => $row['posicion_jefe_directo'] ?? null,
            'cargo_jef' => $row['cargo_jefe_directo'] ?? null,
            'nom_jef' => $row['jefe_directo'] ?? null,

            'division_personal' => $row['division_de_personal'] ?? null,
            'desc_division_personal' => $row['descripcion_de_division_de_personal'] ?? null,
            'desc_area_personal' => $row['descripcion_de_area_de_personal'] ?? null,

            'regimen_laboral' => $row['regimen_laboral'] ?? null,
            'relacion_laboral' => $row['relacion_laboral'] ?? null,
        ]);
    }
}
