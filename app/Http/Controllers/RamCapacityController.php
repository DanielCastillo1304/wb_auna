<?php

namespace App\Http\Controllers;

use App\Models\Maintenance\RamCapacity;
use Illuminate\Validation\Rule;

class RamCapacityController extends CrudController
{
    protected string $model      = RamCapacity::class;
    protected string $view       = 'ram_capacity';
    protected string $primaryKey = 'codram_capacity';

    public function __construct()
    {
        $this->extend = [
            'title'       => 'Memorarias RAM',
            'title_form'  => 'Memorarias RAM',
            'controller'  => 'ram_capacity',
            'totalRecord' => RamCapacity::count(),
        ];
    }

    protected function rules($id = null): array
    {
        $this->middleware('module.permission:listar')->only('index');
        $this->middleware('module.permission:editar')->only('form');
        $this->middleware('module.permission:crear')->only(['store']);
        $this->middleware('module.permission:eliminar')->only('destroy');
        return [
            'capacity_gb' => [
                'required',
                'integer',
                Rule::unique('pgsql.maintenance.ram_capacities', 'capacity_gb')
                    ->ignore($id, 'codram_capacity')
                    ->whereNull('deleted_at'),
            ],
        ];
    }

    protected function searchFields(): array
    {
        return ['capacity_gb'];
    }
}
