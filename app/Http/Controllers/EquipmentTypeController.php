<?php

namespace App\Http\Controllers;

use App\Models\maintenance\EquipmentType;
use Illuminate\Validation\Rule;

class EquipmentTypeController extends CrudController
{
    protected string $model      = EquipmentType::class;
    protected string $view       = 'equipment_type';
    protected string $primaryKey = 'codequipment_type';

    public function __construct()
    {
        $this->extend = [
            'title'       => 'Tipos de equipos',
            'title_form'  => 'Tipo de equipo',
            'controller'  => 'equipment_type',
            'totalRecord' => EquipmentType::count(),
        ];
    }

    protected function rules($id = null): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:250',
                Rule::unique('pgsql.maintenance.equipment_types', 'name')
                    ->ignore($id, 'codequipment_type')
                    ->whereNull('deleted_at'),
            ],
        ];
    }

    protected function searchFields(): array
    {
        return ['name'];
    }
}