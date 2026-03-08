<?php

namespace App\Http\Controllers;

use App\Models\Maintenance\Personal;
use Illuminate\Validation\Rule;

class PersonalController extends CrudController
{
    protected string $model      = Personal::class;
    protected string $view       = 'personal';
    protected string $primaryKey = 'codpersonal';

    public function __construct()
    {
        $this->middleware('module.permission:listar')->only('index');
        $this->middleware('module.permission:editar')->only('form');
        $this->middleware('module.permission:crear')->only(['store']);
        $this->middleware('module.permission:eliminar')->only('destroy');
        $this->extend = [
            'title'       => 'Personal',
            'title_form'  => 'Colaborador',
            'controller'  => 'personal',
            'totalRecord' => Personal::count(),
        ];
    }

    protected function rules($id = null): array
    {
        return [
            'dni' => [
                'nullable',
                'string',
                'max:15',
                Rule::unique('pgsql.maintenance.personal', 'dni')
                    ->ignore($id, 'codpersonal')
                    ->whereNull('deleted_at'),
            ],
            'ape_nom'           => ['required', 'string', 'max:255'],
            'sexo'              => ['nullable', 'string', 'max:20'],
            'correo'            => ['nullable', 'email', 'max:255'],
            'telefono'          => ['nullable', 'string', 'max:50'],
            'fec_ing'           => ['required', 'date'],

            'tipo_contrato'     => ['nullable', 'string', 'max:150'],
            'exclusividad'      => ['nullable', 'string', 'max:100'],

            'cod_sociedad'      => ['nullable', 'string', 'max:50'],
            'soc'               => ['nullable', 'string', 'max:255'],
            'alcance'           => ['nullable', 'string', 'max:255'],
            'negocio_atendido'  => ['nullable', 'string', 'max:255'],

            'cod_n1'            => ['nullable', 'string', 'max:50'],
            'n1'                => ['nullable', 'string', 'max:255'],
            'cod_n2'            => ['nullable', 'string', 'max:50'],
            'n2'                => ['nullable', 'string', 'max:255'],
            'cod_n3'            => ['nullable', 'string', 'max:50'],
            'n3'                => ['nullable', 'string', 'max:255'],
            'cod_n4'            => ['nullable', 'string', 'max:50'],
            'area_n4'           => ['nullable', 'string', 'max:255'],
            'cod_n5'            => ['nullable', 'string', 'max:50'],
            'n5'                => ['nullable', 'string', 'max:255'],

            'cargo'             => ['nullable', 'string', 'max:255'],
            'cod_funcion'       => ['nullable', 'string', 'max:50'],
            'cat_ocup'          => ['nullable', 'string', 'max:255'],

            'ccosto'            => ['nullable', 'string', 'max:50'],
            'desc_ccosto'       => ['nullable', 'string', 'max:255'],

            'cod_sede'          => ['nullable', 'string', 'max:50'],
            'sede'              => ['nullable', 'string', 'max:255'],

            'posicion_jefe'     => ['nullable', 'string', 'max:100'],
            'cargo_jef'         => ['nullable', 'string', 'max:255'],
            'nom_jef'           => ['nullable', 'string', 'max:255'],

            'division_personal'       => ['nullable', 'string', 'max:255'],
            'desc_division_personal'  => ['nullable', 'string', 'max:255'],
            'desc_area_personal'      => ['nullable', 'string', 'max:255'],
            'regimen_laboral'         => ['nullable', 'string', 'max:150'],
            'relacion_laboral'        => ['nullable', 'string', 'max:150'],
        ];
    }

    protected function searchFields(): array
    {
        return [
            'dni',
            'ape_nom',
            'cargo',
            'sede',
            'area_n4',
            'nom_jef',
            'ccosto',
            'soc',
        ];
    }
}
