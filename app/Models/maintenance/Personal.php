<?php

namespace App\Models\Maintenance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personal extends Model
{
    use SoftDeletes;

    protected $table = 'maintenance.personal';
    protected $primaryKey = 'codpersonal';

    protected $fillable = [

        'dni',
        'usr_sfsf',
        'ape_nom',
        'sexo',
        'correo',
        'telefono',
        'fec_ing',

        'tipo_contrato',
        'exclusividad',

        'cod_sociedad',
        'soc',
        'alcance',
        'negocio_atendido',

        'cod_n1',
        'n1',
        'cod_n2',
        'n2',
        'cod_n3',
        'n3',
        'cod_n4',
        'area_n4',
        'cod_n5',
        'n5',

        'cargo',
        'cod_funcion',
        'cat_ocup',

        'ccosto',
        'desc_ccosto',

        'cod_sede',
        'sede',

        'posicion_jefe',
        'cargo_jef',
        'nom_jef',

        'division_personal',
        'desc_division_personal',
        'desc_area_personal',
        'regimen_laboral',
        'relacion_laboral'
    ];
}