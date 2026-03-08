<?php

namespace App\Http\Controllers;

use App\Models\Maintenance\BusinessUnit;
use Illuminate\Validation\Rule;

class BusinessUnitController extends CrudController
{
    protected string $model      = BusinessUnit::class;
    protected string $view       = 'business_unit';
    protected string $primaryKey = 'codbusiness_unit';

    public function __construct()
    {
        $this->middleware('module.permission:listar')->only('index');
        $this->middleware('module.permission:editar')->only('form');
        $this->middleware('module.permission:crear')->only(['store']);
        $this->middleware('module.permission:eliminar')->only('destroy');
        $this->extend = [
            'title'       => 'Unidad de negocio',
            'title_form'  => 'Unidad de negocio',
            'controller'  => 'business_unit',
            'totalRecord' => BusinessUnit::count(),
        ];
    }

    protected function rules($id = null): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:250',
                Rule::unique('pgsql.maintenance.business_units', 'name')
                    ->ignore($id, 'codbusiness_unit')
                    ->whereNull('deleted_at'),
            ],
        ];
    }

    protected function searchFields(): array
    {
        return ['name'];
    }
}
