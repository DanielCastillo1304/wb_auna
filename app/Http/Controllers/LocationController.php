<?php

namespace App\Http\Controllers;

use App\Models\Maintenance\Location;
use Illuminate\Validation\Rule;

class LocationController extends CrudController
{
    protected string $model      = Location::class;
    protected string $view       = 'location';
    protected string $primaryKey = 'codlocation';

    public function __construct()
    {
        $this->middleware('module.permission:listar')->only('index');
        $this->middleware('module.permission:editar')->only('form');
        $this->middleware('module.permission:crear')->only(['store']);
        $this->middleware('module.permission:eliminar')->only('destroy');
        $this->extend = [
            'title'       => 'Nuestras Sedes',
            'title_form'  => 'Nuestras sedes',
            'controller'  => 'location',
            'totalRecord' => Location::count(),
        ];
    }

    protected function rules($id = null): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:250',
                Rule::unique('pgsql.maintenance.locations', 'name')
                    ->ignore($id, 'codlocation')
                    ->whereNull('deleted_at'),
            ],
        ];
    }

    protected function searchFields(): array
    {
        return ['name'];
    }
}
